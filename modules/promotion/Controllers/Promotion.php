<?php
namespace Module\promotion\Controllers;
use Module\core\Controllers\CoreController;

use Module\promotion\Models\PromotionModel;
use Module\promotion\Models\CouponModel;

use Config\Services;
use DOMDocument;



use Exception;

class Promotion extends CoreController
{
    protected $db, $promotionModel, $couponModel;
    protected $memberlib;
    protected $menulib;

    public function __construct()
    {
        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->promotionModel                       = new PromotionModel();
        $this->couponModel                          = new CouponModel();
        $this->memberlib                            = new MemberLib();
        $this->menulib                              = new MenuLib();
    }
}
