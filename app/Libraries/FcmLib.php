<?php
namespace App\Libraries;

use Config\Talk;
use App\Models\FcmModel;

class FcmLib
{
    function sendPushNotification($to, $title, $message) {
        $url = 'https://fcm.googleapis.com/fcm/send';



        // // 알림 데이터
        // $fields = [
        //     'to' => $to, // 단일 기기 토큰 또는 주제명 '/topics/your_topic'
        //     'notification' => [
        //         'title' => $title,
        //         'body'  => $message,
        //         'sound' => 'default',
        //     ],
        //     'data' => [
        //         'title' => $title,
        //         'body' => $message,
        //     ]
        // ];

        // // 헤더 정보
        // $headers = [
        //     'Authorization: key=' . FIREBASE_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // // cURL을 사용한 POST 요청 전송
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL 인증서 문제 무시
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // $result = curl_exec($ch);
        // if ($result === FALSE) {
        //     die('Curl failed: ' . curl_error($ch));
        // }

        // curl_close($ch);

        #------------------------------------------------------------------
        # TODO: DB 저장 로직
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['F_DI_TOKEN']                   = $to;
        $modelParam['F_DI_MESSAGE_TITLE']           = $title;

        $modelParam['F_DI_MESSAGE_BODY']            = $message;
        $modelParam['F_SEND_AT']                    = date( 'Y-m-d H:i:s' );

        $fcmModel                                   = new FcmModel();
        $result                                     = $fcmModel->insertFcmMessage( $modelParam );


        return $result;
    }
}