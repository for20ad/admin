<?php

namespace Module\promotion\Controllers;

use Config\Services;
use DOMDocument;
use Exception;

use Module\promotion\Controllers\Promotion;

class Coupon extends Promotion
{
    public function __construct()
    {
        parent::__construct();
    }

    public function couponLists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(67) === false)
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
            if ($this->menulib->isGrant(67) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }
    }
}