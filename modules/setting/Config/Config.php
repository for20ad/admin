<?php

namespace Module\setting\Config;
use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public $menu = [];
    public $member = [];
    public $policy = [];

    public function __construct()
    {
        // Initialize menu

        $this->menu['menu_group_id']                = 'manage';
        $this->menu['forms']['membership']          = '회원목록';
        $this->menu['forms']['goods']               = '상품목록';
        $this->menu['forms']['order']               = '주문목록';
        $this->menu['forms']['delivery_company']    = '배송업체';
        $this->menu['forms']['member_mileage']      = '회원포인트';


        $this->menu['status']                       = [];
        $this->menu['status'][1]                    = '노출';
        $this->menu['status'][0]                    = '비노출';

        $this->menu['sort']                         = array_combine(range(1, 99), range(1, 99));

        $this->menu['target']                       = [];
        $this->menu['target']['_self']              = '_self';
        $this->menu['target']['_blank']             = '_blank';

        $this->member['status']                     = [];
        $this->member['status'][1]                  = '정상';
        $this->member['status'][0]                  = '대기';
        $this->member['status'][9]                  = '삭제';

        $this->policy['member']['accept']           = [];
        $this->policy['member']['accept']['CHECK']  = '관리자 승인 후 가입';
        $this->policy['member']['accept']['AUTO']   = '관리자 승인없이 자동가입';

        $this->policy['member']['grade']            = [];
        $this->policy['member']['grade']['Y']       = '사용함';
        $this->policy['member']['grade']['N']       = '사용안함';

        $this->policy['member']['password_change_period']          = [];
        $this->policy['member']['password_change_period'][1]       = '1개월';
        $this->policy['member']['password_change_period'][3]       = '3개월';
        $this->policy['member']['password_change_period'][6]       = '6개월';
        $this->policy['member']['password_change_period'][12]      = '12개월';
        $this->policy['member']['password_change_period'][16]      = '16개월';
        $this->policy['member']['password_change_period'][20]      = '20개월';
        $this->policy['member']['password_change_period'][24]      = '2년';
        $this->policy['member']['password_change_period'][36]      = '3년';
        $this->policy['member']['password_change_period'][48]      = '4년';
        $this->policy['member']['password_change_period'][60]      = '5년';

        $this->policy['purchase']['pay_default']                   = [];
        $this->policy['purchase']['pay_default']['card']           = '신용카드';
        $this->policy['purchase']['pay_default']['realtimebank']   = '실시간계좌이체';
        $this->policy['purchase']['pay_default']['bank']           = '무통장입금(가상계좌)';
        $this->policy['purchase']['pay_default']['mobile']         = '휴대폰결제';

        $this->policy['purchase']['pay_simple']                    = [];
        $this->policy['purchase']['pay_simple']['kakao']           = '카카오페이';
        $this->policy['purchase']['pay_simple']['naver']           = '네이버페이';
        $this->policy['purchase']['pay_simple']['payco']           = '페이코';

        $this->policy['purchase']['delivery_end_days']             = [];
        $this->policy['purchase']['delivery_end_days'][3]          = '3';
        $this->policy['purchase']['delivery_end_days'][5]          = '5';
        $this->policy['purchase']['delivery_end_days'][10]         = '10';
        $this->policy['purchase']['delivery_end_days'][15]         = '15';

        $this->policy['purchase']['purchase_conf_days']            = [];
        $this->policy['purchase']['purchase_conf_days'][3]         = '3';
        $this->policy['purchase']['purchase_conf_days'][5]         = '5';
        $this->policy['purchase']['purchase_conf_days'][10]        = '10';
        $this->policy['purchase']['purchase_conf_days'][15]        = '15';
        $this->policy['purchase']['purchase_conf_days'][20]        = '20';
        $this->policy['purchase']['purchase_conf_days'][25]        = '25';
        $this->policy['purchase']['purchase_conf_days'][20]        = '30';

        $this->policy['purchase']['defaultYn']                     = [];
        $this->policy['purchase']['defaultYn']['Y']                = '사용함';
        $this->policy['purchase']['defaultYn']['N']                = '사용안함';

        $this->policy['cart']                                      = [];
        $this->policy['cart']['days']                              = [];
        $this->policy['cart']['days'][1]                           = '1';
        $this->policy['cart']['days'][3]                           = '3';
        $this->policy['cart']['days'][5]                           = '5';
        $this->policy['cart']['days'][10]                          = '10';
        $this->policy['cart']['days'][15]                          = '15';
        $this->policy['cart']['days'][20]                          = '20';
        $this->policy['cart']['days'][30]                          = '30';

        $this->policy['cart']['defaultYn']                         = [];
        $this->policy['cart']['defaultYn']['Y']                    = '사용함';
        $this->policy['cart']['defaultYn']['N']                    = '사용안함';

        $this->policy['cart']['defaultChoice']                     = [];
        $this->policy['cart']['defaultChoice']['Y']                = '';
        $this->policy['cart']['defaultChoice']['N']                = '고객이 삭제할 때 까지';

        $this->policy['cart']['limitChoice']                       = [];
        $this->policy['cart']['limitChoice']['Y']                  = '';
        $this->policy['cart']['limitChoice']['N']                  = '제한없음';

        $this->policy['membership']                                = [];

    }
}
