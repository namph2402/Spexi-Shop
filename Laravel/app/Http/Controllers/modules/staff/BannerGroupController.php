<?php

namespace App\Http\Controllers\modules\staff;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\BannerGroupRepositoryInterface;
use App\Repository\BannerRepositoryInterface;
use App\Utils\FileStorageUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BannerGroupController extends RestController
{
    protected $bannerRepository;

    public function __construct(BannerGroupRepositoryInterface $repository, BannerRepositoryInterface $bannerRepository)
    {
        parent::__construct($repository);
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $clauses = [];
        $with = ['banners'];
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
