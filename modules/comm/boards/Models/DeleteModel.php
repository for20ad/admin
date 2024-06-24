<?php
namespace Module\comm\boards\Models;

use Config\Services;
use CodeIgniter\Model;

class DeleteModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }
}