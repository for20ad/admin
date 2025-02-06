<?php
namespace Module\promotion\Controllers;
use Module\core\Controllers\CoreController;

use Module\promotion\Models\PromotionModel;
use Module\promotion\Models\CouponModel;
use Module\membership\Models\MembershipModel;

use Config\Services;
use DOMDocument;

use App\Libraries\MenuLib;
use App\Libraries\MemberLib;

use Exception;

class Promotion extends CoreController
{
    protected $db, $promotionModel, $couponModel, $memberModel;
    protected $memberlib;
    protected $menulib;

    public function __construct()
    {
        parent::__construct();
        $this->memberlib                            = new MemberLib();
        $this->db                                   = \Config\Database::connect();
        $this->promotionModel                       = new PromotionModel();
        $this->couponModel                          = new CouponModel();
        $this->memberModel                          = new MembershipModel();

        $this->memberlib                            = new MemberLib();
        $this->menulib                              = new MenuLib();
    }
}
