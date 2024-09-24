<?php

namespace Module\goods\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\goods\Models\CategoryModel;

use Module\goods\Config\Config as goodsConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class CategoryApi extends ApiController
{
    protected $memberlib;
    protected $db;
    protected $aConfig;
    public function __construct()
    {

        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->aConfig                              = new goodsConfig();
        $this->aConfig                              = $this->aConfig->goods;
    }

    public function getCategoryLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $categoryModel                              = new CategoryModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];


        if( empty( _elm( $requests , 's_keyword' ) ) === false )
        {
            switch( _elm( $requests , 's_condition' ) )
            {
                case 'mb_id' :
                    $modelParam['MB_USERID']        = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_name' :
                    $modelParam['MB_USERNAME']      = _elm( $requests , 's_keyword' );
                    break;
                case 'title' :
                    $modelParam['F_TITLE']          = _elm( $requests , 's_keyword' );
                    break;
            }
        }

        $modelParam['order']                        = ' C_SORT ASC';

        ###########################################################
        $aLISTS_RESULT                              = $categoryModel->getCategoryLists( $modelParam );

        $cate_lists                                 = _elm( $aLISTS_RESULT, 'lists' );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        $cate                                       = [];

        #############################################################


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['_parent_idx']              = _elm( $requests, 'parent_idx' );

            #------------------------------------------------------------------
            # TODO: 메뉴 트리 적용
            #------------------------------------------------------------------

            if( empty( $cate_lists ) === false  ){
                #------------------------------------------------------------------
                # TODO: 트리형식으로 리스트 변경
                #------------------------------------------------------------------

                $view_datas['cate_tree_lists']      = _build_tree( $cate_lists, _elm($cate_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

                foreach (_elm($view_datas, 'cate_tree_lists', []) as $kIDX => $vCATE)
                {
                    $cate[_elm($vCATE, 'C_IDX')]    = _elm($vCATE, 'C_CATE_NAME');

                    if (empty($vCODE['CHILD']) === false)
                    {
                        foreach (_elm($vCATE, 'CHILD', []) as $kIDX_CHILD => $vCATE_CHILD)
                        {
                            $cate[_elm($vCATE_CHILD, 'C_IDX')] = '   >>>' ._elm($vCATE_CHILD, 'C_CATE_NAME');
                        }
                    }
                }
            }



            $view_datas['lists']                    = $cate;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\category\lists_row' , ['owensView' => $owensView] );


        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function cateDetail()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $requests, 'cate_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $categoryModel                              = new CategoryModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $_parentInfo                                = $categoryModel->getParentInfo( _elm( $requests, 'parentIdx' )  );

        if( empty( $_parentInfo ) ){
            $_parentInfo['C_PARENT_IDX']            = 0;
            $_parentInfo['C_CATE_NAME']             = '최상위';
        }
        $aData                                      = $categoryModel->getCategoryDataByIdx( _elm( $requests, 'cate_idx' ) );

        if( empty($aData) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '카테고리 데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }



        $questions                                  = $categoryModel->getQuestions( _elm( $aData, 'C_IDX' ) );
        if( empty( $questions ) === false  ){
            foreach( $questions as $qKey => $question ){
                $orderKey                           = $qKey+1;
                $aData['questions'][$orderKey]['q_idx']      = _elm( $question, 'Q_IDX' );
                $aData['questions'][$orderKey]['question']   = _elm( $question, 'Q_QUESTION' );
                $exampleDatas                       = $categoryModel->getQuestionsExamples( _elm( $question, 'Q_IDX' ) );
                if( empty( $exampleDatas ) === false ){
                    foreach( $exampleDatas as $eKey => $example ){
                        $exOrderKey                 = $eKey +1;
                        $aData['questions'][$orderKey]['values'][$exOrderKey]['e_idx']      = _elm( $example, 'E_IDX' );
                        $aData['questions'][$orderKey]['values'][$exOrderKey]['description']= _elm( $example, 'E_KEY' );
                        $aData['questions'][$orderKey]['values'][$exOrderKey]['value']      = _elm( $example, 'E_VALUE' );
                    }
                }

            }


        }
        $view_datas['aData']                        = $aData;
        $view_datas['parentInfo']                   = $_parentInfo;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\category\_detail' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }

    public function cateRegister()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $categoryModel                              = new CategoryModel();


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;
        $_cateCode                                  = $categoryModel->getCateCode( _elm( $requests, 'parentIdx' ) );
        $_parentInfo                                = $categoryModel->getParentInfo( _elm( $requests, 'parentIdx' )  );

        $_max                                       = substr(_elm($_cateCode, 'max'), -1) ?? 0;
        $max                                        = (int)$_max + 1;




        if( empty( $_parentInfo ) ){
            $_parentInfo['C_PARENT_IDX']            = 0;
            $_parentInfo['C_CATE_NAME']             = '최상위';
            if ( _elm($_cateCode, 'max' ) >= 999) {
                $newCode                            = str_pad(( ( $max ) % 999), 3, '0', STR_PAD_LEFT);
            } else {
                $newCode                            = str_pad( ( $max ), 3, '0', STR_PAD_LEFT);
            }
        }else{
            if ( _elm($_cateCode, 'max' ) >= 999) {
                $newCode                            = str_pad(( ( $max ) % 999), 3, '0', STR_PAD_LEFT);
            } else {
                $newCode                            = str_pad( ( $max ), 3, '0', STR_PAD_LEFT);
            }

            $newCode                                = _elm( $_parentInfo, 'C_PARENT_CODE' ).$newCode;
        }



        $view_datas['cateCode']                     = $newCode;
        $view_datas['parentInfo']                   = $_parentInfo;

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\category\_register' , ['owensView' => $owensView] );

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);

    }
    public function updateCategoryOrder(){
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $categoryModel                              = new CategoryModel();

        if( empty( _elm( $requests, 'order' ) ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 정렬순서 업데이트
        #------------------------------------------------------------------

        $this->db->transBegin();

        foreach( _elm( $requests, 'order' ) as $sort => $c_idx ){
            $modelParam                             = [];
            $modelParam['C_SORT']                   = $sort + 1;
            $modelParam['C_IDX']                    = $c_idx;

            #------------------------------------------------------------------
            # TODO: run
            #------------------------------------------------------------------
            $aStatus                                = $categoryModel->setCateSort( $modelParam );

            if ( $this->db->transStatus() === false || $aStatus === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '정렬 순서 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                              = [];
        $logParam['MB_HISTORY_CONTENT']        = '상품 카테고리 정렬순서 변경 - data:'.json_encode( _elm($requests, 'order'), JSON_UNESCAPED_UNICODE ) ;
        $logParam['MB_IDX']                    = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                = 400;
            $response['alert']                 = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------



        $this->db->transCommit();
        $response                              = $this->_unset($response);
        $response['status']                    = 200;

        return $this->respond($response);
    }

    public function categoryRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        $categoryModel                              = new CategoryModel();

        $validation                                 = \Config\Services::validation();

        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_cate_name' => [
                'label'  => '카테고리명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '카테고리 이름을 입력하세요.',
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
        $_sort                                      = $categoryModel->getSortMax( _elm( $requests, 'i_parent_idx' ) );

        $modelParam                                 = [];
        $modelParam['C_PARENT_IDX']                 = _elm( $requests, 'i_parent_idx' );
        $modelParam['C_CATE_NAME']                  = _elm( $requests, 'i_cate_name' );
        $modelParam['C_CATE_CODE']                  = _elm( $requests, 'i_cate_code' );
        $modelParam['C_ORDERING_CD']                = _elm( $requests, 'i_ordering_cd' );
        $modelParam['C_SORT']                       = _elm( $_sort, 'max' ) + 1;
        $modelParam['C_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['C_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['C_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['C_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 통합 데이터 세팅
        #------------------------------------------------------------------
        $_modelParam                                = [];
        $_modelParam['table']                       = 'GOODS_CATEGORY';
        $_modelParam['data']                        = $modelParam;

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aIdx                                       = $this->LogModel->integratInsert( $_modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 리뷰지문등록
        #------------------------------------------------------------------

        if( empty( _elm( $requests, 'questions' ) ) === false ){
            foreach( _elm( $requests, 'questions' ) as $qKey => $question ){
                if( empty( _elm( $question, 'values' ) ) === false ){
                    $qParam                         = [];
                    $qParam['Q_QUESTION']           = _elm( $question, 'question' );
                    $qParam['Q_CATE_IDX']           = $aIdx;
                    $qParam['Q_SORT']               = $qKey;
                    $qParam['Q_CREATE_AT']          = date( 'Y-m-d H:i:s' );
                    $qParam['Q_CREATE_IP']          = $this->request->getIPAddress();
                    $qParam['Q_CREATE_MB_IDX']      = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $qIdx                           = $categoryModel->insertQuestions( $qParam );
                    if ( $this->db->transStatus() === false || $qIdx === false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '지문 등록 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                    foreach( _elm( $question, 'values' ) as $eKey => $example ){
                        $eParam                     = [];
                        $eParam['E_Q_IDX']          = $qIdx;
                        $eParam['E_KEY']            = _elm( $example, 'description' );
                        $eParam['E_VALUE']          = _elm( $example, 'value' );
                        $eParam['E_SORT']           = $eKey;
                        $eParam['E_CREATE_AT']      =  date( 'Y-m-d H:i:s' );
                        $eParam['E_CREATE_IP']      = $this->request->getIPAddress();
                        $eParam['E_CREATE_MB_IDX']  = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                        $eIdx                       = $categoryModel->insertQuestionExample( $eParam );

                        if ( $this->db->transStatus() === false || $eIdx === false ) {
                            $this->db->transRollback();
                            $response['status']     = 400;
                            $response['alert']      = '예제 등록 처리중 오류발생.. 다시 시도해주세요.';
                            return $this->respond( $response );
                        }

                    }
                }
            }
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 카테고리 등록 - data:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE ) ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '저장 되었습니다.';
        $response['reload']                         = true;

        return $this->respond( $response );

    }

    public function categoryDetailProc()
    {

        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $categoryModel                              = new CategoryModel();

        $validation                                 = \Config\Services::validation();

        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_parent_idx' => [
                'label'  => '상위 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상위 IDX가 없습니다. 새로고침 후 시도해주세요.',
                ],
            ],
            'i_cate_name' => [
                'label'  => '카테고리명',
                'rules'  => 'trim|required|regex_match[/^[a-zA-Z0-9가-힣]+$/]',
                'errors' => [
                    'required' => '카테고리 이름을 입력하세요.',
                    'regex_match' => '카테고리 이름는 특수문자를 허용하지 않습니다.',
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
        $modelParam['C_IDX']                        = _elm( $requests, 'i_cate_idx' );
        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $categoryModel->getCategoryDataByIdx( _elm( $requests, 'i_cate_idx' ) );

        if( empty( $aData ) ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }



        $modelParam['C_PARENT_IDX']                 = _elm( $requests, 'i_parent_idx' );
        $modelParam['C_CATE_NAME']                  = _elm( $requests, 'i_cate_name' );
        $modelParam['C_CATE_CODE']                  = _elm( $requests, 'i_cate_code' );
        $modelParam['C_ORDERING_CD']                = _elm( $requests, 'i_ordering_cd' );
        $modelParam['C_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['C_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['C_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['C_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        #------------------------------------------------------------------
        # TODO: 통합 데이터 세팅
        #------------------------------------------------------------------
        $_modelParam                                = [];
        $_modelParam['table']                       = 'GOODS_CATEGORY';
        $_modelParam['data']                        = $modelParam;
        $_modelParam['where']                       = 'C_IDX';

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->LogModel->integratUpdate( $_modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 기존 질문 목록 가져오기
        #------------------------------------------------------------------
        $existingQuestions                          = $categoryModel->getQuestions(_elm($requests, 'i_cate_idx'));
        $existingQuestionIds                        = array_column($existingQuestions, 'Q_IDX'); // 기존 지문 ID 목록


        #------------------------------------------------------------------
        # TODO: 리뷰 지문 수정
        #------------------------------------------------------------------
        $submittedQuestionIds = []; // 제출된 지문 ID를 저장하는 배열

        if (!empty(_elm($requests, 'questions'))) {
            foreach (_elm($requests, 'questions') as $qKey => $question) {
                $qIdx                               = _elm($question, 'q_idx'); // 수정할 지문 ID

                // 1. 질문(Q_QUESTION) 존재 여부 확인
                if (!empty($qIdx)) {
                    // 기존 질문을 업데이트
                    $submittedQuestionIds[]         = $qIdx;
                    $qParam                         = [];
                    $qParam['Q_IDX']                = $qIdx;
                    $qParam['Q_QUESTION']           = _elm($question, 'question');
                    $qParam['Q_CATE_IDX']           = _elm( $requests, 'i_cate_idx' );
                    $qParam['Q_SORT']               = $qKey;
                    $qParam['Q_UPDATE_AT']          = date('Y-m-d H:i:s');
                    $qParam['Q_UPDATE_IP']          = $this->request->getIPAddress();
                    $qParam['Q_UPDATE_MB_IDX']      = _elm($this->session->get('_memberInfo'), 'member_idx');

                    $updateStatus = $categoryModel->updateQuestion($qParam);
                    if ($this->db->transStatus() === false || $updateStatus === false) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '지문 수정 처리 중 오류 발생.. 다시 시도해주세요.';
                        return $this->respond($response);
                    }
                } else {
                    // 새 질문 추가 (등록 로직 활용)
                    $qParam                         = [];
                    $qParam['Q_QUESTION']           = _elm($question, 'question');
                    $qParam['Q_CATE_IDX']           = _elm( $requests, 'i_cate_idx' );
                    $qParam['Q_SORT']               = $qKey;
                    $qParam['Q_CREATE_AT']          = date('Y-m-d H:i:s');
                    $qParam['Q_CREATE_IP']          = $this->request->getIPAddress();
                    $qParam['Q_CREATE_MB_IDX']      = _elm($this->session->get('_memberInfo'), 'member_idx');
                    $qIdx                           = $categoryModel->insertQuestions($qParam);

                    if ($this->db->transStatus() === false || $qIdx === false) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '지문 등록 처리 중 오류 발생.. 다시 시도해주세요.';
                        return $this->respond($response);
                    }
                }

                // 2. 기존 예시(보기)를 처리
                $existingExamples                   = $categoryModel->getQuestionsExamples($qIdx); // 기존 예시 가져오기
                $existingExampleIds                 = array_column($existingExamples, 'E_IDX'); // 기존 예시 ID 목록

                // 새로 제출된 예시
                $submittedExamples                  = _elm($question, 'values');
                $submittedExampleIds                = array_column($submittedExamples, 'e_idx'); // 제출된 예시 ID 목록

                // 2-1. 기존 예시 중 제출되지 않은 예시는 삭제
                foreach ($existingExamples as $existingExample) {
                    if (!in_array( _elm( $existingExample, 'E_IDX'), $submittedExampleIds)) {
                        $deleteStatus               = $categoryModel->deleteQuestionExample( _elm( $existingExample, 'E_IDX') );
                        if ($this->db->transStatus() === false || $deleteStatus === false) {
                            $this->db->transRollback();
                            $response['status']     = 400;
                            $response['alert']      = '예제 삭제 중 오류 발생.. 다시 시도해주세요.';
                            return $this->respond($response);
                        }
                    }
                }

                // 2-2. 제출된 예시 업데이트 또는 새로 추가
                foreach ($submittedExamples as $eKey => $example) {
                    $eIdx                           = _elm($example, 'e_idx'); // 예시 ID

                    if (!empty($eIdx)) {
                        // 기존 예시 업데이트
                        $eParam                     = [];
                        $eParam['E_IDX']            = $eIdx;
                        $eParam['E_KEY']            = _elm($example, 'description');
                        $eParam['E_VALUE']          = _elm($example, 'value');
                        $eParam['E_SORT']           = $eKey;
                        $eParam['E_UPDATE_AT']      = date('Y-m-d H:i:s');
                        $eParam['E_UPDATE_IP']      = $this->request->getIPAddress();
                        $eParam['E_UPDATE_MB_IDX']  = _elm($this->session->get('_memberInfo'), 'member_idx');

                        $updateExampleStatus        = $categoryModel->updateQuestionExample($eParam);
                        if ($this->db->transStatus() === false || $updateExampleStatus === false) {
                            $this->db->transRollback();
                            $response['status']     = 400;
                            $response['alert']      = '예제 수정 처리 중 오류 발생.. 다시 시도해주세요.';
                            return $this->respond($response);
                        }
                    } else {
                        // 새로운 예시 추가
                        $eParam                     = [];
                        $eParam['E_Q_IDX']          = $qIdx;
                        $eParam['E_KEY']            = _elm($example, 'description');
                        $eParam['E_VALUE']          = _elm($example, 'value');
                        $eParam['E_SORT']           = $eKey;
                        $eParam['E_CREATE_AT']      = date('Y-m-d H:i:s');
                        $eParam['E_CREATE_IP']      = $this->request->getIPAddress();
                        $eParam['E_CREATE_MB_IDX']  = _elm($this->session->get('_memberInfo'), 'member_idx');

                        $eIdx                       = $categoryModel->insertQuestionExample($eParam);

                        if ($this->db->transStatus() === false || $eIdx === false) {
                            $this->db->transRollback();
                            $response['status']     = 400;
                            $response['alert']      = '예제 등록 처리 중 오류 발생.. 다시 시도해주세요.';
                            return $this->respond($response);
                        }
                    }
                }
            }

            #------------------------------------------------------------------
            # TODO: 기존 지문 중 제출되지 않은 지문 삭제
            #------------------------------------------------------------------
            foreach ($existingQuestionIds as $existingQuestionId) {
                if (!in_array($existingQuestionId, $submittedQuestionIds)) {
                    // 기존에 존재하지만 제출되지 않은 지문 삭제
                    $deleteStatus                   = $categoryModel->deleteQuestion($existingQuestionId);
                    if ($this->db->transStatus() === false || $deleteStatus === false) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '지문 삭제 중 오류 발생.. 다시 시도해주세요.';
                        return $this->respond($response);
                    }

                    // 해당 지문에 연결된 보기도 삭제
                    $deleteExamplesStatus           = $categoryModel->deleteQuestionExampleAll($existingQuestionId);
                    if ($this->db->transStatus() === false || $deleteExamplesStatus === false) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '예제 삭제 중 오류 발생.. 다시 시도해주세요.';
                        return $this->respond($response);
                    }
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 카테고리 수정 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE ) . ' // newData:'.json_encode( $modelParam, JSON_UNESCAPED_UNICODE )  ;
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        $this->db->transCommit();

        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '수정되었습니다.';

        return $this->respond( $response );
    }



    public function deleteCategory()
    {

        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $categoryModel                              = new CategoryModel();

        if( empty( _elm($requests, 'cate_idx') ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 원본 데이터 로드
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['C_IDX']                        = _elm( $requests, 'cate_idx' );
        $aData                                      = $categoryModel->getCategoryDataByIdx( _elm( $requests, 'cate_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '데이터가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: run
        #------------------------------------------------------------------
        $this->db->transBegin();

        $aStatus                                    = $this->deleteCategoryAndChildren($requests['cate_idx']);
        if ( $this->db->transStatus() === false || _elm( $aStatus, 'status' ) !== 200 ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = _elm( $aStatus, 'messages' );
            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 카테고리 삭제 - orgData:'.json_encode( $aData, JSON_UNESCAPED_UNICODE );
        $logParam['MB_IDX']                         = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->LogModel->insertAdminLog( $logParam );
        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 E
        #------------------------------------------------------------------

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '로그 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }



        $this->db->transCommit();
        $response                                   = $this->_unset( $response );
        $response['status']                         = 200;

        $response['alert']                          = '삭제가 완료되었습니다.';


        return $this->respond( $response );

    }

    private function deleteCategoryAndChildren($cateIdx)
    {
        $categoryModel                              = new CategoryModel();

        #------------------------------------------------------------------
        # TODO: 하위메뉴 가져옴
        #------------------------------------------------------------------
        $childCategorys                             = $categoryModel->getChildCategory($cateIdx);

        // 만약 getChildMenus가 실패하면 빈 배열로 처리
        if ($childCategorys === false) {
            $childCategorys                         = [];
        }

        #------------------------------------------------------------------
        # TODO: 하위 메뉴가 있으면 재귀적으로 삭제
        #------------------------------------------------------------------
        foreach ($childCategorys as $childCategory) {
            $loopResult                            = $this->deleteCategoryAndChildren($childCategory['C_IDX']);

            if ($loopResult['status'] === 400) {
                return $loopResult;
            }
        }

        #------------------------------------------------------------------
        # TODO: 현재 메뉴 삭제
        #------------------------------------------------------------------
        if (!$categoryModel->deleteCategory($cateIdx)) {
            return [
                'status' => 400,
                'error' => 400,
                'messages' => '현재 카테고리 삭제 중 오류 발생'
            ];
        } else {
            return [
                'status' => 200,
                'messages' => 'success'
            ];
        }
    }

    public function getCategoryDropDown()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();

        $owensView                                  = new OwensView();

        #------------------------------------------------------------------
        # TODO: 모델 로드
        #------------------------------------------------------------------
        $categoryModel                              = new CategoryModel();

        $view_datas                                 = [];

        $view_datas['aConfig']                      = $this->aConfig;
        $modleParam                                 = [];
        $modelParam['order']                        = ' C_SORT ASC';

        $aLISTS_RESULT                              = $categoryModel->getCategoryLists( $modelParam );

        $cate_lists                                 = _elm( $aLISTS_RESULT, 'lists' );
        if( empty( $cate_lists ) === false  ){
            #------------------------------------------------------------------
            # TODO: 트리형식으로 리스트 변경
            #------------------------------------------------------------------

            $view_datas['cate_tree_lists']          = _build_tree( $cate_lists, _elm($cate_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

            foreach (_elm($view_datas, 'cate_tree_lists', []) as $kIDX => $vCATE)
            {
                $cate[_elm($vCATE, 'C_IDX')]        = _elm($vCATE, 'C_CATE_NAME');

                if (empty($vCODE['CHILD']) === false)
                {
                    foreach (_elm($vCATE, 'CHILD', []) as $kIDX_CHILD => $vCATE_CHILD)
                    {
                        $cate[_elm($vCATE_CHILD, 'C_IDX')] = '   >>>' ._elm($vCATE_CHILD, 'C_CATE_NAME');
                    }
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: AJAX 뷰 처리
        #------------------------------------------------------------------
        $view_datas['aData']                        = $cate;

        $owensView->setViewDatas( $view_datas );

        $page_datas['detail']                       = view( '\Module\goods\Views\goods\_categoty_dropdown' , ['owensView' => $owensView] );

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;

        return $this->respond($response);
    }

    public function getPopCategoryManage( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $categoryModel                              = new CategoryModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }
        $modelParam                                 = [];


        if( empty( _elm( $requests , 's_keyword' ) ) === false )
        {
            switch( _elm( $requests , 's_condition' ) )
            {
                case 'mb_id' :
                    $modelParam['MB_USERID']        = _elm( $requests , 's_keyword' );
                    break;
                case 'mb_name' :
                    $modelParam['MB_USERNAME']      = _elm( $requests , 's_keyword' );
                    break;
                case 'title' :
                    $modelParam['F_TITLE']          = _elm( $requests , 's_keyword' );
                    break;
            }
        }

        $modelParam['order']                        = ' C_SORT ASC';

        ###########################################################
        $aLISTS_RESULT                              = $categoryModel->getCategoryLists( $modelParam );

        $cate_lists                                 = _elm( $aLISTS_RESULT, 'lists' );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        $cate                                       = [];

        #############################################################


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['_parent_idx']              = _elm( $requests, 'parent_idx' );

            #------------------------------------------------------------------
            # TODO: 메뉴 트리 적용
            #------------------------------------------------------------------

            if( empty( $cate_lists ) === false  ){
                #------------------------------------------------------------------
                # TODO: 트리형식으로 리스트 변경
                #------------------------------------------------------------------

                $view_datas['cate_tree_lists']      = _build_tree( $cate_lists, _elm($cate_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

                foreach (_elm($view_datas, 'cate_tree_lists', []) as $kIDX => $vCATE)
                {
                    $cate[_elm($vCATE, 'C_IDX')]    = _elm($vCATE, 'C_CATE_NAME');

                    if (empty($vCODE['CHILD']) === false)
                    {
                        foreach (_elm($vCATE, 'CHILD', []) as $kIDX_CHILD => $vCATE_CHILD)
                        {
                            $cate[_elm($vCATE_CHILD, 'C_IDX')] = '   >>>' ._elm($vCATE_CHILD, 'C_CATE_NAME');
                        }
                    }
                }
            }



            $view_datas['lists']                    = $cate;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\category\pop_list_row' , ['owensView' => $owensView] );


        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            $response['result']                     = $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function getCategoryChilds(){
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());
        $categoryModel                              = new CategoryModel();

        if( empty( _elm( $requests, 'cate_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '선택된 카테고리가 없습니다.';

            return $this->respond( $response );
        }

        $_cates                                     = $categoryModel->getChildCategory( _elm( $requests, 'cate_idx' ) );
        $options                                    = '<option value="">전체</option>';

        if( !empty( $_cates ) ){
            foreach( $_cates as $key => $cate ){
                $options                           .= '<option value="'._elm( $cate, 'C_IDX' ).'">'._elm( $cate, 'C_CATE_NAME' ).'</option>';
            }
        }
        $response                                   = $this->_unset( $response );
        $response['staus']                          = 200;
        $response['options']                        = $options;
        return $this->respond( $response );
    }
}