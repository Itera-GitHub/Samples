<?php

namespace App\Http\Controllers;

use App\Services\Helpers\ModelHelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CandidateTagCreateRequest;
use App\Http\Requests\CandidateTagUpdateRequest;
use App\Repositories\CandidateTagRepository;
use App\Validators\CandidateTagValidator;

/**
 * Class CandidateTagsController.
 *
 * @package namespace App\Http\Controllers;
 */
class CandidateTagsController extends Controller
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
            if($preparedRequest = $modelHelperService->prepareBulkCandidatesTagsModel($request->all())){
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $candidateTag = $this->repository->find($id);
        return response()->json([
            'data' => $candidateTag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CandidateTagUpdateRequest $request
     * @param  string            $id
     *
     * @return JsonResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CandidateTagUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $candidateTag = $this->repository->update($request->all(), $id);
            $response = [
                'message' => 'CandidateTag updated.',
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);
        return response()->json([
            'message' => 'CandidateTag deleted.',
            'deleted' => $deleted,
        ]);
    }
}
