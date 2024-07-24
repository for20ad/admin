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

class Goods extends CoreController
{
    protected $goodsModel, $categoryModel, $iconsModel;
    protected $goodsConfig;
    protected $memberlib;
    protected $menulib;

    public function __construct()
    {
        parent::__construct();
        $this->goodsModel                           = new GoodsModel();
        $this->categoryModel                        = new CategoryModel();
        $this->iconsModel                           = new IconsModel();

        $this->memberlib                           = new MemberLib();
        $this->menulib                             = new MenuLib();

        $this->goodsConfig                          = new goodsConfig();

    }


}
