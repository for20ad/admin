<?php
namespace App\Libraries;

use App\Models\ChatModel;

class ChatService{
    protected $chatModel;
    public function __construct()
    {
        $this->chatModel                    = new ChatModel();
    }
    public function addMessage( $param = [] )
    {

        $response                                   = [];
        try {
            $modelParam                             = [];
            $modelParam['MESSAGE_ID']               = _elm( $param , 'id' );
            $modelParam['ROOM_ID']                  = _elm( $param , 'room_id' );
            $modelParam['USER_ID']                  = _elm( $param , 'user' );
            $modelParam['MESSAGE']                  = _elm( $param , 'message' );
            $modelParam['TIMESTAMP']                = _elm( $param , 'timestamp' );
            $modelParam['FILEPATH']                 = _elm( $param , 'filepath' );
            $modelParam['THUMBPATH']                = _elm( $param , 'thumbpath' );



            $aResult                                = $this->chatModel->addMessage( $modelParam );

            $response['status']                     = 200;

            $response['returnMessage']              = htmlspecialchars_decode( $modelParam['MESSAGE'] );
            $response['timestamp']                  = $modelParam['TIMESTAMP'];
            $response['user']                       = $modelParam['USER_ID'];



        } catch (\Exception $e) {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = $e->getMessage();
        }
        return $response;
    }

    public function getMessages($room_id, $since)
    {
        $response                                   = [];


        try {
            $data                                   = [];
            $initial_since                          = $since;

            do {
                $aResult                            = [];
                $modelParam                         = [];
                $modelParam['ROOM_ID']              = $room_id;
                $modelParam['SINCE']                = $since;
                $aResult                            = $this->chatModel->getMessages($modelParam);


                if (!empty($aResult)) {
                    foreach ($aResult as $key => $list) {
                        $data[]                     = [
                            'MESSAGE_ID'            => _elm($list, 'MESSAGE_ID'),
                            'ROOM_ID'               => _elm($list, 'ROOM_ID'),
                            'USER_ID'               => _elm($list, 'USER_ID'),
                            'MESSAGE'               =>  htmlspecialchars_decode( _elm($list, 'MESSAGE') ),
                            'TIMESTAMP'             => _elm($list, 'TIMESTAMP'),
                            'FILES_PATH'            => json_decode( _elm($list, 'FILEPATH') ),
                            'THUMB_PATH'            => json_decode( _elm($list, 'THUMBPATH') ),
                            'TIME_TXT'              => _time_to_today_or_not(date('Y-m-d H:i:s', _elm($list, 'TIMESTAMP')))
                        ];


                    }
                }

                $since -= 86400; // 이전 날짜로 변경 (1일 전)
            } while (empty($data) && $since >= $initial_since - 86400 * 2); // 최대 2일 전까지 조회


            $response['status']                     = 200;
            $response['data']                       = $data;

        } catch (\Exception $e) {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = $e->getMessage();
        }
        return $response;
    }

    public function addRoom($param = [])
    {
        $response                                   = [];

        try {

            $modelParam                             = [];
            $modelParam['ROOM_ID']                  = _elm( $param , 'room_id' );
            $modelParam['ROOM_INFO']                = _elm( $param , 'room_info' );
            $modelParam['IS_ACTIVE']                = true;
            $modelParam['CREATE_AT']                = _elm( $param , 'timestamp' );



            $aResult                                = $this->chatModel->addRoom( $modelParam );

            $response['status']                     = 200;
            $response['room_id']                    = _elm( $param , 'room_id' );


        } catch (\Exception $e) {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = $e->getMessage();
        }
        return $response;
    }

    public function getMyRoomLists($param = [])
    {
        $response                                   = [];
        $data                                       = [];
        $user                                       = _elm($param, 'user');

        try {

            $modelParam                             = [];
            $modelParam['USER_ID']                  = $user;

            $aResult                                = $this->chatModel->getMyRoomLists( $modelParam );



            if (!empty($aResult)) {
                foreach ($aResult as $list) {
                    $data[]                         = [
                        'ROOM_ID'                   => _elm($list, 'ROOM_ID'),
                        'ROOM_INFO'                 => json_decode( _elm($list, 'ROOM_INFO'), JSON_UNESCAPED_UNICODE ),
                        'CREATED_AT'                => _time_to_today_or_not(date('Y-m-d H:i:s', _elm($list, 'CREATE_AT'))),
                        'LAST_MESSAGE'              => htmlspecialchars_decode( _elm($list, "LAST_MESSAGE") ) ?? null,
                        'LAST_TIMESTAMP'            => empty(date('Y-m-d H:i:s', _elm($list, 'LAST_TIMESTAMP')) ) === false ? _time_to_today_or_not(date('Y-m-d H:i:s', _elm($list, 'LAST_TIMESTAMP'))) : '-',
                    ];
                }


                // LAST_TIMESTAMP 기준으로 정렬
                // usort($data, function($a, $b) {
                //     return $b['LAST_TIMESTAMP'] <=> $a['LAST_TIMESTAMP'];
                // });
            }

            $response['status']                     = 200;
            $response['messages']                   = 'success';
            $response['data']                       = $data;
        } catch (\Exception $e) {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = $e->getMessage();
        }

        return $response;
    }
    public function getRoomInfo($room_id)
    {
        $response                                   = [];
        $data                                       = [];
        try{

            $modelParam                             = [];
            $modelParam['ROOM_ID']                  = $room_id;

            $aResult                                = $this->chatModel->getRoomInfo( $modelParam );

            if (!empty($aResult)) {

                $data                               = [
                    'ROOM_ID'                       => _elm($aResult, 'ROOM_ID'),
                    'ROOM_INFO'                     => _elm( $aResult, 'ROOM_INFO' ) ,
                    'CREATED_AT'                    => _time_to_today_or_not( date( 'Y-m-d H:i:s', _elm( $aResult, 'CREATE_AT' ) ) ),
                ];

            }
            $response['status']                     = 200;
            $response['data']                       = $data;

        }catch(\Exception $e){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = $e->getMessage();
        }

        return $response;
    }

}