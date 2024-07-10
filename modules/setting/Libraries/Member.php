<?php
namespace Module\setting\Libraries;

use Config\Services;

class Member
{
    protected $helpers = ['archie'];
    protected $memberInfo = [];
    protected $response;
    protected $request;
    protected $session;
    protected $uri;

    public function __construct()
    {
        $this->response                             = Services::response();
        $this->request                              = Services::request();
        $this->session                              = Services::session();
        $this->uri                                  = Services::uri();

        // fix : delayed call helper
        helper($this->helpers);

        $this->memberInfo                           = $this->session->get('_memberInfo') ?? [];
    }

    public function isLogin()
    {
        if (empty($this->memberInfo) === true)
        {
            return false;
        }

        return true;
    }

    public function checkLevel($level = 0)
    {
        if ($this->isLogin() === false ||_elm($this->memberInfo, 'member_level') < $level)
        {
            return false;
        }

        return true;
    }
}
