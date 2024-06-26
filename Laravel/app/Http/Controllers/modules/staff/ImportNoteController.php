<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\ImportNoteRepositoryInterface;
use Illuminate\Http\Request;

class ImportNoteController extends RestController
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
        $orderBy = $request->input('orderBy','date_created:desc');

        $month = $request->input('month', date("m"));
        $year = $request->input('year', date("Y"));

        if ($request->has('search')) {
            array_push($clauses, WhereClause::orQuery([WhereClause::queryLike('name', $request->search), WhereClause::queryLike('creator_name', $request->search)]));
        }

        if ($request->has('month') || $request->has('year')) {
            array_push($clauses, WhereClause::queryMonth('date_created', $month));
            array_push($clauses, WhereClause::queryYear('date_created', $year));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

}
