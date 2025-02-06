<?php
namespace Module\promotion\Models;

use Config\Services;
use CodeIgniter\Model;

class PromotionModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

}