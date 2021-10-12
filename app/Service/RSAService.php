<?php
namespace App\Service;

use Exception;

class RSAservice
{
    protected $privateKey = '';

    public function __construct()
    {
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
        if (!$data) {
            return $data;
        }
        
        openssl_private_decrypt(base64_decode($data), $decrypted, $this->privateKey);

        return $decrypted;
    }
}