<?php
namespace App\Common\Config;

class VnPayConfig extends AbstractPayConfig
{
    protected $vnp_Url;
    protected $vnp_TmnCode;
    protected $vnp_HashSecret;
    protected $vnp_Locale;
    protected $vnp_Version;

    /**
     * @return VnPayConfig
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    public function __construct(array $config)
    {
        parent::__construct('VNPay', [
            'vnp_Url', 'vnp_TmnCode', 'vnp_HashSecret', 'vnp_Locale', 'vnp_Version'
        ], $config);
    }

    /**
     * @return mixed
     */
    public function getVnpUrl()
    {
        return $this->vnp_Url;
    }

    /**
     * @return mixed
     */
    public function getVnpTmnCode()
    {
        return $this->vnp_TmnCode;
    }

    /**
     * @return mixed
     */
    public function getVnpHashSecret()
    {
        return $this->vnp_HashSecret;
    }

    /**
     * @return mixed
     */
    public function getVnpLocale()
    {
        return $this->vnp_Locale;
    }

    /**
     * @return mixed
     */
    public function getVnpVersion()
    {
        return $this->vnp_Version;
    }

}
