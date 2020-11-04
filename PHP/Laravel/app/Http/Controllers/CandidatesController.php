<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendGroupMailRequest;
use App\Http\Requests\UploadCvRequest;
use App\Jobs\SendEmail;
use App\Presenters\CandidatePresenter;
use App\Repositories\ContactRepository;
use App\Repositories\OfferRepository;
use App\Services\Helpers\EmailHelperService;
use App\Services\Helpers\ModelHelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CandidateCreateRequest;
use App\Http\Requests\CandidateUpdateRequest;
use App\Repositories\CandidateRepository;
use App\Validators\CandidateValidator;
use App\Services\Parsers\LingWayLeaService;


/**
 * Class CandidatesController.
 *
 * @package namespace App\Http\Controllers;
 */
class CandidatesController extends Controller
{
    /**
     * @var CandidateRepository
     */
    protected $repository;

    /**
     * @var CandidateValidator
     */
    protected $validator;

    /**
     * CandidatesController constructor.
     *
     * @param CandidateRepository $repository
     * @param CandidateValidator $validator
     */
    public function __construct(CandidateRepository $repository, CandidateValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $this->repository->pushCriteria(app('App\Criterias\CandidatesFulltextSearchRequestCriteria'));
        $this->repository->pushCriteria(app('App\Criterias\RequestCriteriaExtension'));
        $this->repository->setPresenter(CandidatePresenter::class);
        $limit = $request->limit ?? false;
        $limit = $limit > 0 ? $limit : env('DEFAULT_PER_PAGE', 5);
        $request->page = $request->page ?? 1;
        $candidates = $this->repository->paginate($limit);
        return response()->json([
            'candidats' => $candidates['data'],
            'pagination' => $candidates['meta']['pagination'] ?? []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CandidateCreateRequest $request
     *
     * @return JsonResponse
     *
     * @throws FileExistsException
     * @throws FileNotFoundException
     */
    public function store(CandidateCreateRequest $request, ModelHelperService $modelHelper)
    {
        $storageParsedPath = env('CV_FILES_PARSED_DIR', 'cv_files/parsed');
        try {
            $modelData = $request->candidat;
            foreach ($modelData as $key => &$value) {
                $value = $value ?? '';
            }
            $this->validator->with($modelData)->passesOrFail(ValidatorInterface::RULE_CREATE);
            //Set Candidat status = New created candidat - Nouvel inscrit par RH
            $modelData['actif_candidat'] = 'c2304511d5a3e';
            $candidate = $this->repository->create($modelData);
            if ($candidate->id_candidat && isset($modelData['file_name']) && $modelData['file_name'] && Storage::disk('local')->exists($storageParsedPath . '/' . $modelData['file_name'])) {
                $candidateAttachment = $modelHelper->storeCandidateAttachment($candidate, $modelData['file_name']);
            }
            $response = [
                'message' => 'Candidate created.',
                'candidat' => $candidate->toArray(),
            ];

            return response()->json($response);

        } catch (ValidatorException $e) {
            if ($messages = $e->getMessageBag()) {
                foreach ($messages->messages() as $field => $message) {
                    foreach ($modelData['form_blocks'] as $key => &$block) {
                        foreach ($block['fields'] as $block_key => &$block_field) {
                            if ($block_field['field_name'] == $field) {
                                $block_field['error_message'] = $message[0];
                            }

                        }
                    }
                }
            }
            return response()->json([
                'error' => true,
                'candidat' => $modelData,
                'message' => $e->getMessageBag()
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        $this->repository->setPresenter(CandidatePresenter::class);
        try {
            $candidate = $this->repository->find(['id_candidat' => $id]);
            return response()->json([
                'candidat' => $candidate['data'][0],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'candidat' => [],
                'error' => true,
                'message' => $e->getMessage()
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $candidate = $this->repository->find($id);

        return view('candidates.edit', compact('candidate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CandidateUpdateRequest $request
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CandidateUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $candidate = $this->repository->update($request->all(), $id);
            $response = [
                'message' => 'Candidate updated.',
                'data' => $candidate->toArray(),
            ];
            return response()->json($response);
        } catch (ValidatorException $e) {

            return response()->json([
                'error' => true,
                'message' => $e->getMessageBag()
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);
        return response()->json([
            'message' => 'Candidate deleted.',
            'deleted' => $deleted,
        ]);
    }

    /**
     * @get initial form temporary method while models for form is not ready
     * @param $name
     * @param $request
     */
    public function getForm(Request $request, $name, ModelHelperService $modelHelperService)
    {
        if ($name) {
            switch ($name) {
                case 'create':
                    $formBlocks = [];
                    $modelHelperService->addCandidateFormBlock($formBlocks);
                    $response = [
                        'message' => 'initial form generated',
                        'candidat' => $formBlocks,
                    ];
                    return response()->json($response);
                default:
                    return response()->json(['message' => 'default', 'candidat' => []]);
            }
        }
        return response()->json(['error' => true, 'message' => 'FormName is required']);
    }

    /**
     * Upload single CV file and parse it
     *
     * @return JsonResponse
     */
    public function uploadSingleCV(
        UploadCvRequest $request,
        LingWayLeaService $lingWayLeaService,
        ModelHelperService $modelHelperService
    ) {
        try {
            $storageProgressPath = env('CV_FILES_INPROGRESS_DIR', 'cv_files/in_progress');
            $storageParsedPath = env('CV_FILES_PARSED_DIR', 'cv_files/parsed');
            $this->validator->with($request->all())->passesOrFail(CandidateValidator::RULE_UPLOAD_CV);
            $fileName = str_replace('zip','docx', $request->cv_file->hashName());
            $request->cv_file->storeAs($storageProgressPath, $fileName);
            $preparedModel = [];
            if ($parsed = $lingWayLeaService->parseResume(Storage::disk('local')->path($storageProgressPath . '/' . $fileName),
                $fileName)) {
                $preparedModel = $modelHelperService->prepareCandidateModel($parsed);
                Storage::disk('local')->move($storageProgressPath . '/' . $fileName,
                    $storageParsedPath . '/' . $fileName);
                $preparedModel['file_name'] = $fileName;
            }
            $response = [
                'message' => 'CV uploaded',
                'candidat' => $preparedModel,
            ];
            return response()->json($response);
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessageBag()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Upload bulk CV files
     *
     * @return JsonResponse
     */
    public function uploadBulkCV(UploadCvRequest $request)
    {
        try {
            $storageBulkUploadedPath = env('CV_FILES_BULK_UPLOADED_DIR', 'cv_files/bulk_uploaded');
            $this->validator->with($request->all())->passesOrFail(CandidateValidator::RULE_UPLOAD_CV);
            $fileName = $request->cv_file->hashName();
            $request->cv_file->storeAs($storageBulkUploadedPath, $fileName);
            $response = [
                'message' => 'CV uploaded',
                'status' => 'Succès',
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (ValidatorException $e) {
            return response()->json([
                'status' => 'error',
                'error' => isset($e->getMessageBag()->messages()['cv_file']) ? $e->getMessageBag()->messages()['cv_file'][0] : 'server error',
                'message' => $e->getMessageBag(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function sendGroupMail(SendGroupMailRequest $request, EmailHelperService $emailHelperService, ModelHelperService $modelHelperService, ContactRepository $contactRepository, OfferRepository $offerRepository)
    {
        $validated = $request->validated();
        if($details = $emailHelperService->prepareCandidatesContactEmailDetails($validated,$modelHelperService,$this->repository,$contactRepository, $offerRepository)) {
            foreach ($details as $detail){
                SendEmail::dispatch($detail);
            }
        }
        $response = [
            'message' => 'Emails sent',
            'status' => 'Succès',
        ];

        return response()->json($response);
    }

    public function assignOffer(Request $request)
    {
        return response()->json($request->all());
    }
}
