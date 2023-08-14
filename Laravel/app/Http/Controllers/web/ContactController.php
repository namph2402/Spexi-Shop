<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use Illuminate\Http\Request;
use App\Repository\StorePostRepositoryInterface;

class ContactController extends RestController
{
    public function __construct(StorePostRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $post = $this->repository->find([WhereClause::query('name', 'Bài viết giới thiệu')]);
        return view('pages.contact', compact('post'));
    }
}
