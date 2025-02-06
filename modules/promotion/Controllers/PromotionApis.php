<?php

namespace Module\promotion\Controllers;

use Module\core\Controllers\ApiController;

use Module\promotion\Models\PromotionModel;
use Module\promotion\Models\CouponModel;
use Module\membership\Models\MembershipModel;

use Module\promotion\Config\Config as promotionConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;


use Exception;

class PromotionApis extends ApiController
{
    protected $db, $promotionModel, $couponModel, $memberModel;
    protected $memberlib;
    protected $menulib;


    public function __construct()
    {
        parent::__construct();
        $this->db                                   = \Config\Database::connect();
        $this->promotionModel                       = new PromotionModel();
        $this->couponModel                          = new CouponModel();
        $this->memberModel                          = new MembershipModel();

        $this->memberlib                            = new MemberLib();


    }
}