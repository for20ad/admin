<?php
#------------------------------------------------------------------
# Boards.php
# 커뮤니티 Boards controller
# 김우진
# 2024-05-21 11:05:14
# @Desc :
#------------------------------------------------------------------
namespace Module\comm\boards\Controllers;
use Module\core\Controllers\ApiController;
use Module\comm\boards\Models\RegisterModel;
use Module\comm\boards\Models\BoardsModel;
use Module\comm\boards\Models\DeleteModel;
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

        helper(['owens','jwt']);
    }

}
