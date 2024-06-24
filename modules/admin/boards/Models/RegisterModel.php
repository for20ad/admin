<?php
namespace Module\admin\boards\Models;

use Config\Services;
use CodeIgniter\Model;

class RegisterModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function addPostsHit( $posts_idx )
    {
        $aReturn = false;
        if( empty( $posts_idx ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_POSTS' );
        $builder->set( 'P_HITS', 'P_HITS+1', false);
        $builder->where( 'P_IDX', $posts_idx );

        $builder->update();

    }
    public function setPostsLog( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_POSTS_LOG' );
        foreach( $param as $key => $value ){
            $builder->set( $key, $value );
        }
        $builder->insert();

    }

    public function setComment( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_COMMENTS' );
        foreach( $param as $key => $value ){
            if( $key == "C_IDX" ){
                $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );
            }else{
                $builder->set( $key, $value );
            }
        }

        #------------------------------------------------------------------
        # TODO: INSERT
        #------------------------------------------------------------------
        if( empty( _elm( $param, 'C_IDX' ) ) === true ){
            $aResult = $builder->insert();
            if( $aResult ){
                $aReturn = $this->db->insertID();
            }
        }
        #------------------------------------------------------------------
        # TODO: UPDATE
        #------------------------------------------------------------------
        else{
            $aReturn = $builder->update();
        }
        return $aReturn;

    }

    public function modifyPosts( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_POSTS' );
        foreach( $param as $key => $value ){
            if( $key == 'P_IDX' ){
                $builder->where( $key, $value );
            }else{
                $builder->set( $key, $value );
            }

        }

        $aReturn = $builder->update();

        return $aReturn;

    }

    public function registerPosts( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_POSTS' );
        foreach( $param as $key => $value ){
            $builder->set( $key, $value );
        }

        $aResult = $builder->insert();
        if( $aResult ){
            $aReturn = $this->db->insertID();
        }
        return $aReturn;

    }

    public function setFileDatas( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_FILES' );
        foreach( $param as $key => $value ){
            $builder->set( $key, $value );
        }

        $aReturn = $builder->insert();

        return $aReturn;
    }


    public function updateBoardInfo( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD' );

        if( empty( _elm( $param, 'B_GROUP' ) ) === false ){
            $builder->set( 'B_GROUP', _elm( $param, 'B_GROUP' ) );
        }
        if( empty( _elm( $param, 'B_ID' ) ) === false ){
            $builder->set( 'B_ID', _elm( $param, 'B_ID' ) );
        }
        if( empty( _elm( $param, 'B_TITLE' ) ) === false ){
            $builder->set( 'B_TITLE', _elm( $param, 'B_TITLE' ) );
        }
        if( empty( _elm( $param, 'B_STATUS' ) ) === false ){
            $builder->set( 'B_STATUS', _elm( $param, 'B_STATUS' ) );
        }
        if( empty( _elm( $param, 'B_SORT' ) ) === false ){
            $builder->set( 'B_SORT', _elm( $param, 'B_SORT' ) );
        }

        if( empty( _elm( $param, 'B_CATEGORY_CODE' ) ) === false ){
            $builder->set( 'B_CATEGORY_CODE', _elm( $param, 'B_CATEGORY_CODE' ) );
        }

        if( empty( _elm( $param, 'B_IS_FREE' ) ) === false ){
            $builder->set( 'B_IS_FREE', _elm( $param, 'B_IS_FREE' ) );
        }

        if( empty( _elm( $param, 'B_HITS' ) ) === false ){
            $builder->set( 'B_HITS', _elm( $param, 'B_HITS' ) );
        }
        if( empty( _elm( $param, 'B_SECRET' ) ) === false ){
            $builder->set( 'B_SECRET', _elm( $param, 'B_SECRET' ) );
        }
        if( empty( _elm( $param, 'B_PASSWORD' ) ) === false ){
            $builder->set( 'B_PASSWORD', _elm( $param, 'B_PASSWORD' ) );
        }
        if( empty( _elm( $param, 'B_COMMENT' ) ) === false ){
            $builder->set( 'B_COMMENT', _elm( $param, 'B_COMMENT' ) );
        }
        $builder->set( 'B_MODIFY_AT', _elm( $param, 'B_MODIFY_AT' ) );

        $builder->set( 'B_MODIFY_IP', _elm( $param, 'B_MODIFY_IP' ) );

        $builder->set( 'B_MODIFY_MB_IDX', _elm( $param, 'B_MODIFY_MB_IDX' ) );

        $builder->where( 'B_IDX', _elm( $param, 'B_IDX' ) );


        $aReturn = $builder->update();

        return $aReturn;

    }


    public function setBoardInfo( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD' );

        $builder->set( 'B_GROUP', _elm( $param, 'B_GROUP' ) );
        $builder->set( 'B_TITLE', _elm( $param, 'B_TITLE' ) );
        $builder->set( 'B_STATUS', _elm( $param, 'B_STATUS' ) );
        $builder->set( 'B_SORT', _elm( $param, 'B_SORT' ) );
        $builder->set( 'B_CATEGORY_CODE', _elm( $param, 'B_CATEGORY_CODE' ) );
        $builder->set( 'B_TITLE', _elm( $param, 'B_TITLE' ) );
        $builder->set( 'B_HITS', _elm( $param, 'B_HITS' ) );
        $builder->set( 'B_IS_FREE', _elm( $param, 'B_IS_FREE' ) );
        $builder->set( 'B_SECRET', _elm( $param, 'B_SECRET' ) );
        $builder->set( 'B_PASSWORD', _elm( $param, 'B_PASSWORD' ) );
        $builder->set( 'B_COMMENT', _elm( $param, 'B_COMMENT' ) );
        $builder->set( 'B_CREATE_AT', _elm( $param, 'B_CREATE_AT' ) );
        $builder->set( 'B_CREATE_IP', _elm( $param, 'B_CREATE_IP' ) );
        $builder->set( 'B_CREATE_MB_IDX', _elm( $param, 'B_CREATE_MB_IDX' ) );

        $aResult = $builder->insert();

        if( $aResult ){
            $aReturn = $this->db->insertID();
        }
        return $aReturn;
    }



}

