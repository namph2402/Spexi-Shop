<?php

namespace App\Utils;


use Illuminate\Database\Eloquent\Model;

class AuthUtil
{
    protected $model;

    public function __construct()
    {
    }

    public static function getInstance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new AuthUtil();
        }
        return $instance;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    public function setArrayModel(array $model)
    {
        $this->model = $model;
    }
}
