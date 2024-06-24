<?php
#------------------------------------------------------------------
# Chat.php
# 챗 컨트롤러
# 김우진
# 2024-05-31 15:10:57
# @Desc :
#------------------------------------------------------------------

namespace Module\admin\chat\Controllers;
use Module\core\Controllers\ApiController;
use Module\test\Models\TestModel;
use Config\Services;
use DOMDocument;

use App\Libraries\TarantoolService;

use Exception;

class Chat extends ApiController
{
    private $db;
    private $tarantoolService;
    public function __construct()
    {
        parent::__construct();
        $this->db                                  = \Config\Database::connect();
        $this->tarantoolService                    = new TarantoolService();
    }

    public function sendMessage() {


        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        $chkParam                                  = [];
        $chkParam['room_id']                       = _elm( $requests, 'room_id' );
        $chkParam['raw_return']                    = true;

        $room_info                                 = $this->getRoomInfo( $chkParam );
        if( empty( $room_info ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '채팅방이 없습니다. 확인 후 다시 시도해 주세요.';

            return $this->respond( $response );
        }

        $returnFiles                               = [];

        $modelParam                                = [];
        $modelParam['user']                        = _elm( _elm( $GLOBALS, 'userInfo'), 'MB_USERID' );
        $modelParam['room_id']                     = _elm( $requests, 'room_id' );
        $modelParam['message']                     = _elm( $requests, 'message' );
        $modelParam['timestamp']                   = time();
        $modelParam['id']                          = _uniqid( 16, false, 'numberic' ); // 임의의 ID 생성
        $modelParam['filepath']                    = null;
        $modelParam['thumbpath']                   = null;


        #------------------------------------------------------------------
        # TODO: 파일 처리
        #------------------------------------------------------------------
        $aConfig                                   = [
            "path"      => 'chat/'._elm($requests, 'room_id'),
            'mimes'     => 'gif|jpg|jpeg|png',
        ];
        for( $i=0 ; $i < 20 ; $i++ ){
            if( empty( _elm( $requests, 'file'.$i ) ) === false ){

                $data                              = '';
                $data                              = _elm( $requests, 'file'.$i );
                list($type, $data)                 = explode(';', $data);
                list(, $data)                      = explode(',', $data);
                $data                              = base64_decode($data);

                $file_return                       = $this->_upload_base64( $data , 'file'.$i  , $aConfig );

                #------------------------------------------------------------------
                # TODO: 파일처리 실패 시
                #------------------------------------------------------------------
                if( _elm($file_return , 'status') === false ){
                    $this->db->transRollback();
                    $response['status']            = 400;
                    $response['messages']          = '파일 처리중 오류빌셍.. 다시 시도해주세요.';
                    return $this->respond( $response, 400 );
                }

                #------------------------------------------------------------------
                # TODO: 리턴값 세팅
                #------------------------------------------------------------------
                $filePath                          = _elm( $file_return, 'uploaded_path');
                $_hosts                            = 'https://api.brav.co.kr/';
                if ($filePath) {
                    $returnFiles['real'][$i]       = $_hosts.$filePath;
                    $thumbPath                     = $this->createThumbnail($filePath);
                    if ($thumbPath) {
                        $returnFiles['thumb'][$i]  = $_hosts.$thumbPath;
                    }
                }
            }
        }

        if( empty( $returnFiles ) === false ){
            $modelParam['filepath']                = json_encode( _elm($returnFiles, 'real' ) );
            $modelParam['thumbpath']               = json_encode( _elm($returnFiles, 'thumb' ) );
        }

        $aResult                                   = $this->tarantoolService->addMessage($modelParam);

        $response                                  = $aResult;

        if( empty( $returnFiles ) === false ){
            $response['files']                     = $returnFiles;
        }

        return $this->respond( $response );

        // 메시지 추가
        //$tarantoolService->addMessage(1, 'test_room_1', 'user1', 'Hello, world!', time(), '/path/to/file');
    }



    private function createThumbnail($filePath)
    {
        $thumbnailPath = pathinfo($filePath, PATHINFO_DIRNAME) . '/thumb/' . pathinfo($filePath, PATHINFO_FILENAME) . '_thumb.jpg';
        $directory     = pathinfo($filePath, PATHINFO_DIRNAME) . '/thumb/';

        if (!is_dir( $directory ) ) {
            mkdir($directory, 0755, true);
        }

        list($originalWidth, $originalHeight) = getimagesize($filePath);
        $maxWidth = 150; // 썸네일 최대 너비
        $maxHeight = 150; // 썸네일 최대 높이

        // 원본 이미지의 비율 유지
        $ratio = $originalWidth / $originalHeight;
        if ($maxWidth / $maxHeight > $ratio) {
            $newWidth = $maxHeight * $ratio;
            $newHeight = $maxHeight;
        } else {
            $newHeight = $maxWidth / $ratio;
            $newWidth = $maxWidth;
        }

        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        $source = imagecreatefromjpeg($filePath);

        // 썸네일 생성
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        imagejpeg($thumb, $thumbnailPath);

        // 메모리 해제
        imagedestroy($thumb);
        imagedestroy($source);

        return $thumbnailPath;
    }


    public function getMessages()
    {

        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        $chkParam                                  = [];
        $chkParam['room_id']                       = _elm( $requests, 'room_id' );
        $chkParam['raw_return']                    = true;

        $room_info                                 = $this->getRoomInfo( $chkParam );
        if( empty( $room_info ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '채팅방이 없습니다. 확인 후 다시 시도해 주세요.';

            return $this->respond( $response );
        }

        $lastDate                                  = _elm($requests, 'lastDate', null, true);
        $since                                     = empty( $lastDate ) === false ? strtotime($lastDate) : strtotime(date('Y-m-d', time() - 86400 * 2));


        $room_id                                   = _elm($requests, 'room_id');

        $messages                                  = $this->tarantoolService->getMessages($room_id, $since);

        $response                                  = $messages;


        return $this->respond($response);
    }

    public function addRoom( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                    = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'room_title' => [
                'label'  => '방제목',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '빙제목',
                ],
            ],
            'room_type' => [
                'label'  => '공개 여부',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '공개 여부값 누락',
                ],
            ],
            'room_type' => [
                'label'  => '공개 여부',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '공개 여부값 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }
        $modelParam                                = [];

        if( _elm( $requests, 'room_type' ) == 'secret' ){
            if( empty( _elm( $requests, 'join_member' ) ) === true ){
                $response['status']                = 400;
                $response['error']                 = 400;
                $response['messages']              = '초대할 대상 누락';

                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------

        $modelParam['room_id']                     = _uniqid(16,true);

        $modelParam['room_info']                   = [
            'room_title'                           => _elm( $requests, 'room_title' ),
            'room_type'                            => _elm( $requests, 'room_type' ),
            'room_discript'                        => _elm( $requests, 'room_discript' ),
            'join_member'                          => _elm( $requests, 'join_member' ),
            'creater'                              => _elm( $requests, 'creater' ),
        ];
        $modelParam['timestamp']                   = time();

        $aResult                                   = $this->tarantoolService->addRoom( $modelParam );

        $response                                  = $aResult;

        return $this->respond( $response );
    }

    public function getRoomLists()
    {
        $response                                  = $this->_initApiResponse();

        $room_lists                                = $this->tarantoolService->getRoomLists();

        $response                                  = $room_lists;

        return $this->respond( $response );

    }

    public function getRoomInfo( $param = [] )
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        if( empty( $param ) === false ){
            $requests                              = $param;
        }

        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                    = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'room_id' => [
                'label'  => 'room_id',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'room_id 값 누락',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }

        $room_info                                 = $this->tarantoolService->getRoomInfo( _elm( $requests, 'room_id' ) );

        if( _elm( $requests, 'raw_return' ) === true ){
            return _elm( $room_info, 'data' );
        }

        $response                                  = $room_info;

        return $this->respond( $response );

    }


    public function setRommStatus()
    {
        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();



        $validation                                = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                    = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'room_id' => [
                'label'  => 'room_id',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => 'room_id 값 누락',
                ],
            ],
            'status' => [
                'label'  => '상태값',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상태값 누락',
                ],
            ],
            'status_message' => [
                'label'  => '상태값에 따른 메시지',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상태값 누락',
                ],
            ],
        ]);

        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = $validation->getErrors();

            return $this->respond($response, 400);
        }

        $chkParam                                  = [];
        $chkParam['room_id']                       = _elm( $requests, 'room_id' );
        $chkParam['raw_return']                    = true;

        $room_info                                 = $this->getRoomInfo( $chkParam );
        if( empty( $room_info ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '채팅방이 없습니다. 확인 후 다시 시도해 주세요.';

            return $this->respond( $response );
        }

        $modelParam                                = [];
        $modelParam                                = $requests;

        $aResult                                   = $this->tarantoolService->setRommStatus( $modelParam );


        return $this->respond( $response );

    }

}
