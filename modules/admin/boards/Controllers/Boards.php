<?php
#------------------------------------------------------------------
# Boards.php
# 보드 관련 main 컨트롤러
# 김우진
# 2024-05-20 11:15:53
# @Desc : 보드관련 amin컨트롤러
#------------------------------------------------------------------

namespace Module\admin\boards\Controllers;
use Module\core\Controllers\ApiController;
use Module\admin\boards\Models\RegisterModel;
use Module\admin\boards\Models\BoardsModel;
use Module\admin\boards\Models\DeleteModel;


use Config\Services;
use DOMDocument;


use Exception;

class Boards extends ApiController
{
    protected $db;
    protected $boardsModel;
    protected $registerModel;
    protected $deleteModel;
    public function __construct()
    {

        parent::__construct();
        $this->db                                  = \Config\Database::connect();
        $this->boardsModel                         = new BoardsModel();
        $this->registerModel                       = new RegisterModel();
        $this->deleteModel                         = new DeleteModel();
    }

}
