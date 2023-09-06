<?php

namespace App\Utils\Logistics;

use App\Models\StoreInformation;
use App\Models\Order;
use App\Models\OrderShip;
use App\Models\ShippingService;
use App\Models\ShippingStore;
use App\Models\ShippingUnit;

abstract class GiaoHangAbstractUtil
{
    protected $input;
    protected $name;
    protected $endpoint;
    protected $token;
    protected $username;
    protected $password;
    protected $shippingUnit;

    public function __construct($input)
    {
        if (is_string($input)) {
            $unit = ShippingUnit::where('name', $input)->first();
            $this->loadConfig($unit);
        }
        if (is_numeric($input)) {
            $unit = ShippingUnit::find($input);
            $this->loadConfig($unit);
        }
        if ($input instanceof \App\Models\ShippingUnit) {
            $this->loadConfig($input);
        }

    }

    public function loadConfig(ShippingUnit $unit)
    {
        $this->shippingUnit = $unit;
        $this->name = $unit->name;
        $this->endpoint = $unit->endpoint;
        $this->token = $unit->token;
        $this->username = $unit->username;
        $this->password = $unit->password;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     */
    public function setInput($input): void
    {
        $this->input = $input;
    }

    abstract public function getServices();

    abstract public function getStores();

    abstract public function getOrder(OrderShip $order);

    abstract public function createOrder(Order $order, ShippingStore $store, ShippingService $service = null);

    abstract public function cancelOrder(OrderShip $order);

    public function printOrders($orders)
    {
        $dataStoreOrder = [
            'logo' => StoreInformation::whereName('Logo')->first()->value,
            'name' => StoreInformation::whereName('Name')->first()->value,
            'address' => StoreInformation::whereName('Address')->first()->value,
            'phone' => StoreInformation::whereName('Phone')->first()->value,
            'barcodeType' => 'CODE128',
        ];
        return view('giaohang.default', compact('orders', 'dataStoreOrder'))->render();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param mixed $endpoint
     */
    public function setEndpoint($endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return ShippingUnit
     */
    public function getShippingUnit(): ShippingUnit
    {
        return $this->shippingUnit;
    }

    /**
     * @param mixed $shippingUnit
     */
    public function setShippingUnit($shippingUnit): void
    {
        $this->shippingUnit = $shippingUnit;
    }

    public function returnMethod($curl)
    {
        if ($curl->status == 200) {
            if ($curl->content->status == 200) {
                return $curl->content->data;
            } else {
                throw new \Exception($curl->content->message);
            }
        } else {
            throw new \Exception('Lỗi kết nối đến ' . $this->name);
        }
    }

}
