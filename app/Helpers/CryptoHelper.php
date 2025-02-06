<?php namespace App\Helpers;

class CryptoHelper
{
    private $cipher                                = 'AES-256-CBC'; // 사용할 대칭키 암호화 알고리즘
    private $key                                   = 'timber256'; // 암호화에 사용할 키
    private $options                               = 0; // 암호화에 사용할 옵션

    // public function encrypt($data)
    // {
    //     // 암호화 키 생성
    //     $key                                       = hash('sha256', $this->key, true);
    //     echo "key::".$key.PHP_EOL;
    //     // 암호화 IV 생성
    //     $ivlen                                     = openssl_cipher_iv_length($this->cipher);
    //     echo "ivlen::".$ivlen.PHP_EOL;
    //     $iv                                        = openssl_random_pseudo_bytes($ivlen);
    //     $iv_hex                                    = bin2hex( $iv );
    //     echo  " iv_hex ::  ".$iv_hex   ; // Hex 형식으로 출력

    //     echo "iv::".$iv.PHP_EOL;

    //     // 데이터 암호화
    //     $encrypted_data                            = openssl_encrypt($data, $this->cipher, $key, $this->options, $iv);

    //     echo "result :: ".base64_encode($iv.$encrypted_data).PHP_EOL;
    //     // 암호화된 데이터와 IV를 연결하여 반환
    //     return base64_encode($iv.$encrypted_data);
    // }

    // public function decrypt($data)
    // {
    //     // 암호화 키 생성
    //     $key                                       = hash('sha256', $this->key, true);
    //     echo "key::".$key.PHP_EOL;
    //     // IV와 암호화된 데이터 추출
    //     $data                                      = base64_decode($data);
    //     $ivlen                                     = openssl_cipher_iv_length($this->cipher);
    //     echo "ivlen::".$ivlen.PHP_EOL;
    //     $iv                                        = substr($data, 0, $ivlen);
    //     echo "iv::".$iv.PHP_EOL;
    //     $encrypted_data                            = substr($data, $ivlen);

    //     // 데이터 복호화
    //     $decrypted_data                            = openssl_decrypt($encrypted_data, $this->cipher, $key, $this->options, $iv);

    //     // 복호화된 데이터 반환
    //     return $decrypted_data;
    // }



    public function encrypt($data)
    {
        // 암호화 키 생성
        $key = hash('sha256', $this->key, true);

        // 암호화 IV 생성
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        // 데이터 암호화
        $encrypted_data = openssl_encrypt($data, $this->cipher, $key, $this->options, $iv);

        // IV를 Hex로 변환 후 암호화된 데이터와 결합
        $ivHex = bin2hex($iv);
        $result = base64_encode($ivHex . "::" . $encrypted_data);

        // 디버깅 출력
        // echo "Key: " . bin2hex($key) . PHP_EOL;
        // echo "IV (Hex): $ivHex" . PHP_EOL;
        // echo "Encrypted Data: $encrypted_data" . PHP_EOL;
        // echo "Result: $result" . PHP_EOL;

        return $result;
    }

    public function decrypt($data)
    {
        // 암호화 키 생성
        $key = hash('sha256', $this->key, true);

        // Base64 디코딩
        $decodedData = base64_decode($data);

        // IV와 암호화된 데이터 분리
        [$ivHex, $encrypted_data] = explode('::', $decodedData);

        // Hex -> Binary 변환
        if (!ctype_xdigit($ivHex)) {
            throw new Exception("Invalid IV format: Not a hexadecimal string.");
        }
        $iv = hex2bin($ivHex);

        // 데이터 복호화
        $decrypted_data = openssl_decrypt($encrypted_data, $this->cipher, $key, $this->options, $iv);

        // // 디버깅 출력
        // echo "Key: " . bin2hex($key) . PHP_EOL;
        // echo "IV (Hex): $ivHex" . PHP_EOL;
        // echo "Decrypted Data: $decrypted_data" . PHP_EOL;

        return $decrypted_data;
    }

}