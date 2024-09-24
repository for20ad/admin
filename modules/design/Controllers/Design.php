<?php
namespace Module\design\Controllers;
use Module\core\Controllers\CoreController;

use Module\design\Config\Config as designConfig;

use App\Libraries\MenuLib;
use App\Libraries\MemberLib;

use Module\design\Models\LogoModel;
use Module\design\Models\BannerModel;
use Module\design\Models\PopupModel;
use Config\Services;
use DOMDocument;


use Exception;

class Design extends CoreController
{
    protected $logoModel, $bannerModel, $popupModel;
    protected $designConfig;
    protected $memberlib;
    protected $menulib;
    public function __construct()
    {
        parent::__construct();

        $this->logoModel                            = new LogoModel();
        $this->bannerModel                          = new BannerModel();
        $this->popupModel                           = new PopupModel();

        $this->memberlib                            = new MemberLib();
        $this->menulib                              = new MenuLib();

        $this->designConfig                         = new designConfig();
    }


}
