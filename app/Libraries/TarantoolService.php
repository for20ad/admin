<?php

namespace App\Libraries;

use Tarantool\Client\Client;
use Tarantool\Client\Schema\Criteria;
use Tarantool\Client\Schema\Options;

class TarantoolService
{
    protected $client;

    public function __construct()
    {
        $this->client = Client::fromOptions([
            'uri' => 'tcp://49.247.40.200:3301',  // 마스터 노드의 주소
            'username' => 'timber',
            'password' => 'timber!@',
        ]);
    }

    public function addMessage( $param = [] )
    {
        $response                           = [];
        try {
            $id                             = _elm( $param , 'id' );
            $room_id                        = _elm( $param , 'room_id' );
            $user                           = _elm( $param , 'user' );
            $message                        = _elm( $param , 'message' );
            $timestamp                      = _elm( $param , 'timestamp' );
            $filepath                       = _elm( $param , 'filepath' );
            $thumbpath                      = _elm( $param , 'thumbpath' );

            $space                          = $this->client->getSpace('messages');

            $aResult                        = $space->insert([$id, $room_id, $user, $message, $timestamp, $filepath, $thumbpath]);

            $response['status']             = 200;

            $response['returnMessage']      = $message;
            $response['timestamp']          = $timestamp;
            $response['user']               = $user;



        } catch (\Exception $e) {
            $response['status']             = 400;
            $response['error']              = 400;
            $response['messages']           = $e->getMessage();
        }
        return $response;
    }

    public function getMessages($room_id, $since)
    {
        $response                           = [];
        try {
            $data = [];
            $initial_since = $since;

            do {
                $sql = "SELECT * FROM \"messages\" WHERE \"room_id\" = '$room_id' AND \"timestamp\" >= $since ORDER BY \"timestamp\" DESC";
                $result = $this->client->executeQuery($sql);
                $_data = $result->getData();

                if (!empty($_data)) {
                    foreach ($_data as $key => $list) {


                        // 메시지, 파일 경로, 썸네일 경로가 모두 비어있는 항목은 제외
                        if (empty( _elm( $list, 0 ) ) && empty( _elm( $list, 1 ) ) && empty( _elm( $list, 2 ) ) ) {
                            continue;
                        }

                        $data[] = [
                            'MESSAGE_ID'       => _elm($list, 0),
                            'ROOM_ID'          => _elm($list, 1),
                            'MEMBER_IDX'       => _elm($list, 2),
                            'MESSAGE'          => _elm($list, 3),
                            'TIMESTAMP'        => _elm($list, 4),
                            'FILES_PATH'       => json_decode( _elm($list, 5) ),
                            'THUMB_PATH'       => json_decode( _elm($list, 6) ),
                            'TIME_TXT'         => _time_to_today_or_not(date('Y-m-d H:i:s', _elm($list, 4)))
                        ];
                    }
                }

                $since -= 86400; // 이전 날짜로 변경 (1일 전)
            } while (empty($data) && $since >= $initial_since - 86400 * 2); // 최대 2일 전까지 조회

            $response['status']                = 200;
            $response['data']                  = $data;

        } catch (\Exception $e) {
            $response['status']                = 400;
            $response['error']                 = 400;
            $response['messages']              = $e->getMessage();
        }
        return $response;
    }


    public function addRoom($param = [])
    {
        $response                           = [];

        try {
            $room_id                        = _elm( $param , 'room_id' );
            //$room_info                      = json_encode( _elm( $param , 'room_info' ), JSON_UNESCAPED_UNICODE ) ;
            $room_info                      = _elm( $param , 'room_info' );
            $is_active                      = true;
            $created_at                     = _elm( $param , 'timestamp' );

            $space                          = $this->client->getSpace('roominfo');

            $aResult                        = $space->insert([$room_id, $room_info, $is_active, $created_at]);

            $response['status']             = 200;
            $response['room_id']            = $room_id;


        } catch (\Exception $e) {
            $response['status']             = 400;
            $response['error']              = 400;
            $response['messages']           = $e->getMessage();
        }
        return $response;
    }


    public function getMyRoomLists($param = [])
    {
        $response = [];
        $data = [];
        $user = _elm($param, 'user');
        try {
            // 모든 방 정보 가져오기
            $result = $this->client->executeQuery("SELECT * FROM \"roominfo\" WHERE \"is_active\"=true");

            $_data = $result->getData();

            if (!empty($_data)) {
                foreach ($_data as $list) {
                    $roomInfo = _elm($list, 1);
                    $creater = _elm($roomInfo, 'creater');
                    $joinMembers = _elm($roomInfo, 'join_member');

                    // 사용자가 방의 creater 또는 join_member에 포함되어 있는지 확인
                    if ($creater === $user || in_array($user, explode(',', $joinMembers))) {
                        $lastMessageResult = $this->client->executeQuery("SELECT * FROM \"messages\" WHERE \"room_id\" = '". _elm($list, 0) ."' ORDER BY \"timestamp\" DESC LIMIT 1");
                        $lastMessageData = $lastMessageResult->getData();
                        $lastMessage = !empty($lastMessageData) ? $lastMessageData[0] : null;

                        $data[] = [
                            'ROOM_ID' => _elm($list, 0),
                            'ROOM_INFO' => $roomInfo,
                            'CREATED_AT' => _time_to_today_or_not(date('Y-m-d H:i:s', _elm($list, 3))),
                            'LAST_MESSAGE' => $lastMessage ? _elm($lastMessage, 3) : null,
                            'LAST_TIMESTAMP' => $lastMessage ? _time_to_today_or_not(date('Y-m-d H:i:s', _elm($lastMessage, 4))) : null,
                        ];
                    }
                }

                // LAST_TIMESTAMP 기준으로 정렬
                usort($data, function($a, $b) {
                    return $b['LAST_TIMESTAMP'] <=> $a['LAST_TIMESTAMP'];
                });
            }

            $response['status'] = 200;
            $response['messages'] = 'success';
            $response['data'] = $data;
        } catch (\Exception $e) {
            $response['status'] = 400;
            $response['error'] = 400;
            $response['messages'] = $e->getMessage();
        }

        return $response;
    }









    public function getRoomLists()
    {
        $response                              = [];
        $data                                  = [];
        try{
            $sql                               = " SELECT * FROM \"roominfo\" WHERE \"is_active\"=true ORDER BY \"created_at\" DESC ";

            $result                            = $this->client->executeQuery($sql);

            $_data                             = $result->getData();

            if (!empty($_data)) {
                foreach ($_data as $key => $list) {
                    $data[]                    = [
                        'ROOM_ID'              => _elm($list, 0),
                        'ROOM_INFO'            =>  _elm( $list, 1 ) ,
                        'CREATED_AT'           => _time_to_today_or_not( date( 'Y-m-d H:i:s', _elm( $list, 3 ) ) ),
                    ];
                }
            }
            $response['status']                = 200;
            $response['data']                  = $data;

        }catch(\Exception $e){
            $response['status']                = 400;
            $response['error']                 = 400;
            $response['messages']              = $e->getMessage();
        }

        return $response;
    }



    public function getRoomInfo($room_id)
    {
        $response                              = [];
        $data                                  = [];
        try{
            $sql                               = " SELECT * FROM \"roominfo\" WHERE \"is_active\"=true AND \"room_id\"='$room_id' ORDER BY \"created_at\" DESC ";

            $result                            = $this->client->executeQuery($sql);

            $_data                             = $result->getData();

            if (!empty($_data)) {
                foreach ($_data as $key => $list) {
                    $data                      = [
                        'ROOM_ID'              => _elm($list, 0),
                        'ROOM_INFO'            => _elm( $list, 1 ) ,
                        'CREATED_AT'           => _time_to_today_or_not( date( 'Y-m-d H:i:s', _elm( $list, 3 ) ) ),
                    ];
                }
            }
            $response['status']                = 200;
            $response['data']                  = $data;

        }catch(\Exception $e){
            $response['status']                = 400;
            $response['error']                 = 400;
            $response['messages']              = $e->getMessage();
        }

        return $response;
    }

    public function updateRoom($room_id, $room_info, $is_active)
    {
        try {
            $response = $this->client->call('update_room', [$room_id, json_encode($room_info), $is_active]);
            var_dump($response);
        } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }


    public function setRommStatus( $param = [] )
    {
        $response                              = [];
        $data                                  = [];
        try{
            #------------------------------------------------------------------
            # TODO: 실행
            #------------------------------------------------------------------
            switch( _elm( $param, 'status' ) ){
                #------------------------------------------------------------------
                # TODO: 추방
                #------------------------------------------------------------------
                case 'ban' :
                    $sql                       = "";
                    break;
                #------------------------------------------------------------------
                # TODO: 강제 채팅방 종료
                #------------------------------------------------------------------
                case 'force_close' :

                    break;
                default :

                    break;
            }
            if( _elm( $param, 'status' ) == 'ven' ){

            }
            $response['status']                    = 200;
            $response['messages']                  = 'success';
            $response['data']                      = $data;
        } catch ( \Exception $e ){
            #------------------------------------------------------------------
            # TODO: 에러리턴
            #------------------------------------------------------------------
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $e->getMessage();
        }


    }

}
