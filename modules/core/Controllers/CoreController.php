<?php
namespace Module\core\Controllers;

use App\Controllers\BaseController;
use Config\Services;
use Config\View;

use Config\Site as SiteConfig;

use App\Libraries\OwensView;
use App\Libraries\MemberLib;

use App\Libraries\OwensCache;


class CoreController extends BaseController
{
    protected $_encryption_key = 'ZIeAzykfMrqDKwtKTjk573t0ovtQS1Xn'; // 256bit
    protected $helpers = ['owens', 'owens_url', 'owens_convert',  'sweet_alert', 'form', 'html', 'text', 'url'];
    public $session = [];
    protected $member;
    public $uri;
    protected $owensView;
    protected $owensCache;
    public function __construct()
    {
        $this->response                        = Services::response();
        $this->request                         = Services::request();
        $this->session                         = Services::session();
        $this->uri                             = Services::uri();
        $this->member                          = new MemberLib();

        helper($this->helpers);

        $this->loadLibrary();
        $this->setHeaderScriptVar();


        //print_R( $this->session->get() );
        #------------------------------------------------------------------
        # TODO: 로그인 확인
        #------------------------------------------------------------------
        if (strpos($this->_getSegment(), '/login') === false && strpos($this->_getSegment(), 'logOut') === false && strpos($this->_getSegment(), 'test') === false)
        {
            if ($this->member->isLogin() === false)
            {
                $this->response->redirect('/login');
            }
        }
    }

    protected function _initResponse()
    {
        $response                                  = [];
        $response['status']                        = false;
        $response['messages']                      = [];
        $response['alert']                         = '';
        $response['redirect_url']                  = '';
        $response['replace_url']                   = '';
        $response['goback']                        = false;
        $response['reload']                        = false;
        $response['time']                          = time();

        return $response;
    }

    private function loadLibrary()
    {
        $config = new View();
        $this->owensCache                       = new OwensCache();
        $this->owensView                        = new OwensView($config);
        $this->owensView->setSiteLayout('\Module\core\Views\layouts\default');

        //$this->member = new Member();
    }

    private function setHeaderScriptVar()
    {
        $this->owensView->setHeaderScriptVar('site_time', time());
        $this->owensView->setHeaderScriptVar('site_datetime', date("Y-m-d H:i:s"));
        $this->owensView->setHeaderScriptVar('site_is_admin_login', $this->member->isAdminLogin());

        $csrfToken = service('security')->getCSRFHash();
        $this->owensView->setHeaderScriptVar('site_csrf_hash', $csrfToken);

        $admin_login_time = $this->session->get('TEMP_ADMIN_LOGIN_TIME');
        $site_config = new SiteConfig();

        if (empty($admin_login_time) == false)
        {

            $admin_expire_time = (int)$site_config->adminExpireTime ?? 7200;

            $admin_auth_time = (int)$this->session->get('TEMP_ADMIN_LOGIN_TIME') + $admin_expire_time - time();

            $this->owensView->setHeaderScriptVar('admin_auth_time', $admin_auth_time);
            $this->owensView->setViewData('admin_auth_time', $admin_auth_time + 1);
        }

    }

    protected function _getSegment($offset = 0)
    {
        if ($offset == 0)
        {
            return $this->uri->getPath();
        }

        return $this->uri->getSegment($offset);
    }

    // encrypt
    protected function _aesEncrypt($string = '')
    {
        if (empty($string) === true)
        {
            return $string;
        }

        $key                                    = $this->_encryption_key;

        if (empty($key) === true)
        {
            return $string;
        }

        return '[' . _encrypt($string, $key, '', 'AES-256-ECB');
    }

    // decrypt
    protected function _aesDecrypt($string = '')
    {
        if (empty($string) === true)
        {
            return $string;
        }

        $key                                    = $this->_encryption_key;

        if (empty($key) === true)
        {
            return $string;
        }

        return _decrypt(substr($string, 1), $key, '', 'AES-256-ECB');
    }


}
