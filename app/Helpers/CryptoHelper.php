<?php namespace App\Helpers;

class CryptoHelper
{
    private $cipher                                = 'AES-256-CBC'; // 사용할 대칭키 암호화 알고리즘
    private $key                                   = 'timber256'; // 암호화에 사용할 키
    private $options                               = 0; // 암호화에 사용할 옵션

    public function encrypt($data)
    {
        // 암호화 키 생성
        $key                                       = hash('sha256', $this->key, true);

        // 암호화 IV 생성
        $ivlen                                     = openssl_cipher_iv_length($this->cipher);
        $iv                                        = openssl_random_pseudo_bytes($ivlen);

        // 데이터 암호화
        $encrypted_data                            = openssl_encrypt($data, $this->cipher, $key, $this->options, $iv);

        // 암호화된 데이터와 IV를 연결하여 반환
        return base64_encode($iv.$encrypted_data);
    }

    public function decrypt($data)
    {
        // 암호화 키 생성
        $key                                       = hash('sha256', $this->key, true);

        // IV와 암호화된 데이터 추출
        $data                                      = base64_decode($data);
        $ivlen                                     = openssl_cipher_iv_length($this->cipher);
        $iv                                        = substr($data, 0, $ivlen);
        $encrypted_data                            = substr($data, $ivlen);

        // 데이터 복호화
        $decrypted_data                            = openssl_decrypt($encrypted_data, $this->cipher, $key, $this->options, $iv);

        // 복호화된 데이터 반환
        return $decrypted_data;
    }
}