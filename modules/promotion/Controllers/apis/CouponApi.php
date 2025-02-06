<?php
namespace Module\promotion\Controllers\apis;

use Module\promotion\Controllers\PromotionApis;
use App\Libraries\OwensView;

use Module\goods\Controllers\apis\GoodsApi;

use Module\goods\Models\GoodsModel;
use Module\goods\Models\CategoryModel;
use Module\goods\Models\IconsModel;
use Module\goods\Models\BrandModel;

use Module\membership\Models\MembershipModel;

use Module\goods\Config\Config as goodsConfig;

use Config\Services;
use DOMDocument;
use Exception;

class CouponApi extends PromotionApis
{
    protected $aConfig;
    public function __construct()
    {
        parent::__construct();
        $this->aConfig                              = new goodsConfig();
        $this->aConfig                              = $this->aConfig->goods;
    }

    public function getCouponLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $goodsModel                                 = new GoodsModel();
        $requests                                   = _trim($this->request->getPost());
        $modelParam                                 = [];
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }

        $modelParam['order']                        = 'C_CREATE_AT DESC';
        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 20;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 20;
        }


        $modelParam['C_NAME']                       = _elm( $requests, 's_coupon_name' );
        $modelParam['C_STATUS']                     = _elm( $requests, 's_status' );
        $modelParam['C_SCOPE_GBN']                  = _elm( $requests, 's_scope_gbn' );
        $modelParam['S_DATE_CONFITION']             = _elm( $requests, 's_date_condition' );
        $modelParam['S_START_DATE']                 = _elm( $requests, 's_start_date' );
        $modelParam['S_END_DATE']                   = _elm( $requests, 's_end_date' );
        $modelParam['C_PUB_GBN']                    = _elm( $requests, 's_coupon_gbn' );


        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;
        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        ###########################################################
        $aLISTS_RESULT                              = $this->couponModel->getCouponLists( $modelParam );


        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );

        $search_count                               = _elm( $aLISTS_RESULT, 'search_count', 0 );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        #############################################################
        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];
            $view_datas['row']                      = $start;
            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['total_rows']               = $total_count;
            $view_datas['search_count']             = $search_count;
            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );


            $view_datas['lists']                    = $lists;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\promotion\Views\coupon\lists_row' , ['owensView' => $owensView] );

            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/promotion/coupon/cpnLists' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);


        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            return $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function couponRegisterProc()
    {
        $response                                   = $this->_initApiResponse();
        $requests                                   = _parseJsonFormData();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_coupon_name' => [
                'label'  => '쿠폰명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰명을 입력해주세요.',
                ],
            ],
            'i_coupon_discription' => [
                'label'  => '쿠폰설명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰설명을 입력해주세요.',
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
        $modelParam['C_NAME']                       = _elm( $requests, 'i_coupon_name' );
        $modelParam['C_DISCRIPTION']                = _elm( $requests, 'i_coupon_discription' );
        $modelParam['C_PUB_GBN']                    = _elm( $requests, 'i_coupon_gbn' );
        $modelParam['C_TARGET']                     = _elm( $requests, 'i_target' );
        $modelParam['C_TARGET_GRADE_IDX']           = null;
        $modelParam['C_ISSUE_NO_LIMIT_FLAG']        = 'Y';
        $modelParam['C_ISSUE_LIMIT']                = null;
        #------------------------------------------------------------------
        # TODO: 쿠폰유형이 다운로드 또는 지정발행일경우 회원 등급 넣음
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_coupon_gbn' ) == 'P' || _elm( $requests, 'i_coupon_gbn' ) == 'D'){
            if( _elm( $requests, 'i_target' ) == 'grade' ){
                $modelParam['C_TARGET_GRADE_IDX']   = _elm( $requests, 'i_mb_grade' );
            }
        }
        #------------------------------------------------------------------
        # TODO: 쿠폰유형이 다운로드 일 경우 발행수량을 체크, 제한없음일 경우 카운트는 넣지 않는다.
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_coupon_gbn' ) == 'D' ){
            if( empty( _elm( $requests, 'i_issue_no_limit' ) ) ){
                if( empty( _elm( $requests, 'i_issue_cnt' ) ) ){
                    $response['status']             = 400;
                    $response['alert']              = '발행수량을 입력해주세요. <br>무제한일 경우 수량제한없음을 체크해주세요.';

                    return $this->respond( $response );
                }
                $modelParam['C_ISSUE_NO_LIMIT_FLAG']= 'N';
                $modelParam['C_ISSUE_LIMIT']        = _elm( $requests, 'i_issue_cnt' );
            }else{
                $modelParam['C_ISSUE_NO_LIMIT_FLAG']= _elm( $requests, 'i_issue_no_limit' );
            }
        }
        $modelParam['C_NOTI_FLAG']                  = _elm( $requests, 'i_noti' );
        $modelParam['C_BENEFIT_GBN']                = _elm( $requests, 'i_benefit_gbn' );
        $modelParam['C_BENEFIT']                    = preg_replace('/,/','', _elm( $requests, 'i_benefit' ) );
        $modelParam['C_BENEFIT_UNIT']               = _elm( $requests, 'i_benefit_unit' );
        $modelParam['C_MIN_PRICE']                  = preg_replace('/,/','', _elm( $requests, 'i_min_price' ) );
        $modelParam['C_SCOPE_GBN']                  = _elm( $requests, 'i_scope_gbn' );
        $modelParam['C_EXCEPT_PRODUCT_IDXS']        = _elm( $requests, 'i_except_product_idxs' );
        $modelParam['C_PICK_ITEMS']                 = null;

        #------------------------------------------------------------------
        # TODO: 사용범위가 전체가 아닐때 (category|brand|product) 해당 idx를 넣는다. comma 로 분리하여 검색
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_scope_gbn' ) != 'all' ){
            $modelParam['C_PICK_ITEMS']             = _elm( $requests, 'i_pick_items' );
        }

        $modelParam['C_PERIDO_LIMIT']               = 'Y';
        $modelParam['C_PERIOD_START_AT']            = null;
        $modelParam['C_PERIOD_END_AT']              = null;
        $modelParam['C_EXPIRE_MONTH']               = null;

        #------------------------------------------------------------------
        # TODO: 사용기간 확인
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_coupon_gbn' ) != 'A' ){
            if( empty( _elm( $requests, 'i_period_limit' ) ) === true ){
                if( empty( _elm( $requests, 'i_period_start_date' ) ) || empty( _elm( $requests, 'i_period_end_date' ) ) ){
                    $response['status']             = 400;
                    $response['alert']              = '사용기간을 입력해주세요.<br>무제한인 경우 기간제한 없음을 체크해주세요.';

                    return $this->respond( $response );
                }
                $modelParam['C_PERIOD_START_AT']    = _elm( $requests, 'i_period_start_date' ) ;
                $modelParam['C_PERIOD_END_AT']      = _elm( $requests, 'i_period_end_date' ) ;
            }else{
                $modelParam['C_PERIDO_LIMIT']       = _elm( $requests, 'i_period_limit' );
            }
        }else{
            if( empty( _elm( $requests, 'i_period_limit' ) ) === true ){
                if( empty( _elm( $requests, 'i_exfire_months' ) ) ){
                    $response['status']             = 400;
                    $response['alert']              = '사용 가능 기간을 선택해주세요.<br>무제한인 경우 기간제한 없음을 체크해주세요.';

                    return $this->respond( $response );
                }
                $modelParam['C_EXPIRE_MONTH']       = _elm( $requests, 'i_expire_months' ) ;
            }else{
                $modelParam['C_PERIDO_LIMIT']       = _elm( $requests, 'i_period_limit' );
            }
        }

        #------------------------------------------------------------------
        # TODO: 중복사용 허용을 체크하는 방법에서 자동발행,지정발행은 무조건 중복사용 가능으로 변경
        #------------------------------------------------------------------
        $modelParam['C_DUPLICATE_USE_FLAG']         = 'N';
        if( _elm( $requests, 'i_coupon_gbn' ) == 'P' || _elm( $requests, 'i_coupon_gbn' ) == 'A' ){
            $modelParam['C_DUPLICATE_USE_FLAG']     = 'Y';
        }

        #------------------------------------------------------------------
        # TODO: 슈퍼쿠폰일 경우 모든 상품 적용되도록 수정
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_coupon_gbn' ) == 'P' && _elm( $requests, 'i_is_super_flag' ) == 'Y' ){
            $modelParam['C_SCOPE_GBN']              = 'all';
            $modelParam['C_EXCEPT_PRODUCT_IDXS']    = null;
            $modelParam['C_PICK_ITEMS']             = null;
            $modelParam['C_IS_SUPER_FLAG']          = _elm( $requests, 'i_is_super_flag' );
        }


        $modelParam['C_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['C_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['C_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->db->transBegin();

        $aIdx                                       = $this->couponModel->insertCoupon( $modelParam );

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '쿠폰 저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '쿠폰 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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
        $response['redirect_url']                   = _link_url('/promotion/coupon/cpnLists');

        return $this->respond( $response );

    }

    public function couponModifyProc()
    {
        $response                                   = $this->_initApiResponse();
        $requests                                   = _parseJsonFormData();


        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_c_idx' => [
                'label'  => '쿠폰IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰고유값 누락. 다시 시도해주세요.',
                ],
            ],
            'i_coupon_name' => [
                'label'  => '쿠폰명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰명을 입력해주세요.',
                ],
            ],
            'i_coupon_discription' => [
                'label'  => '쿠폰설명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰설명을 입력해주세요.',
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
        $aData                                      = $this->couponModel->getCouponDataByIdx( _elm( $requests, 'i_c_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        $modelParam                                 = [];
        $modelParam['C_IDX']                        = _elm( $requests, 'i_c_idx' );
        $modelParam['C_NAME']                       = _elm( $requests, 'i_coupon_name' );
        $modelParam['C_DISCRIPTION']                = _elm( $requests, 'i_coupon_discription' );
        $modelParam['C_PUB_GBN']                    = _elm( $requests, 'i_coupon_gbn' );
        $modelParam['C_TARGET']                     = _elm( $requests, 'i_target' );
        $modelParam['C_TARGET_GRADE_IDX']           = null;
        $modelParam['C_ISSUE_NO_LIMIT_FLAG']        = 'Y';
        $modelParam['C_ISSUE_LIMIT']                = null;
        #------------------------------------------------------------------
        # TODO: 쿠폰유형이 다운로드 또는 지정발행일경우 회원 등급 넣음
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_coupon_gbn' ) == 'P' || _elm( $requests, 'i_coupon_gbn' ) == 'D'){
            if( _elm( $requests, 'i_target' ) == 'grade' ){
                $modelParam['C_TARGET_GRADE_IDX']   = _elm( $requests, 'i_mb_grade' );
            }
        }
        #------------------------------------------------------------------
        # TODO: 쿠폰유형이 다운로드 일 경우 발행수량을 체크, 제한없음일 경우 카운트는 넣지 않는다.
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_coupon_gbn' ) == 'D' ){

            if( empty( _elm( $requests, 'i_issue_no_limit' ) ) ){
                if( empty( _elm( $requests, 'i_issue_cnt' ) ) ){
                    $response['status']             = 400;
                    $response['alert']              = '발행수량을 입력해주세요. <br>무제한일 경우 수량제한없음을 체크해주세요.';

                    return $this->respond( $response );
                }
                $modelParam['C_ISSUE_NO_LIMIT_FLAG']= 'N';
                $modelParam['C_ISSUE_LIMIT']        = _elm( $requests, 'i_issue_cnt' );
            }else{
                $modelParam['C_ISSUE_NO_LIMIT_FLAG']= _elm( $requests, 'i_issue_no_limit' );
            }
        }
        $modelParam['C_NOTI_FLAG']                  = _elm( $requests, 'i_noti' );
        $modelParam['C_BENEFIT_GBN']                = _elm( $requests, 'i_benefit_gbn' );
        $modelParam['C_BENEFIT']                    = preg_replace('/,/','', _elm( $requests, 'i_benefit' ) );
        $modelParam['C_BENEFIT_UNIT']               = _elm( $requests, 'i_benefit_unit' );
        $modelParam['C_MIN_PRICE']                  = preg_replace('/,/','', _elm( $requests, 'i_min_price' ) );
        $modelParam['C_STATUS']                     = _elm( $requests, 'i_status' );
        $modelParam['C_SCOPE_GBN']                  = _elm( $requests, 'i_scope_gbn' );
        $modelParam['C_EXCEPT_PRODUCT_IDXS']        = _elm( $requests, 'i_except_product_idxs' );
        $modelParam['C_PICK_ITEMS']                 = null;

        #------------------------------------------------------------------
        # TODO: 사용범위가 전체가 아닐때 (category|brand|product) 해당 idx를 넣는다. comma 로 분리하여 검색
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_scope_gbn' ) != 'all' ){
            $modelParam['C_PICK_ITEMS']             = _elm( $requests, 'i_pick_items' );
        }

        $modelParam['C_PERIDO_LIMIT']               = 'Y';
        $modelParam['C_PERIOD_START_AT']            = null;
        $modelParam['C_PERIOD_END_AT']              = null;
        $modelParam['C_EXPIRE_MONTH']               = null;

        #------------------------------------------------------------------
        # TODO: 사용기간 확인
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_coupon_gbn' ) != 'A' ){
            if( empty( _elm( $requests, 'i_period_limit' ) ) === true ){
                if( empty( _elm( $requests, 'i_period_start_date' ) ) || empty( _elm( $requests, 'i_period_end_date' ) ) ){
                    $response['status']             = 400;
                    $response['alert']              = '사용기간을 입력해주세요.<br>무제한인 경우 기간제한 없음을 체크해주세요.';

                    return $this->respond( $response );
                }
                $modelParam['C_PERIOD_START_AT']    = _elm( $requests, 'i_period_start_date' ) ;
                $modelParam['C_PERIOD_END_AT']      = _elm( $requests, 'i_period_end_date' ) ;
            }else{
                $modelParam['C_PERIDO_LIMIT']       = _elm( $requests, 'i_period_limit' );
            }
        }else{
            if( empty( _elm( $requests, 'i_period_limit' ) ) === true ){
                if( empty( _elm( $requests, 'i_expire_months' ) ) ){
                    $response['status']             = 400;
                    $response['alert']              = '사용 가능 기간을 선택해주세요.<br>무제한인 경우 기간제한 없음을 체크해주세요.';

                    return $this->respond( $response );
                }
                $modelParam['C_EXPIRE_MONTH']       = _elm( $requests, 'i_expire_months' ) ;
            }else{
                $modelParam['C_PERIDO_LIMIT']       = _elm( $requests, 'i_period_limit' );
            }
        }
        #------------------------------------------------------------------
        # TODO: 중복사용 허용을 체크하는 방법에서 자동발행,지정발행은 무조건 중복사용 가능으로 변경
        #------------------------------------------------------------------
        $modelParam['C_DUPLICATE_USE_FLAG']         = 'N';
        if( _elm( $requests, 'i_coupon_gbn' ) == 'P' || _elm( $requests, 'i_coupon_gbn' ) == 'A' ){
            $modelParam['C_DUPLICATE_USE_FLAG']     = 'Y';
        }

        #------------------------------------------------------------------
        # TODO: 슈퍼쿠폰일 경우 모든 상품 적용되도록 수정
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_coupon_gbn' ) == 'P' && _elm( $requests, 'i_is_super_flag' ) == 'Y' ){
            $modelParam['C_SCOPE_GBN']              = 'all';
            $modelParam['C_EXCEPT_PRODUCT_IDXS']    = null;
            $modelParam['C_PICK_ITEMS']             = null;
            $modelParam['C_IS_SUPER_FLAG']          = _elm( $requests, 'i_is_super_flag' );
        }


        $modelParam['C_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['C_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['C_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->db->transBegin();

        $aStatus                                       = $this->couponModel->updateCoupon( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '쿠폰 저장 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '쿠폰 수정 - orgData : '.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) . ' // newData : '.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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

        return $this->respond( $response );

    }

    public function couponPopIssueLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $owensView                                  = new OwensView();
        $moduleParam                                = [];
        $moduleParam['post']                        = $requests;
        $moduleParam['post'] ['raw_return']         = true;


        $couponInfo                                 = $this->couponModel->getCouponDataByIdx( _elm( $requests, 'coupon_idx' ) );

        $aLISTS_RESULT                              = $this->couponIssueLists( $moduleParam );

        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 10;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 10;
        }

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;
        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );



        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );


        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];
            $view_datas['row']                      = $start;
            $view_datas['total_rows']               = $total_count;
            $view_datas['aTargetId']                = _elm( $requests, 'coupon_idx' );

            $view_datas['couponInfo']               = $couponInfo;

            $view_datas['lists']                    = $lists;

            $owensView->setViewDatas( $view_datas );

            $page_datas['detail']                   = view( '\Module\promotion\Views\coupon\issue_lists' , ['owensView' => $owensView] );

            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/design/banner' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);



    }

    public function couponIssueLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $requests                                   = $this->request->getPost();

        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
        }


        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'coupon_idx' => [
                'label'  => '쿠폰 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰 IDX 누락. 새로고침 후 이용해주세요.',
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

        $modelParam                                 = [];

        $modelParam['C_IDX']                        = _elm( $requests, 'coupon_idx' );

        if( empty( _elm( $requests, 's_keyword' ) ) === false ){
            switch( _elm( $requests, 's_condition' ) )
            {
                case 'MB_NM':
                    $modelParam['MEMBER_NAME']      = _elm( $requests, 's_keyword' );
                    break;
                case 'MB_USERID':
                    $modelParam['MEMBER_ID']        = _elm( $requests, 's_keyword' );
                    break;
                default :
                    break;
            }
        }

        $modelParam['I_STATUS']                     = _elm( $requests, 's_status' );

        $modelParam['order']                        = 'I_IDX DESC,  I_ISSUE_AT ASC';

        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 10;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if ($per_page < 0 || is_numeric( $per_page ) === false)
        {
            $per_page                               = 10;
        }

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;
        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        ###########################################################
        $aLISTS_RESULT                              = $this->couponModel->getCouponIssueLists( $modelParam );

        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        #############################################################
        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {

            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];
            $view_datas['row']                      = $start;
            $view_datas['total_rows']               = $total_count;
            $view_datas['aTargetId']                = _elm( $requests, 'coupon_idx' );

            $view_datas['lists']                    = $lists;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\promotion\Views\coupon\issue_lists_row' , ['owensView' => $owensView] );

            $paging_param                           = [];
            $paging_param['num_links']              = 5;
            $paging_param['per_page']               = $per_page;
            $paging_param['total_rows']             = $total_count;
            $paging_param['base_url']               = rtrim( _link_url( '/design/banner' ), '/');
            $paging_param['ajax']                   = true;
            $paging_param['cur_page']               = $page;

            $page_datas['pagination']               = $this->_pagination($paging_param);

        }

        #------------------------------------------------------------------
        # TODO: 데이터만 리턴요청이면
        #------------------------------------------------------------------

        if (_elm($requests, 'raw_return') === true)
        {
            return $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);

    }


    public function getPopProductLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $goodsModel                                 = new GoodsModel();
        $goodsApi                                   = new GoodsApi();
        $requests                                   = _trim($this->request->getPost());

        $param['post']                              = $requests;
        $param['post']['raw_return']                = true;

        $aLISTS_RESULT = $goodsApi->getGoodsLists( $param );

        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );

        $search_count                               = _elm( $aLISTS_RESULT, 'search_count', 0 );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

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
            $view_datas['search_count']             = $search_count;
            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );


            $view_datas['lists']                    = $lists;

            $owensView->setViewDatas( $view_datas );
            if( empty( _elm( $requests, 'xPickLists' ) ) === false){
                $page_datas['lists_row']                = view( '\Module\promotion\Views\coupon\pop_lists' , ['owensView' => $owensView] );
            }else{
                $page_datas['lists_row']                = view( '\Module\goods\Views\goods\pop_lists' , ['owensView' => $owensView] );
            }

        }

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function goodsAddRows()
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $goodsModel                                 = new GoodsModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $requests, 'goodsIdxs' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '선택된 상품이 없습니다.';

            return $this->respond( $response );
        }
        $idxs                                       = explode(',', _elm( $requests, 'goodsIdxs' )  );
        $aLISTS_RESULT                              = $goodsModel->getGoodsListsByIdxs( $idxs );
        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );
        $page_datas                                 = [];

        if (_elm($requests, 'page_return') === true || $this->request->isAjax() === true)
        {
            // ---------------------------------------------------------------------
            // 서브뷰 처리
            // ---------------------------------------------------------------------
            // 리스트
            $view_datas                             = [];

            $view_datas['aConfig']                  = $this->aConfig;
            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );
            $view_datas['aColorConfig']             = $this->sharedConfig::$goodsColor;

            $view_datas['lists']                    = $lists;

            $owensView->setViewDatas( $view_datas );
            if( _elm( $requests, 'targetId' ) == 'excp_product' ){
                $page_datas['lists_row']                = view( '\Module\promotion\Views\coupon\_addGoodsRows_product' , ['owensView' => $owensView] );
            }else if( _elm( $requests, 'targetId' ) == 'add_product' ){
                $page_datas['lists_row']                = view( '\Module\promotion\Views\coupon\_addGoodsRows_addProduct' , ['owensView' => $owensView] );
            }
        }

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

    public function getPopBrandManage( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $brandModel                                 = new BrandModel();
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
        $aLISTS_RESULT                              = $brandModel->getBrandLists( $modelParam );

        $brand_lists                                = _elm( $aLISTS_RESULT, 'lists' );

        $total_count                                = _elm( $aLISTS_RESULT, 'total_count', 0 );

        $page_datas                                 = [];

        $brand                                      = [];

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

            if( empty( $brand_lists ) === false  ){
                #------------------------------------------------------------------
                # TODO: 트리형식으로 리스트 변경
                #------------------------------------------------------------------

                $view_datas['brand_tree_lists']     = _build_tree( $brand_lists, _elm($brand_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

                foreach (_elm($view_datas, 'brand_tree_lists', []) as $kIDX => $vBRAND)
                {
                    $brand[_elm($vBRAND, 'C_IDX')]  = _elm($vBRAND, 'C_BRAND_NAME');

                    if (empty($vCODE['CHILD']) === false)
                    {
                        foreach (_elm($vBRAND, 'CHILD', []) as $kIDX_CHILD => $vBRAND_CHILD)
                        {
                            $brand[_elm($vBRAND_CHILD, 'C_IDX')] = '   >>>' ._elm($vBRAND_CHILD, 'C_BRNAD_NAME');
                        }
                    }
                }
            }



            $view_datas['lists']                    = $brand;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\brand\pop_list_row' , ['owensView' => $owensView] );


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

    public function makeCpnIssue()
    {
        $response                                   = $this->_initApiResponse();
        $requests                                   = _trim($this->request->getPost());


        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'cpn_idx' => [
                'label'  => 'cpn_idx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰IDX값 누락. 다시 시도해주세요.',
                ],
            ],
            'issue_cnt' => [
                'label'  => 'issue_cnt',
                'rules'  => 'trim|required|is_natural|less_than_equal_to[999]',
                'errors' => [
                    'required' => '발급 수량을 입력해주세요.',
                    'is_natural' => '발급 수량은 숫자여야 합니다.',
                    'less_than_equal_to' => '발급 수량은 최대 999이어야 합니다.',
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

        $cpnInfo                                    = $this->couponModel->getCouponDataByIdx( _elm( $requests, 'cpn_idx' ) );
        #------------------------------------------------------------------
        # TODO: 쿠폰정보 체크
        #------------------------------------------------------------------
        if( empty( $cpnInfo ) ){
            $response['status']                     = 400;
            $response['alert']                      = '쿠폰 정보가 없습니다.';

            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 다운로드 쿠폰일 경우
        #------------------------------------------------------------------
        if( _elm( $cpnInfo, 'C_PUB_GBN' ) == 'A' ){
            $response['status']                     = 400;
            $response['alert']                      = '쿠폰코드를 생성할 수 없는 쿠폰입니다.<br>형식을 확인해주세요.';

            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 수량제한이 있는 경우
        #------------------------------------------------------------------
        if( _elm( $cpnInfo, 'C_PUB_GBN' ) == 'D' && _elm( $cpnInfo, 'C_ISSUE_NO_LIMIT_FLAG' ) == 'N' ){
            $issueCnt                               = $this->couponModel->getCpnIssueCnt( _elm( $requests, 'cpn_idx' ) );
            if( ( $issueCnt + _elm( $requests, 'issue_cnt' ) ) >= _elm( $cpnInfo, 'C_ISSUE_LIMIT' ) ){
                $response['status']                 = 400;
                $response['alert']                  = '쿠폰 발행수량이 초과하였습니다.';

                return $this->respond( $response );
            }
        }
        $agent                                      = $this->request->getUserAgent();

        $this->db->transBegin();

        for( $i=0; $i < _elm( $requests, 'issue_cnt' ); $i++ ){
            $modelParam                             = [];
            $modelParam['I_COUPON_CODE']            = $this->generateUniqueCouponCode();
            $modelParam['I_CPN_IDX']                = _elm( $requests, 'cpn_idx' );
            $modelParam['I_START_AT']               = _elm( $cpnInfo, 'C_PERIDO_LIMIT' ) == 'Y' ? _elm( $cpnInfo, 'C_PERIOD_START_AT' ) : '';
            $modelParam['I_END_AT']                 = _elm( $cpnInfo, 'C_PERIDO_LIMIT' ) == 'Y' ? _elm( $cpnInfo, 'C_PERIOD_END_AT' ) : '';
            // 다양한 디바이스, 플랫폼, 브라우저 정보 확인

            if ($agent->isBrowser()) {
                $modelParam['I_ISSUE_AGENT']        = "Browser: " . $agent->getBrowser() . " " . $agent->getVersion() . "<br>";
            } elseif ($agent->isRobot()) {
                $modelParam['I_ISSUE_AGENT']        = "Robot: " . $agent->getRobot() . "<br>";
            } elseif ($agent->isMobile()) {
                $modelParam['I_ISSUE_AGENT']        = "Mobile: " . $agent->getMobile() . "<br>";
            } else {
                $modelParam['I_ISSUE_AGENT']        = "Platform: " . $agent->getPlatform() . "<br>";
            }
            $modelParam['I_STATUS']                 = 'N';

            $modelParam['I_ISSUE_GBN']              = 'ADM';
            $modelParam['I_ISSUE_AT']               = date( 'Y-m-d H:i:s' );
            $modelParam['I_ISSUE_IP']               = $this->request->getIPAddress();
            $modelParam['I_SSUE_MB_IDX']            = _elm( $this->session->get('_memberInfo') , 'member_idx' );
            $aStatus                                = $this->couponModel->insertCpnIssue( $modelParam );
            if ( $this->db->transStatus() === false || $aStatus == false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '쿠폰번호 생성 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '쿠폰 코드 발급 - 쿠폰번호: '._elm( $requests, 'cpn_idx' ).' // 수량 : '._elm( $requests, 'issue_cnt' );
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
        $response['alert']                          = '발행이 완료 되었습니다.';

        return $this->respond( $response );
    }

    public function generateUniqueCouponCode()
    {
        $couponCode = $this->generateCouponCode();
        if ($this->couponModel->checkCpnCode($couponCode)) {
            return $this->generateUniqueCouponCode();
        }
        // 중복되지 않는 쿠폰 코드 반환
        return $couponCode;
    }

    private function generateCouponCode()
    {
        // 영문 대문자, 소문자, 숫자 포함
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $couponCode = '';

        // 5글자씩 3개의 블록을 생성 (각 블록은 '-'로 구분)
        for ($i = 0; $i < 3; $i++) {
            $block = '';
            for ($j = 0; $j < 5; $j++) {
                $block .= $characters[rand(0, strlen($characters) - 1)];
            }
            $couponCode .= $block;

            if ($i < 2) {
                $couponCode .= '-'; // 블록 사이에 '-' 추가
            }
        }
        return $couponCode;
    }

    public function couponJoinUser()
    {
        $response                                   = $this->_initApiResponse();
        $requests                                   = _trim($this->request->getPost());
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
            'mbIdx' => [
                'label'  => 'mbIdx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '회원 IDX누락 ',
                ],
            ],
            'issueIdx' => [
                'label'  => 'issueIdx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰발행번호 누락',
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

        $memberInfo                                 = $membershipModel->getMembershipDataByIdx( _elm( $requests, 'mbIdx' ) );
        if( empty( $memberInfo )  ){
            $response['status']                     = 400;
            $response['alert']                      = '회원정보가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }

        if( _elm( $memberInfo, 'MB_STATUS' ) != 2 ){
            $response['status']                     = 400;
            $response['alert']                      = '회원이 승인상태가 아닙니다. 상태정보를 확인해주세요.';

            return $this->respond( $response );
        }

        $issueInfo                                  = $this->couponModel->getIsseuDataByidx( _elm( $requests, 'issueIdx' ) );

        if( empty( $issueInfo ) ){
            $response['status']                     = 400;
            $response['alert']                      = '발급된 쿠폰정보가 없습니다.';

            return $this->respond( $response );
        }

        if( empty( _elm( $issueInfo, 'I_MB_IDX' ) ) === false ){
            $response['status']                     = 400;
            $response['alert']                      = '이미 사용자에게 지급된 쿠폰입니다. 다른 쿠폰을 이용해주세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 등급확인
        #------------------------------------------------------------------
        if( _elm( $issueInfo, 'C_TARGET' ) == 'grade' ){
            if( _elm( $memberInfo, 'MB_GRADE_IDX' ) !=  _elm( $issueInfo, 'C_TARGET_GRADE_IDX' )){
                $response['status']                 = 400;
                $response['alert']                  = '회원의 등급이 쿠폰 지정등급과 다릅니다.';

                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: 기간확인
        #------------------------------------------------------------------
        if( _elm( $issueInfo, 'C_PERIDO_LIMIT' ) == 'Y' ){
            #------------------------------------------------------------------
            # TODO: 발급기간 확인(시작일은 선 지급이 될수도 있으므로 종료일만 체크한다.)
            #------------------------------------------------------------------
            if( date('Ymd') > _elm( $issueInfo, 'C_PERIOD_END_AT' )  ){
                $response['status']                 = 400;
                $response['alert']                  = '쿠폰 발급 기간이 종료되었습니다. 다른 쿠폰을 생성해주세요.';
                return $this->respond( $response );
            }
        }


        $modelParam                                 = [];
        $modelParam['I_MB_IDX']                     = _elm( $requests, 'mbIdx' );
        $modelParam['I_IDX']                        = _elm( $requests, 'issueIdx' );
        $this->db->transBegin();

        $aStatus                                    = $this->couponModel->couponJoinUser( $modelParam );
        if ( $this->db->transStatus() === false || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '등록 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '쿠폰 사용자 지정 - issueIdx: '._elm( $requests, 'issueIdx' ).' // member_idx : '._elm( $requests, 'mbIdx' );
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
        $response['alert']                          = '발급이 완료 되었습니다.';

        return $this->respond( $response );


    }

    public function deleteIssueData()
    {
        $response                                   = $this->_initApiResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'issueIdx' => [
                'label'  => 'issueIdx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰발급번호 누락. 다시 시도해주세요.',
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

        $issueInfo                                  = $this->couponModel->getIsseuDataByidx( _elm( $requests, 'issueIdx' ) );
        if( empty( $issueInfo ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '쿠폰 발행 정보가 없습니다.';

            return $this->respond( $response );
        }

        if( empty( _elm( $issueInfo, 'I_USED_AT' ) ) === false ){
            $response['status']                     = 400;
            $response['alert']                      = '이미 사용한 쿠폰입니다.';

            return $this->respond( $response );
        }
        if( _elm( $issueInfo, 'I_STATUS' )  == 'Y' ){
            $response['status']                     = 400;
            $response['alert']                      = '이미 사용한 쿠폰입니다.';

            return $this->respond( $response );
        }


        $aStatus                                    = $this->couponModel->deleteIssueDataByIdx( _elm( $requests, 'issueIdx' ) );
        if ( $this->db->transStatus() === false || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '삭제 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '쿠폰 코드 삭제 - data: '.json_encode( $issueInfo, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
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
        $response['alert']                          = '삭제 되었습니다.';

        return $this->respond( $response );
    }

    public function deleteCoupon()
    {
        $response                                   = $this->_initApiResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_idxs' => [
                'label'  => '쿠폰 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰을 선택해주세요.',
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


        $idxs                                       = explode( ',', _elm( $requests, 'i_idxs' ) );
        $this->db->transBegin();
        foreach( $idxs as $idx ){
            $aData                                  = $this->couponModel->getCouponDataByIdx( $idx );
            if( empty( $aData ) ){
                $response['status']                 = 400;
                $response['alert']                  = '쿠폰번호('.$idx.') 번의 데이터가 존재하지 않습니다.';

                return $this->respond( $response );
            }

            $modelParam                             = [];
            $modelParam['C_IDX']                    = $idx;
            $modelParam['C_STATUS']                 = 'D';

            $aStatus                                = $this->couponModel->ChangeCouponStatus( $modelParam );

            if ( $this->db->transStatus() === false || $aStatus == false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '쿠폰 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                               = [];
            $logParam['MB_HISTORY_CONTENT']         = '쿠폰 삭제처리 - 쿠폰 Idx: '.$idx;
            $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            if ( $this->db->transStatus() === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '로그 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------
        }


        $this->db->transCommit();
        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '삭제 되었습니다.';

        return $this->respond( $response );
    }


    public function copyCoupon()
    {
        $response                                   = $this->_initApiResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_idxs' => [
                'label'  => '쿠폰 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰을 선택해주세요.',
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


        $idxs                                       = explode( ',', _elm( $requests, 'i_idxs' ) );
        $this->db->transBegin();
        foreach( $idxs as $idx ){
            $aData                                  = $this->couponModel->getCouponDataByIdx( $idx );
            if( empty( $aData ) ){
                $response['status']                 = 400;
                $response['alert']                  = '쿠폰번호('.$idx.') 번의 데이터가 존재하지 않습니다.';

                return $this->respond( $response );
            }

            $modelParam                             = [];
            $modelParam['C_NAME']                   = _elm( $aData, 'C_NAME' ) . '( 복사 )';
            $modelParam['C_DISCRIPTION']            = _elm( $aData, 'C_DISCRIPTION' );
            $modelParam['C_PUB_GBN']                = _elm( $aData, 'C_PUB_GBN' );
            $modelParam['C_TARGET']                 = _elm( $aData, 'C_TARGET' );
            $modelParam['C_TARGET_GRADE_IDX']       = _elm( $aData, 'C_TARGET_GRADE_IDX' );
            $modelParam['C_ISSUE_NO_LIMIT_FLAG']    = _elm( $aData, 'C_ISSUE_NO_LIMIT_FLAG' );
            $modelParam['C_ISSUE_LIMIT']            = _elm( $aData, 'C_ISSUE_LIMIT' );
            $modelParam['C_NOTI_FLAG']              = _elm( $aData, 'C_NOTI_FLAG' );
            $modelParam['C_BENEFIT_GBN']            = _elm( $aData, 'C_BENEFIT_GBN' );
            $modelParam['C_BENEFIT']                = _elm( $aData, 'C_BENEFIT' );
            $modelParam['C_BENEFIT_UNIT']           = _elm( $aData, 'C_BENEFIT_UNIT' );
            $modelParam['C_MIN_PRICE']              = _elm( $aData, 'C_MIN_PRICE' );
            $modelParam['C_STATUS']                 = _elm( $aData, 'C_STATUS' );
            $modelParam['C_SCOPE_GBN']              = _elm( $aData, 'C_SCOPE_GBN' );
            $modelParam['C_EXCEPT_PRODUCT_IDXS']    = _elm( $aData, 'C_EXCEPT_PRODUCT_IDXS' );
            $modelParam['C_PICK_ITEMS']             = _elm( $aData, 'C_PICK_ITEMS' );
            $modelParam['C_PERIDO_LIMIT']           = _elm( $aData, 'C_PERIDO_LIMIT' );
            $modelParam['C_PERIOD_START_AT']        = _elm( $aData, 'C_PERIOD_START_AT' );
            $modelParam['C_PERIOD_END_AT']          = _elm( $aData, 'C_PERIOD_END_AT' );
            $modelParam['C_EXPIRE_MONTH']           = _elm( $aData, 'C_EXPIRE_MONTH' );
            $modelParam['C_DUPLICATE_USE_FLAG']     = _elm( $aData, 'C_DUPLICATE_USE_FLAG' );
            $modelParam['C_CREATE_AT']              = date( 'Y-m-d H:i:s' );
            $modelParam['C_CREATE_IP']              = $this->request->getIPAddress();
            $modelParam['C_CREATE_MB_IDX']          = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $aIdx                                       = $this->couponModel->insertCoupon( $modelParam );

            if ( $this->db->transStatus() === false || $aIdx == false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '쿠폰 복사 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                               = [];
            $logParam['MB_HISTORY_CONTENT']         = '쿠폰 복사 - 쿠폰 Idx: '.$idx .' => new Idx : '.$aIdx;
            $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            if ( $this->db->transStatus() === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '로그 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------
        }


        $this->db->transCommit();
        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '복사 되었습니다.';

        return $this->respond( $response );
    }


    public function stopIssue()
    {
        $response                                   = $this->_initApiResponse();
        $requests                                   = _trim($this->request->getPost());

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_idxs' => [
                'label'  => '쿠폰 IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '쿠폰을 선택해주세요.',
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


        $idxs                                       = explode( ',', _elm( $requests, 'i_idxs' ) );
        $this->db->transBegin();
        foreach( $idxs as $idx ){
            $aData                                  = $this->couponModel->getCouponDataByIdx( $idx );
            if( empty( $aData ) ){
                $response['status']                 = 400;
                $response['alert']                  = '쿠폰번호('.$idx.') 번의 데이터가 존재하지 않습니다.';

                return $this->respond( $response );
            }

            $modelParam                             = [];
            $modelParam['C_IDX']                    = $idx;
            $modelParam['C_STATUS']                 = 'N';

            $aStatus                                = $this->couponModel->ChangeCouponStatus( $modelParam );

            if ( $this->db->transStatus() === false || $aStatus == false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '발행중지 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                               = [];
            $logParam['MB_HISTORY_CONTENT']         = '쿠폰 발행중지 - 쿠폰 Idx: '.$idx;
            $logParam['MB_IDX']                     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

            $this->LogModel->insertAdminLog( $logParam );
            if ( $this->db->transStatus() === false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '로그 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 E
            #------------------------------------------------------------------
        }


        $this->db->transCommit();
        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '발행중지 되었습니다.';

        return $this->respond( $response );
    }

}