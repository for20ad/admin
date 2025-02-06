<?php
namespace Module\goods\Controllers;
use Module\core\Controllers\CoreController;

use Config\Services;
use DOMDocument;
use Exception;

use Module\goods\Config\Config as goodsConfig;

use App\Libraries\MenuLib;
use App\Libraries\MemberLib;

use Module\goods\Models\GoodsModel;
use Module\goods\Models\CategoryModel;
use Module\goods\Models\IconsModel;
use Module\goods\Models\BrandModel;
use Module\goods\Models\BundleModel;
use Module\goods\Models\GoodsAddInfoModel;


class Goods extends CoreController
{
    protected $goodsModel, $categoryModel, $iconsModel, $brandModel, $bundleModel, $aInfoModel ;
    protected $goodsConfig;
    protected $memberlib;
    protected $menulib;

    public function __construct()
    {
        parent::__construct();
        $this->goodsModel                           = new GoodsModel();
        $this->categoryModel                        = new CategoryModel();
        $this->iconsModel                           = new IconsModel();
        $this->brandModel                           = new BrandModel();
        $this->bundleModel                          = new BundleModel();
        $this->aInfoModel                           = new GoodsAddInfoModel();

        $this->memberlib                            = new MemberLib();
        $this->menulib                              = new MenuLib();

        $this->goodsConfig                          = new goodsConfig();


    }


}
