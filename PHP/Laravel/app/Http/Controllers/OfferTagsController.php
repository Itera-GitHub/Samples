<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateTagCreateRequest;
use App\Repositories\CandidateTagRepository;
use App\Services\Helpers\ModelHelperService;
use App\Validators\CandidateTagValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class OfferTagsController extends Controller
{

    /**
     * @var CandidateTagRepository
     */
    protected $repository;

    /**
     * @var CandidateTagValidator
     */
    protected $validator;


    /**
     * CandidateTagsController constructor.
     *
     * @param CandidateTagRepository $repository
     * @param CandidateTagValidator $validator
     */
    public function __construct(CandidateTagRepository $repository, CandidateTagValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $candidateTags = $this->repository->all();
        return response()->json([
            'data' => $candidateTags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CandidateTagCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CandidateTagCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $candidateTag = $this->repository->create($request->all());
            $response = [
                'message' => 'CandidateTag created.',
                'data'    => $candidateTag->toArray(),
            ];
            return response()->json($response);
        } catch (ValidatorException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }

    /**
     * Bulk assign tags to candidates
     *
     * @param  CandidateTagCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function bulkStore(CandidateTagCreateRequest $request, ModelHelperService $modelHelperService)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(CandidateTagValidator::RULE_BULK_CREATE);
            if($preparedRequest = $modelHelperService->prepareBulkOffersTagsModel($request->all())){
                foreach($preparedRequest as $row) {
                    $this->repository->updateOrCreate($row,$row);
                }
                $response = [
                    'message' => 'Des balises sont attribuées.',
                    'tags_assigned'    => $preparedRequest,
                ];
            } else {
                $response = [
                    'message' => 'Rien à attribuer',
                    'tags_assigned'    => [],
                ];
            }
            return response()->json($response, Response::HTTP_OK);
        } catch (ValidatorException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessageBag()
            ],Response::HTTP_BAD_REQUEST);
        }
    }

}
