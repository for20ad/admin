<?php
#------------------------------------------------------------------
# RegisterModel.php
# comm/ 게시글 등록 관련 컨트롤러
# 김우진
# 2024-06-10 10:54:00
# @Desc :
#------------------------------------------------------------------

namespace Module\comm\boards\Models;

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
}