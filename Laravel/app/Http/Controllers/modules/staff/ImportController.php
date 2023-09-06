<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ImportNoteRepositoryInterface;
use Illuminate\Http\Request;

class ImportController extends RestController
{
    public function __construct(ImportNoteRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['details'];
        $withCount = [];
        $orderBy = $request->input('orderBy','created_at:desc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::orQuery([WhereClause::queryLike('name', $request->search), WhereClause::queryLike('creator_name', $request->search)]));
        }

        if ($request->has('date')) {
            array_push($clauses, WhereClause::queryDate('created_at', $request->date));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

}
