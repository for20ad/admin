<?php
namespace Module\admin\boards\Models;

use Config\Services;
use CodeIgniter\Model;

class DeleteModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }

    public function deleteBoard( $param = [] )
    {
        $aReturn = false;
        if( empty( _elm( $param, 'B_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD' );
        $builder->set( 'B_STATUS', 3 );
        $builder->where( 'B_IDX', _elm( $param, 'B_IDX' ) );

        $aReturn = $builder->update();
        return $aReturn;
    }

    public function deletePosts( $param = [] )
    {
        $aReturn = false;
        if( empty( _elm( $param, 'P_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_POSTS' );
        $builder->set( 'P_STATUS', 3 );
        $builder->where( 'P_IDX', _elm( $param, 'P_IDX' ) );

        $aReturn = $builder->update();
        return $aReturn;

    }

    public function deleteFile( $param = [] )
    {
        $aReturn = false;
        if( empty( _elm( $param, 'F_P_IDX' ) ) === true ){
            return $aReturn;
        }
        if( empty( _elm( $param, 'F_SORT' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_P_IDX', _elm( $param, 'F_P_IDX' ) );
        $builder->where( 'F_SORT', _elm( $param, 'F_SORT' ) );

        $aReturn = $builder->delete();

        return $aReturn;
    }

    public function deleteFileByPath( $param = [] )
    {
        $aReturn = false;
        if( empty( _elm( $param, 'F_PATH' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_PATH', _elm( $param, 'F_PATH' ) );

        $aReturn = $builder->delete();

        return $aReturn;
    }

}