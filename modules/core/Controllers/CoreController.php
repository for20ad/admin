<?php
namespace Module\core\Controllers;

use App\Controllers\BaseController;
use Config\Services;
use Config\View;
use App\Libraries\OwensView;
use App\Libraries\OwensCache;
//use Module\member\Libraries\Member;

class CoreController extends BaseController
{
    protected $_encryption_key = 'ZIeAzykfMrqDKwtKTjk573t0ovtQS1Xn'; // 256bit
    protected $helpers = ['owens', 'owens_url', 'owens_convert',  'sweet_alert', 'form', 'html', 'text', 'url'];
    public $session = [];
    public $uri;
    public $owensView;
    public $owensCache;
    public function __construct()
    {
        $this->response                        = Services::response();
        $this->request                         = Services::request();
        $this->session                         = Services::session();
        $this->uri                             = Services::uri();

        helper($this->helpers);

        $this->loadLibrary();
        $this->setHeaderScriptVar();


        // ---------------------------------------------------------------------
        // 로그인 확인
        // ---------------------------------------------------------------------
        // if (strpos($this->_getSegment(), 'member') === false)
        // {
        //     if ($this->member->isLogin() === false)
        //     {
        //         $this->response->redirect('/member/logout');
        //     }
        // }
    }

    private function loadLibrary()
    {
        $config = new View();

        $this->owensView                        = new OwensView($config);
        $this->owensView->setSiteLayout('\Module\core\Views\layouts\default');

        $this->owensCache                       = new OwensCache();

        //$this->member = new Member();
    }

    private function setHeaderScriptVar()
    {
        $this->owensView->setHeaderScriptVar('site_time', time());
        $this->owensView->setHeaderScriptVar('site_datetime', date("Y-m-d H:i:s"));
        $this->owensView->setHeaderScriptVar('site_is_login', false);
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
