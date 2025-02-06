<?php
#------------------------------------------------------------------
# Board.php
# 게시판 top 컨트롤러
# 김우진
# 2024-11-05 10:21:11
# @Desc :
#------------------------------------------------------------------

namespace Module\board\Controllers;
use Module\core\Controllers\CoreController;
use Module\board\Models\BoardModel;
use Module\membership\Models\MembershipModel;

use App\Libraries\MemberLib;
use App\Libraries\OwensView;
use App\Libraries\MenuLib;

use Config\Services;
use DOMDocument;


use Exception;

class Boards extends CoreController
{
    protected $db, $boardModel, $memberModel, $memberlib;
    protected $menulib;
    public function __construct()
    {
        parent::__construct();
        $this->db                                  = \Config\Database::connect();
        $this->memberModel                          = new MembershipModel();
        $this->boardModel                           = new BoardModel();
        $this->memberlib                            = new MemberLib();

        $this->menulib                              = new MenuLib();
    }


}