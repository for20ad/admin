<?php
#------------------------------------------------------------------
# MenuLib.php
# 메뉴라이브러리
# 김우진
# 2024-06-28 10:14:07
# @Desc :
#------------------------------------------------------------------
namespace App\Libraries;

use  Module\setting\Models\MenuModel;

class MenuLib
{
    public function getAdminAllMenu()
    {
        $Menu_model                          = new MenuModel();
        $menu_lists                          = $Menu_model->getAdminMenuLists(1);

        $menu_tree_lists                     = _build_tree($menu_lists);

        return $menu_tree_lists;
    }

    public function getAdminGroupMenu($group_id = '')
    {
        $Menu_model                          = new MenuModel();
        $menu_lists                          = $Menu_model->getAdminMenuListsByGroupId($group_id);

        $menu_tree_lists                     = _build_tree($menu_lists);

        return $menu_tree_lists;
    }

    public function isGrant($menu_idx = 0)
    {
        $bReturn                              = false;

        if (empty($menu_idx) === true)
        {
            return $bReturn;
        }


        $session                             = \Config\Services::session();
        $Menu_model                          = new MenuModel();
        $member_info                         = $session->get('_memberInfo');
        $member_group_idx                    = _elm($member_info, 'member_group_idx');
        $member_group_idxs                   = _trim(explode(',', $member_group_idx));


        $menu_info                           = $Menu_model->getAdminMenuListsByIdx($menu_idx);
        $menu_display_member_group           = _elm($menu_info, 'MENU_DISPLAY_MEMBER_GROUP', '');
        $menu_display_member_groups          = _trim(explode(',', _elm($menu_info, 'MENU_DISPLAY_MEMBER_GROUP', '')));


        if (count(array_intersect($member_group_idxs, $menu_display_member_groups)) > 0)
        {
            $bReturn                         = true;
        }

        return $bReturn;
    }

}
