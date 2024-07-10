<?php
#------------------------------------------------------------------
# Setting.php
# setting 최상위 컨트롤러
# 김우진
# 2024-07-02 17:23:24
# @Desc :
#------------------------------------------------------------------
namespace Module\setting\Controllers;

use App\Libraries\MemberLib;
use App\Libraries\MenuLib;
use Module\core\Controllers\CoreController;

use Config\Services;
use DOMDocument;

use Module\setting\Config\Config as memberConfig;
use Config\Site;

use CodeIgniter\Debug\Toolbar\Collectors\Views;
use Predis\Command\Argument\Server\To;

class Setting extends CoreController
{
    protected $memberlib;
    protected $menulib;
    protected $mConfig;
    public function __construct()
    {
        parent::__construct();
        $this->memberlib                           = new MemberLib();
        $this->menulib                             = new MenuLib();
        $this->mConfig                             = new memberConfig();

    }
}