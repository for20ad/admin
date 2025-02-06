<?php
#------------------------------------------------------------------
# Detail.php
# 주문 상세
# 김우진
# 2025-01-14 09:29:34
# @Desc :
#------------------------------------------------------------------
namespace Module\order\Controllers;
use Module\order\Controllers\Order;

class Detail extends Order
{
    public function __construct()
    {
        parent::__construct();
    }


    function orderDetail( $orderNo )
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(77) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 최고관리자 권한체크
        #------------------------------------------------------------------
        // 권한 체크
        if ($this->memberlib->isSuperAdmin() === false)
        {
            if ($this->menulib->isGrant(77) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        if( empty( $orderNo ) === true ){
            box_alert_back( '잘못된 접근입니다.' );
            exit;
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------


        $pageDatas                                  = [];
        #------------------------------------------------------------------
        # TODO: 주문정보 로드
        #------------------------------------------------------------------
        $orderOriginData                            = $this->orderModel->getOrderDetail( $orderNo );

        if( empty( $orderOriginData ) === true ){
            box_alert_back( '주문정보가 없습니다.' );
            exit;
        }


        $pageDatas['orderTopInfo']                  = [
            'odrId'                                 => _elm( $orderOriginData, 'O_ORDID' ),
            'odrInitAt'                             => _elm( $orderOriginData, 'O_ORDER_AT' ),
            'odrInitDevice'                         => _elm( $orderOriginData, 'O_DEVICE' ),
        ];

        $userInfo                                   = $this->memberModel->getMembershipDataByIdx( _elm( $orderOriginData, 'O_MB_IDX' ) );
        print_R( $userInfo );

        if( empty( $userInfo ) === false){
            $pageDatas['userInfo']                  = [
                'MB_NM'                             => _elm( $userInfo, 'MB_NM' ),
                'MB_NICK_NM'                        => _elm( $userInfo, 'MB_NICK_NM' ),
                'MB_USER_ID'                        => _elm( $userInfo, 'MB_USERID' ),
                'R_CHANNEL'                         => _elm( $userInfo, 'MB_R_LOGIN_CHANNEL' ),
            ];

        }


        $orderDatas                                 = $this->orderModel->getOrderDataByOrdId( _elm( $orderOriginData, 'O_ORDID' ) );

        if( empty( $orderDatas ) === true ){
            box_alert_back( '주문정보가 없습니다.' );
            exit;
        }

        $orderGroup                                 = [
            'shipping'                              => [
                'ShippingWaiting',
                'ShippingProgress',
                'ShippingComplete',
                'ExchangeRequestRetract',
            ],
            'cancels'                               => [
                'ShippingCancelRequest',           //'배송 결제 취소 예정',
                'ShippingCancelComplete',          //'배송 결제 취소 완료',
                'ShippingRefundRequest',           //'배송 환불 요청 대기',
                'ShippingRefundWaiting',           //'배송 환불 예정',
                'ShippingRefundFailed',            //'배송 환불 재요청 대기',
                'ShippingRefundComplete',          //'배송 환불 완료',
                'ShippingCancelRequestBuyer',      //'구매자 배송 취소 요청',
                'ShippingCancelRequestSeller',     //'판매자 배송 취소 요청',
                'ShippingCancelRejected',          //'취소 불가(기발송)',
                'ShippingCancelRequestRetract',    //'취소 요청 철회',
            ],
            'exchnages'                             => [
                'ExchangeRequest',                 //'교환 요청',
                'ExchangePickupRequest',           //'교환 수거 요청',
                'ExchangeApproved',                //'교환 승인',
                'ExchangePending',                 //'교환 보류',
                'ExchangeReturning',               //'교환 반송 중',
                'ExchangeReturnComplete',          //'교환 반송 완료',
                'ExchangeShippingProgress',        //'교환 재배송 중',
                'ExchangeShippingComplete',        //'교환 완료',
                'ExchangeRejected',                //'교환 불가',
            ],
            'returns'                               => [
                'ReturnRequest',                   //'반품 요청',
                'ReturnPickupRequest',             //'반품 수거 요청',
                'ReturnApproved',                  //'반품 승인',
                'ReturnPending',                   //'반품 보류',
                'ReturnShippingProgress',          //'반품 반송 중',
                'ReturnShippingComplete',          //'반품 반송 완료',
                'ReturnCancelRequest',             //'반품 결제 취소 예정',
                'ReturnCancelComplete',            //'반품 결제 취소 완료',
                'ReturnRefundRequest',             //'반품 환불 요청 대기',
                'ReturnRefundWaiting',             //'반품 환불 예정',
                'ReturnRefundFailed',              //'환불 재요청 대기',
                'ReturnRefundComplete',            //'반품 환불 완료',
                'ReturnRejected',                  //'반품 불가',
            ],
        ];

        $userOrder                                 = [];
        foreach( $orderDatas as $oKey => $oData ){
            $status = _elm($oData, 'O_STATUS');
            $userOrder['allStatus'] = $oData;

            // $orderGroup에서 $status가 포함된 그룹 찾기
            foreach ($orderGroup as $groupKey => $statuses) {
                if (in_array($status, $statuses)) {
                    // 해당 그룹 키에 데이터 추가
                    if (!isset($userOrder[$groupKey])) {
                        $userOrder[$groupKey] = [];
                    }
                    $userOrder[$groupKey][] = $oData;
                    break;
                }
            }

        }

        print_r( $userOrder );
        $pageDatas['orderCnt']                      = count( _elm( $userOrder, 'allStatus', [] ) );
        $pageDatas['deleveryCnt']                   = count( _elm( $userOrder, 'shipping', [] ) );
        $pageDatas['cancelCnt']                     = count( _elm( $userOrder, 'cancels', [] ) );
        $pageDatas['exchnageCnt']                   = count( _elm( $userOrder, 'exchnages', [] ) );
        $pageDatas['returnCnt']                     = count( _elm( $userOrder, 'returns', [] ) );

        $requests                                   = $this->request->getGet();
        $pageDatas['aOrderConf']                    = $this->sharedConfig::$orderStatus;
        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];

        $pageParam['file']                          = '\Module\order\Views\order\detail';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;

        $this->owensView->loadLayoutView($pageParam);
    }


}