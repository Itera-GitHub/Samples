<?php

namespace App\Http\Controllers;

use App\Services\Helpers\ModelHelperService;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CandidateOfferHistoryCreateRequest;
use App\Http\Requests\CandidateOfferHistoryUpdateRequest;
use App\Repositories\CandidateOfferHistoryRepository;
use App\Validators\CandidateOfferHistoryValidator;

/**
 * Class CandidateOfferHistoriesController.
 *
 * @package namespace App\Http\Controllers;
 */
class CandidateOfferHistoriesController extends Controller
{
    /**
     * @var CandidateOfferHistoryRepository
     */
    protected $repository;

    /**
     * @var CandidateOfferHistoryValidator
     */
    protected $validator;

    /**
     * CandidateOfferHistoriesController constructor.
     *
     * @param CandidateOfferHistoryRepository $repository
     * @param CandidateOfferHistoryValidator $validator
     */
    public function __construct(CandidateOfferHistoryRepository $repository, CandidateOfferHistoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $candidateOfferHistories = $this->repository->all();
        if (request()->wantsJson()) {
            return response()->json([
                'data' => $candidateOfferHistories,
            ]);
        }
        return view('candidateOfferHistories.index', compact('candidateOfferHistories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CandidateOfferHistoryCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CandidateOfferHistoryCreateRequest $request, ModelHelperService $modelHelperService)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            if($preparedRequest = $modelHelperService->prepareBulkCandidatesOfferHistoryModel($request->all())){
                foreach($preparedRequest as $row) {
                    $this->repository->updateOrCreate($row,$row);
                }
                $response = [
                    'message' => 'Offres attribuées',
                    'offers_assigned'    => $preparedRequest,
                ];
            } else {
                $response = [
                    'message' => 'Rien à attribuer',
                    'offers_assigned' => [],
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidateOfferHistory = $this->repository->find($id);
        return response()->json([
            'data' => $candidateOfferHistory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CandidateOfferHistoryUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CandidateOfferHistoryUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $candidateOfferHistory = $this->repository->update($request->all(), $id);
            $response = [
                'message' => 'CandidateOfferHistory updated.',
                'data'    => $candidateOfferHistory->toArray(),
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);
        return response()->json([
            'message' => 'CandidateOfferHistory deleted.',
            'deleted' => $deleted,
        ]);
    }
}
