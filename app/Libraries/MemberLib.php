<?php
namespace App\Libraries;

use CodeIgniter\Session\Session;

use Config\Services;
use Module\setting\Controllers\Member;

class MemberLib
{
    protected $session;

    public function __construct()
    {
        $this->session = Services::session();
    }

    // 로그인 여부
    public function isLogin()
    {
        if (!empty($this->session->get('_memberInfo')))
        {
            if (!empty($this->_elm($this->session->get('_memberInfo'), 'member_idx')))
            {
                return true;
            }
        }

        return false;
    }

    // 최고 관리자 여부
    public function isSuperAdmin()
    {
        if ($this->isAdminLogin() === true)
        {
            if (!empty($this->session->get('_memberInfo')))
            {
                if ($this->_elm($this->session->get('_memberInfo'), 'member_level') == '1')
                {
                    return true;
                }
            }
        }

        return false;
    }

    // 관리자 로그인 여부
    public function isAdminLogin()
    {

        if (!empty($this->session->get('_memberInfo')))
        {
            $admin_login_time = $this->session->getTempdata('TEMP_ADMIN_LOGIN_TIME');

            if (!empty($admin_login_time))
            {
                return true;
            }
        }


        return false;
    }

    // _elm 함수 정의 (필요한 경우)
    private function _elm($array, $key)
    {
        return isset($array[$key]) ? $array[$key] : null;
    }


}
