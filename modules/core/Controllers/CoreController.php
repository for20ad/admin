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

    protected $_encryption_key                      = 'ZIeAzykfMrqDKwtKTjk573t0ovtQS1Xn'; // 256bit
    protected $helpers                              = ['owens', 'owens_form', 'owens_url', 'owens_convert',  'sweet_alert', 'form', 'html', 'text', 'url'];
    public $session                                 = [];
    protected $member;
    public $uri;
    protected $owensView;
    protected $owensCache;
    public function __construct()
    {
        $this->response                             = Services::response();
        $this->request                              = Services::request();
        $this->session                              = Services::session();
        $this->uri                                  = Services::uri();
        $this->member                               = new MemberLib();

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
        $response                                   = [];
        $response['status']                         = false;
        $response['messages']                       = [];
        $response['alert']                          = '';
        $response['redirect_url']                   = '';
        $response['replace_url']                    = '';
        $response['goback']                         = false;
        $response['reload']                         = false;
        $response['time']                           = time();

        return $response;
    }

    private function loadLibrary()
    {
        $config = new View();
        $this->owensCache                           = new OwensCache();
        $this->owensView                            = new OwensView($config);
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

    if (!empty($admin_login_time))
    {
        $admin_expire_time = (int)$site_config->adminExpireTime ?? 7200;
        $admin_auth_time = $admin_expire_time - (time() - $admin_login_time);

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

        $key                                        = $this->_encryption_key;

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

        $key                                        = $this->_encryption_key;

        if (empty($key) === true)
        {
            return $string;
        }

        return _decrypt(substr($string, 1), $key, '', 'AES-256-ECB');
    }



    /**
     * 페이징 처리
     *
     * @param array $param
     * @return string
     */
    protected function _pagination($param = [])
    {

        $pager                                      = \Config\Services::pager();

        $currentPage                                = $param['cur_page'] ?? 1;
        $perPage                                    = $param['per_page'] ?? 10;
        $totalRows                                  = $param['total_rows'] ?? 0;
        $baseUrl                                    = $param['base_url'] ?? '';

        $totalPages                                 = ceil($totalRows / $perPage);

        $html                                       = '<ul class="pagination align-items-center body2-c">';

        // 이전 페이지 버튼
        if ($currentPage > 1) {
            $prev                                   = _elm( $param, 'ajax' ) === false ? $baseUrl . '?page=' . ($currentPage - 1) : 'javascript:void(0);';
            $html                                  .= '<li class="page-item">
                                                        <a href="' . $prev . '" data-page="'.($currentPage - 1).'" class="page-link">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M10 4L6 8L10 12" stroke="#ADB5BD" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    </li>';
        }

        // 페이지 번호
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                $html                              .= '<li class="page-item active"><a href="javascript:void(0);" class="">' . $i . '</a></li>';
            } else {
                $nums                               = _elm( $param, 'ajax' ) === false ? $baseUrl . '?page=' . ($i) : 'javascript:void(0);';
                $html                              .= '<li class="page-item"><a href="' . $nums.'" data-page="'.$i.'"class="page-link">' . $i . '</a></li>';
            }
        }

        // 다음 페이지 버튼
        if ($currentPage < $totalPages) {
            $next                                   = _elm( $param, 'ajax' ) === false ? $baseUrl . '?page=' . ($currentPage + 1) : 'javascript:void(0);';
            $html                                  .= '<li class="page-item">
                                                        <a href="' . $next .'" data-page="'.( $currentPage + 1 ).'" class="">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 4L10 8L6 12" stroke="#616876" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    </li>';
        }

        $html                                      .= '</ul>';

        // 총 페이지 및 이동 드롭다운
        $html                                      .= '<div class="pagination-goto" style="gap: 8px">';
        $html                                      .= '<p>페이지</p>';
        $html                                      .= '<select class="form-select">';

        for ($i = 1; $i <= $totalPages; $i++) {
            $selected                               = ($i == $currentPage) ? ' selected' : '';
            $html                                  .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
        }

        $html                                      .= '</select>';
        $html                                      .= '<p>총 ' . $totalPages . '</p>';
        $html                                      .= '</div>';

        return $html;
    }


}
