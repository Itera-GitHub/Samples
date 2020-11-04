<?php
/**
 * Full text search configuration file
 */
return [
  'candidates' => [
      'use_joins' => true,
      'joins' => [
          'attachments' => [
              'repository_class'=> 'App\Repositories\CandidateAttachmentRepository',
              'table' => 'cv_pj',
              'parent_join_field' => 'id_candidat',
              'join_field' => 'id_candidat',
          ]
      ]
  ]
];
