<?php
require_once realpath(__DIR__).'/../vendor/autoload.php';

class IrDriveWrapper
{
    private $config = array(
        'serviceAccountId' => '',//g service account
        'serviceAuthJson' => '',
        'defaultFolderName' => 'IR_APP_FOLDER',
        'defaultFolderId' => '',
        'defaultShareAccount' => '',//google account
    );
    private $client = false;
    private $driveService = false;

    public function __construct($config = array())
    {
        if ($config) {
            $this->config = array_merge($this->config, $config);
        }
    }

    public function getClient()
    {
        if (!$this->client) $this->initClient();
        return $this->client;
    }

    public function initClient($config = array())
    {
        if ($config) {
            $this->config = array_merge($this->config, $config);
        }
        try {
            $this->client = new Google_Client();
            $this->client->setAccessType('offline');
            $this->client->addScope("https://www.googleapis.com/auth/drive");
            $this->client->setAuthConfig($this->getConfig('serviceAuthJson'));
        } catch (Exception $e) {

        }
    }

    public function clientAddScope($scopeId = false)
    {
        if ($scopeId) {
            $this->getClient()->addScope($scopeId);
        }
    }

    public function getDriveService()
    {
        if (!$this->driveService) $this->initDriveService($this->getClient());
        return $this->driveService;
    }

    public function initDriveService($client)
    {
        if ($client) $this->client = $client;
        try {
            $this->driveService = new Google_Service_Drive($client);
        } catch (Exception $e) {
            $this->driveService = false;
        }

    }

    public function getConfig($key)
    {
        if (array_key_exists($key, $this->config))
            return $this->config[$key];
        return false;
    }

    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function uploadFile($fileName, $fileContent, $mimeType = 'text/html', $parentId = false)
    {
        if (!$fileName || !$fileContent) return false;
        try {
            $file = new Google_Service_Drive_DriveFile();
            if (!$parentId) $parentId = $this->getConfig('defaultFolderId');
            if ($parentId) $file->setParents([$parentId]);
            $file->setName($fileName);
            $result = $this->getDriveService()->files->create($file, array(
                'data' => $fileContent,
                'mimeType' => $mimeType,
                'uploadType' => 'media',
            ));
            return $result->getId();
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateFile($fileId, $fileContent, $mimeType = 'text/html', $parentId = false,$revision=false)
    {
        if (!$fileId || !$fileContent) return false;
        try {
            $file = new Google_Service_Drive_DriveFile();
            $file->setMimeType($mimeType);
            $file->setModifiedTime(date(DATE_RFC3339));
            $params = array(
                'data' => $fileContent,
                'mimeType' => $mimeType,
                'uploadType' => 'media'
            );
            if($revision){
                $params['newRevision'] = $revision;
            }
            $updatedFile = $this->getDriveService()->files->update($fileId, $file ,$params);
            return $updatedFile->getId();
        } catch (Exception $e) {
            return false;
        }
    }

    public function createDir($name, $parentId = false)
    {
        if (!$name) return false;
        try {
            $newFolderMeta = new Google_Service_Drive_DriveFile(array(
                'name' => $name,
                'mimeType' => 'application/vnd.google-apps.folder'));
            if ($parentId) {
                $newFolderMeta->setParents([$parentId]);
            }
            $newFolder = $this->getDriveService()->files->create($newFolderMeta, array(
                'fields' => 'id'));
            return $newFolder->getId();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getFilesByName($name)
    {
        $result = array();
        if ($allFiles = $this->getFiles()) {
            foreach ($allFiles as $file) {
                if ($file['name'] == $name) {
                    $result[] = $file;
                }
            }
        }
        return $result;
    }

    public function getFileById($id)
    {
        $result = array();
        if ($allFiles = $this->getFiles()) {
            foreach ($allFiles as $file) {
                if ($file['id'] == $id) {
                    $result[] = $file;
                    break;
                }
            }
        }
        return $result;
    }

    public function getFiles()
    {
        $result = array();
        try {
            if ($files = $this->getDriveService()->files->listFiles()->getFiles()) {
                foreach ($files as $file) {
                    $tmp = array();
                    $tmp['type'] = 'file';
                    if ($file->mimeType == 'application/vnd.google-apps.folder') {
                        $tmp['type'] = 'dir';
                    }
                    $tmp['name'] = $file->name;
                    $tmp['id'] = $file->getId();
                    $result[] = $tmp;
                }
            }
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function grantPermissionToUser($fileId, $user, $role)
    {
        if (!$fileId || !$user || !$role) return false;
        $transferOwnerShip = ($role == 'owner');
        try {
            $userPermission = new Google_Service_Drive_Permission(array(
                'type' => 'user',
                'role' => $role,
                'emailAddress' => $user

            ));
            return $this->getDriveService()->permissions->create(
                $fileId, $userPermission, array('fields' => 'id', 'transferOwnership' => $transferOwnerShip));
        } catch (Exception $e) {
            return false;
        }
    }

    public function getUserFilePermissionId($email){
        return false;//not implemented in api v3
        try {
            $permissions = $this->getDriveService()->permissions->getIdForEmail($email);
            return $permissions->getId();
        } catch (Exception $e) {
            return false;
        }
    }
    public function getFilePermissions($fileId,$permissionId=false,$role=false)
    {
        try {
            $permissions = $this->getDriveService()->permissions->listPermissions($fileId);
            $allPermissions =  $permissions->getPermissions();
            $result = array('permissionId'=>false,'role'=>false);
            if($permissionId || $role){
                foreach ($allPermissions as $permission){
                    if($permissionId && $permissionId == $permission->getId()){
                        $result['permissionId'][] = $permission;
                    }
                    if($role && $role == $permission->getRole()){
                        $result['role'][] = $permission;
                    }
                }
                return $result;
            } else {
                return $allPermissions;
            }

        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteAllFiles()
    {
        try {
            if ($files = $this->getFiles()) {
                foreach ($files as $file) {
                    $this->getDriveService()->files->delete($file['id']);
                }
            }
        } catch (Exception $e) {

        }
    }

    public function getAboutUser(){
        try {
            $result = $this->getDriveService()->about->get(array('fields'=>'user'));
            return $result->getUser();
        } catch (Exception $e) {
            return false;
        }
    }
    public function deleteFileById($fileId)
    {
        if (!$fileId) return false;
        try {
            $this->getDriveService()->files->delete($fileId());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}