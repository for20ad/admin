<?php
#------------------------------------------------------------------
# GoodsApi.php
# 싱픔관련 API
# 김우진
# 2024-08-23 14:03:00
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\goods\Models\IconsModel;
use Module\goods\Models\GoodsModel;
use Module\goods\Models\CategoryModel;
use Module\setting\Models\MembershipModel;
use Module\goods\Models\BrandModel;

use Module\goods\Config\Config as goodsConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class GoodsApi extends ApiController
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

    public function updateGoodsPrice()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $goodsModel                                 = new GoodsModel();


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
                'label'  => '상품IDX',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상품IDX가 없습니다. 새로고침 후 이용해주세요.',
                ],
            ],
            'amt' => [
                'label'  => '금액',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '금액을 입력해주세요.',
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
        $modelParam['G_IDX']                        = _elm( $requests, 'g_idx' );

        $aData                                      = $goodsModel->getGoodsDataByIdx( _elm( $requests, 'g_idx' ) );
        if( empty( $aData ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '잘못된 접근입니다.';

            return $this->respond( $response );
        }

        $modelParam['G_PRICE']                      = preg_replace('/,/','', _elm( $requests, 'amt' ) );
        $modelParam['G_PRICE_RATE']                 = round( ( (_elm( $aData, 'G_SELL_PRICE' ) - _elm( $modelParam, 'G_PRICE' )) / _elm( $aData, 'G_SELL_PRICE' ) ) * 100, 2);


        $this->db->transBegin();
        $aStatus                                    = $goodsModel->updateGoodsPrice( $modelParam );

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 금액 수정 - orgData:'.json_encode($aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE).' // -> //'. json_encode($modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) ;
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


        $this->db->transCommit();

    }

    public function goodsDetailProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();


        $goodsModel                                 = new GoodsModel();
        $membershipModel                            = new MembershipModel();
        $categoryModel                              = new CategoryModel();
        $brandModel                                 = new BrandModel();
        $iconModel                                  = new IconsModel();

        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_goods_name' => [
                'label'  => '상품명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상품명을 입력하세요.',
                ],
            ],
            'i_goods_name_eng' => [
                'label'  => '상품명(영문)',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상품명(영문)을 입력하세요',
                ],
            ],
            'i_mobile_content_same_chk' => [
                'label'  => '모바일 설명이 PC와 동일한지 선택하세요.',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '모바일 설명이 PC와 동일한지 선택하세요.',
                ],
            ],
            'i_sell_price' => [
                'label'  => '소비자가',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '소비자가를 입력하세요.',
                ],
            ],
            'i_buy_price' => [
                'label'  => '공급가',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '공급가를 입력하세요.',
                ],
            ],
            'i_goods_price' => [
                'label'  => '판매가',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '판매가를 입력하세요.',
                ],
            ],
            'i_goods_price_rate' => [
                'label'  => '마진율',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '마진율을 입력하세요.',
                ],
            ],
            'i_tax_type' => [
                'label'  => '과세구분',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '과세구분을 입력하세요.',
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
        # TODO: 원본데이터 로드
        #------------------------------------------------------------------
        $aData                                      = $goodsModel->getGoodsDataByIdx( _elm( $requests, 'i_goods_idx' ) );
        $this->db->transBegin();
        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['G_IDX']                        = _elm( $requests, 'i_goods_idx' );

        $existingGroupData = json_decode(_elm($aData, 'G_GROUP'), true);  // 기존 G_GROUP 데이터

        #------------------------------------------------------------------
        # TODO: 새로 요청된 그룹 데이터 로드
        #------------------------------------------------------------------
        $newGroupData = _elm($requests, 'i_goods_group_idxs');

        #------------------------------------------------------------------
        # TODO:  기존 데이터의 g_idx 배열 추출
        #------------------------------------------------------------------
        $existingGroupIds = array_column($existingGroupData, 'g_idx');

        #------------------------------------------------------------------
        # TODO: 기존 데이터에서 새로 요청된 데이터에 없는 항목(제거된 항목)을 추출
        #------------------------------------------------------------------
        $toRemove = array_diff($existingGroupIds, $newGroupData);

        if (!empty($toRemove)) {
            foreach ($toRemove as $removeIdx) {
                $removeData = $goodsModel->getGoodsDataByIdx($removeIdx);
                if (!empty($removeData)) {
                    $removeGroupData = json_decode(_elm($removeData, 'G_GROUP'), true);
                    if (is_array($removeGroupData)) {
                        #------------------------------------------------------------------
                        # TODO: $removeIdx 값을 제외한 항목만 남기도록 필터링
                        #------------------------------------------------------------------
                        $currentGoodsIdx = _elm($requests, 'i_goods_idx');
                        $filteredGroupData = array_filter($removeGroupData, function($item) use ( $currentGoodsIdx ) {
                            return $item['g_idx'] != $currentGoodsIdx; // 단일 값 비교
                        });
                        $removeParam                =[];
                        $removeParam['G_IDX']       = $removeIdx;
                        $removeParam['G_GROUP']     = json_encode( $filteredGroupData );
                        $removeParam['G_GROUP_MAIN']= 'N';
                        $rmStatus                   = $goodsModel->updateGoodsGroup( $removeParam );

                        if ( $this->db->transStatus() === false || $rmStatus == false ) {
                            $this->db->transRollback();
                            $response['status']     = 400;
                            $response['alert']      = '그룹상품 제외 처리중 오류발생.. 다시 시도해주세요.';
                            return $this->respond( $response );
                        }
                    }
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 그룹상품 세팅
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'i_goods_group_idxs' ) ) === false ){
            $i = 0;
            $originalGroupInfo = [];
            foreach( _elm( $requests, 'i_goods_group_idxs') as $goodsGroupIdx  ){
                $isMain = ($i == 0) ? 'Y' : 'N';
                $originalGroupInfo[]                = [
                    'g_idx'                         => $goodsGroupIdx,
                    'g_is_main'                     => $isMain
                ];

                $i++;
            }
            $groupDatas                             = json_encode( $originalGroupInfo );

            // groupDatas 배열을 순회하며 업데이트 작업 수행
            foreach ($originalGroupInfo as $groupData) {
                $goodsGroupIdx                      = _elm($groupData, 'g_idx');
                $isMain                             = _elm($groupData, 'g_is_main');

                // 여기에 업데이트 쿼리를 넣습니다
                $groupUpdateParam = [
                    'G_IDX' => _elm($groupData, 'g_idx'),
                    'G_GROUP' => $groupDatas,
                    'G_GROUP_MAIN' => $isMain,
                ];

                // 그룹 업데이트 함수 호출 (여기서는 예시로 사용)
                $grStatus                           = $goodsModel->updateGoodsGroup( $groupUpdateParam );
                if ( $this->db->transStatus() === false || $grStatus == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '그룹상품 등록 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 메인 카테고리 TXT 저장
        #------------------------------------------------------------------
        $mainCateInfo                               = $categoryModel->getCateTopNameJoin( _elm( $requests, 'i_is_category_main' ) );
        $modelParam['G_CATEGORY_MAIN']              = _elm( $mainCateInfo, 'FullCategoryName' );
        $modelParam['G_CATEGORY_MAIN_IDX']          = _elm( $requests, 'i_is_category_main' );

        #------------------------------------------------------------------
        # TODO: 검색용 카테고리 JSON 형식으로 저장
        #------------------------------------------------------------------
        $cateInfo                                   = $categoryModel->getCategoryDataByIdxs( _elm( $requests, 'i_cate_idx' ) );
        $modelParam['G_CATEGORYS']                  = json_encode( $cateInfo, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );

        $modelParam['G_NAME']                       = _elm( $requests, 'i_goods_name' );
        $modelParam['G_NAME_ENG']                   = _elm( $requests, 'i_goods_name_eng' );
        $modelParam['G_LOCAL_PRID']                 = _elm( $requests, 'i_goods_local_code' );
        $modelParam['G_SHORT_DESCRIPTION']          = htmlspecialchars(  _elm( $requests, 'i_description' ) );
        $modelParam['G_CONTETN_IS_SAME_FLAG']       = _elm( $requests, 'i_mobile_content_same_chk' );
        $modelParam['G_CONTENT_PC']                 = htmlspecialchars(  _elm( $requests, 'i_content_pc' ) );
        #------------------------------------------------------------------
        # TODO:  G_CONTETN_IS_SAME_FLAG == N 이면 모바일 내용 따로 저장
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_mobile_content_same_chk' ) == 'N' ){
            $modelParam['G_CONTENT_MOBILE']         = htmlspecialchars(  _elm( $requests, 'i_content_mobile' ) );
        }
        $modelParam['G_SEARCH_KEYWORD']             = _elm( $requests, 'i_search_keyword' );
        $modelParam['G_ADD_POINT']                  = preg_replace('/,/','', _elm( $requests, 'i_goods_add_point' ) );
        $modelParam['G_IS_PERFIT_FLAG']             = _elm( $requests, 'i_perfit_use' );
        $modelParam['G_SELL_PERIOD_START_AT']       = _elm( $requests, 'i_sell_period_start_at' );
        $modelParam['G_SELL_PERIOD_END_AT']         = _elm( $requests, 'i_sell_period_end_at' );
        $modelParam['G_COLOR']                      = _elm( $requests, 'i_goods_color' );
        $modelParam['G_SELL_PRICE']                 = preg_replace('/,/','', _elm( $requests, 'i_sell_price' ) );
        $modelParam['G_SELL_UNIT']                  = preg_replace('/,/','', _elm( $requests, 'i_sell_unit' ) ) ?? 'EA';
        $modelParam['G_BUY_PRICE']                  = preg_replace('/,/','', _elm( $requests, 'i_buy_price' ) );
        $modelParam['G_PRICE']                      = preg_replace('/,/','', _elm( $requests, 'i_goods_price' ) );
        $modelParam['G_PRICE_RATE']                 = preg_replace('/,/','', _elm( $requests, 'i_goods_price_rate' ) );
        $modelParam['G_TAX_TYPE']                   = _elm( $requests, 'i_tax_type' );
        $modelParam['G_DISCOUNT_CD']                = empty( _elm( $requests, 'i_discount_cd' ) ) === false ? join( ',' , _elm( $requests, 'i_discount_cd' ) ) : '';
        #------------------------------------------------------------------
        # TODO: 개별 포인트설정일 경우 등급데이터 세팅
        #------------------------------------------------------------------
        $modelParam['G_SELL_POINT_FLAG']            = _elm( $requests, 'i_sell_point_flag' );
        if( _elm( $requests, 'i_sell_point_flag' ) == 'Y' ){
            $gDelStatus                             = $goodsModel->deleteData( 'GOODS_DISCOUNT_GROUP', ['field'=>'D_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            if ( $this->db->transStatus() === false || $gDelStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = 'DC 그룹 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            if( empty( _elm( $requests, 'i_discount_mb_group' ) ) === true ){
                $response['status']                               = 400;
                $response['alert']                                = '할인성정 적용대상을 추가해주세요. ';

                return $this->respond( $response );
            }
            foreach( _elm( $requests, 'i_discount_mb_group' ) as $g_key => $d_mb_group_idx  ){
                $groupParam                         = [];
                $groupParam['D_MB_GROUP_IDX']       = $d_mb_group_idx;
                $gInfo                              = $membershipModel->getMembershopGradeByIdx($d_mb_group_idx);
                $groupParam['D_GOODS_IDX']          = _elm( $requests, 'i_goods_idx' );
                $groupParam['D_MB_GROUP_NAME']      = _elm( $gInfo, 'G_NAME' );
                $groupParam['D_MB_GROUP_DC_AMT']    = _elm( _elm( $requests, 'i_discount_mb_group_amt' ), $g_key );
                $groupParam['D_MB_GOURP_DC_UNIT']   = _elm( _elm( $requests, 'i_discount_mb_group_amt_unit' ), $g_key );
                $groupParam['D_DC_PERIOD_START_AT'] = _elm( _elm( $requests, 'i_discount_start_date' ), $g_key );
                $groupParam['D_DC_PERIOD_END_AT']   = _elm( _elm( $requests, 'i_discount_end_date' ), $g_key );
                $groupParam['D_CREATE_AT']          = date( 'Y-m-d H:i:s' );
                $groupParam['D_CREATE_IP']          = $this->request->getIPAddress();
                $groupParam['D_CREATE_MB_IDX']      = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                $gIdx                               = $goodsModel->insertDCGroup( $groupParam );
                if ( $this->db->transStatus() === false || $gIdx == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '그룹 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }else{
            #------------------------------------------------------------------
            # TODO: 기존 데이터 삭제
            #------------------------------------------------------------------
            $gDelStatus                             = $goodsModel->deleteData( 'GOODS_DISCOUNT_GROUP', ['field'=>'D_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            if ( $this->db->transStatus() === false || $gDelStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = 'DC 그룹 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }

        }
        #------------------------------------------------------------------
        # TODO: 연관상품 설정 i_relation_use_flag == 'Y'
        #------------------------------------------------------------------
        $modelParam['G_RELATION_GOODS_FLAG']        = _elm( $requests, 'i_relation_use_flag' );

        $existingRelationData                       = json_decode(_elm($aData, 'G_RELATION_GOODS'), true);  // 기존 G_GROUP 데이터
        if( empty( $existingRelationData ) === false ){
            #------------------------------------------------------------------
            # TODO: 새로 요청된 연관상품 데이터 로드
            #------------------------------------------------------------------
            $newRelationData                        = _elm($requests, 'i_relation_goods_idxs');

            #------------------------------------------------------------------
            # TODO:  기존 데이터의 g_idx 배열 추출
            #------------------------------------------------------------------
            if( empty( $newRelationData ) === false ){

                $existingwRelationIds               = array_column($newRelationData, 'g_idx');

                #------------------------------------------------------------------
                # TODO: 기존 데이터에서 새로 요청된 데이터에 없는 항목(제거된 항목)을 추출
                #------------------------------------------------------------------
                $toRemoveRelation = array_diff($existingwRelationIds, $newRelationData);

                if (!empty($toRemoveRelation)) {
                    foreach ($toRemoveRelation as $removeIdx) {
                        $removeDataRel              = $goodsModel->getGoodsDataByIdx($removeIdx);
                        if (!empty($removeDataRel)) {
                            $removeRelationData = json_decode(_elm($removeDataRel, 'G_GROUP'), true);
                            if (is_array($removeRelationData)) {
                                #------------------------------------------------------------------
                                # TODO: $removeIdx 값을 제외한 항목만 남기도록 필터링
                                #------------------------------------------------------------------
                                $currentGoodsIdx    = _elm($requests, 'i_goods_idx');
                                $filteredRelationData = array_filter($removeRelationData, function($item) use ( $currentGoodsIdx ) {
                                    return $item['g_idx'] != $currentGoodsIdx; // 단일 값 비교
                                });
                                $removeParam                     =[];
                                $removeParam['G_IDX']            = $removeIdx;
                                $removeParam['G_RELATION_GOODS'] = json_encode( $filteredRelationData ) ;
                                $rmStatus                         = $goodsModel->updateGoodsRelationData( $removeParam );

                                if ( $this->db->transStatus() === false || $rmStatus == false ) {
                                    $this->db->transRollback();
                                    $response['status']                     = 400;
                                    $response['alert']                      = '그룹상품 제외 처리중 오류발생.. 다시 시도해주세요.';
                                    return $this->respond( $response );
                                }
                            }
                        }
                    }
                }
            }
        }

        $relationDatas                          = [];
        if( _elm( $requests, 'i_relation_use_flag' ) == 'Y' ){
            if( empty( _elm( $requests, 'i_relation_goods_idxs' ) ) === false ){
                foreach( _elm( $requests, 'i_relation_goods_idxs' ) as $r_key => $relationIdx ){
                    $relationDatas[$r_key]['g_idx'] = $relationIdx;
                    $relationDatas[$r_key]['g_add_gbn']= _elm( _elm( $requests, 'i_relation_goods_add_gbn' ), $r_key );
                    if( _elm( _elm( $requests, 'i_relation_goods_add_gbn' ), $r_key ) == 'dup' ){
                        $targetGoodsInfo            = $goodsModel->getGoodsDataByIdx( $relationIdx );
                        if( empty( $targetGoodsInfo ) == false ){
                            $tRelInfo               = json_decode( _elm($targetGoodsInfo, 'G_RELATION_GOODS'), true );
                            $tRelInfo[]             = [
                                'g_idx'             => _elm( $requests, 'i_goods_idx' ),
                                'g_add_gbn'         => _elm( _elm( $relationDatas, $r_key ), 'g_add_gbn' ),
                            ];
                            #------------------------------------------------------------------
                            # TODO:  중복 제거: g_idx가 중복된 항목을 제거.
                            #------------------------------------------------------------------
                            $uniqueTRelInfo             = [];
                            foreach ($tRelInfo as $item) {
                                $uniqueTRelInfo[$item['g_idx']] = $item;
                            }

                            #------------------------------------------------------------------
                            # TODO: 배열을 다시 값 배열로 변환 (키 제거)
                            #------------------------------------------------------------------
                            $uniqueTRelInfo = array_values($uniqueTRelInfo);

                            #------------------------------------------------------------------
                            # TODO: 결과를 JSON 문자열로 인코딩
                            #------------------------------------------------------------------
                            $tRelInfoJson = json_encode($uniqueTRelInfo);

                            $relParam                   = [];
                            $relParam['G_IDX']          =  _elm( _elm($relationDatas, $r_key ), 'g_idx') ;
                            $relParam['G_RELATION_GOODS']= $tRelInfoJson;

                            $tStatus                    = $goodsModel->updateGoodsRelationData( $relParam );
                            if ( $this->db->transStatus() === false || $tStatus == false ) {
                                $this->db->transRollback();
                                $response['status']     = 400;
                                $response['alert']      = '연관상품 상호등록 처리중 오류발생.. 다시 시도해주세요.';
                                return $this->respond( $response );
                            }
                        }
                    }

                }

            }
            $modelParam['G_RELATION_GOODS']         = json_encode( $relationDatas , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        }else{
            $modelParam['G_RELATION_GOODS']         = NULL;
        }

        #------------------------------------------------------------------
        # TODO: 추가상품 설정  i_add_goods_flag == 'Y'
        #------------------------------------------------------------------
        $modelParam['G_ADD_GOODS_FLAG']             = _elm( $requests, 'i_add_goods_flag' );
        if( _elm( $requests, 'i_add_goods_flag' ) == 'Y' ){
            $modelParam['G_ADD_GOODS']              = json_encode( _elm( $requests, 'i_add_goods_idxs', [] ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        }

        #------------------------------------------------------------------
        # TODO: 옵션사용 설정 i_option_use_flag == 'Y'
        #------------------------------------------------------------------
        $modelParam['G_OPTION_USE_FLAG']            = _elm( $requests, 'i_option_use_flag' );
        if( empty( _elm( $requests, 'i_option_use_flag' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '옵션 사용 여부를 선택해주세요.';

            return $this->respond( $response );
        }
        if( _elm( $requests, 'i_option_use_flag' ) == 'Y' ){
            if( empty(  _elm( $requests, 'i_option_keys' ) ) === true ){
                $response['status']                 = 400;
                $response['alert']                  = '옵션명을 입력해주세요.';

                return $this->respond( $response );
            }
            #------------------------------------------------------------------
            # TODO: 기존 옵션 데이터 조회
            #------------------------------------------------------------------
            $existingOptions                        = $goodsModel->getGoodsOptions(_elm($requests, 'i_goods_idx'));

            #------------------------------------------------------------------
            # TODO: 전달된 옵션 인덱스 목록
            #------------------------------------------------------------------
            $postOptionIdxs                         = array_filter( _elm( $requests, 'i_option_idx' )?? [] );

            #------------------------------------------------------------------
            # TODO: 기존 데이터 중 POST로 넘어온 데이터에 없는 항목 삭제
            #------------------------------------------------------------------
            if( empty($postOptionIdxs) === false ){
                foreach ($existingOptions as $existingOption) {
                    if ( !in_array( _elm( $existingOption, 'O_IDX' ) , $postOptionIdxs ) ) {
                        $deleteStatus               = $goodsModel->deleteData('GOODS_OPTIONS', [ 'field' => 'O_IDX', 'idx' => _elm( $existingOption, 'O_IDX') ] );
                        if ($this->db->transStatus() === false || $deleteStatus == false) {
                            $this->db->transRollback();
                            $response['status'] = 400;
                            $response['alert'] = '옵션 삭제 처리 중 오류 발생.. 다시 시도해주세요.';
                            return $this->respond($response);
                        }
                    }
                }
            }else{
                foreach ($existingOptions as $existingOption) {
                    $deleteStatus                   = $goodsModel->deleteData('GOODS_OPTIONS', [ 'field' => 'O_IDX', 'idx' => _elm( $existingOption, 'O_IDX') ] );
                    if ($this->db->transStatus() === false || $deleteStatus == false) {
                        $this->db->transRollback();
                        $response['status'] = 400;
                        $response['alert'] = '옵션 삭제 처리 중 오류 발생.. 다시 시도해주세요.';
                        return $this->respond($response);
                    }
                }
            }

            // #------------------------------------------------------------------
            // # TODO: 기존 데이터 삭제
            // #------------------------------------------------------------------
            // $oDelStatus                             = $goodsModel->deleteData( 'GOODS_OPTIONS', ['field'=>'O_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            // if ( $this->db->transStatus() === false || $oDelStatus == false ) {
            //     $this->db->transRollback();
            //     $response['status']                 = 400;
            //     $response['alert']                  = '옵션 삭제 처리중 오류발생.. 다시 시도해주세요.';
            //     return $this->respond( $response );
            // }

            foreach( _elm( $requests, 'i_option_keys' ) as $o_key => $option_key ){
                $optionParam                        = [];
                $optionParam['O_KEYS']              = $option_key;
                $optionParam['O_VALUES']            = _elm( _elm( $requests, 'i_option_value' ), $o_key );
                $optionParam['O_STOCK']             = _elm( _elm( $requests, 'i_option_stock' ), $o_key );
                $optionParam['O_ADD_PRICE']         = preg_replace('/,/','', _elm( _elm( $requests, 'i_option_add_price' ), $o_key ) ) ;
                $optionParam['O_VIEW_STATUS']       = _elm( _elm( $requests, 'i_option_status' ), $o_key );

                $optionIdx                          = _elm( _elm($requests, 'i_option_idx'), $o_key, null );
                if ($optionIdx) {
                    $optionParam['O_IDX']           = $optionIdx;
                    $optionParam['O_UPDATE_AT']     = date( 'Y-m-d H:i:s' );
                    $optionParam['O_UPDATE_IP']     = $this->request->getIPAddress();
                    $optionParam['O_UPDATE_MB_IDX'] = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $optionParam['O_GOODS_IDX']     = _elm( $requests, 'i_goods_idx' );
                    $updateStatus                   = $goodsModel->updateGoodsOptions($optionParam);
                    if ($this->db->transStatus() === false || $updateStatus == false) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '옵션 수정 처리 중 오류 발생.. 다시 시도해주세요.';
                        return $this->respond($response);
                    }
                } else {
                    $optionParam['O_CREATE_AT']     = date( 'Y-m-d H:i:s' );
                    $optionParam['O_CREATE_IP']     = $this->request->getIPAddress();
                    $optionParam['O_CREATE_MB_IDX'] = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $optionParam['O_GOODS_IDX']     = _elm( $requests, 'i_goods_idx' );

                    $oIdx                           = $goodsModel->insertGoodsOptions($optionParam);
                    if ($this->db->transStatus() === false || $oIdx == false) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '옵션 저장 처리 중 오류 발생.. 다시 시도해주세요.';
                        return $this->respond($response);
                    }
                }
            }
        }else{
            #------------------------------------------------------------------
            # TODO: 기존 데이터 삭제
            #------------------------------------------------------------------
            $oDelStatus                             = $goodsModel->deleteData( 'GOODS_OPTIONS', ['field'=>'O_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            if ( $this->db->transStatus() === false || $oDelStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '옵션 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: 판매재고 무한정일때는 재고수량 999999로 넣고 아니면 재고수량에 자동계산된 재고수량으로 넣는다.
        #------------------------------------------------------------------
        $modelParam['G_STOCK_FLAG']                 = _elm( $requests, 'i_goods_stock_flag' );
        $modelParam['G_STOCK_CNT']                  = _elm( $requests, 'i_goods_stock_flag' ) == 'Y' ? _elm( $requests, 'i_goods_stock' ) : '999999';
        $modelParam['G_SAFETY_STOCK']               = _elm( $requests, 'i_goods_safe_stock' );

        #------------------------------------------------------------------
        # TODO: 텍스트 옵션 세팅
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_text_option_use_flag' ) == 'Y' ){
            if( empty( _elm( $requests, 'i_text_option_keys' ) ) === false ){
                $textOptions                        = [];
                foreach( _elm( $requests, 'i_text_option_keys' ) as $tKey => $textOption ){
                    $_textOptions                   = [];
                    $_textOptions['type']           = _elm( _elm( $requests, 'i_text_option_type' ), $tKey );
                    $_textOptions['title']          = $textOption;
                    if( _elm( $_textOptions, 'type' ) != 'text' ){
                        foreach( _elm( _elm( $requests, 'i_text_option_extra' ), $tKey ) as $exKey => $extra ){
                            $_textOptions['extras'][$exKey] = $extra;
                        }
                    }
                    $textOptions[$tKey]             = $_textOptions;
                }
            }
        }
        $modelParam['G_TEXT_OPTION_USE_FLAG']       = _elm( $requests, 'i_text_option_use_flag' );
        $modelParam['G_TEXT_OPTION']                =   empty( $textOptions ) === false ? json_encode(  $textOptions , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) :  json_encode([]) ;


        $modelParam['G_DELIVERY_PAY_CD']            = _elm( $requests, 'i_delivery_pay' );

        $modelParam['G_PC_OPEN_FLAG']               = _elm( $requests, 'i_is_pc_open' );
        $modelParam['G_PC_SELL_FLAG']               = _elm( $requests, 'i_is_pc_sell' );
        $modelParam['G_MOBILE_OPEN_FLAG']           = _elm( $requests, 'i_is_mobile_open' );
        $modelParam['G_MOBILE_SELL_FLAG']           = _elm( $requests, 'i_is_mobile_sell' );

        $modelParam['G_ORIGIN_NAME']                = _elm( $requests, 'i_origin_name' );
        $modelParam['G_MAKER_NAME']                 = _elm( $requests, 'i_maker_name' );

        $modelParam['G_BRAND_IDX']                  = _elm( $requests, 'i_is_brand_main' );
        $brandInfo                                  = $brandModel->getBrandDataByIdx( _elm( $requests, 'i_is_brand_main' ) );
        $modelParam['G_BRAND_NAME']                 = _elm( $brandInfo, 'C_BRAND_NAME' );

        $modelParam['G_SEO_TITLE']                  = _elm( $requests, 'i_seo_title' );
        $modelParam['G_SEO_DESCRIPTION']            = _elm( $requests, 'i_seo_description' );

        #------------------------------------------------------------------
        # TODO: 아이콘 데이터 삽입
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'i_icon_select' ) ) === false ){
            #------------------------------------------------------------------
            # TODO: 기존 데이터 삭제
            #------------------------------------------------------------------
            $iDelStatus                             = $goodsModel->deleteData( 'GOODS_IN_ICONS', ['field'=>'I_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            if ( $this->db->transStatus() === false || $iDelStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '옵션 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            foreach( _elm( $requests, 'i_icon_select' ) as $i_key => $iconIdx ){
                $iconsParam                                          = [];
                $iconData                                       = $iconModel->getIconsDataByIdx( $iconIdx );
                $iconsParam['I_ICONS_IDX']                      = $iconIdx;
                $iperiod_start                                  = _elm( _elm( $requests, 'i_icon_selct_start_at' ), $iconIdx );
                $iperiod_end                                    = _elm( _elm( $requests, 'i_icon_selct_end_at' ), $iconIdx );
                if( _elm($iconData, 'I_GBN') == 'P' ){
                    if( empty( $iperiod_start ) === true || empty( $iperiod_end ) === true ){
                        $response['status']                     = 400;
                        $response['alert']                      = '아이콘 시작일 또는 종료일을 입력해주세요.';

                        return $this->respond( $response );
                    }
                }else{
                    $iperiod_start                              = '0000-00-00 00:00:00';
                    $iperiod_end                                = '0000-00-00 00:00:00';
                }

                $iconsParam['I_ICONS_PERIOD_START_AT']   = $iperiod_start;
                $iconsParam['I_ICONS_PERIOD_END_AT']     = $iperiod_end;
                $iconsParam['I_ICONS_GBN']               = _elm( $iconData, 'I_GBN' );
                $iconsParam['I_CREATE_AT']               = date( 'Y-m-d H:i:s' );
                $iconsParam['I_CREATE_IP']               = $this->request->getIPAddress();
                $iconsParam['I_CREATE_MB_IDX']           = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                $iconsParam['I_GOODS_IDX']          = _elm( $requests, 'i_goods_idx' );

                $iIdx                               = $goodsModel->insertGoodsInIcons( $iconsParam );
                if ( $this->db->transStatus() === false || $iIdx == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '아이콘 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }else{
            #------------------------------------------------------------------
            # TODO: 기존 데이터 삭제
            #------------------------------------------------------------------
            $iDelStatus                             = $goodsModel->deleteData( 'GOODS_IN_ICONS', ['field'=>'I_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            if ( $this->db->transStatus() === false || $iDelStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '옵션 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }

        $modelParam['G_OUT_VIEW']                   = empty( _elm( $requests, 'i_out_view' ) ) === false ? join( ',', _elm( $requests, 'i_out_view' ) ): '' ;
        #------------------------------------------------------------------
        # TODO: 외부노출이미지 설정
        #------------------------------------------------------------------
        $config                                     = [
            'path' => 'goods/out_site',
            'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
        ];

        if( _elm( $files, 'i_out_main_img' ) ){
            $outfile = _elm( $files, 'i_out_main_img' );
            if( $outfile->getSize() > 0 ){
                #------------------------------------------------------------------
                # TODO: 기존 외부노출 이미지 삭제
                #------------------------------------------------------------------
                $orgOutImgFilePath                              = WRITEPATH . _elm( $aData, 'G_OUT_MAIN_IMG_PATH' );
                if (file_exists($orgOutImgFilePath)) {
                    @unlink($orgOutImgFilePath);
                }

                $file_return                            = $this->_upload( $outfile, $config );

                #------------------------------------------------------------------
                # TODO: 파일처리 실패 시
                #------------------------------------------------------------------
                if( _elm($file_return , 'status') === false ){
                    $this->db->transRollback();
                    $response['status']                 = 400;
                    $response['alert']                  = _elm( $file_return, 'error' );
                    return $this->respond( $response, 400 );
                }

                #------------------------------------------------------------------
                # TODO: 데이터모델 세팅
                #------------------------------------------------------------------

                $modelParam['G_OUT_MAIN_IMG_PATH']  = _elm( $file_return, 'uploaded_path');
                $modelParam['G_OUT_MAIN_IMG_NAME']  = _elm( $file_return, 'org_name');
            }else{
                $modelParam['G_OUT_MAIN_IMG_PATH']  = _elm( $aData, 'G_OUT_MAIN_IMG_PATH' );
                $modelParam['G_OUT_MAIN_IMG_NAME']  = _elm( $aData, 'G_OUT_MAIN_IMG_NAME' );
            }
        }

        $modelParam['G_OUT_GOODS_NAME']             = _elm( $requests, 'i_out_goods_name' );
        $modelParam['G_OUT_EVENT_TXT']              = _elm( $requests, 'i_out_event_txt' );

        $modelParam['G_GOODS_CONDITION']            = _elm( $requests, 'i_goods_condition' );
        $modelParam['G_GOODS_PRODUCT_TYPE']         = empty( _elm( $requests, 'i_is_product_type' ) ) === false ? join( ',', _elm( $requests, 'i_is_product_type' ) ) : '' ;
        $modelParam['G_IS_SALES_TYPE']              = _elm( $requests, 'i_is_goods_seles_type' );

        $modelParam['G_MIN_BUY_COUNT']              = _elm( $requests, 'i_min_buy_count' ) < 1 ? '1' : _elm( $requests, 'i_min_buy_count' ) ;
        $modelParam['G_MEM_MAX_BUY_COUNT']          = _elm( $requests, 'i_mem_max_buy_count' ) == 0 || empty(_elm( $requests, 'i_mem_max_buy_count' )) === true? '9999' : _elm( $requests, 'i_mem_max_buy_count' ) ;
        $modelParam['G_IS_ADULT_PRODUCT']           = _elm( $requests, 'i_is_adult_product' );

        $modelParam['G_UPDATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['G_UPDATE_IP']                  = $this->request->getIPAddress();
        $modelParam['G_UPDATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );



        $aIdx                                       = $goodsModel->updateGoods( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상품 수정 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }



        #------------------------------------------------------------------
        # TODO: 상품정보제공고시 저장
        #------------------------------------------------------------------

        if( empty( _elm( $requests, 'i_req_info_keys' ) ) === false ){
            #------------------------------------------------------------------
            # TODO: 기존 데이터 삭제
            #------------------------------------------------------------------
            $rDelStatus                             = $goodsModel->deleteData( 'GOODS_ADD_REQUIRED_INFO', ['field'=>'I_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            if ( $this->db->transStatus() === false || $rDelStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '정보제공고시 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            foreach( _elm( $requests, 'i_req_info_keys' ) as $key => $req_info ){
                if( !empty( $req_info  ) ){
                    $reqParam                       = [];
                    $reqParam['I_SORT']             = $key+1;
                    $reqParam['I_GOODS_IDX']        = _elm( $requests, 'i_goods_idx' );
                    $reqParam['I_KEY']              = $req_info;
                    $reqParam['I_VALUE']            = _elm(_elm( $requests, 'i_req_info_values' ), $key  );
                    $reqParam['I_CREATE_AT']        = date( 'Y-m-d H:i:s' );
                    $reqParam['I_CREATE_IP']        = $this->request->getIPAddress();
                    $reqParam['I_CREATE_MB_IDX']    = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                    $rStatus                        = $goodsModel->insertReqInfos( $reqParam );
                    if ( $this->db->transStatus() === false || $rStatus == false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '정보제공고시 저장 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }

            }

        }else{
            #------------------------------------------------------------------
            # TODO: 기존 데이터 삭제
            #------------------------------------------------------------------
            $rDelStatus                             = $goodsModel->deleteData( 'GOODS_ADD_REQUIRED_INFO', ['field'=>'I_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            if ( $this->db->transStatus() === false || $rDelStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '정보제공고시 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }


        #------------------------------------------------------------------
        # TODO: 기존 파일 처리
        #------------------------------------------------------------------

        $imgDatas                                  = $goodsModel->getGoodsInImages( _elm( $requests, 'i_goods_idx' ) );
        if( empty( $imgDatas ) == false ){
            foreach( $imgDatas as $iKey => $img ){
                $mainImgFilePath                              = WRITEPATH . _elm( $img, 'I_IMG_PATH' );
                if (file_exists($mainImgFilePath)) {
                    @unlink($mainImgFilePath);
                }
            }
            #------------------------------------------------------------------
            # TODO: 기존 데이터 삭제
            #------------------------------------------------------------------
            $rDelStatus                             = $goodsModel->deleteData( 'GOODS_IMAGES', ['field'=>'I_GOODS_IDX', 'idx'=>_elm( $requests, 'i_goods_idx' ) ] );
            if ( $this->db->transStatus() === false || $rDelStatus == false ) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '이미지 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
        }
        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------
        if( empty( _elm($files, 'i_goods_img') ) === false ){
            $config                                     = [
                'path' => 'goods/product/'._elm( $requests, 'i_goods_idx' ),
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            if ($this->request->getFiles() && $this->request->getPost('img_info')) {
                $uploadedFiles = $this->request->getFiles()['i_goods_img'] ?? [];


                $imgInfo = $this->request->getPost('img_info');

                // img_info에서 순서와 파일 이름을 기반으로 매핑
                $orderedFiles = [];
                foreach ($imgInfo as $info) {
                    foreach ($uploadedFiles as $file) {
                        if (strcasecmp(trim($file->getClientName()), trim($info['filename'])) === 0) {
                            $orderedFiles[(int)$info['order']] = $file;
                            break;
                        }
                    }
                }


                // 순서에 따라 정렬
                ksort($orderedFiles);

                // 정렬된 파일 처리
                foreach ($orderedFiles as $order => $file) {
                    if( $file->getSize() > 0 ){
                        $imgParam                           = [];
                        $fileSize                           = $file->getSize();
                        $fileExtension                      = $file->getExtension();
                        $fileMimeType                       = $file->getMimeType();
                        $file_return                        = $this->_uploadAndResize( $file, $config );

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

                        $imgParam['I_SORT']                 = $order + 1;
                        $imgParam['I_GOODS_IDX']            = _elm( $requests, 'i_goods_idx' );
                        $imgParam['I_IMG_NAME']             = _elm( $file_return, 'org_name');
                        $imgParam['I_IMG_PATH']             = _elm( $file_return, 'uploaded_path');
                        $imgParam['I_IMG_VIEW_SIZE']        = '';
                        $imgParam['I_IS_ORIGIN']            = 'Y';
                        $imgParam['I_IMG_SIZE']             = $fileSize;
                        $imgParam['I_IMG_EXT']              = $fileExtension;
                        $imgParam['I_IMG_MIME_TYPE']        = $fileMimeType;
                        $imgParam['I_CREATE_AT']            = date( 'Y-m-d H:i:s' );
                        $imgParam['I_CREATE_IP']            = $this->request->getIPAddress();
                        $imgParam['I_CREATE_MB_IDX']        = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                        $imgIdx                             = $goodsModel->insertGoodsImages( $imgParam );
                        if ( $this->db->transStatus() === false || $imgIdx == false ) {
                            $this->db->transRollback();
                            $response['status']             = 400;
                            $response['alert']              = '이미지 처리중 오류발생.. 다시 시도해주세요.';
                            return $this->respond( $response );
                        }
                        #------------------------------------------------------------------
                        # TODO: 리사이즈된 이미지 저장
                        #------------------------------------------------------------------
                        $resizedImages = _elm($file_return, 'resized');
                        if (!empty($resizedImages)) {
                            foreach ($resizedImages as $resized) {
                                $resizeParam = [
                                    'I_SORT'                => $order + 1,
                                    'I_GOODS_IDX'           => _elm( $requests, 'i_goods_idx' ),
                                    'I_IMG_NAME'            => _elm( $file_return, 'org_name'),
                                    'I_IMG_PATH'            => _elm($resized, 'path'),
                                    'I_IMG_VIEW_SIZE'       => _elm($resized, 'size'),
                                    'I_IMG_SIZE'            => filesize($resized['path']),
                                    'I_IMG_EXT'             => $fileExtension,
                                    'I_IMG_MIME_TYPE'       => $fileMimeType,
                                    'I_IS_ORIGIN'           => 'N', // 리사이즈된 이미지 플래그 설정
                                    'I_CREATE_AT'           => date('Y-m-d H:i:s'),
                                    'I_CREATE_IP'           => $this->request->getIPAddress(),
                                    'I_CREATE_MB_IDX'       => _elm($this->session->get('_memberInfo'), 'member_idx')
                                ];

                                $resizeImgIdx = $goodsModel->insertGoodsImages($resizeParam);
                                if ($this->db->transStatus() === false || $resizeImgIdx == false) {
                                    $this->db->transRollback();
                                    $response['status'] = 400;
                                    $response['alert'] = '리사이즈 이미지 처리중 오류발생.. 다시 시도해주세요.';
                                    return $this->respond($response);
                                }
                            }
                        }
                    }
                }
            }
        }












        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 수정 - orgData:'.json_encode($aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE).' // -> //'. json_encode($modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) ;
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

    function deleteDirectory($dirPath) {
        // 디렉토리가 존재하는지 확인
        if (!is_dir($dirPath)) {
            return false;
        }

        // 디렉토리 내의 파일과 서브디렉토리를 검색
        $files                                      = array_diff(scandir($dirPath), array('.', '..'));

        foreach ($files as $file) {
            $filePath                               = $dirPath . '/' . $file;

            // 만약 디렉토리라면 재귀적으로 삭제
            if (is_dir($filePath)) {
                deleteDirectory($filePath);
            } else {
                // 파일이라면 삭제
                unlink($filePath);
            }
        }

        // 모든 파일과 서브디렉토리가 삭제된 후, 최종적으로 디렉토리를 삭제
        return rmdir($dirPath);
    }



    public function deleteToCopyGoods()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $goodsModel                                 = new GoodsModel();
        $iconsModel                                 = new IconsModel();


        $goodsIdxs                                  = explode( ',', _elm( $requests, 'goodsIdxs' ) );
        if( empty( $goodsIdxs ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '선택된 데이터가 없습니다. 새로고침 후 이용해주세요.';

            return $this->respond( $response );
        }
        $this->db->transBegin();





        foreach( $goodsIdxs as $goodsIdx ){
            $aData                                  = $goodsModel->getGoodsDataByIdx( $goodsIdx );

            #------------------------------------------------------------------
            # TODO: 이미지 삭제
            #------------------------------------------------------------------
            $imgDatas                                  = $goodsModel->getGoodsInImages( _elm( $requests, 'i_goods_idx' ) );
                if( empty( $imgDatas ) == false ){
                    $removeFilePath                     = WRITEPATH.'uploads/goods/product/'.$goodsIdx;
                    if (is_dir($removeFilePath)) {
                        $this->deleteDirectory( $removeFilePath );
                    }
                #------------------------------------------------------------------
                # TODO: 기존 데이터 삭제
                #------------------------------------------------------------------
                $rDelStatus                             = $goodsModel->deleteData( 'GOODS_IMAGES', ['field'=>'I_GOODS_IDX', 'idx'=> $goodsIdx ] );
                if ( $this->db->transStatus() === false || $rDelStatus === false) {
                    $response['status']                 = 400;
                    $response['alert']                  = '이미지 완전 삭제 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }

            #------------------------------------------------------------------
            # TODO: 할인그룹 삭제
            #------------------------------------------------------------------
            $gDelStatus                             = $goodsModel->deleteData( 'GOODS_DISCOUNT_GROUP', ['field'=>'D_GOODS_IDX', 'idx'=> $goodsIdx ] );
            if ( $this->db->transStatus() === false || $gDelStatus === false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '할인그룹 데이터 완전 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }

            #------------------------------------------------------------------
            # TODO: 옵션데이터 삭제
            #------------------------------------------------------------------
            $oDelStatus                             = $goodsModel->deleteData( 'GOODS_OPTIONS', ['field'=>'O_GOODS_IDX', 'idx'=> $goodsIdx ] );
            if ( $this->db->transStatus() === false || $oDelStatus === false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '옵션 데이터 완전 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }
            #------------------------------------------------------------------
            # TODO: 아이콘 삭제
            #------------------------------------------------------------------
            $iDelStatus                             = $goodsModel->deleteData( 'GOODS_IN_ICONS', ['field'=>'I_GOODS_IDX', 'idx'=> $goodsIdx ] );
            if ( $this->db->transStatus() === false || $iDelStatus === false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '아이콘 데이터 완전 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }

            #------------------------------------------------------------------
            # TODO: 정보제공고시 삭제
            #------------------------------------------------------------------
            $rDelStatus                             = $goodsModel->deleteData( 'GOODS_ADD_REQUIRED_INFO', ['field'=>'I_GOODS_IDX', 'idx'=> $goodsIdx ] );
            if ( $this->db->transStatus() === false || $rDelStatus === false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '추가정보제공 데이터 완전 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }

            #------------------------------------------------------------------
            # TODO: 상품데이터 삭제
            #------------------------------------------------------------------
            $gDelStatus                             = $goodsModel->deleteData( 'GOODS', ['field'=>'G_IDX', 'idx'=> $goodsIdx ] );
            if ( $this->db->transStatus() === false || $gDelStatus === false) {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = '상품 데이터 완전 삭제 처리중 오류발생.. 다시 시도해주세요.';
                return $this->respond( $response );
            }

            #------------------------------------------------------------------
            # TODO: goodsIdx가 그룹에 속한 상품 업데이트
            #------------------------------------------------------------------

            $groupInGoods = $goodsModel->getGroupInGoods($goodsIdx);


            if (!empty($groupInGoods)) {
                foreach ($groupInGoods as $aKey => $inGoods) {
                    $_group = json_decode(_elm($inGoods, 'G_GROUP'), true);
                    $targetIdx = $goodsIdx;

                    // use 키워드를 사용하여 $targetIdx 변수를 클로저 함수에 전달
                    $filteredArray = array_filter($_group, function($item) use ($targetIdx) {
                        return $item['g_idx'] != $targetIdx;
                    });

                    // 필터링된 배열을 JSON 문자열로 다시 변환
                    $gModel = [];
                    $gModel['G_IDX'] = _elm($inGoods, 'G_IDX');
                    $gModel['G_GROUP'] = json_encode(array_values($filteredArray), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

                    // 수정된 데이터베이스 업데이트 작업
                    $grStatus = $goodsModel->updateGoodsGroup($gModel);

                    if ($this->db->transStatus() === false || $grStatus == false) {
                        $this->db->transRollback();
                        $response['status'] = 400;
                        $response['alert'] = '삭제된 상품의 그룹 삭제 처리 중 오류 발생.. 다시 시도해주세요.';
                        return $this->respond($response);
                    }
                }
            }


            #------------------------------------------------------------------
            # TODO: 관리자 로그남기기 S
            #------------------------------------------------------------------
            $logParam                               = [];
            $logParam['MB_HISTORY_CONTENT']         = '상품 데이터 완전 삭제 - orgData'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE )  ;
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

    public function copyGoods()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $goodsModel                                 = new GoodsModel();
        $iconsModel                                 = new IconsModel();
        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'goodsIdx' => [
                'label'  => '상품idx',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상품IDX가 없습니다.',
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
        $goodsInfo                                  = $goodsModel->getGoodsDataByIdx( _elm( $requests, 'goodsIdx' ) );

        $modelParam                                 = [];

        #------------------------------------------------------------------
        # TODO: 기본 데이터 세팅
        #------------------------------------------------------------------
        $modelParam['G_PRID']                       = _uniqid( 12, true );
        $modelParam['G_GROUP']                      = _elm( $goodsInfo, 'G_GROUP' );
        $modelParam['G_LOCAL_PRID']                 = _elm( $goodsInfo, 'G_LOCAL_PRID' );
        $modelParam['G_CATEGORY_MAIN']              = _elm( $goodsInfo, 'G_CATEGORY_MAIN' );
        $modelParam['G_CATEGORY_MAIN_IDX']          = _elm( $goodsInfo, 'G_CATEGORY_MAIN_IDX' );
        $modelParam['G_CATEGORYS']                  = _elm( $goodsInfo, 'G_CATEGORYS' );
        $modelParam['G_NAME']                       = _elm( $goodsInfo, 'G_NAME' );
        $modelParam['G_NAME_ENG']                   = _elm( $goodsInfo, 'G_NAME_ENG' );
        $modelParam['G_SHORT_DESCRIPTION']          = _elm( $goodsInfo, 'G_SHORT_DESCRIPTION' );
        $modelParam['G_CONTENT_PC']                 = _elm( $goodsInfo, 'G_CONTENT_PC' );
        $modelParam['G_CONTENT_MOBILE']             = _elm( $goodsInfo, 'G_CONTENT_MOBILE' );
        $modelParam['G_CONTETN_IS_SAME_FLAG']       = _elm( $goodsInfo, 'G_CONTETN_IS_SAME_FLAG' );
        $modelParam['G_SEARCH_KEYWORD']             = _elm( $goodsInfo, 'G_SEARCH_KEYWORD' );
        $modelParam['G_ADD_POINT']                  = _elm( $goodsInfo, 'G_ADD_POINT' );
        $modelParam['G_IS_PERFIT_FLAG']             = _elm( $goodsInfo, 'G_IS_PERFIT_FLAG' );
        $modelParam['G_SELL_PERIOD_START_AT']       = _elm( $goodsInfo, 'G_SELL_PERIOD_START_AT' );
        $modelParam['G_SELL_PERIOD_END_AT']         = _elm( $goodsInfo, 'G_SELL_PERIOD_END_AT' );
        $modelParam['G_COLOR']                      = _elm( $goodsInfo, 'G_COLOR' );
        $modelParam['G_SELL_PRICE']                 = _elm( $goodsInfo, 'G_SELL_PRICE' );
        $modelParam['G_SELL_UNIT']                  = _elm( $goodsInfo, 'G_SELL_UNIT' );
        $modelParam['G_BUY_PRICE']                  = _elm( $goodsInfo, 'G_BUY_PRICE' );
        $modelParam['G_PRICE']                      = _elm( $goodsInfo, 'G_PRICE' );
        $modelParam['G_PRICE_RATE']                 = _elm( $goodsInfo, 'G_PRICE_RATE' );
        $modelParam['G_TAX_TYPE']                   = _elm( $goodsInfo, 'G_TAX_TYPE' );
        $modelParam['G_DISCOUNT_CD']                = _elm( $goodsInfo, 'G_DISCOUNT_CD' );
        $modelParam['G_SELL_POINT_FLAG']            = _elm( $goodsInfo, 'G_SELL_POINT_FLAG' );
        $modelParam['G_RELATION_GOODS_FLAG']        = _elm( $goodsInfo, 'G_RELATION_GOODS_FLAG' );
        $modelParam['G_RELATION_GOODS']             = _elm( $goodsInfo, 'G_RELATION_GOODS' );
        $modelParam['G_ADD_GOODS_FLAG']             = _elm( $goodsInfo, 'G_ADD_GOODS_FLAG' );
        $modelParam['G_ADD_GOODS']                  = _elm( $goodsInfo, 'G_ADD_GOODS' );
        $modelParam['G_DELETE_STATUS']              = _elm( $goodsInfo, 'G_DELETE_STATUS' );
        $modelParam['G_OPTION_USE_FLAG']            = _elm( $goodsInfo, 'G_OPTION_USE_FLAG' );
        $modelParam['G_STOCK_FLAG']                 = _elm( $goodsInfo, 'G_STOCK_FLAG' );
        $modelParam['G_STOCK_CNT']                  = _elm( $goodsInfo, 'G_STOCK_CNT' );
        $modelParam['G_SAFETY_STOCK']               = _elm( $goodsInfo, 'G_SAFETY_STOCK' );
        $modelParam['G_DELIVERY_PAY_CD']            = _elm( $goodsInfo, 'G_DELIVERY_PAY_CD' );
        $modelParam['G_DELIVERY_INFO']              = _elm( $goodsInfo, 'G_DELIVERY_INFO' );
        $modelParam['G_EXCHANGE_INFO']              = _elm( $goodsInfo, 'G_EXCHANGE_INFO' );
        $modelParam['G_AS_INFO']                    = _elm( $goodsInfo, 'G_AS_INFO' );
        $modelParam['G_REFOUND_INFO']               = _elm( $goodsInfo, 'G_REFOUND_INFO' );
        $modelParam['G_PC_OPEN_FLAG']               = _elm( $goodsInfo, 'G_PC_OPEN_FLAG' );
        $modelParam['G_PC_SELL_FLAG']               = _elm( $goodsInfo, 'G_PC_SELL_FLAG' );
        $modelParam['G_MOBILE_OPEN_FLAG']           = _elm( $goodsInfo, 'G_MOBILE_OPEN_FLAG' );
        $modelParam['G_MOBILE_SELL_FLAG']           = _elm( $goodsInfo, 'G_MOBILE_SELL_FLAG' );
        $modelParam['G_ORIGIN_NAME']                = _elm( $goodsInfo, 'G_ORIGIN_NAME' );
        $modelParam['G_MAKER_NAME']                 = _elm( $goodsInfo, 'G_MAKER_NAME' );
        $modelParam['G_BRAND_IDX']                  = _elm( $goodsInfo, 'G_BRAND_IDX' );
        $modelParam['G_BRAND_NAME']                 = _elm( $goodsInfo, 'G_BRAND_NAME' );
        $modelParam['G_SEO_TITLE']                  = _elm( $goodsInfo, 'G_SEO_TITLE' );
        $modelParam['G_SEO_DESCRIPTION']            = _elm( $goodsInfo, 'G_SEO_DESCRIPTION' );
        $modelParam['G_ICON_IDXS']                  = _elm( $goodsInfo, 'G_ICON_IDXS' );
        $modelParam['G_ICON_PERIOD_START']          = _elm( $goodsInfo, 'G_ICON_PERIOD_START' );
        $modelParam['G_ICON_PERIOD_END']            = _elm( $goodsInfo, 'G_ICON_PERIOD_END' );
        $modelParam['G_OUT_VIEW']                   = _elm( $goodsInfo, 'G_OUT_VIEW' );
        $modelParam['G_OUT_MAIN_IMG_PATH']          = _elm( $goodsInfo, 'G_OUT_MAIN_IMG_PATH' );
        $modelParam['G_OUT_MAIN_IMG_NAME']          = _elm( $goodsInfo, 'G_OUT_MAIN_IMG_NAME' );
        $modelParam['G_OUT_GOODS_NAME']             = _elm( $goodsInfo, 'G_OUT_GOODS_NAME' );
        $modelParam['G_OUT_EVENT_TXT']              = _elm( $goodsInfo, 'G_OUT_EVENT_TXT' );
        $modelParam['G_GOODS_CONDITION']            = _elm( $goodsInfo, 'G_GOODS_CONDITION' );
        $modelParam['G_IS_SALES_TYPE']              = _elm( $goodsInfo, 'G_IS_SALES_TYPE' );
        $modelParam['G_GOODS_PRODUCT_TYPE']         = _elm( $goodsInfo, 'G_GOODS_PRODUCT_TYPE' );
        $modelParam['G_MIN_BUY_COUNT']              = _elm( $goodsInfo, 'G_MIN_BUY_COUNT' );
        $modelParam['G_MEM_MAX_BUY_COUNT']          = _elm( $goodsInfo, 'G_MEM_MAX_BUY_COUNT' );
        $modelParam['G_IS_ADULT_PRODUCT']           = _elm( $goodsInfo, 'G_IS_ADULT_PRODUCT' );
        $modelParam['G_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['G_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['G_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );


        $this->db->transBegin();

        $aIdx                                       = $goodsModel->insertGoods( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상품 복사중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 그룹 설정
        #------------------------------------------------------------------
        #------------------------------------------------------------------
        # TODO: 1. 원본 상품의 G_GROUP 필드 가져오기 및 디코딩
        #------------------------------------------------------------------
        $originalGroupInfo                          = json_decode(_elm($goodsInfo, 'G_GROUP'), true);
        #------------------------------------------------------------------
        # TODO: 2. 복사된 상품의 정보를 G_GROUP에 추가
        #------------------------------------------------------------------
        $originalGroupInfo[]                        = [
            'g_idx'                                 => $aIdx,
            'g_is_main'                             => 'N'  // 복제된 상품에서는 메인이 아니므로 'N'
        ];
        #------------------------------------------------------------------
        # TODO: 3. JSON 인코딩하여 원본과 복사본 모두에 동일한 G_GROUP 정보 설정
        #------------------------------------------------------------------
        $groupInfoJson                              = json_encode($originalGroupInfo);

        #------------------------------------------------------------------
        # TODO: 4. 복사된 상품의 그룹 정보 업데이트
        #------------------------------------------------------------------
        $goodsGroupParam['G_IDX']                   = $aIdx;
        $goodsGroupParam['G_GROUP']                 = $groupInfoJson;

        $gStatus                                    = $goodsModel->updateGoodsGroup($goodsGroupParam);

        if ($this->db->transStatus() === false || $gStatus == false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '복사된 상품의 그룹 등록 처리 중 오류 발생.. 다시 시도해주세요.';
            return $this->respond($response);
        }

        # 5. 원본 상품의 그룹 정보 업데이트 (복사본과 동일한 데이터)
        $originalGoodsGroupParam['G_IDX']           = _elm($goodsInfo, 'G_IDX');
        $originalGoodsGroupParam['G_GROUP']         = $groupInfoJson;
        $ogStatus                                   = $goodsModel->updateGoodsGroup($originalGoodsGroupParam);

        if ($this->db->transStatus() === false || $ogStatus == false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '원본 상품 그룹 정보 업데이트 처리 중 오류 발생.. 다시 시도해주세요.';
            return $this->respond($response);
        }

        #------------------------------------------------------------------
        # TODO:이미지 복사를 위한 이미지 데이터 로드
        #------------------------------------------------------------------
        $copyData['IMGS_INFO']                      = $goodsModel->getGoodsInImages( _elm( $requests, 'goodsIdx') );
        $newDir                                     = 'uploads/goods/product/'.$aIdx;
        #------------------------------------------------------------------
        # TODO: 디렉토리 존재 확인 및 생성
        #------------------------------------------------------------------

        if (!is_dir($newDir)) {
            mkdir($newDir, 0777, true);
        }
        foreach ( _elm( $copyData, 'IMGS_INFO' ) as $imgInfo) {
            $sourcePath                             = $imgInfo['I_IMG_PATH']; // 원본 경로
            $fileName                               = basename($sourcePath); // 파일명 추출
            $destinationPath                        = $newDir . '/' . $fileName; // 새로운 경로

            #------------------------------------------------------------------
            # TODO: 파일이 존재하는지 확인하고 복사
            #------------------------------------------------------------------
            if (file_exists($sourcePath)) {
                if (copy($sourcePath, $destinationPath)) {
                    $imgParam                       = [];
                    $imgParam['I_SORT']             = _elm( $imgInfo, 'I_SORT' );
                    $imgParam['I_GOODS_IDX']        = $aIdx;
                    $imgParam['I_IMG_NAME']         = _elm( $imgInfo, 'I_IMG_NAME' );
                    $imgParam['I_IMG_PATH']         = $destinationPath;
                    $imgParam['I_IMG_SIZE']         = filesize($destinationPath);
                    $imgParam['I_IMG_VIEW_SIZE']    = _elm( $imgInfo, 'I_IMG_VIEW_SIZE' );
                    $imgParam['I_IS_ORIGIN']        = _elm( $imgInfo, 'I_IS_ORIGIN' );
                    $imgParam['I_IMG_EXT']          = _elm( $imgInfo, 'I_IMG_EXT' );
                    $imgParam['I_IMG_MIME_TYPE']    = _elm( $imgInfo, 'I_IMG_MIME_TYPE' );
                    $imgParam['I_CREATE_AT']        = date( 'Y-m-d H:i:s' );
                    $imgParam['I_CREATE_IP']        = $this->request->getIPAddress();
                    $imgParam['I_CREATE_MB_IDX']    = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                    $imgIdx                         = $goodsModel->insertGoodsImages( $imgParam );

                    if ( $this->db->transStatus() === false || $imgIdx == false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '이미지 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                } else {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = 'Failed to copy: $sourcePath to $destinationPath';
                    return $this->respond( $response );
                }
            } else {
                $this->db->transRollback();
                $response['status']                 = 400;
                $response['alert']                  = "File does not exist: $sourcePath";
                return $this->respond( $response );
            }
        }

        #------------------------------------------------------------------
        # TODO: DC 그룹 저장
        #------------------------------------------------------------------
        $dcGroupData                                = $goodsModel->getGoodsDCGroup( _elm( $requests, 'goodsIdx' ) );

        if( empty( $dcGroupData ) === false ){
            foreach( $dcGroupData as $gKey => $group ){
                $groupParam                         = [];

                $groupParam['D_GOODS_IDX']          = $aIdx;
                $groupParam['D_MB_GROUP_IDX']       = _elm( $group, 'D_MB_GROUP_IDX' );
                $groupParam['D_MB_GROUP_NAME']      = _elm( $group, 'D_MB_GROUP_NAME' );
                $groupParam['D_MB_GROUP_DC_AMT']    = _elm( $group, 'D_MB_GROUP_DC_AMT' );
                $groupParam['D_MB_GOURP_DC_UNIT']   = _elm( $group, 'D_MB_GOURP_DC_UNIT' );
                $groupParam['D_DC_PERIOD_START_AT'] = _elm( $group, 'D_DC_PERIOD_START_AT' );
                $groupParam['D_DC_PERIOD_END_AT']   = _elm( $group, 'D_DC_PERIOD_END_AT' );
                $groupParam['D_CREATE_AT']          = date( 'Y-m-d H:i:s' );
                $groupParam['D_CREATE_IP']          = $this->request->getIPAddress();
                $groupParam['D_CREATE_MB_IDX']      = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                $gIdx                               = $goodsModel->insertDCGroup( $groupParam );
                if ( $this->db->transStatus() === false || $gIdx == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '그룹 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 옵션 저장
        #------------------------------------------------------------------
        $optionData                                 = $goodsModel->getGoodsOptions( _elm( $requests, 'goodsIdx' ) );
        if( empty( $optionData ) === false ){
            foreach( $optionData as $oKey => $option  ){
                $optionParam                        = [];
                $optionParam['O_GOODS_IDX']         = $aIdx;

                $optionParam['O_KEYS']              = _elm( $option, 'O_KEYS' );
                $optionParam['O_VALUES']            = _elm( $option, 'O_VALUES' );
                $optionParam['O_ADD_PRICE']         = _elm( $option, 'O_ADD_PRICE' );
                $optionParam['O_STOCK']             = _elm( $option, 'O_STOCK' );
                $optionParam['O_VIEW_STATUS']       = _elm( $option, 'O_VIEW_STATUS' );
                $optionParam['O_CREATE_AT']         = date( 'Y-m-d H:i:s' );
                $optionParam['O_CREATE_IP']         = $this->request->getIPAddress();
                $optionParam['O_CREATE_MB_IDX']     = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                $oIdx                               = $goodsModel->insertGoodsOptions( $optionParam );
                if ( $this->db->transStatus() === false || $oIdx == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '옵션 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 아이콘 저장
        #------------------------------------------------------------------
        $iconData                                   = $iconsModel->getGoodsInIcons( _elm( $requests, 'goodsIdx' ) );
        if( empty( $iconData ) === false ){
            foreach( $iconData as $iKey => $icon  ){
                $iconsParam                         = [];
                $iconsParam['I_GOODS_IDX']          = $aIdx;

                $iconsParam['I_ICONS_IDX']          = _elm( $icon, 'I_ICONS_IDX' );
                $iconsParam['I_ICONS_PERIOD_START_AT']= _elm( $icon, 'I_ICONS_PERIOD_START_AT' );
                $iconsParam['I_ICONS_PERIOD_END_AT']= _elm( $icon, 'I_ICONS_PERIOD_END_AT' );
                $iconsParam['I_ICONS_GBN']          = _elm( $icon, 'I_ICONS_GBN' );
                $iconsParam['I_CREATE_AT']          = date( 'Y-m-d H:i:s' );
                $iconsParam['I_CREATE_IP']          = $this->request->getIPAddress();
                $iconsParam['I_CREATE_MB_IDX']      = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                $iIdx                               = $goodsModel->insertGoodsInIcons( $iconsParam );
                if ( $this->db->transStatus() === false || $iIdx == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '아이콘 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 상품정보제공고시 저장
        #------------------------------------------------------------------
        $reqData                                    = $goodsModel->getReqInfos( _elm( $requests, 'goodsIdx' ) );
        if( empty( $reqData ) === false ){
            foreach( $reqData as $rKey => $req_info ){

                $reqParam                           = [];
                $reqParam['I_SORT']                 = _elm( $req_info, 'I_SORT' );
                $reqParam['I_GOODS_IDX']            = $aIdx;
                $reqParam['I_KEY']                  = _elm( $req_info, 'I_KEY' );
                $reqParam['I_VALUE']                = _elm( $req_info, 'I_VALUE' );
                $reqParam['I_CREATE_AT']            = date( 'Y-m-d H:i:s' );
                $reqParam['I_CREATE_IP']            = $this->request->getIPAddress();
                $reqParam['I_CREATE_MB_IDX']        = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                $rStatus                            = $goodsModel->insertReqInfos( $reqParam );
                if ( $this->db->transStatus() === false || $rStatus == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '정보제공고시 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 상호등록일 경우 연관상품도 같이 데이터 수정해줘야 한다. $relationDatas
        #------------------------------------------------------------------
        $relationDatas                              = json_decode( _elm( $goodsInfo, 'G_RELATION_GOODS' ), true );
        if( empty( $relationDatas ) === false ){
            foreach( $relationDatas as $r_key => $relationData ){
                if( _elm( $relationData, 'g_add_gbn' ) == 'dup' ){
                    $targetGoodsInfo                = $goodsModel->getGoodsDataByIdx( _elm($relationData, 'g_idx') );
                    if( empty( $targetGoodsInfo ) == false ){
                        $tRelInfo                   = json_decode( _elm($targetGoodsInfo, 'G_RELATION_GOODS'), true );
                        $tRelInfo[]                 = [
                            'g_idx'                 => $aIdx,
                            'g_add_gbn'             => _elm( $relationData, 'g_add_gbn' ),
                        ];
                        #------------------------------------------------------------------
                        # TODO:  중복 제거: g_idx가 중복된 항목을 제거.
                        #------------------------------------------------------------------
                        $uniqueTRelInfo             = [];
                        foreach ($tRelInfo as $item) {
                            $uniqueTRelInfo[$item['g_idx']] = $item;
                        }

                        #------------------------------------------------------------------
                        # TODO: 배열을 다시 값 배열로 변환 (키 제거)
                        #------------------------------------------------------------------
                        $uniqueTRelInfo = array_values($uniqueTRelInfo);

                        #------------------------------------------------------------------
                        # TODO: 결과를 JSON 문자열로 인코딩
                        #------------------------------------------------------------------
                        $tRelInfoJson = json_encode($uniqueTRelInfo);

                        $relParam                   = [];
                        $relParam['G_IDX']          =  _elm($relationData, 'g_idx') ;
                        $relParam['G_RELATION_GOODS']= $tRelInfoJson;

                        $tStatus                    = $goodsModel->updateGoodsRelationData( $relParam );
                        if ( $this->db->transStatus() === false || $tStatus == false ) {
                            $this->db->transRollback();
                            $response['status']     = 400;
                            $response['alert']      = '연관상품 상호등록 처리중 오류발생.. 다시 시도해주세요.';
                            return $this->respond( $response );
                        }

                        #------------------------------------------------------------------
                        # 3. 복사된 상품($aIdx)의 G_RELATION_GOODS 필드도 업데이트
                        #------------------------------------------------------------------
                        $newRelInfo                 = json_decode(_elm($goodsInfo, 'G_RELATION_GOODS'), true);
                        $newRelInfo[]               = [
                            'g_idx'                 => _elm($relationData, 'g_idx'),
                            'g_add_gbn'             => _elm($relationData, 'g_add_gbn'),
                        ];

                        # 중복 제거
                        $uniqueNewRelInfo           = [];
                        foreach ($newRelInfo as $item) {
                            $uniqueNewRelInfo[$item['g_idx']] = $item;
                        }
                        $uniqueNewRelInfo           = array_values($uniqueNewRelInfo);  # 배열을 다시 값 배열로 변환

                        $newRelInfoJson             = json_encode($uniqueNewRelInfo);  # 결과를 JSON 문자열로 인코딩

                        # 복사된 상품($aIdx)의 G_RELATION_GOODS 필드 업데이트
                        $newRelParam                = [];
                        $newRelParam['G_IDX']       = $aIdx;
                        $newRelParam['G_RELATION_GOODS'] = $newRelInfoJson;

                        $nStatus = $goodsModel->updateGoodsRelationData($newRelParam);
                        if ($this->db->transStatus() === false || $nStatus == false) {
                            $this->db->transRollback();
                            $response['status']     = 400;
                            $response['alert']      = '복사된 상품의 연관상품 업데이트 처리 중 오류 발생.. 다시 시도해주세요.';
                            return $this->respond($response);
                        }

                    }
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 복사 - '._elm($requests, 'goodsIdx').'->'.$aIdx ;
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

    public function goodsRegisterProc()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = $this->request->getPost();
        $files                                      = $this->request->getFiles();
        $goodsModel                                 = new GoodsModel();
        $membershipModel                            = new MembershipModel();
        $categoryModel                              = new CategoryModel();
        $brandModel                                 = new BrandModel();
        $iconModel                                  = new IconsModel();


        $validation                                 = \Config\Services::validation();
        #------------------------------------------------------------------
        # TODO: 검사 변수 true로 설정
        #------------------------------------------------------------------
        $isRule                                     = true;

        #------------------------------------------------------------------
        # TODO: 필수 parameter 검사
        #------------------------------------------------------------------
        $validation->setRules([
            'i_goods_name' => [
                'label'  => '상품명',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상품명을 입력하세요.',
                ],
            ],
            'i_goods_name_eng' => [
                'label'  => '상품명(영문)',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '상품명(영문)을 입력하세요',
                ],
            ],
            'i_mobile_content_same_chk' => [
                'label'  => '모바일 설명이 PC와 동일한지 선택하세요.',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '모바일 설명이 PC와 동일한지 선택하세요.',
                ],
            ],
            'i_sell_price' => [
                'label'  => '소비자가',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '소비자가를 입력하세요.',
                ],
            ],
            'i_buy_price' => [
                'label'  => '공급가',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '공급가를 입력하세요.',
                ],
            ],
            'i_goods_price' => [
                'label'  => '판매가',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '판매가를 입력하세요.',
                ],
            ],
            'i_goods_price_rate' => [
                'label'  => '마진율',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '마진율을 입력하세요.',
                ],
            ],
            'i_tax_type' => [
                'label'  => '과세구분',
                'rules'  => 'trim|required',
                'errors' => [
                    'required' => '과세구분을 입력하세요.',
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
        # TODO: 파일 검사
        #------------------------------------------------------------------
        if( empty( $files ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '아이콘 파일을 선택하세요.';

            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 데이터 세팅
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['G_PRID']                       = _uniqid( 12, true );
        $modelParam['G_GROUP']                      = ''; // 그룹번호 없으면 insertid가 그룹임 인서트 후 넣어야함.


        #------------------------------------------------------------------
        # TODO: 메인 카테고리 TXT 저장
        #------------------------------------------------------------------
        $mainCateInfo                               = $categoryModel->getCateTopNameJoin( _elm( $requests, 'i_is_category_main' ) );
        $modelParam['G_CATEGORY_MAIN']              = _elm( $mainCateInfo, 'FullCategoryName' );
        $modelParam['G_CATEGORY_MAIN_IDX']          = _elm( $requests, 'i_is_category_main' );

        #------------------------------------------------------------------
        # TODO: 검색용 카테고리 JSON 형식으로 저장
        #------------------------------------------------------------------
        $cateInfo                                   = $categoryModel->getCategoryDataByIdxs( _elm( $requests, 'i_cate_idx' ) );
        $modelParam['G_CATEGORYS']                  = json_encode( $cateInfo, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );

        $modelParam['G_NAME']                       = _elm( $requests, 'i_goods_name' );
        $modelParam['G_NAME_ENG']                   = _elm( $requests, 'i_goods_name_eng' );
        $modelParam['G_LOCAL_PRID']                 = _elm( $requests, 'i_goods_local_code' );
        $modelParam['G_SHORT_DESCRIPTION']          = htmlspecialchars(  _elm( $requests, 'i_description' ) );
        $modelParam['G_CONTETN_IS_SAME_FLAG']       = _elm( $requests, 'i_mobile_content_same_chk' );
        $modelParam['G_CONTENT_PC']                 = htmlspecialchars(  _elm( $requests, 'i_content_pc' ) );
        #------------------------------------------------------------------
        # TODO:  G_CONTETN_IS_SAME_FLAG == N 이면 모바일 내용 따로 저장
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_mobile_content_same_chk' ) == 'N' ){
            $modelParam['G_CONTENT_MOBILE']         = htmlspecialchars(  _elm( $requests, 'i_content_mobile' ) );
        }
        $modelParam['G_SEARCH_KEYWORD']             = _elm( $requests, 'i_search_keyword' );
        $modelParam['G_ADD_POINT']                  = preg_replace('/,/','', _elm( $requests, 'i_goods_add_point' ) );
        $modelParam['G_IS_PERFIT_FLAG']             = _elm( $requests, 'i_perfit_use' );
        $modelParam['G_SELL_PERIOD_START_AT']       = _elm( $requests, 'i_sell_period_start_at' );
        $modelParam['G_SELL_PERIOD_END_AT']         = _elm( $requests, 'i_sell_period_end_at' );
        $modelParam['G_COLOR']                      = _elm( $requests, 'i_goods_color' );
        $modelParam['G_SELL_PRICE']                 = preg_replace('/,/','', _elm( $requests, 'i_sell_price' ) );
        $modelParam['G_SELL_UNIT']                  = preg_replace('/,/','', _elm( $requests, 'i_sell_unit' ) ) ?? 'EA';
        $modelParam['G_BUY_PRICE']                  = preg_replace('/,/','', _elm( $requests, 'i_buy_price' ) );
        $modelParam['G_PRICE']                      = preg_replace('/,/','', _elm( $requests, 'i_goods_price' ) );
        $modelParam['G_PRICE_RATE']                 = preg_replace('/,/','', _elm( $requests, 'i_goods_price_rate' ) );
        $modelParam['G_TAX_TYPE']                   = _elm( $requests, 'i_tax_type' );
        $modelParam['G_DISCOUNT_CD']                = empty( _elm( $requests, 'i_discount_cd' ) ) === false ? join( ',' , _elm( $requests, 'i_discount_cd' ) ) : '';
        #------------------------------------------------------------------
        # TODO: 개별 포인트설정일 경우 등급데이터 세팅
        #------------------------------------------------------------------
        $modelParam['G_SELL_POINT_FLAG']            = _elm( $requests, 'i_sell_point_flag' );
        if( _elm( $requests, 'i_sell_point_flag' ) == 'Y' ){

            $groupParam                             = [];
            foreach( _elm( $requests, 'i_discount_mb_group' ) as $g_key => $d_mb_group_idx  ){
                $groupParam[$g_key]['D_MB_GROUP_IDX']       = $d_mb_group_idx;
                $gInfo                                      = $membershipModel->getMembershopGradeByIdx($d_mb_group_idx);
                $groupParam[$g_key]['D_MB_GROUP_NAME']      = _elm( $gInfo, 'G_NAME' );
                $groupParam[$g_key]['D_GOODS_IDX']          = $aIdx;
                $groupParam[$g_key]['D_MB_GROUP_DC_AMT']    = _elm( _elm( $requests, 'i_discount_mb_group_amt' ), $g_key );
                $groupParam[$g_key]['D_MB_GOURP_DC_UNIT']   = _elm( _elm( $requests, 'i_discount_mb_group_amt_unit' ), $g_key );
                $groupParam[$g_key]['D_DC_PERIOD_START_AT'] = _elm( _elm( $requests, 'i_discount_start_date' ), $g_key );
                $groupParam[$g_key]['D_DC_PERIOD_END_AT']   = _elm( _elm( $requests, 'i_discount_end_date' ), $g_key );
                $groupParam[$g_key]['D_CREATE_AT']          = date( 'Y-m-d H:i:s' );
                $groupParam[$g_key]['D_CREATE_IP']          = $this->request->getIPAddress();
                $groupParam[$g_key]['D_CREATE_MB_IDX']      = _elm( $this->session->get('_memberInfo') , 'member_idx' );
                $gIdx                                       = $goodsModel->insertDCGroup( $groupParam );
                if ( $this->db->transStatus() === false || $gIdx == false ) {
                    $this->db->transRollback();
                    $response['status']                     = 400;
                    $response['alert']                      = '그룹 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }

            }
        }
        #------------------------------------------------------------------
        # TODO: 연관상품 설정 i_relation_use_flag == 'Y'
        #------------------------------------------------------------------
        $modelParam['G_RELATION_GOODS_FLAG']        = _elm( $requests, 'i_relation_use_flag' );
        if( _elm( $requests, 'i_relation_use_flag' ) == 'Y' ){
            $relationDatas                          = [];
            if( empty( _elm( $requests, 'i_relation_goods_idxs' ) ) === false ){
                foreach( _elm( $requests, 'i_relation_goods_idxs' ) as $r_key => $relationIdx ){
                    $relationDatas[$r_key]['g_idx'] = $relationIdx;
                    $relationDatas[$r_key]['g_add_gbn']= _elm( _elm( $requests, 'i_relation_goods_add_gbn' ), $r_key );

                }
            }
            $modelParam['G_RELATION_GOODS']         = json_encode( $relationDatas , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        }

        #------------------------------------------------------------------
        # TODO: 추가상품 설정  i_add_goods_flag == 'Y'
        #------------------------------------------------------------------
        $modelParam['G_ADD_GOODS_FLAG']             = _elm( $requests, 'i_add_goods_flag' );
        if( _elm( $requests, 'i_add_goods_flag' ) == 'Y' ){
            $modelParam['G_ADD_GOODS']              = json_encode( _elm( $requests, 'i_add_goods_idxs', [] ), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        }

        #------------------------------------------------------------------
        # TODO: 옵션사용 설정 i_option_use_flag == 'Y'
        #------------------------------------------------------------------
        $modelParam['G_OPTION_USE_FLAG']            = _elm( $requests, 'i_option_use_flag' );
        if( empty( _elm( $requests, 'i_option_use_flag' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '옵션 사용 여부를 선택해주세요.';

            return $this->respond( $response );
        }
        if( _elm( $requests, 'i_option_use_flag' ) == 'Y' ){
            $optionParam                            = [];
            if( empty( _elm( $requests, 'i_option_keys' ) ) === true ){
                $response['status']                     = 400;
                $response['alert']                      = '옵션을 입력해주세요.';

                return $this->respond( $response );
            }
            foreach( _elm( $requests, 'i_option_keys' ) as $o_key => $option_key ){
                $optionParam[$o_key]['O_KEYS']      = $option_key;
                $optionParam[$o_key]['O_VALUES']    = _elm( _elm( $requests, 'i_option_value' ), $o_key );
                $optionParam[$o_key]['O_STOCK']     = _elm( _elm( $requests, 'i_option_stock' ), $o_key );
                $optionParam[$o_key]['O_ADD_PRICE'] = preg_replace('/,/','', _elm( _elm( $requests, 'i_option_add_price' ), $o_key ) ) ;
                $optionParam[$o_key]['O_VIEW_STATUS']= _elm( _elm( $requests, 'i_option_status' ), $o_key );
                $optionParam[$o_key]['O_CREATE_AT'] = date( 'Y-m-d H:i:s' );
                $optionParam[$o_key]['O_CREATE_IP'] = $this->request->getIPAddress();
                $optionParam[$o_key]['O_CREATE_MB_IDX']= _elm( $this->session->get('_memberInfo') , 'member_idx' );
            }
        }

        #------------------------------------------------------------------
        # TODO: 판매재고 무한정일때는 재고수량 999999로 넣고 아니면 재고수량에 자동계산된 재고수량으로 넣는다.
        #------------------------------------------------------------------
        $modelParam['G_STOCK_FLAG']                 = _elm( $requests, 'i_goods_stock_flag' );
        $modelParam['G_STOCK_CNT']                  = _elm( $requests, 'i_goods_stock_flag' ) == 'Y' ? _elm( $requests, 'i_goods_stock' ) : '999999';
        $modelParam['G_SAFETY_STOCK']               = _elm( $requests, 'i_goods_safe_stock', 999999 );
        $modelParam['G_DELIVERY_PAY_CD']            = _elm( $requests, 'i_delivery_pay' );

        #------------------------------------------------------------------
        # TODO: 텍스트 옵션 세팅
        #------------------------------------------------------------------
        if( _elm( $requests, 'i_text_option_use_flag' ) == 'Y' ){
            if( empty( _elm( $requests, 'i_text_option_keys' ) ) === false ){
                $textOptions                        = [];
                foreach( _elm( $requests, 'i_text_option_keys' ) as $tKey => $textOption ){
                    $_textOptions                   = [];
                    $_textOptions['type']           = _elm( _elm( $requests, 'i_text_option_type' ), $tKey );
                    $_textOptions['title']          = $textOption;
                    if( _elm( $_textOptions, 'type' ) != 'text' ){
                        foreach( _elm( _elm( $requests, 'i_text_option_extra' ), $tKey ) as $exKey => $extra ){
                            $_textOptions['extras'][$exKey] = $extra;
                        }
                    }
                    $textOptions[$tKey]             = $_textOptions;
                }
            }
        }
        $modelParam['G_TEXT_OPTION_USE_FLAG']       = _elm( $requests, 'i_text_option_use_flag' );
        $modelParam['G_TEXT_OPTION']                = empty( $textOptions ) === false ? json_encode(  $textOptions , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) :  json_encode([]) ;

        $modelParam['G_PC_OPEN_FLAG']               = _elm( $requests, 'i_is_pc_open' );
        $modelParam['G_PC_SELL_FLAG']               = _elm( $requests, 'i_is_pc_sell' );
        $modelParam['G_MOBILE_OPEN_FLAG']           = _elm( $requests, 'i_is_mobile_open' );
        $modelParam['G_MOBILE_SELL_FLAG']           = _elm( $requests, 'i_is_mobile_sell' );

        $modelParam['G_ORIGIN_NAME']                = _elm( $requests, 'i_origin_name' );
        $modelParam['G_MAKER_NAME']                 = _elm( $requests, 'i_maker_name' );

        $modelParam['G_BRAND_IDX']                  = _elm( $requests, 'i_is_brand_main' );
        $brandInfo                                  = $brandModel->getBrandDataByIdx( _elm( $requests, 'i_is_brand_main' ) );
        $modelParam['G_BRAND_NAME']                 = _elm( $brandInfo, 'C_BRAND_NAME' );

        $modelParam['G_SEO_TITLE']                  = _elm( $requests, 'i_seo_title' );
        $modelParam['G_SEO_DESCRIPTION']            = _elm( $requests, 'i_seo_description' );

        #------------------------------------------------------------------
        # TODO: 아이콘 데이터 삽입
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'i_icon_select' ) ) === false ){
            $iconsParam                                          = [];
            foreach( _elm( $requests, 'i_icon_select' ) as $i_key => $iconIdx ){
                $iconData                                       = $iconModel->getIconsDataByIdx( $iconIdx );
                $iconsParam[$i_key]['I_ICONS_IDX']               = $iconIdx;
                $iperiod_start                                  = _elm( _elm( $requests, 'i_icon_selct_start_at' ), $iconIdx );
                $iperiod_end                                    = _elm( _elm( $requests, 'i_icon_selct_end_at' ), $iconIdx );
                if( _elm($iconData, 'I_GBN') == 'P' ){
                    if( empty( $iperiod_start ) === true || empty( $iperiod_end ) === true ){
                        $response['status']                     = 400;
                        $response['alert']                      = '아이콘 시작일 또는 종료일을 입력해주세요.';

                        return $this->respond( $response );
                    }
                }else{
                    $iperiod_start                              = '0000-00-00 00:00:00';
                    $iperiod_end                                = '0000-00-00 00:00:00';
                }

                $iconsParam[$i_key]['I_ICONS_PERIOD_START_AT']   = $iperiod_start;
                $iconsParam[$i_key]['I_ICONS_PERIOD_END_AT']     = $iperiod_end;
                $iconsParam[$i_key]['I_ICONS_GBN']               = _elm( $iconData, 'I_GBN' );
                $iconsParam[$i_key]['I_CREATE_AT']               = date( 'Y-m-d H:i:s' );
                $iconsParam[$i_key]['I_CREATE_IP']               = $this->request->getIPAddress();
                $iconsParam[$i_key]['I_CREATE_MB_IDX']           = _elm( $this->session->get('_memberInfo') , 'member_idx' );
            }
        }

        $modelParam['G_OUT_VIEW']                   = empty( _elm( $requests, 'i_out_view' ) ) === false ? join( ',', _elm( $requests, 'i_out_view' ) ): '' ;
        #------------------------------------------------------------------
        # TODO: 외부노출이미지 설정
        #------------------------------------------------------------------
        $config                                     = [
            'path' => 'goods/out_site',
            'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
        ];

        if( _elm( $files, 'i_out_main_img' ) ){
            $outfile = _elm( $files, 'i_out_main_img' );
            if( $outfile->getSize() > 0 ){
                $file_return                            = $this->_upload( $outfile, $config );

                #------------------------------------------------------------------
                # TODO: 파일처리 실패 시
                #------------------------------------------------------------------
                if( _elm($file_return , 'status') === false ){
                    $this->db->transRollback();
                    $response['status']                 = 400;
                    $response['alert']                  = _elm( $file_return, 'error' );
                    return $this->respond( $response, 400 );
                }

                #------------------------------------------------------------------
                # TODO: 데이터모델 세팅
                #------------------------------------------------------------------

                $modelParam['G_OUT_MAIN_IMG_PATH']  = _elm( $file_return, 'uploaded_path');
                $modelParam['G_OUT_MAIN_IMG_NAME']  = _elm( $file_return, 'org_name');
            }
        }

        $modelParam['G_OUT_GOODS_NAME']             = _elm( $requests, 'i_out_goods_name' );
        $modelParam['G_OUT_EVENT_TXT']              = _elm( $requests, 'i_out_event_txt' );

        $modelParam['G_GOODS_CONDITION']            = _elm( $requests, 'i_goods_condition' );
        $modelParam['G_GOODS_PRODUCT_TYPE']         = empty( _elm( $requests, 'i_is_product_type' ) ) === false ? join( ',', _elm( $requests, 'i_is_product_type' ) ) : '' ;
        $modelParam['G_IS_SALES_TYPE']              = _elm( $requests, 'i_is_goods_seles_type' );

        $modelParam['G_MIN_BUY_COUNT']              = _elm( $requests, 'i_min_buy_count' ) < 1 ? '1' : _elm( $requests, 'i_min_buy_count' ) ;
        $modelParam['G_MEM_MAX_BUY_COUNT']          = _elm( $requests, 'i_mem_max_buy_count' ) == 0 || empty(_elm( $requests, 'i_mem_max_buy_count' )) === true? '9999' : _elm( $requests, 'i_mem_max_buy_count' ) ;

        $modelParam['G_IS_ADULT_PRODUCT']           = _elm( $requests, 'i_is_adult_product' );

        $modelParam['G_CREATE_AT']                  = date( 'Y-m-d H:i:s' );
        $modelParam['G_CREATE_IP']                  = $this->request->getIPAddress();
        $modelParam['G_CREATE_MB_IDX']              = _elm( $this->session->get('_memberInfo') , 'member_idx' );

        $this->db->transBegin();

        $aIdx                                       = $goodsModel->insertGoods( $modelParam );

        if ( $this->db->transStatus() === false || $aIdx == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상품 등록 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 그룹 설정
        #------------------------------------------------------------------
        $groupIdxs                                  = [];
        $groupIdxs[]                                = [
            'g_idx'                                 => $aIdx,
            'g_is_main'                             => 'Y',
        ];

        $goodsGroupParam['G_IDX']                   = $aIdx;
        $goodsGroupParam['G_GROUP']                 = json_encode( $groupIdxs );
        $goodsGroupParam['G_GROUP_MAIN']            = 'Y';
        $gStatus                                    = $goodsModel->updateGoodsGroup( $goodsGroupParam );

        if ( $this->db->transStatus() === false || $gStatus == false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '상품 그룹 등록 처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 파일처리
        #------------------------------------------------------------------
        if( empty( _elm($files, 'i_goods_img') ) === false ){
            $config                                     = [
                'path' => 'goods/product/'.$aIdx,
                'mimes' => 'pdf|jpg|gif|png|jpeg|svg',
            ];

            foreach( _elm($files, 'i_goods_img') as $f_key => $file ){
                if( $file->getSize() > 0 ){
                    $imgParam                           = [];
                    $fileSize                           = $file->getSize();
                    $fileExtension                      = $file->getExtension();
                    $fileMimeType                       = $file->getMimeType();
                    $file_return                        = $this->_uploadAndResize( $file, $config );

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

                    $imgParam['I_SORT']                 = $f_key + 1;
                    $imgParam['I_GOODS_IDX']            = $aIdx;
                    $imgParam['I_IMG_NAME']             = _elm( $file_return, 'org_name');
                    $imgParam['I_IMG_PATH']             = _elm( $file_return, 'uploaded_path');
                    $imgParam['I_IMG_VIEW_SIZE']        = '';
                    $imgParam['I_IS_ORIGIN']            = 'Y';
                    $imgParam['I_IMG_SIZE']             = $fileSize;
                    $imgParam['I_IMG_EXT']              = $fileExtension;
                    $imgParam['I_IMG_MIME_TYPE']        = $fileMimeType;
                    $imgParam['I_CREATE_AT']            = date( 'Y-m-d H:i:s' );
                    $imgParam['I_CREATE_IP']            = $this->request->getIPAddress();
                    $imgParam['I_CREATE_MB_IDX']        = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                    $imgIdx                             = $goodsModel->insertGoodsImages( $imgParam );
                    if ( $this->db->transStatus() === false || $imgIdx == false ) {
                        $this->db->transRollback();
                        $response['status']             = 400;
                        $response['alert']              = '이미지 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                    #------------------------------------------------------------------
                    # TODO: 리사이즈된 이미지 저장
                    #------------------------------------------------------------------
                    $resizedImages = _elm($file_return, 'resized');
                    if (!empty($resizedImages)) {
                        foreach ($resizedImages as $resized) {
                            $resizeParam = [
                                'I_SORT'                => $f_key + 1,
                                'I_GOODS_IDX'           => $aIdx,
                                'I_IMG_NAME'            => _elm( $file_return, 'org_name'),
                                'I_IMG_PATH'            => _elm($resized, 'path'),
                                'I_IMG_VIEW_SIZE'       => _elm($resized, 'size'),
                                'I_IMG_SIZE'            => filesize($resized['path']),
                                'I_IMG_EXT'             => $fileExtension,
                                'I_IMG_MIME_TYPE'       => $fileMimeType,
                                'I_IS_ORIGIN'           => 'N', // 리사이즈된 이미지 플래그 설정
                                'I_CREATE_AT'           => date('Y-m-d H:i:s'),
                                'I_CREATE_IP'           => $this->request->getIPAddress(),
                                'I_CREATE_MB_IDX'       => _elm($this->session->get('_memberInfo'), 'member_idx')
                            ];

                            $resizeImgIdx = $goodsModel->insertGoodsImages($resizeParam);
                            if ($this->db->transStatus() === false || $resizeImgIdx == false) {
                                $this->db->transRollback();
                                $response['status'] = 400;
                                $response['alert'] = '리사이즈 이미지 처리중 오류발생.. 다시 시도해주세요.';
                                return $this->respond($response);
                            }
                        }
                    }

                }


            }
        }
        #------------------------------------------------------------------
        # TODO: DC 그룹 저장
        #------------------------------------------------------------------
        if( empty( $groupParam ) === false ){
            foreach( $groupParam as $g_key => $group ){
                $groupParam                         = [];
                $groupParam                         = $group;
                $groupParam['D_GOODS_IDX']          = $aIdx;

                $gIdx                               = $goodsModel->insertDCGroup( $groupParam );
                if ( $this->db->transStatus() === false || $gIdx == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '그룹 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 옵션 저장
        #------------------------------------------------------------------
        if( empty( $optionParam ) === false ){
            foreach( $optionParam as $o_key => $option  ){
                $optionParam                        = [];
                $optionParam                        = $option;
                $optionParam['O_GOODS_IDX']         = $aIdx;

                $oIdx                               = $goodsModel->insertGoodsOptions( $optionParam );
                if ( $this->db->transStatus() === false || $oIdx == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '옵션 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }

        #------------------------------------------------------------------
        # TODO: 아이콘 저장
        #------------------------------------------------------------------
        if( empty( $iconsParam ) === false ){
            foreach( $iconsParam as $i_key => $icon  ){
                $iconsParam                         = [];
                $iconsParam                         = $icon;
                $iconsParam['I_GOODS_IDX']          = $aIdx;

                $iIdx                               = $goodsModel->insertGoodsInIcons( $iconsParam );
                if ( $this->db->transStatus() === false || $iIdx == false ) {
                    $this->db->transRollback();
                    $response['status']             = 400;
                    $response['alert']              = '아이콘 저장 처리중 오류발생.. 다시 시도해주세요.';
                    return $this->respond( $response );
                }
            }
        }
        #------------------------------------------------------------------
        # TODO: 상품정보제공고시 저장
        #------------------------------------------------------------------
        if( empty( _elm( $requests, 'i_req_info_keys' ) ) === false ){
            foreach( _elm( $requests, 'i_req_info_keys' ) as $key => $req_info ){
                if( !empty( $req_info  ) ){
                    $reqParam                       = [];
                    $reqParam['I_SORT']             = $key+1;
                    $reqParam['I_GOODS_IDX']        = $aIdx;
                    $reqParam['I_KEY']              = $req_info;
                    $reqParam['I_VALUE']            = _elm(_elm( $requests, 'i_req_info_values' ), $key  );
                    $reqParam['I_CREATE_AT']        = date( 'Y-m-d H:i:s' );
                    $reqParam['I_CREATE_IP']        = $this->request->getIPAddress();
                    $reqParam['I_CREATE_MB_IDX']    = _elm( $this->session->get('_memberInfo') , 'member_idx' );

                    $rStatus                        = $goodsModel->insertReqInfos( $reqParam );
                    if ( $this->db->transStatus() === false || $rStatus == false ) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['alert']          = '정보제공고시 저장 처리중 오류발생.. 다시 시도해주세요.';
                        return $this->respond( $response );
                    }
                }

            }

        }


        #------------------------------------------------------------------
        # TODO: 상호등록일 경우 연관상품도 같이 데이터 수정해줘야 한다. $relationDatas
        #------------------------------------------------------------------
        if( empty( $relationDatas ) === false ){
            foreach( $relationDatas as $r_key => $relationData ){
                if( _elm( $relationData, 'g_add_gbn' ) == 'dup' ){
                    $targetGoodsInfo                = $goodsModel->getGoodsDataByIdx( _elm($relationData, 'g_idx') );
                    if( empty( $targetGoodsInfo ) == false ){
                        $tRelInfo                   = json_decode( _elm($targetGoodsInfo, 'G_RELATION_GOODS'), true );
                        $tRelInfo[]                 = [
                            'g_idx'                 => $aIdx,
                            'g_add_gbn'             => _elm( $relationData, 'g_add_gbn' ),
                        ];
                        #------------------------------------------------------------------
                        # TODO:  중복 제거: g_idx가 중복된 항목을 제거.
                        #------------------------------------------------------------------
                        $uniqueTRelInfo             = [];
                        foreach ($tRelInfo as $item) {
                            $uniqueTRelInfo[$item['g_idx']] = $item;
                        }

                        #------------------------------------------------------------------
                        # TODO: 배열을 다시 값 배열로 변환 (키 제거)
                        #------------------------------------------------------------------
                        $uniqueTRelInfo = array_values($uniqueTRelInfo);

                        #------------------------------------------------------------------
                        # TODO: 결과를 JSON 문자열로 인코딩
                        #------------------------------------------------------------------
                        $tRelInfoJson = json_encode($uniqueTRelInfo);

                        $relParam                   = [];
                        $relParam['G_IDX']          =  _elm($relationData, 'g_idx') ;
                        $relParam['G_RELATION_GOODS']= $tRelInfoJson;

                        $tStatus                    = $goodsModel->updateGoodsRelationData( $relParam );
                        if ( $this->db->transStatus() === false || $tStatus == false ) {
                            $this->db->transRollback();
                            $response['status']     = 400;
                            $response['alert']      = '연관상품 상호등록 처리중 오류발생.. 다시 시도해주세요.';
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
        $logParam['MB_HISTORY_CONTENT']             = '상품 등록 - data:'.json_encode( $modelParam, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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
        $response['redirect_url']                   = _link_url('/goods/goodsLists');

        return $this->respond( $response );

    }
    public function getPopProductLists( $param = [] )
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $goodsModel                                 = new GoodsModel();
        $requests                                   = _trim($this->request->getPost());

        $param['post']                              = $requests;
        $param['post']['raw_return']                = true;

        $param['post']['per_page']                  = 10;

        $aLISTS_RESULT                              = $this->getGoodsLists( $param );

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
            $page_datas['lists_row']                = view( '\Module\goods\Views\goods\pop_lists' , ['owensView' => $owensView] );
            $page_datas['pagination']               = _elm( $aLISTS_RESULT, 'pagination' );

        }

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    function getPopGoodsLists( $param = [] ){
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $goodsModel                                 = new GoodsModel();
        $requests                                   = _trim($this->request->getPost());

        $param['post']                              = $requests;
        $param['post']['raw_return']                = true;

        $param['post']['per_page']                  = 10;

        $aLISTS_RESULT                              = $this->getGoodsLists( $param );

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

            if( !empty( _elm( $requests, 'xPickLists' ) ) ){
                $page_datas['lists_row']            = view( '\Module\promotion\Views\coupon\pop_lists_row' , ['owensView' => $owensView] );
            }else{
                $page_datas['lists_row']            = view( '\Module\goods\Views\goods\pop_lists_row' , ['owensView' => $owensView] );
            }
            $page_datas['pagination']               = _elm( $aLISTS_RESULT, 'pagination' );


        }

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }

    public function getGoodsLists( $param = [] ){
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $goodsModel                                 = new GoodsModel();
        $requests                                   = _trim($this->request->getPost());
        $modelParam                                 = [];
        if( empty( _elm( $param, 'post' ) ) === false ){
            $requests                               = _elm( $param, 'post' );
            if( empty( _elm( $requests , 's_keyword' ) ) === false )
            {
                $modelParam['G_NAME']                   = _elm( $requests , 's_keyword' );
                $modelParam['G_NAME_ENG']               = _elm( $requests , 's_keyword' );
            }
        }

        $modelParam['order']                        = ' G_CREATE_AT DESC';
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

        if( empty( _elm( $requests , 's_keyword' ) ) === false )
        {
            switch( _elm( $requests , 's_condition' ) )
            {
                case 'goods_name' :
                    $modelParam['G_NAME']           = _elm( $requests , 's_keyword' );
                    $modelParam['G_NAME_ENG']       = _elm( $requests , 's_keyword' );
                    break;
                case 'goods_code' :
                    $modelParam['G_PRID']           = _elm( $requests , 's_keyword' );
                    $modelParam['G_LOCAL_PRID']     = _elm( $requests , 's_keyword' );
                    break;
                case 'keyword' :
                    $modelParam['G_SEARCH_KEYWORD'] = _elm( $requests , 's_keyword' );
                    break;
                case 'maker' :
                    $modelParam['G_MAKER_NAME']     = _elm( $requests , 's_keyword' );
                    break;
            }
        }

        if( empty( _elm( $requests , 's_start_date' ) ) === false && empty( _elm( $requests , 's_start_date' ) ) === false )
        {
            switch( _elm( $requests , 's_date_condition' ) )
            {
                case 'create_at' :
                    $modelParam['G_CREATE_AT']      = true;
                    break;
                case 'update_at' :
                    $modelParam['G_UPDATE_AT']      = true;
                    break;
            }
            $modelParam['S_START_DATE']             = _elm( $requests, 's_start_date' );
            $modelParam['S_END_DATE']               = _elm( $requests, 's_end_date' );
        }

        $modelParam['IS_NOT_CATEGORY']              = _elm( $requests, 's_is_not_category' );
        if( empty( _elm( $requests, 's_is_not_category' ) ) === true ){
            if( empty( _elm( $requests, 's_category' ) ) === false ){
                $modelParam['G_CATEGORY_MAIN_IDX'] = _elm( $requests, 's_category' );
                if( empty( _elm( $requests, 's_child_category' ) ) === false ){
                    $modelParam['G_CATEGORY_MAIN_IDX'] = _elm( $requests, 's_child_category' );
                    if( empty( _elm( $requests, 's_grand_child_category' ) ) === false ){
                        $modelParam['G_CATEGORY_MAIN_IDX'] = _elm( $requests, 's_grand_child_category' );
                    }
                }
            }
        }
        $modelParam['G_BRAND_IDX']                  = _elm( $requests, 's_is_brand_main' );

        $modelParam['MIN_PRICE']                    = _elm( $requests, 's_min_price' );
        $modelParam['MAX_PRICE']                    = _elm( $requests, 's_max_price' );
        $modelParam['GROUP_USE_FLAG']               = _elm( $requests, 's_group_use' );
        $modelParam['VIEW_GBN_FLAG']                = _elm( $requests, 's_view_gbn' );
        $modelParam['OPTION_USE_FLAG']              = _elm( $requests, 's_option_use' );
        $modelParam['STOCK_OVER_FLAG']              = _elm( $requests, 's_stock_over' );

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;
        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;

        $modelParam['notIdx']                       = explode( ',', _elm( $requests, 'picLists' ) );

        if( !empty( _elm( $requests, 'xPickLists' ) ) ){
            $modelParam['pickIdx']                  = explode( ',', _elm( $requests, 'xPickLists' ) );
        }

        ###########################################################
        $aLISTS_RESULT                              = $goodsModel->getGoodsLists( $modelParam );



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
            $page_datas['lists_row']                = view( '\Module\goods\Views\goods\lists_row' , ['owensView' => $owensView] );

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
            $aLISTS_RESULT['pagination']            = _elm($page_datas, 'pagination' );
            return $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

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
            if( _elm( $requests, 'targetId' ) == 'product' ){
                $page_datas['lists_row']                = view( '\Module\goods\Views\goods\_addGoodsRows_product' , ['owensView' => $owensView] );
            }else if( _elm( $requests, 'targetId' ) == 'add_product' ){
                $page_datas['lists_row']                = view( '\Module\goods\Views\goods\_addGoodsRows_addProduct' , ['owensView' => $owensView] );
            }else if( _elm( $requests, 'targetId' ) == 'group_product' ){
                $page_datas['lists_row']                = view( '\Module\goods\Views\goods\_addGoodsRows_group' , ['owensView' => $owensView] );
            }else if( _elm( $requests, 'targetId' ) == 'best' ){
                $page_datas['lists_row']                = view( '\Module\goods\Views\goods\_addGoodsRows_best' , ['owensView' => $owensView] );
            }
        }

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;


        return $this->respond($response);
    }

    public function allReloadGroupGoods()
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $goodsModel                                 = new GoodsModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $requests, 'i_goods_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '선택된 상품이 없습니다.';

            return $this->respond( $response );
        }

        $aDatas                                     = $goodsModel->getGoodsDataByIdx( _elm( $requests, 'i_goods_idx' ) );
        if( empty( $aDatas ) === true ){
            $response['status']                     = 400;
            $response['alert']                      = '상품 데이터가 없습니다.';

            return $this->respond( $response );
        }

        $idxs                                       = [];
        $group                                      = json_decode( _elm( $aDatas, 'G_GROUP' ), true );
        if( empty( $group ) === false ){
            foreach( $group as $key => $gp ){
                $idxs[]                             = _elm( $gp, 'g_idx' );
            }
        }

        $aLISTS_RESULT                              = $goodsModel->getGoodsListsByIdxsToOdering( $idxs );
        $page_datas                                 = [];

        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );

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
            $view_datas['nowIdx']                   = _elm( $requests, 'i_goods_idx' );
            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\goods\_addGoodsRows_group' , ['owensView' => $owensView] );

        }

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;


        return $this->respond($response);

    }

    public function reloadGroupGoods()
    {
        $response                                   = $this->_initResponse();
        $owensView                                  = new OwensView();
        $goodsModel                                 = new GoodsModel();
        $requests                                   = _trim($this->request->getPost());

        if( empty( _elm( $requests, 'i_goods_idx' ) ) === true ){
            $response['status']                     = 400;
            $response['aler t']                      = '선택된 상품이 없습니다.';

            return $this->respond( $response );
        }

        $idxs                                       = explode( ',', _elm( $requests, 'order' ) );

        $aLISTS_RESULT                              = $goodsModel->getGoodsListsByIdxsToOdering( $idxs );
        $page_datas                                 = [];

        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );

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
            $view_datas['nowIdx']                   = _elm( $requests, 'i_goods_idx' );
            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\goods\_addGoodsRows_group' , ['owensView' => $owensView] );

        }

        $response['status']                         = 200;
        $response['page_datas']                     = $page_datas;


        return $this->respond($response);

    }

    public function deleteGoods()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());
        $goodsModel                                 = new GoodsModel();

        if( empty( _elm( $requests, 'g_idx' ) ) ){
            $response['status']                     = 400;
            $response['alert']                      = '삭제할 상품번호가 없습니다.';

            return $this->respond( $response );
        }
        $aData                                      = $goodsModel->getGoodsDataByIdx( _elm( $requests, 'g_idx' ) );
        $this->db->transBegin();
        $aStatus                                    = $goodsModel->statusToDeleteGoods( _elm( $requests, 'g_idx' ) );

        if ( $this->db->transStatus() === false ) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        #------------------------------------------------------------------
        # TODO: 관리자 로그남기기 S
        #------------------------------------------------------------------
        $logParam                                   = [];
        $logParam['MB_HISTORY_CONTENT']             = '상품 삭제처리 - data:'.json_encode( $aData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) ;
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


    public function getGodoGoods()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());
        $goodsModel                                 = new GoodsModel();
        $owensView                                  = new OwensView();
        $modelParam                                 = [];


        $modelParam['order']                        = ' goodsNo ASC';
        $page                                       = (int)_elm($requests, 'page', 1);

        if (empty($page) === true || $page <= 0 || is_numeric($page) === false)
        {
            $page                                   = 1;
        }
        $per_page                                   = 100;

        if (empty( _elm( $requests, 'per_page' ) ) === false)
        {
            $per_page                               = (int)_elm( $requests, 'per_page' );
        }

        if( empty( _elm( $requests, 'cate' ) ) === false ){
            $modelParam['cate']                     = _elm( $requests, 'cate' );
        }

        $limit                                      = $per_page;
        $start                                      = ($page - 1) * $limit;
        $modelParam['limit']                        = $limit;
        $modelParam['start']                        = $start;



        ###########################################################
        $aLISTS_RESULT                              = $goodsModel->getGodoGoodsLists( $modelParam );



        $lists                                      = _elm( $aLISTS_RESULT, 'lists' );
        if (!empty($lists)) {
            foreach ($lists as $lKey => $data) {
                // 'cateCd' 값을 3자리씩 분리
                $cates = str_split(_elm($data, 'cateCd'), 3);
                if (!empty($cates)) {
                    $result = []; // 결과를 담을 배열
                    $tempString = ''; // 단계적으로 값을 연결할 변수

                    foreach ($cates as $index => $cate) {
                        $tempString .= $cate; // 현재 카테고리를 연결
                        $nCateNm                    = $goodsModel->getGodoCateNm( $tempString );
                        $result[] = '<a href="javascript:$(\'#frm_search [name=cate]\').val(\'' . _elm($nCateNm, 'cateCd') . '\');getSearchList(1)">' . _elm($nCateNm, 'cateNm') . '</a>';


                    }
                    $lists[$lKey]['cates']         = implode(' < ',$result);
                }
            }
        }



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

            $view_datas['openGroup']                = _elm( $requests, 'openGroup' );


            $view_datas['lists']                    = $lists;

            $owensView->setViewDatas( $view_datas );
            $page_datas['lists_row']                = view( '\Module\goods\Views\gdGoods\lists_row' , ['owensView' => $owensView] );

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
            $aLISTS_RESULT['pagination']            = _elm($page_datas, 'pagination' );
            return $aLISTS_RESULT;
        }
        unset($aLISTS_RESULT);

        $response['status']                         = 'true';
        $response['page_datas']                     = $page_datas;

        $response['total_count']                    = $total_count;

        return $this->respond($response);
    }



    public function updateGodoGoods()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = _trim($this->request->getPost());
        $categoryModel                              = new CategoryModel();
        $goodsModel                                 = new GoodsModel();
        $modelParam                                 = [];
        $modelParam['goodsNo']                      = _elm( $requests, 'goodsNo' );
        //Men > 자켓 > 베스트 , Woman > 자켓 > 베스트
        // $textValueWithoutSpaces                     = preg_replace('/\s+/', '', _elm( $requests, 'textValue' ));
        $textValueWithoutSpaces                     = trim( _elm( $requests, 'textValue' ) );
        $textValueArr                               = explode( ',', $textValueWithoutSpaces );

        $lastCategory = []; // 마지막 카테고리 데이터를 저장할 배열
        $modelParam['newCateNm']                    = '';
        $modelParam['newCateCd']                    = '';
        $cateCodeArray                              = []; // 카테고리 코드 저장 배열
        if (!empty( $textValueArr )) {
            $n = 0;
            foreach ($textValueArr as $textValue) {
                $parent_idx = null; // 초기 parent_idx는 null
                $textValues = explode('>', trim($textValue));

                foreach ($textValues as $value) {
                    $cateData = $categoryModel->getCategoryDataByName(trim($value), $parent_idx);

                    if (!empty($cateData)) {
                        // 현재 카테고리의 C_IDX, C_CATE_CODE 저장
                        $lastCategory = [
                            'C_IDX' => $cateData['C_IDX'],
                            'C_CATE_CODE' => $cateData['C_CATE_CODE']
                        ];

                        // 현재 카테고리의 C_CATE_CODE를 cateCodeArray에 추가
                        $cateCodeArray[$n] = $lastCategory['C_CATE_CODE'];

                        // 현재 카테고리의 C_IDX를 다음 루프의 parent_idx로 설정
                        $parent_idx = $cateData['C_IDX'];
                    } else {
                        $response['status']                     = 400;
                        $response['alert']                      = '카테고리 명을 정확히 입력해주세요.';

                        return $this->respond( $response );
                        break;
                    }
                }
                $n++;
            }

            $modelParam['newCateNm']                    = _elm( $requests, 'textValue' );
            $modelParam['newCateCd']                    = implode(',', $cateCodeArray); // 카테고리 코드를 콤마로 조인하여 저장
        }



        // $textValues                                 = explode( '>', $textValueWithoutSpaces );

        // $parent_idx = null; // 초기 parent_idx는 null
        // $lastCategory = []; // 마지막 카테고리 데이터를 저장할 배열
        // $modelParam['newCateNm']                    = '';
        // $modelParam['newCateCd']                    = '';

        // if (!empty( _elm($textValues, 0 ) )) {
        //     foreach ($textValues as $value) {
        //         $cateData = $categoryModel->getCategoryDataByName(trim($value), $parent_idx);

        //         if (!empty($cateData)) {
        //             // 현재 카테고리의 C_IDX, C_CATE_CODE 저장
        //             $lastCategory = [
        //                 'C_IDX' => $cateData['C_IDX'],
        //                 'C_CATE_CODE' => $cateData['C_CATE_CODE']
        //             ];

        //             // 현재 카테고리의 C_IDX를 다음 루프의 parent_idx로 설정
        //             $parent_idx = $cateData['C_IDX'];
        //         } else {
        //             $response['status']                     = 400;
        //             $response['alert']                      = '카테고리 명을 정확히 입력해주세요.';

        //             return $this->respond( $response );
        //             break;
        //         }
        //     }
        //     $modelParam['newCateNm']                    = _elm( $requests, 'textValue' );
        //     $modelParam['newCateCd']                    = _elm( $lastCategory, 'C_CATE_CODE' );
        // }


        $this->db->transBegin();

        $aStatus                                    = $goodsModel->updateGodoGoods( $modelParam );

        if ( $this->db->transStatus() === false || $aStatus === false) {
            $this->db->transRollback();
            $response['status']                     = 400;
            $response['alert']                      = '처리중 오류발생.. 다시 시도해주세요.';
            return $this->respond( $response );
        }

        $this->db->transCommit();


        $response                                   = $this->_unset($response);
        $response['status']                         = 200;
        $response['alert']                          = '';
        return $this->respond( $response );

    }











}