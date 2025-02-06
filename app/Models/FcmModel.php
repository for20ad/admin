<?php
namespace App\Models;

use CodeIgniter\Model;

class FcmModel extends Model
{
    protected $fcmDB;
    // public function __construct()
    // {
    //     parent::__construct();

    //     // KakaoDB 로드
    //     $this->db = \Config\Database::connect('kakaoDB');
    // }

    public function __construct()
    {

        $this->fcmDB = \Config\Database::connect('fcmDB', false);
    }

    public function insertFcmMessage( $param = [] )
    {
        $aReturn                                    = [];

        if( empty($param) === true ){
            return $aReturn;
        }

        $builder                                    = $this->fcmDB->table( 'FCM_SEND_INFO' );
        $builder->set( 'F_DI_TOKEN', _elm( $param, 'F_DI_TOKEN' ) );
        $builder->set( 'F_DI_MESSAGE_TITLE', _elm( $param, 'F_DI_MESSAGE_TITLE' ) );
        $builder->set( 'F_DI_MESSAGE_BODY', _elm( $param, 'F_DI_MESSAGE_BODY' ) );
        $builder->set( 'F_SEND_AT', _elm( $param, 'F_SEND_AT' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->fcmDB->insertID();
        }
        return $aReturn;
    }


}

