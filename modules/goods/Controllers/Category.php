<?php
#------------------------------------------------------------------
# Icons.php
# 상품 아이콘 관리
# 김우진
# 2024-07-24 11:11:43
# @Desc :
#------------------------------------------------------------------
namespace Module\goods\Controllers;

use Config\Services;
use DOMDocument;
use Exception;
use Module\goods\Controllers\Goods;


class Category extends Goods
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
            if ($this->menulib->isGrant(47) === false)
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
            if ($this->menulib->isGrant(47) === false)
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
        $pageDatas['aDatas']                        = $this->categoryModel->getCategoryLists( $modelParam );

        $cate_lists                                 = _elm( _elm( $pageDatas, 'aDatas'), 'lists' );

        $cate                                       = [];

        $keyword_group                              = $this->categoryModel->getQuestionKeywords();


        #------------------------------------------------------------------
        # TODO: 메뉴 트리 적용
        #------------------------------------------------------------------
        if( empty( $cate_lists ) === false  ){
            #------------------------------------------------------------------
            # TODO: 트리형식으로 리스트 변경
            #------------------------------------------------------------------

            $pageDatas['cate_tree_lists']           = _build_tree( $cate_lists, _elm($cate_lists, 'C_PARENT_IDX', 0 , true), 'C_IDX', 'C_PARENT_IDX'  );

            foreach (_elm($pageDatas, 'cate_tree_lists', []) as $kIDX => $vCATE)
            {
                $cate[_elm($vCATE, 'C_IDX')]        = _elm($vCATE, 'C_CATE_NAME');

                if (empty($vCODE['CHILD']) === false)
                {
                    foreach (_elm($vCATE, 'CHILD', []) as $kIDX_CHILD => $vCATE_CHILD)
                    {
                        $cate[_elm($vCATE_CHILD, 'C_IDX')] = '   >>>' ._elm($vCATE_CHILD, 'C_CATE_NAME');
                    }
                }
            }
        }

        $pageDatas['aDatas']                        = $cate;
        $pageDatas['aKeyword']                      = $keyword_group;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                                  = [];
        $pageParam['file']                          = '\Module\goods\Views\category\lists';
        $pageParam['pageLayout']                    = '';
        $pageParam['pageDatas']                     = $pageDatas;


        $this->owensView->loadLayoutView($pageParam);
    }

}