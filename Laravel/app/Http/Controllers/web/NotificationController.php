<?php

namespace App\Http\Controllers\web;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\NotificationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends RestController
{
    public function __construct(NotificationRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function index(Request $request)
    {
        $clauses = [WhereClause::query('status',1), WhereClause::queryIn('user_id',['0',Auth::user()->id])];
        $with = [];
        $withCount = [];
        $orderBy = 'id:desc';
        $notifications = $this->repository->paginate(10, $clauses, $orderBy, $with, $withCount);
        return view('profile.notification', compact('notifications'));
    }

    public function detail(Request $request, $slug)
    {
        $notification = $this->repository->find([WhereClause::query('status',1), WhereClause::query('slug',$slug)]);
        if (empty($notification)) {
            return $this->errorNotFoundView();
        }

        return view('profile.notification-detail', compact('notification'));
    }
}
