<?php

namespace App\Common\Config;


use App\Common\SingletonPattern;

abstract class AbstractPayConfig extends SingletonPattern
{
    protected $method;

    public function __construct($paymentMethod, $attributes = [], array $config)
    {
        $this->method = $paymentMethod;
        if (empty($config)) {
            throw new \Exception("Chưa cấu hình $paymentMethod");
        }
        foreach ($attributes as $attribute) {
            $this->check($attribute, $config);
        }
    }

    protected function check($key, $config)
    {
        if (!array_key_exists($key, $config)) {
            throw new \Exception("Cấu hình $this->method chưa đặt giá trị $key");
        } else {
            $this->{$key} = $config[$key];
        }
    }
}
