<?php
namespace Module\goods\Models;

use Config\Services;
use CodeIgniter\Model;

class CategoryModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }

    public function updateQustKeyword( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_KEYWOD_GROUP' );

        $builder->where( 'K_IDX',                   _elm( $param, 'K_IDX' ) );
        $builder->set( 'K_STATUS',                  _elm( $param, 'K_STATUS' ) );
        $builder->set( 'K_DELETE_AT',               _elm( $param, 'K_DELETE_AT' ) );
        $builder->set( 'K_DELETE_IP',               _elm( $param, 'K_DELETE_IP' ) );
        $builder->set( 'K_DELETE_MB_IDX',           _elm( $param, 'K_DELETE_MB_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function insertQustKeyword( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_KEYWOD_GROUP' );
        $builder->set( 'K_NAME',                    _elm( $param, 'K_NAME' ) );
        $builder->set( 'K_STATUS',                  _elm( $param, 'K_STATUS' ) );
        $builder->set( 'K_CREATE_AT',               _elm( $param, 'K_CREATE_AT' ) );
        $builder->set( 'K_CREATE_IP',               _elm( $param, 'K_CREATE_IP' ) );
        $builder->set( 'K_CREATE_MB_IDX',           _elm( $param, 'K_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }
    public function sameChkQustKeyword( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_KEYWOD_GROUP' );
        $builder->where( 'K_NAME',                  _elm( $param, 'K_NAME' ) );


        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getQuestionKeywords()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_KEYWOD_GROUP' );

        $builder->where( 'K_STATUS',                1 );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }
    public function updateCagegoryFileData( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_CATEGORY_FILES' );
        foreach( $param as $key => $val ){
            if( $key == 'F_IDX' ){
                $builder->where( $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getCategoryFilesByParentIdx( $f_b_idx )
    {
        $aReturn                                    = [];
        if( empty( $f_b_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY_FILES' );
        $builder->where( 'F_B_IDX',                 $f_b_idx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function deleteCategoryFileDataByIdx( $f_idx )
    {
        $aReturn                                    = [];
        if( empty( $f_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_CATEGORY_FILES' );
        $builder->where( 'F_IDX',                   $f_idx );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function getCategoryFileDataByIdx( $f_idx )
    {
        $aReturn                                    = [];
        if( empty( $f_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_CATEGORY_FILES' );
        $builder->where( 'F_IDX',                   $f_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getCategoryFiles( $cate_idx )
    {
        $aReturn                                    = [];
        if( empty( $cate_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY_FILES' );
        $builder->where( 'F_B_IDX',                 $cate_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function insertCategoryFiles( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY_FILES' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function getCateCode( $parentIdx = 0 )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->select( 'IFNULL( MAX( C_CATE_CODE ), 0 ) max' );
        $builder->where( 'C_PARENT_IDX', $parentIdx );
        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getSortMax( $parent_idx ){
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->select( 'MAX(C_SORT) max' );
        $builder->where( 'C_PARENT_IDX',$parent_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getCategoryLists( $param = [] )
    {
        $aReturn                                    = [];
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];
        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );

        $query                                      = $builder->get();

        // 총 결과 수
        $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음

        // 정렬
        if (!empty( _elm( $param, 'order') ) ) {
            $builder->orderBy( _elm( $param, 'order' ) );
        }

        // 페이징 처리
        if (!empty( _elm( $param, 'limit' ) ) ) {
            $builder->limit((int)_elm( $param, 'limit' ), (int)( _elm( $param, 'start' )  ?? 0));
        }

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getChildCategory( $parentIdx )
    {
        $aReturn                                    = false;
        if( empty( $parentIdx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->where( 'C_PARENT_IDX', $parentIdx );

        $query                                      = $builder->get();


        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function setCateSort( $param = [] )
    {
        $aReturn                                      = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->set( 'C_SORT', _elm( $param, 'C_SORT' ) );
        $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function deleteCategory( $code_idx )
    {
        $aReturn = false;
        if(  empty( $code_idx ) === true){
            return $aReturn;
        }

        $builder = $this->db->table( 'GOODS_CATEGORY' );
        $builder->where( 'C_IDX', $code_idx );

        $aReturn = $builder->delete();
        return $aReturn;
    }


    public function getParentInfo( $parentIdx = 0 )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->select( 'C_CATE_NAME, C_CATE_CODE as C_PARENT_CODE, C_IDX as C_PARENT_IDX' );
        $builder->where( 'C_IDX', $parentIdx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getCategoryDataByIdx( $cate_idx )
    {
        $aReturn                                    = [];
        if( empty( $cate_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->where( 'C_IDX', $cate_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }
    public function getCategoryDataByName( $cate_name, $parent_idx=null )
    {
        $aReturn                                    = [];
        if( empty( $cate_name ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->where( 'C_CATE_NAME', $cate_name );
        // parent_idx가 null이 아니면 조건 추가
        if (!is_null($parent_idx)) {
            $builder->where('C_PARENT_IDX', $parent_idx);
        }
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getCategoryDataByCode( $cate_code, $parent_idx=null )
    {
        $aReturn                                    = [];
        if( empty( $cate_code ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->where( 'C_CATE_CODE', $cate_code );
        // parent_idx가 null이 아니면 조건 추가
        if (!is_null($parent_idx)) {
            $builder->where('C_PARENT_IDX', $parent_idx);
        }
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }


    public function getCategoryDataSelectFieldByIdx( $cate_idx, $fields = [] )
    {
        $aReturn                                    = [];
        if( empty( $cate_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        if( empty( $fields ) === false ){
            foreach( $fields as $field ){
                $builder->select( $field );
            }
        }
        $builder->where( 'C_IDX', $cate_idx );
        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }


    public function getTopLists( )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->where( 'C_PARENT_IDX', '0' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getCategoryDataByIdxs( $cate_idxs )
    {
        $aReturn                                    = [];
        if( empty( $cate_idxs ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_CATEGORY' );
        $builder->select( 'C_IDX, C_CATE_NAME' );
        $builder->whereIn( 'C_IDX', $cate_idxs );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }
    public function getCateTopNameJoin( $cate_idx )
    {
        $aReturn                                    = [];
        if( empty( $cate_idx ) ){
            return $aReturn;
        }
        $_sql                                       = "
            WITH RECURSIVE CategoryHierarchy AS (
                -- 기본적으로 자신을 포함한 첫 번째 레벨의 카테고리를 가져옴.
                SELECT
                    C_IDX,
                    C_PARENT_IDX,
                    C_CATE_NAME AS FullCategoryName
                FROM
                    GOODS_CATEGORY
                WHERE
                    C_IDX = '".$cate_idx."'
                UNION ALL
                -- 상위 카테고리를 재귀적으로 찾음.
                SELECT
                    gc.C_IDX,
                    gc.C_PARENT_IDX,
                    CONCAT(gc.C_CATE_NAME, ' > ', ch.FullCategoryName) AS FullCategoryName
                FROM
                    GOODS_CATEGORY gc
                INNER JOIN
                    CategoryHierarchy ch
                ON
                    gc.C_IDX = ch.C_PARENT_IDX
            )
            -- 최종적으로 FullCategoryName을 가져옴.
            SELECT
                FullCategoryName
            FROM
                CategoryHierarchy
            WHERE
                C_PARENT_IDX = 0;
        ";
        $query                                      =  $this->db->query( $_sql );

        $aReturn                                    = $query->getRowArray();
        return $aReturn;


    }

    public function getQuestions( $cate_idx )
    {
        $aReturn                                    = [];
        if( empty( $cate_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_QUESTION' );
        $builder->where( 'Q_CATE_IDX',              $cate_idx );
        $builder->orderBy( 'Q_SORT',                'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getQuestionsExamples( $q_idx )
    {
        $aReturn                                    = [];
        if( empty( $q_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_QUESTION_EXAMPLE' );
        $builder->where( 'E_Q_IDX',                 $q_idx );
        $builder->orderBy( 'E_SORT',                'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }
    public function updateQuestion( $param )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_QUESTION' );
        $builder->set( 'Q_KEYWORD',                 _elm( $param, 'Q_KEYWORD' ) );
        $builder->set( 'Q_QUESTION',                _elm( $param, 'Q_QUESTION' ) );
        $builder->set( 'Q_CATE_IDX',                _elm( $param, 'Q_CATE_IDX' ) );
        $builder->set( 'Q_SORT',                    _elm( $param, 'Q_SORT' ) );
        $builder->set( 'Q_UPDATE_AT',               _elm( $param, 'Q_UPDATE_AT' ) );
        $builder->set( 'Q_UPDATE_IP',               _elm( $param, 'Q_UPDATE_IP' ) );
        $builder->set( 'Q_UPDATE_MB_IDX',           _elm( $param, 'Q_UPDATE_MB_IDX' ) );

        $builder->where( 'Q_IDX', _elm( $param, 'Q_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function insertQuestions( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_QUESTION' );
        $builder->set( 'Q_KEYWORD',                 _elm( $param, 'Q_KEYWORD' ) );
        $builder->set( 'Q_QUESTION',                _elm( $param, 'Q_QUESTION' ) );
        $builder->set( 'Q_SORT',                    _elm( $param, 'Q_SORT' ) );
        $builder->set( 'Q_CATE_IDX',                _elm( $param, 'Q_CATE_IDX' ) );
        $builder->set( 'Q_CREATE_AT',               _elm( $param, 'Q_CREATE_AT' ) );
        $builder->set( 'Q_CREATE_IP',               _elm( $param, 'Q_CREATE_IP' ) );
        $builder->set( 'Q_CREATE_MB_IDX',           _elm( $param, 'Q_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function insertQuestionExample( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_QUESTION_EXAMPLE' );

        $builder->set( 'E_Q_IDX',                  _elm( $param, 'E_Q_IDX' ) );
        $builder->set( 'E_KEY',                    _elm( $param, 'E_KEY' ) );
        $builder->set( 'E_VALUE',                  _elm( $param, 'E_VALUE' ) );
        $builder->set( 'E_SORT',                   _elm( $param, 'E_SORT' ) );
        $builder->set( 'E_CREATE_AT',              _elm( $param, 'E_CREATE_AT' ) );
        $builder->set( 'E_CREATE_IP',              _elm( $param, 'E_CREATE_IP' ) );
        $builder->set( 'E_CREATE_MB_IDX',          _elm( $param, 'E_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function deleteQuestion( $q_idx )
    {
        $aReturn                                    = false;
        if( empty( $q_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_QUESTION' );
        $builder->where( 'Q_IDX',                   $q_idx );

        $aReturn                                    = $builder->delete();

        return $aReturn;
    }

    public function deleteQuestionExample( $e_idx )
    {
        $aReturn                                    = false;
        if( empty( $e_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_QUESTION_EXAMPLE' );
        $builder->where( 'E_IDX',                   $e_idx );
        $aReturn                                    = $builder->delete();
        return $aReturn;

    }



    public function updateQuestionExample( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_QUESTION_EXAMPLE' );

        $builder->set( 'E_KEY',                    _elm( $param, 'E_KEY' ) );
        $builder->set( 'E_VALUE',                  _elm( $param, 'E_VALUE' ) );
        $builder->set( 'E_SORT',                   _elm( $param, 'E_SORT' ) );
        $builder->set( 'E_UPDATE_AT',              _elm( $param, 'E_UPDATE_AT' ) );
        $builder->set( 'E_UPDATE_IP',              _elm( $param, 'E_UPDATE_IP' ) );
        $builder->set( 'E_UPDATE_MB_IDX',          _elm( $param, 'E_UPDATE_MB_IDX' ) );
        $builder->where( 'E_IDX',                  _elm( $param, 'E_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function deleteQuestionExampleAll( $q_idx )
    {
        $aReturn                                    = false;
        if( empty( $q_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_QUESTION_EXAMPLE' );
        $builder->where( 'E_Q_IDX',                 $q_idx );

        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

}