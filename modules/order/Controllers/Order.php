<?php
#------------------------------------------------------------------
# Banner.php
# 배너관리
# 김우진
# 2024-08-08 09:21:45
# @Desc :
#------------------------------------------------------------------
namespace Module\order\Controllers;


use App\Libraries\MemberLib;
use App\Libraries\OwensView;
use App\Libraries\MenuLib;

use Module\board\Models\BoardModel;
use Module\membership\Models\MembershipModel;
use Module\goods\Models\GoodsModel;
use Module\goods\Models\CategoryModel;
use Module\goods\Models\IconsModel;
use Module\goods\Models\BrandModel;
use Module\goods\Models\GoodsAddInfoModel;
use Module\order\Models\OrderModel;

use Config\Services;
use DOMDocument;
use Exception;
use Module\core\Controllers\CoreController;

use App\Libraries\LogisCrawling;
use Shared\Config as SharedConfig;

// curl 라이브러리
use CodeIgniter\HTTP\CURLRequest;

class Order extends CoreController
{
    protected $db, $boardModel, $memberModel, $memberlib;
    protected $goodsModel, $categoryModel, $iconsModel, $brandModel, $goodsAddInfoModel;
    protected $sharedConfig;
    protected $orderModel;
    protected $menulib;
    public function __construct()
    {
        parent::__construct();
        $this->db                                   = \Config\Database::connect();
        $this->memberModel                          = new MembershipModel();
        $this->boardModel                           = new BoardModel();
        $this->memberlib                            = new MemberLib();

        $this->goodsModel                           = new GoodsModel();
        $this->categoryModel                        = new CategoryModel();
        $this->iconsModel                           = new IconsModel();
        $this->brandModel                           = new BrandModel();
        $this->goodsAddInfoModel                    = new GoodsAddInfoModel();
        $this->orderModel                           = new OrderModel();

        $this->menulib                              = new MenuLib();
        $this->sharedConfig                        = new SharedConfig();
    }
    public function testLogis()
    {
        $logis                                      = new LogisCrawling();

        $aData = $logis->logenLogis( '91175140346' );
        echo "<pre>";
        print_r( $aData );
        echo "</pre>";

        $keys                                       = [
            'date',           // 날짜
            'businessSite',   // 사업장
            'deliveryStatus', // 배송상태
            'deliveryDetails',// 배송내용
            'responsibleStaff', // 담당직원
            'receiver',       // 인수자
            'branchOffice',   // 영업소
            'branchContact',  // 영업소연락처
        ];
        $trackingInfo                               = [];

        if( empty( $aData ) === false  ){
            foreach ($aData as $lists) {
                $mappedData = [];
                foreach ($keys as $index => $key) {
                    $mappedData[$key]               = $lists[$index] ?? '';
                }
                $trackingInfo[]                     = $mappedData;
            }
        }

        $modelParam['O_IDX']                        = _elm( $requests, 'ordIdx' );
        $modelParam['O_SHIP_TRACKING']              = json_encode( $trackingInfo ,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

        print_r( $modelParam );

        $this->orderModel->updateShipTracking( $modelParam );
    }


}