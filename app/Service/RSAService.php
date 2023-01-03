<?php
namespace App\Service;

use Exception;

class RSAService
{
    protected $privateKey = '';

    /**
     * 是否进行rsa解码
     *
     * @var boolean
     */
    protected $allowDecode = true;

    public function __construct()
    {
        if (!file_exists(base_path('rsa_1024_priv.pem'))) {
            $this->allowDecode = false;
            return;
        }

        $this->privateKey = openssl_pkey_get_private('file://' . base_path('rsa_1024_priv.pem'));

        if (!$this->privateKey) {
            throw new Exception(__('message.unknown private key'));
        }
    }
    /**
     * 解密
     *
     * @param string $data
     * @return string
     */
    public function decrypt($data)
    {
        if (!$data || !$this->allowDecode) {
            return $data;
        }
        
        openssl_private_decrypt(base64_decode($data), $decrypted, $this->privateKey);

        return $decrypted;
    }
}