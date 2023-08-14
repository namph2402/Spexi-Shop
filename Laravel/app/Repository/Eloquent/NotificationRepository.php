<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\Notification;
use App\Repository\NotificationRepositoryInterface;

class NotificationRepository extends EloquentRepository implements NotificationRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Notification::class;
    }

}
