<?php
namespace App\Common\Config;

class MomoConfig extends AbstractPayConfig
{
    protected $mm_endpoint;
    protected $mm_partnerCode;
    protected $mm_accessKey;
    protected $mm_secretKey;
    protected $mm_type;

    /**
     * @return MomoConfig
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function __construct(array $config)
    {
        parent::__construct('Momo', [
            'mm_endpoint', 'mm_partnerCode', 'mm_accessKey', 'mm_secretKey', 'mm_type'
        ], $config);
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->mm_endpoint;
    }

    /**
     * @return mixed
     */
    public function getPartnerCode()
    {
        return $this->mm_partnerCode;
    }

    /**
     * @return mixed
     */
    public function getAccessKey()
    {
        return $this->mm_accessKey;
    }

    /**
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->mm_secretKey;
    }


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->mm_type;
    }

}
