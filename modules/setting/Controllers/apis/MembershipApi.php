<?php

namespace Module\setting\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\setting\Models\MembershipModel;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class MembershipApi extends ApiController
{
    protected $memberlib;
    protected $db;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
    }

    public function addGrade()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $membershipModel                            = new MembershipModel();

        $files                                      = $this->request->getFiles();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_name' => [
                'label'  => '등급명',
                'rules'  => 'trim|required|is_unique[MEMBER_GRADE.G_NAME]',
                'errors' => [
                    'required'      => '등급명을 입력하세요.',
                    'unique_user_id' => '등급명이 이미 존재합니다.',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $max_sort                                   = $membershipModel->getMembershipGradeMaxSortNum();
        $modelParam                                 = [];

        $modelParam['G_NAME']                       = _elm( $requests, 'i_name' );
        $modelParam['G_SAVE_RATE']                  = _elm( $requests, 'i_save_rate' );
        $modelParam['G_DELIVERY_FREE']              = _elm( $requests, 'i_delivery_free' );
        $modelParam['G_SORT']                       = (int) $max_sort + 1;

        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------
        if( empty( $files ) === false ){

            $config                                 = [
                'path' => 'grade_icon',
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            foreach( $files as $file ){
                $file_return                        = $this->_upload( $file, $config );

                #------------------------------------------------------------------
                # TODO: 파일처리 실패 시
                #------------------------------------------------------------------
                if( _elm($file_return , 'status') === false ){
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = _elm( $file_return, 'error' );
                    return $this->respond( $response, 400 );
                }

                #------------------------------------------------------------------
                # TODO: 데이터모델 세팅
                #------------------------------------------------------------------

                $modelParam['G_ICON_PATH']          = _elm( $file_return, 'uploaded_path');
                $modelParam['G_ICON_NAME']          = _elm( $file_return, 'org_name');
            }
        }
        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------

        $this->db->transBegin();

        $aStatus                                    = $membershipModel->insertMembershipGrade( $modelParam );


        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '회원등급 추가 - newData:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }


        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '등록이 완료되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/membershipGrade' );

        return $this->respond( $response );

    }

    public function modifyGrade( $param = [] )
    {
        $pageDatas                                  = [];
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();

        $membershipModel                            = new MembershipModel();

        if ($this->memberlib->isAdminLogin() === false)
        {
            $response['status']                     = 500;
            $response['alert']                      = '로그인 후 이용 가능합니다.';

            return $this->respond($response);
        }

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post');
        }

        if( count( _elm($requests, 'i_grade_idx' ) ) < 1 ){
            $response['status']                     = 400;
            $response['alert']                      = '수정할 등급을 선택해주세요.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 메뉴 수정
        #------------------------------------------------------------------
        $this->db->transBegin();

        foreach( _elm( $requests, 'i_grade_idx' )  as $vGradeIdx ){
            $aData                                  = $membershipModel->getMembershipGradeListsByIdx($vGradeIdx);
            $modelParam                             = [];
            $modelParam['G_NAME']                   = _elm(_elm($requests, 'i_name', []), $vGradeIdx, '');
            $modelParam['G_SAVE_RATE']              = _elm(_elm($requests, 'i_save_rate', []), $vGradeIdx, '');
            $modelParam['G_DELIVERY_FREE']          = _elm(_elm($requests, 'i_delivery_free', []), $vGradeIdx, '');


            #------------------------------------------------------------------
            # TODO: 파일처리
            #------------------------------------------------------------------

            if( empty( _elm( _elm( $files, 'i_icon' ), $vGradeIdx ) ) === false ){
                $config                             = [
                    'path' => 'grade_icon',
                    'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
                ];

                $file = _elm( _elm( $files, 'i_icon' ), $vGradeIdx );

                $file_return                        = $this->_upload( $file, $config );

                #------------------------------------------------------------------
                # TODO: 파일처리 실패 시
                #------------------------------------------------------------------
                if( _elm($file_return , 'status') === false ){
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = _elm( $file_return, 'error' );
                    return $this->respond( $response, 400 );
                }

                #------------------------------------------------------------------
                # TODO: 데이터모델 세팅
                #------------------------------------------------------------------

                $modelParam['G_ICON_PATH']          = _elm( $file_return, 'uploaded_path');
                $modelParam['G_ICON_NAME']          = _elm( $file_return, 'org_name');

                #------------------------------------------------------------------
                # TODO: 기존파일 삭제처리
                #------------------------------------------------------------------
                if( empty( _elm( $aData, 'G_ICON_PATH' ) ) === false ){
                    if( file_exists( WRITEPATH._elm( $aData, 'G_ICON_PATH' ) ) ){
                        @unlink( WRITEPATH._elm( $aData, 'G_ICON_PATH' )  );
                    }
                }

            }

            $modelParam['G_IDX']                    = $vGradeIdx;

            #------------------------------------------------------------------
            # TODO: run
            #------------------------------------------------------------------
            $aResult                                = $membershipModel->updateMembershipGrade($modelParam);

            if ( $this->db->transStatus() === false || $aResult === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response, 400 );
            }

            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                               = [];
            $logParam['MB_HISTORY_CONTENT']         = '회원등급 수정 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ). ' // newData::'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE );
            $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------

        }

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '수정되었습니다.';
        $response['reload']                         = true;

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond($response);

    }

    public function updateGradeSort()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $membershipModel                            = new MembershipModel();

        $this->db->transBegin();

        foreach( _elm( $requests, 'sort' ) as $sort => $gidx ){
            $modelParam                             = [];
            $modelParam['G_SORT']                   = $sort + 1;
            $modelParam['G_IDX']                    = $gidx;

            $aStatus                                = $membershipModel->setMembershipGradeSort( $modelParam );

            if ( $this->db->transStatus() === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '';

        return $this->respond( $response );
    }

    public function deleteMembershipGrade( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $membershipModel                            = new MembershipModel();
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        #------------------------------------------------------------------
        # TODO: 삭제 진행
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['G_IDX']                        = _elm( $requests, 'g_idx' );
        $aData                                      = $membershipModel->getMembershopGradeByIdx( _elm( $requests, 'g_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '등급 데이터가 없습니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $aStatus                                    = $membershipModel->deleteMembershipGrade( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------


        if( empty( _elm( $aData, 'G_ICON_PATH' ) ) === false ){
            if( file_exists( WRITEPATH._elm( $aData, 'G_ICON_PATH' ) ) ){
                @unlink( WRITEPATH._elm( $aData, 'G_ICON_PATH' )  );
            }
        }



        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '회원 등급 삭제 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '삭제되었습니다.';
        $response['redirect_url']                   = _link_url( '/setting/membershipGrade' );

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond($response);



    }

    public function deleteMembershipGradeIcon( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $membershipModel                            = new MembershipModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'g_idx' => [
                'label'  => '등급IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '등급IDX 누락 다시 시도해주세요',
                ],
            ],
        ]);
        #------------------------------------------------------------------
        # TODO: parameter 검사 수행
        #------------------------------------------------------------------
        if ( $isRule === true && $validation->run($requests) === false )
        {
            $response['status']                     = 400;
            $response['error']                      = 400;
            $messages                               = [];
            foreach( $validation->getErrors() as $field => $message ){
                $messages[]                         = $message;
            }
            $response['alert']                      = join( PHP_EOL, $messages);

            return $this->respond($response);
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['G_IDX']                        = _elm( $requests, 'g_idx' );
        $aData                                      = $membershipModel->getMembershopGradeByIdx( $modelParam );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '등급 데이터가 없습니다. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: 아이콘 삭제
        #------------------------------------------------------------------
        $aStatus                                    = $membershipModel->deleteMembershipGradeIcon( $modelParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 파일 삭제
        #------------------------------------------------------------------
        if( empty( _elm( $aData, 'G_ICON_PATH' ) ) === false ){
            if( file_exists( WRITEPATH._elm( $aData, 'G_ICON_PATH' ) ) ){
                @unlink( WRITEPATH._elm( $aData, 'G_ICON_PATH' )  );
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '회원 등급 아이콘 - orgdata:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response['status']                         = 200;
        $response['alert']                          = '삭제되었습니다.';
        $response['reload']                         = true;

        if( _elm( $requests, 'rawData' ) === true ){
            return $response;
        }

        return $this->respond($response);


    }

}