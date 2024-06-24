<?php
#------------------------------------------------------------------
# CodeModel.php
# 코드관리 모델
# 김우진
# 2024-05-24 09:43:54
# @Desc :
#------------------------------------------------------------------
namespace Module\admin\setting\Models;

use Config\Services;
use CodeIgniter\Model;


class CodeModel extends Model
{
    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function deleteCode( $code_idx )
    {
        $aReturn = false;
        if(  empty( $code_idx ) === true){
            return $aReturn;
        }

        $builder = $this->db->table( 'CODE' );
        $builder->where( 'C_IDX', $code_idx );

        $aReturn = $builder->delete();
        return $aReturn;
    }

    public function getCodeLists( $param = [] )
    {
        $aReturn = [];
        $builder = $this->db->table( 'CODE' );
        $builder->select( '*' );
        $builder->where( 'C_USE_YN', 'Y' );

        if( empty( _elm( $param, 'C_IDX' ) ) === false ){
            $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );
            $builder->orWhere( 'C_PARENT_IDX', _elm( $param, 'C_IDX' ) );

        }
        $builder->orderBy( 'C_PARENT_IDX',  'ASC' );
        $builder->orderBy( 'C_SORT',  'ASC' );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getChildCodes( $parentIdx )
    {
        $aReturn = false;
        if( empty( $parentIdx ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'CODE' );
        $builder->where( 'C_PARENT_IDX', $parentIdx );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;
    }


    public function patchCode( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'CODE' );

        foreach( $param as $key => $data )
        {
            if( $key == "C_IDX" ){
                $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );
            }else{
                $builder->set( $key, $data );
            }
        }

        $aReturn = $builder->update();

        return $aReturn;
    }


    public function getCodeInfo( $param = [] )
    {
        $aReturn = false;
        if(  empty( $param ) === true){
            return $aReturn;
        }
        $builder = $this->db->table( 'CODE' );
        $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;
    }

    public function setCodes( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }


        $builder = $this->db->table('CODE');
        foreach( $param as $key => $val )
        {
            $builder->set( $key, $val);
        }

        $aResult = $builder->insert();
        if( $aResult ){
            $aReturn = $this->db->insertID();
        }
        return $aReturn;

    }
    public function getLastCodeSortNum( $param = [] )
    {
        $aReturn = false;
        $builder = $this->db->table( 'CODE' );
        $builder->select( 'C_SORT' );
        $builder->where( 'C_PARENT_IDX', _elm( $param, 'C_PARENT_IDX' ) );
        $builder->where( 'C_USE_YN', 'Y' );
        $builder->orderBy( 'C_SORT',  'DESC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aResult = $query->getRowArray();
            $aReturn = _elm( $aResult, 'C_SORT' );
        }
        return $aReturn;

    }


    public function getCodeOneCnt( $param = [] )
    {
        $aReturn = false;

        if( empty( _elm( $param, 'C_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'CODE' );
        $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );
        $aReturn = $builder->countAllResults(false);

        return $aReturn;
    }

    public function getCodeParentCnt( $param = [] )
    {
        $aReturn = false;

        if( empty( _elm( $param, 'C_PARENT_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'CODE' );
        $builder->where( 'C_PARENT_IDX', _elm( $param, 'C_PARENT_IDX' ) );
        $aReturn = $builder->countAllResults(false);

        return $aReturn;
    }



    public function sameChecked( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'CODE' );
        $builder->select('*');
        $builder->where( 'C_PARENT_IDX', _elm( $param, 'C_PARENT_IDX' ) );
        if( empty( _elm( $param, 'C_NAME' ) ) === false ){
            $builder->where( 'C_NAME', _elm( $param, 'C_NAME' ) );
        }
        if( empty( _elm( $param, 'C_CODE' ) ) === false ){
            $builder->where( 'C_CODE', _elm( $param, 'C_CODE' ) );
        }

        $aReturn = $builder->countAllResults(false);

        return $aReturn;
    }

    public function reSort($parentIdx, $currentSort, $newSort)
    {
        $aReturn = false;
        if (isset($parentIdx) === false || empty($currentSort) || empty($newSort)) {
            return $aReturn;
        }

        $builder = $this->db->table('FRONT_MENU');
        $builder->where('MENU_PARENT_IDX', $parentIdx);

        if ($currentSort < $newSort) {
            // currentSort가 newSort보다 작은 경우
            $builder->where('MENU_SORT >', $currentSort);
            $builder->where('MENU_SORT <=', $newSort);
            $builder->set('MENU_SORT', 'MENU_SORT - 1', false);
        } else {
            // currentSort가 newSort보다 큰 경우
            $builder->where('MENU_SORT <', $currentSort);
            $builder->where('MENU_SORT >=', $newSort);
            $builder->set('MENU_SORT', 'MENU_SORT + 1', false);
        }

        $aReturn = $builder->update();
        return $aReturn;
    }

}