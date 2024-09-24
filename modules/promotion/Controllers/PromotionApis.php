<?php

namespace Module\promotion\Controllers\apis;

use Module\core\Controllers\ApiController;

use Module\promotion\Models\PromotionModel;
use Module\promotion\Models\CouponModel;


use Module\promotion\Config\Config as promotionConfig;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;


use Exception;

class PromotionApis extends ApiController
{
    protected $db, $promotionModel, $couponModel;

    public function __construct()
    {
        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->promotionModel                       = new PromotionModel();
        $this->couponModel                          = new CouponModel();
    }
}