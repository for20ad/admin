<?php
namespace Module\membership\Controllers;
use Module\core\Controllers\CoreController;
use Module\membership\Models\MembershipModel;

use Module\membership\Config\Config as membershipConfig;

use App\Libraries\MenuLib;
use App\Libraries\MemberLib;
use Config\Services;
use DOMDocument;


use Exception;

class Membership extends CoreController
{
    protected $membershipModel;
    protected $menulib;
    protected $memberlib;
    protected $aConfig;

    public function __construct()
    {
        parent::__construct();

        $this->memberlib                            = new MemberLib();
        $this->menulib                              = new MenuLib();
        $this->aConfig                              = new membershipConfig();
        $this->membershipModel                      = new MembershipModel();
    }

}
