<?php
namespace Module\core\Models;
use CodeIgniter\Model;

class LogModel extends Model
{

    public function __construct()
    {
        $this->db                                   = \Config\Database::connect();
    }
    public function setRequestApiLog( $param = [] )
    {
        $aReturn                                    = false;

        if (empty($param) === true)
        {
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'RECIVE_API_METHOD' );

        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }

        $aReturn = $builder->insert();

        return $aReturn;
    }
    public function insertUserLog($param = [])
    {
        $aReturn                                    = false;

        if (empty($param) === true)
        {
            return $aReturn;
        }

        if (empty($param['MB_IDX']) === true)
        {
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_HISTORY' );
        $builder->set( 'MB_IDX', _elm($param, 'MB_IDX') );
        $builder->set( 'MB_HISTORY_CONTENT', _elm($param, 'MB_HISTORY_CONTENT') );
        $builder->set( 'MB_HISTORY_DATETIME', date("Y-m-d H:i:s") );
        $builder->set( 'MB_HISTORY_IP', $_SERVER['REMOTE_ADDR'] );


        $aReturn                                    = $builder->insert();

        return $aReturn;
    }

    public function insertAdminLog( $param = [] )
    {
        $aReturn                                    = false;
        if (empty($param) === true)
        {
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ADMIN_MEMBER_HISTORY' );

        $builder->set( 'MB_IDX', _elm($param, 'MB_IDX') );
        $builder->set( 'MB_HISTORY_CONTENT', _elm($param, 'MB_HISTORY_CONTENT') );
        $builder->set( 'MB_HISTORY_DATETIME', date("Y-m-d H:i:s") );
        $builder->set( 'MB_HISTORY_IP', $_SERVER['REMOTE_ADDR'] );


        $aReturn                                    = $builder->insert();
        return $aReturn;

    }

    public function integratUpdate( $_param = [] )
    {
        $aReturn                                    = false;
        if( empty( _elm($_param, 'table') ) === true ){
            return $aReturn;
        }
        if( empty( _elm($_param, 'data') ) === true ){
            return $aReturn;
        }
        if( empty( _elm( $_param, 'where' ) ) ){
            return $aReturn;
        }
        $param                                      = _elm( $_param, 'data' );
        $table                                      = _elm( $_param, 'table' );
        $whereField                                 = _elm( $_param, 'where' );

        $builder                                    = $this->db->table( $table );
        foreach( $param as $key => $val ){
            if( $key == $whereField ){
                $builder->where( $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }

        $aReturn                                    = $builder->update();

        return $aReturn;
    }

    public function integratInsert( $_param = [] )
    {
        $aReturn                                    = false;
        if( empty( _elm($_param, 'table') ) === true ){
            return $aReturn;
        }
        if( empty( _elm($_param, 'data') ) === true ){
            return $aReturn;
        }

        $param                                      = _elm( $_param, 'data' );
        $table                                      = _elm( $_param, 'table' );

        $builder                                    = $this->db->table( $table );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }
}