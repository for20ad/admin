<?php
namespace Module\download\Models;

use Config\Services;
use CodeIgniter\Model;

class DownloadModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getFileData( $files = '' )
    {
        $aReturn                            = [];
        if( empty( $files ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_PATH', $files );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;

    }
    public function getBoardConfigByFileInfo( $p_idx = '' )
    {

        $aReturn                            = [];
        if( empty( $p_idx ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_POSTS PS' );
        $builder->select( 'BO.*' );
        $builder->join( 'WJ_BOARD BO', 'PS.P_B_ID = BO.B_ID', 'left' );
        $builder->where( 'PS.P_IDX', $p_idx );
        $builder->where( 'BO.B_STATUS', 1 );


        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;
    }

    public function setLogs( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_FILE_DOWNLOAD_LOGS' );
        $aResult = $builder->insert( $param );
        if( $aResult ){
            $aReturn = $this->db->insertID();
        }

        return $aReturn;
    }

    public function updateDownloadCnt( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->set( 'F_DOWNLOAD_CNT', _elm( $param, 'F_DOWNLOAD_CNT' ) );
        $builder->where( 'F_PATH', _elm( $param, 'F_PATH' ) );

        $aReturn = $builder->update();
        return $aReturn;
    }

}