<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\MenuGroupRepositoryInterface;
use Illuminate\Http\Request;

class MenuGroupController extends RestController
{
    public function __construct(MenuGroupRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['menus.parent'];
        $withCount = [];
        $clauses = [];
        $orderBy = $request->input('orderBy', 'id:asc');

        if ($request->has('search')) {
            array_push($clauses, WhereClause::queryLike('name', $request->search));
        }

        if ($limit) {
            $data = $this->repository->paginate($limit, $clauses, $orderBy, $with, $withCount);
        } else {
            $data = $this->repository->get($clauses, $orderBy, $with, $withCount);
        }
        return $this->success($data);
    }

}
