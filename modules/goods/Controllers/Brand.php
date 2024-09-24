<?php
#------------------------------------------------------------------
# Brand.php
# 브랜드 관리
# 김우진
# 2024-08-16 14:59:00
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers;

use Config\Services;
use DOMDocument;
use Exception;
use Module\goods\Controllers\Goods;


class Brand extends Goods
{
    public function index()
    {
        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\core\Views\errors\error_404';
        $pageParam['pageLayout']                    = 'blank';

        $this->owensView->loadLayoutView($pageParam);
    }

    public function lists()
    {
        #------------------------------------------------------------------
        # TODO: 일반 관리자 권한 체크
        #------------------------------------------------------------------
        if ($this->memberlib->isLogin() === true)
        {
            if ($this->menulib->isGrant(56) === false)
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
            if ($this->menulib->isGrant(56) === false)
            {
                $this->response->redirect(_link_url('/main'));
            }
        }

        #------------------------------------------------------------------
        # TODO: 기본 페이지 변수 세팅
        #------------------------------------------------------------------

        $pageDatas                                  = [];
        $requests                                   = $this->request->getGet();

        #------------------------------------------------------------------
        # TODO: Config 세팅
        #------------------------------------------------------------------


        #------------------------------------------------------------------
        # TODO: 그룹 로드
        #------------------------------------------------------------------
        $modelParam                                 = [];
        $modelParam['order']                        = ' C_SORT ASC';
        $pageDatas['aDatas']                        = $this->brandModel->getBrandLists( $modelParam );

        $brand_lists                                = _elm( _elm( $pageDatas, 'aDatas'), 'lists' );

        $brand                                      = [];

        #------------------------------------------------------------------
        # TODO: 메뉴 트리 적용
        #------------------------------------------------------------------
        if( empty( $brand_lists ) === false  ){
            #------------------------------------------------------------------
            # TODO: 트리형식으로 리스트 변경
            #------------------------------------------------------------------

            $pageDatas['brand_tree_lists']          = _build_tree( $brand_lists, _elm($brand_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

            foreach (_elm($pageDatas, 'brand_tree_lists', []) as $kIDX => $vBRAND)
            {
                $brand[_elm($vBRAND, 'C_IDX')]        = _elm($vBRAND, 'C_BRAND_NAME');

                if (empty($vBRAND['CHILD']) === false)
                {
                    foreach (_elm($vBRAND, 'CHILD', []) as $kIDX_CHILD => $vBRAND_CHILD)
                    {
                        $brand[_elm($vBRAND_CHILD, 'C_IDX')] = '   >>>' ._elm($vBRAND_CHILD, 'C_BRAND_NAME');
                    }
                }
            }
        }

        $pageDatas['aDatas']                        = $brand;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\brand\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }

}