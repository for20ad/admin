<?php
#------------------------------------------------------------------
# Delete.php
# 삭제관련 API
# 김우진
# 2024-05-24 15:17:34
# @Desc :
#------------------------------------------------------------------

namespace Module\admin\boards\Controllers;
use Module\admin\boards\Controllers\Boards;


use Config\Services;
use DOMDocument;

class Delete extends Boards
{
    public function __construct()
    {
        parent::__construct();
    }

    public function deleteBoards()
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
            'u_idx' => [
                'label'  => '게시판 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시판 IDX 값 누락',
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
        #------------------------------------------------------------------
        # TODO: 원본 데이터값 가져온다.
        #------------------------------------------------------------------
        $modelParam                                = [];
        $modelParam['B_IDX']                       = _elm( $requests, 'u_idx' );
        $aData                                     = $this->boardsModel->getBoardsInfo( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '데이터가 없습니다. 시퀀스값을 확인해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 코드 삭제 시 하위 코드도 삭제
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aResult                                   = $this->deleteModel->deleteBoard( $modelParam );

        if ( $this->db->transStatus() === false | $aResult === false ) {
            $this->db->transRollback();
            $response['status']                    = 400;
            $response['messages']                  = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = 'BOARD 삭제 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).PHP_EOL;

        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                        = 200;
        $response['messages']                      = 'success';

        return $this->respond( $response );

    }

    public function deletePosts()
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
            'u_idx' => [
                'label'  => '게시글 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '게시글 IDX 값 누락',
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


        $modelParam                                 = [];
        $modelParam['P_IDX']                        = _elm( $requests, 'u_idx' );
        $modelParam['P_STATUS']                     = 1;
        $aData                                      = $this->boardsModel->getPostsInfo( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['error']                      = 400;
            $response['messages']                   = '게시글이 존재하지 않습니다.';

            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 게시글 삭제처리 (상태값 변경)
        #------------------------------------------------------------------
        $modelParam['P_STATUS']                     = 3;

        $this->db->transBegin();

        $aResult                                    = $this->deleteModel->deletePosts( $modelParam );

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['messages']                   = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시물 삭제 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).PHP_EOL;

        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        $this->db->transCommit();

        $response['status']                        = 200;
        $response['messages']                      = 'success';

        return $this->respond( $response );
    }

    public function fileDelete( $param = [] )
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
            'file' => [
                'label'  => 'file 경로',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '파일경로 값 누락',
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
        $modelParam['F_PATH']                      = _elm( $requests, 'file' );

        $aData                                     = $this->boardsModel->getFileInfoByPath( $modelParam );

        if( empty( $aData ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '파일정보가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------
        $aResult                                   = $this->deleteModel->deleteFileByPath( $modelParam );


        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                    = 400;
            $response['messages']                  = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response, 400 );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                  = [];
        $logParam['MB_HISTORY_CONTENT']            = '게시물 파이 삭제 -  orgData: '.json_encode( $aData, JSON_UNESCAPED_UNICODE ).PHP_EOL;

        $logParam['MB_IDX']                        = _elm( _elm( $GLOBALS, 'userInfo') , 'MB_IDX' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------


        $this->db->transCommit();

        $response['status']                        = 200;
        $response['messages']                      = 'success';

        return $this->respond( $response );



    }

}