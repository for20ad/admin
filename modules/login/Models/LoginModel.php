<?php
namespace Module\login\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    public function updateLoginCnt( $param = [] )
    {
        $aReturn          = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table('ADMIN_MEMBER');
        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );
        if( empty( _elm( $param, 'MB_LOGIN_STATUS') ) === false ){
            $builder->set('MB_LOGIN_STATUS', _elm( $param, 'MB_LOGIN_STATUS') );
        }
        $builder->set('MB_PASS_ERR_COUNT', _elm( $param, 'MB_PASS_ERR_COUNT') );

        $aReturn = $builder->update();

        return $aReturn;
    }
    public function updateLoginDate( $param = [] )
    {
        $aReturn          = [];

        $builder = $this->db->table('ADMIN_MEMBER');
        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );
        $builder->set( 'MB_LOGIN_COUNT', 'IFNULL(MB_LOGIN_COUNT, 0) + 1', false );
        $builder->set('MB_LAST_LOGIN_AT', _elm( $param, 'MB_LAST_LOGIN_AT') );
        $builder->set('MB_LAST_LOGIN_IP', _elm( $param, 'MB_LAST_LOGIN_IP') );

        $aReturn = $builder->update();

        return $aReturn;
    }

    public function setLoginLog( $param = [] ){

    }

    function getUserData( $param = [] )
    {
        $aReturn          = [];

        $builder = $this->db->table('ADMIN_MEMBER AD');
        $builder->select('AD.*');
        $builder->select( 'GP.MB_GROUP_IDX' );
        $builder->join( 'ADMIN_MEMBER_GROUP_MEMBER GP', 'AD.MB_IDX = GP.MB_IDX', 'left' );
        $builder->where( 'MB_STATUS' , '1' );
        if( empty( _elm($param, 'MB_USERID') ) === false ){
            $builder->where( 'AD.MB_USERID', _elm($param, 'MB_USERID') );
        }
        if( empty( _elm($param, 'MB_PASSWORD') ) === false ){
            $builder->where( 'AD.MB_PASSWORD' , _elm($param, 'MB_UPW'), false );
        }

        if( empty( _elm($param, 'MB_IDX') ) === false ){
            $builder->where( 'AD.MB_IDX', _elm($param, 'MB_IDX') );
        }

        $builder->limit( '1' );
        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;
    }
}