<?php
namespace Module\goods\Models;

use Config\Services;
use CodeIgniter\Model;

class GoodsModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }


    public function updateGoodsRealColor( $param = [] )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS' );
        $builder->where( 'G_IDX',                   _elm( $param, 'G_IDX' ) );
        $builder->set( 'G_REAL_COLOR',              _elm( $param, 'G_REAL_COLOR' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }
    public function getDahaeOptionCodeLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( '_D_OPTION_CODES' );
        $builder->select( '*' );
        if( empty( _elm( $param, 'd_g_name' ) ) === false ){
            $builder->like( 'd_option_g_name', _elm( $param, 'd_g_name' ), 'after' );
            $builder->orLike( 'd_option_name', _elm( $param, 'd_g_name' ), 'after' );
        }
        $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음

        //$builder->orderBy( 'newCateCd', 'ASC' );
        // 정렬
        if (!empty( _elm( $param, 'order') ) ) {
            $builder->orderBy( _elm( $param, 'order' ) );
        }

        // 페이징 처리
        if (!empty( _elm( $param, 'limit' ) ) ) {
            $builder->limit((int)_elm( $param, 'limit' ), (int)( _elm( $param, 'start' )  ?? 0));
        }

        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();

        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }

        return $aReturn;
    }
    public function updateDahaeOptionCode( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '_D_OPTION_CODES' );
        $builder->where( 'idx',                     _elm( $param, 'idx' ) );
        if( _elm( $param, 'd_option_g_name' ) ){
            $builder->set( 'd_option_g_name',       _elm( $param, 'd_option_g_name' ) );
        }
        if( _elm( $param, 'd_option_name' ) ){
            $builder->set( 'd_option_name',         _elm( $param, 'd_option_name' ) );
        }
        if( _elm( $param, 'd_result' ) ){
            $builder->set( 'd_result',              _elm( $param, 'd_result' ) );
        }
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function deleteDahaeOptionCode( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '_D_OPTION_CODES' );
        $builder->where( 'idx',                     _elm( $param, 'idx' ) );
        $aReturn                                    = $builder->delete();

        return $aReturn;

    }

    public function getDahaeOptionCodeByIdx( $idx )
    {
        $aReturn                                    = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '_D_OPTION_CODES' );
        $builder->where( 'idx',                     $idx );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function insertDahaeOptionCode( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '_D_OPTION_CODES' );
        $builder->set( 'd_option_g_name',           _elm( $param, 'd_option_g_name' ) );
        $builder->set( 'd_option_name',             _elm( $param, 'd_option_name' ) );
        $builder->set( 'd_result',                  _elm( $param, 'd_result' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }


    public function getDahaeCateLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( '_DAHAE_CATE_CODES' );
        $builder->select( '*' );
        if( empty( _elm( $param, 'cate' ) ) === false ){
            $builder->like( 'C_CATE_BIG', _elm( $param, 'cate' ), 'after' );
            $builder->orLike( 'C_CATE_MID', _elm( $param, 'cate' ), 'after' );
            $builder->orLike( 'C_CATE_SMALL', _elm( $param, 'cate' ), 'after' );
        }
        $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음

        //$builder->orderBy( 'newCateCd', 'ASC' );
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

    public function updateDahaeCate( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '_DAHAE_CATE_CODES' );
        $builder->set( 'C_RESULT_LOCAL_CATE_NM', _elm( $param, 'C_RESULT_LOCAL_CATE_NM' ) );
        $builder->set( 'C_RESULT_LOCAL_CATE_CD', _elm( $param, 'C_RESULT_LOCAL_CATE_CD' ) );
        $builder->where( 'C_IDX', _elm( $param, 'C_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function updateGodoGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '_es_goods' );
        $builder->set( 'newCateNm', _elm( $param, 'newCateNm' ) );
        $builder->set( 'newCateCd', _elm( $param, 'newCateCd' ) );
        $builder->where( 'goodsNo', _elm( $param, 'goodsNo' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }
    public function getGodoCateNm( $cateCd )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( '_es_categoryGoods' );
        $builder->select( 'cateNm,cateCd' );
        $builder->where( 'cateCd', $cateCd );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getGodoGoodsLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( '_es_goods' );
        $builder->select( 'goodsNo, goodsNm, cateCd, newCateCd, newCateNm' );
        if( empty( _elm( $param, 'cate' ) ) === false ){
            $builder->like( 'cateCd', _elm( $param, 'cate' ), 'after' );
        }
        $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음

        //$builder->orderBy( 'newCateCd', 'ASC' );
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

    public function getGoodsColor( $g_idx )
    {
        $aReturn                                    = [];
        if( empty( $g_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS' );
        $builder->select( 'G_COLOR' );
        $builder->where( 'G_IDX',                   $g_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function updateGoodsPrice( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS' );
        $builder->set( 'G_PRICE',                   _elm( $param, 'G_PRICE' ) );
        $builder->set( 'G_PRICE_RATE',              _elm( $param, 'G_PRICE_RATE' ) );

        $builder->where( 'G_IDX',                   _elm( $param, 'G_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function getGoodsDCGroup( $goods_idx )
    {
        $aReturn                                    = [];
        if( empty( $goods_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_DISCOUNT_GROUP' );
        $builder->where( 'D_GOODS_IDX',             $goods_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getGoodsOptions( $goods_idx )
    {
        $aReturn                                    = [];
        if( empty( $goods_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        $builder->where( 'O_GOODS_IDX',             $goods_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }


    public function getGoodsStockOverOptions( $goods_idx )
    {
        $aReturn                                    = [];
        if( empty( $goods_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        $builder->where( 'O_GOODS_IDX',             $goods_idx );
        $builder->where("O_STOCK <= (SELECT G_SAFETY_STOCK FROM GOODS WHERE G_IDX='{$goods_idx}')");

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getReqInfos( $goods_idx )
    {
        $aReturn                                    = [];
        if( empty( $goods_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_ADD_REQUIRED_INFO' );
        $builder->where( 'I_GOODS_IDX',             $goods_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }
    public function getGoodsInImagesToOrigin( $goods_idx )
    {
        $aReturn                                      = [];
        if( empty( $goods_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_IMAGES' );

        $builder->select( '*' );
        $builder->where( 'I_GOODS_IDX',             $goods_idx );
        $builder->where( 'I_IS_ORIGIN',             'Y' );
        $builder->orderBy( 'I_SORT',                'ASC' );

        $query                                      = $builder->get();


        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getGoodsInImages( $goods_idx )
    {
        $aReturn                                    = [];
        if( empty( $goods_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_IMAGES' );

        $builder->select( '*' );
        $builder->where( 'I_GOODS_IDX',             $goods_idx );
        $builder->orderBy( 'I_SORT',                'ASC' );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getGoodsInImagesFixSize( $goods_idx, $size )
    {
        $aReturn                                    = [];
        if( empty( $goods_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_IMAGES' );

        $builder->select( '*' );
        $builder->where( 'I_GOODS_IDX',             $goods_idx );
        $builder->where( 'I_IMG_VIEW_SIZE',         $size );
        $builder->orderBy( 'I_SORT',                'ASC' );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function insertReqInfos( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_ADD_REQUIRED_INFO' );
        $builder->set( 'I_SORT',                    _elm( $param, 'I_SORT' ) );
        $builder->set( 'I_GOODS_IDX',               _elm( $param, 'I_GOODS_IDX' ) );
        $builder->set( 'I_KEY',                     _elm( $param, 'I_KEY' ) );
        $builder->set( 'I_VALUE',                   _elm( $param, 'I_VALUE' ) );
        $builder->set( 'I_CREATE_AT',               _elm( $param, 'I_CREATE_AT' ) );
        $builder->set( 'I_CREATE_IP',               _elm( $param, 'I_CREATE_IP' ) );
        $builder->set( 'I_CREATE_MB_IDX',           _elm( $param, 'I_CREATE_MB_IDX' ) );
        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;


    }

    public function updateGoodsRelationData( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS' );
        $builder->set( 'G_RELATION_GOODS', _elm( $param, 'G_RELATION_GOODS' ) );
        $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getGoodsListsByIdxs( $idxs )
    {
        $aReturn                                    = [
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'GOODS G' );
        $builder->select('G.*');
        $builder->select('GI.I_IMG_PATH');

        $builder->join( 'GOODS_IMAGES GI', 'G.G_IDX = GI.I_GOODS_IDX AND I_SORT=1 AND I_IS_ORIGIN = \'Y\'', 'left' );
        $builder->whereIn( 'G.G_IDX', $idxs );
        $builder->where( 'G_DELETE_STATUS',        'N' );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }

        return $aReturn;

    }


    public function getGoodsListsByIdxsToOdering( $idxs )
    {
        $aReturn                                    = [
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'GOODS G' );
        $builder->select('G.*');
        $builder->select('GI.I_IMG_PATH');

        $builder->join( 'GOODS_IMAGES GI', 'G.G_IDX = GI.I_GOODS_IDX AND I_SORT=1 AND I_IS_ORIGIN = \'Y\'', 'left' );
        $builder->whereIn( 'G.G_IDX',               $idxs );
        $builder->where( 'G_DELETE_STATUS',         'N' );
        $builder->orderBy("FIELD(g_idx, ".join( ',', $idxs)." )",    '', false);
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }

        return $aReturn;

    }
    public function statusToDeleteGoods( $g_idx )
    {
        $aReturn                                    = false;
        if( empty( $g_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS' );
        $builder->where( 'G_IDX', $g_idx );
        $builder->set( 'G_DELETE_STATUS',           'Y' );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getGroupInGoods( $g_idx )
    {
        $aReturn                                      = [];
        if( empty( $g_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS' );
        $builder->where( 'JSON_CONTAINS(G_GROUP, JSON_OBJECT(\'g_idx\', '.$g_idx.'))' );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getGoodsOptionsByIdxs( $idxs )
    {
        $aReturn                                    = [];
        if( empty( $idxs ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        $builder->whereIn( 'O_IDX', $idxs );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getGoodsOptionsByIdx( $idx )
    {
        $aReturn                                    = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        $builder->whereIn( 'O_IDX',                 $idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function restockAlimChangeStatus( $a_idx )
    {
        $aReturn                                    = false;
        if( empty( $a_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_RESTOCK_ALIM' );
        $builder->where( 'A_IDX',                   $a_idx );
        $builder->set( 'A_ALIM_SEND_AT',          'now()', false );
        $aReturn                                    = $builder->update();
        return $aReturn;

    }
    public function getGoodsOptionsByOptionText( $g_idx, $texts = []  )
    {

        $aReturn                                    = [];
        if( empty( $texts ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        if( is_array( $texts ) ){

            foreach( $texts as $text ){
                $builder->orGroupStart();
                $_text                                  = explode(' ', trim($text));
                if( empty( $_text ) === false ){
                    foreach( $_text as $aKey => $t ){
                        $builder->like( 'O_KEYS', str_replace(':','',$t) );
                        $builder->like( 'O_VALUES', $t );
                    }
                }
                $builder->groupEnd();
            }
        }else{
            $_text                                  = explode(':', trim($texts));
            if( empty( $_text ) === false ){
                foreach( $_text as $aKey => $t ){
                    if( $aKey == 0 ){
                        $builder->like( 'O_KEYS', str_replace(':','',$t) );
                    }else{
                        $builder->like( 'O_VALUES', $t );
                    }
                }
            }
        }


        $builder->where( 'O_GOODS_IDX',             $g_idx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }
    public function getGoodsOptionsCombinationByOptionText( $g_idx, $texts = [] )
    {
        $aReturn                                    = [];
        if( empty( $texts ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        foreach( $texts as $text ){
            $builder->orGroupStart();
            $_text                                  = explode(' ', trim($text));
            if( empty( $_text ) === false ){
                foreach( $_text as $aKey => $t ){
                    if( $aKey == 0 ){
                        $builder->like( 'O_VALUES', $t );
                    }else{
                        $builder->like( 'O_VALUES'.($aKey+1), $t );
                    }

                }
            }
            $builder->groupEnd();
        }

        $builder->where( 'O_GOODS_IDX',             $g_idx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getGoodsDataSelectFieldByIdx( $idx, $fields = []  )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS G' );
        if( empty( $fields ) === false ){
            foreach( $fields as $field ){
                $builder->select( $field );
            }
        }
        $builder->select('GI.I_IMG_PATH');

        $builder->join( 'GOODS_IMAGES GI', 'G.G_IDX = GI.I_GOODS_IDX AND I_SORT=1 AND I_IS_ORIGIN = \'Y\'', 'left' );

        $builder->where( 'G_DELETE_STATUS',         'N' );
        $builder->where( 'G.G_IDX', $idx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }

        return $aReturn;

    }


    public function getGoodsDataByIdx( $idx )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'GOODS G' );
        $builder->select('G.*');
        $builder->select('GI.I_IMG_PATH');

        $builder->join( 'GOODS_IMAGES GI', 'G.G_IDX = GI.I_GOODS_IDX AND I_SORT=1 AND I_IS_ORIGIN = \'Y\'', 'left' );

        $builder->where( 'G_DELETE_STATUS',         'N' );
        $builder->where( 'G.G_IDX', $idx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }

        return $aReturn;

    }


    public function getGoodsLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'search_count'                          => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'GOODS G' );
        $builder->select('G.*');
        $builder->select('GI.I_IMG_PATH');
        $builder->select('BR.C_BRAND_NAME');
        $builder->where( 'G.G_DELETE_STATUS',       'N' );
        // 총 결과 수

        //$builder->resetQuery(); // 빌더 초기화
        $builder->join( 'GOODS_IMAGES GI', 'G.G_IDX = GI.I_GOODS_IDX AND I_SORT=1 AND I_IS_ORIGIN = \'Y\'', 'left' );
        $builder->join( 'GOODS_BRAND BR', 'BR.C_IDX = G.G_BRAND_IDX', 'left' );


        if( !empty( _elm( $param, 'G_NAME' ) ) ){
            $builder->groupStart();
            $builder->like('G.G_NAME', _elm( $param, 'G_NAME' ), 'both' );
            $builder->orLike('G.G_NAME_ENG', _elm( $param, 'G_NAME_ENG' ), 'both' );
            $builder->groupEnd();
        }

        if( !empty( _elm( $param, 'G_PRID' ) ) ){
            $builder->groupStart();
            $builder->like('G.G_PRID', _elm( $param, 'G_PRID' ), 'both' );
            $builder->orLike('G.G_LOCAL_PRID', _elm( $param, 'G_LOCAL_PRID' ), 'both' );
            $builder->groupEnd();
        }
        if( !empty( _elm( $param, 'G_SEARCH_KEYWORD' ) ) ){
            $builder->groupStart();
            $builder->like('G.G_SEARCH_KEYWORD', ','._elm( $param, 'G_SEARCH_KEYWORD' ).',', 'after' );
            $builder->orLike('G.G_SEARCH_KEYWORD', ','._elm( $param, 'G_SEARCH_KEYWORD' ), 'before' );
            $builder->orLike('G.G_SEARCH_KEYWORD', _elm( $param, 'G_SEARCH_KEYWORD' ), 'both' );
            $builder->orWhere('G.G_SEARCH_KEYWORD', _elm( $param, 'G_SEARCH_KEYWORD' ));
            $builder->groupEnd();
        }
        if( !empty( _elm( $param, 'G_MAKER_NAME' ) ) ){
            $builder->where( 'G.G_MAKER_NAME', _elm( $param, 'G_MAKER_NAME' ) );
        }

        if( !empty( _elm( $param, 'S_START_DATE' ) ) && !empty( _elm( $param, 'S_END_DATE' ) ) ){
            if( !empty( _elm( $param, 'G_CREATE_AT' ) ) ){
                $builder->where( 'G.G_CREATE_AT >=', _elm( $param, 'S_START_DATE' ) );
                $builder->where( 'G.G_CREATE_AT <=', _elm( $param, 'S_END_DATE' ) );
            }else if( !empty( _elm( $param, 'G_UPDATE_AT' ) ) ){
                $builder->where( 'G.G_UPDATE_AT >=', _elm( $param, 'S_START_DATE' ) );
                $builder->where( 'G.G_UPDATE_AT <=', _elm( $param, 'S_END_DATE' ) );
            }
        }

        if( !empty( _elm( $param, 'IS_NOT_CATEGORY' ) ) ){
            $builder->groupStart();
            $builder->where( 'G_CATEGORY_MAIN IS NULL' );
            $builder->orWhere( 'G.G_CATEGORYS IS NULL' );
            $builder->groupEnd();
        }

        if( !empty( _elm( $param, 'G_CATEGORY_MAIN_IDX' ) ) ){
            $builder->where( 'G_CATEGORY_MAIN_IDX', _elm( $param, 'G_CATEGORY_MAIN_IDX' ) );
        }
        if( !empty( _elm( $param, 'G_BRAND_IDX' ) )  ){
            $builder->where( 'G.G_BRAND_IDX', _elm( $param, 'G_BRAND_IDX' ) );
        }

        if( !empty( _elm( $param, 'notIdx' ) ) ){
            $builder->whereNotIn( 'G.G_IDX', _elm( $param, 'notIdx' ) );
        }
        if( !empty( _elm( $param, 'pickIdx' ) ) ){
            $builder->whereIn( 'G.G_IDX', _elm( $param, 'pickIdx' ) );
        }

        if( empty( _elm( $param, 'MIN_PRICE' ) ) === false && empty( _elm( $param, 'MAX_PRICE' ) ) === false ){
            $builder->where( 'G.G_PRICE >=', _elm( $param, 'MIN_PRICE' ) );
            $builder->where( 'G.G_PRICE <=', _elm( $param, 'MAX_PRICE' ) );
        }

        if( empty( _elm( $param, 'GROUP_USE_FLAG' ) ) === false ){
            if( _elm( $param, 'GROUP_USE_FLAG' ) == 'Y'  ){
                $builder->where( 'G.G_GROUP IS NOT NULL', null , false );
            }else{
                $builder->where( 'G.G_GROUP IS NULL', null , false );
            }
        }

        if( empty( _elm( $param, 'VIEW_GBN_FLAG' ) ) === false ){
            if( _elm( $param, 'VIEW_GBN_FLAG' ) == 'Y' ){
                $builder->groupStart();
                    $builder->groupStart();
                        $builder->where( 'G_PC_OPEN_FLAG', 'Y' );
                        $builder->orWhere( 'G_PC_SELL_FLAG', 'Y' );
                    $builder->groupEnd();
                    $builder->orGroupStart();
                        $builder->where( 'G_MOBILE_OPEN_FLAG', 'Y'  );
                        $builder->orWhere( 'G_MOBILE_SELL_FLAG', 'Y'  );
                    $builder->groupEnd();
                $builder->groupEnd();
            }else{
                $builder->groupStart();
                    $builder->groupStart();
                        $builder->where( 'G_PC_OPEN_FLAG', 'N'  );
                        $builder->orWhere( 'G_PC_SELL_FLAG', 'N'  );
                    $builder->groupEnd();
                    $builder->orGroupStart();
                        $builder->where( 'G_MOBILE_OPEN_FLAG', 'N' );
                        $builder->orWhere( 'G_MOBILE_SELL_FLAG', 'N'  );
                    $builder->groupEnd();
                $builder->groupEnd();
            }
        }

        if( empty( _elm( $param, 'OPTION_USE_FLAG' ) ) === false ){
            $builder->where( 'G_OPTION_USE_FLAG', _elm( $param, 'OPTION_USE_FLAG' ) );
        }

        if( empty( _elm( $param, 'STOCK_OVER_FLAG' ) ) === false ){
            if( _elm( $param, 'STOCK_OVER_FLAG' ) == 'over' ){
                $builder->groupStart();
                $builder->where( 'G_STOCK_FLAG', 'Y' );
                $builder->where( 'G.G_SAFETY_STOCK > G.G_STOCK_CNT', null, false );
                $builder->groupEnd();
            }else{
                $builder->groupStart();
                $builder->where( 'G_STOCK_FLAG', 'N' );
                $builder->orWhere( 'G.G_SAFETY_STOCK < G.G_STOCK_CNT', null, false );
                $builder->groupEnd();
            }
        }

        $builder->where( 'G_GROUP_MAIN',            'Y' );

        $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음
        $aReturn['search_count']                    = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음

        // 정렬
        if (!empty( _elm( $param, 'order') ) ) {
            $builder->orderBy( _elm( $param, 'order' ) );
        }

        // 페이징 처리
        if (!empty( _elm( $param, 'limit' ) ) ) {
            $builder->limit((int)_elm( $param, 'limit' ), (int)( _elm( $param, 'start' )  ?? 0));
        }

        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();

        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }

        return $aReturn;

    }

    public function getGoodsRestockLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'search_count'                          => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'GOODS_RESTOCK_ALIM GRA' );
        //$builder->select( 'GRA.*, count(GRA.A_MB_IDX) ALIM_CNT, CASE WHEN GRA.A_SEND_AT IS NOT NULL THEN COUNT( GRA.A_SEND_AT ) END SEND_CNT, GRA.A_SEND_AT IS NULL THEN COUNT( GRA.A_SEND_AT ) END NOT_SEND_CNT' );
        $builder->select( 'GRA.*, count(GRA.A_MB_IDX) ALIM_CNT,  COUNT(GRA.A_ALIM_SEND_AT) AS SEND_CNT, COUNT(CASE WHEN GRA.A_ALIM_SEND_AT IS NULL THEN 1 ELSE NULL END) AS NOT_SEND_CNT');
        $builder->select('G.*');
        $builder->select('GI.I_IMG_PATH');
        $builder->select('BR.C_BRAND_NAME');
        $builder->where( 'G.G_DELETE_STATUS',       'N' );
        $builder->join( 'MEMBERSHIP MB', 'MB.MB_IDX=GRA.A_MB_IDX', 'left' );
        $builder->join( 'GOODS G', 'GRA.A_PRD_IDX=G.G_IDX', 'left' );

        // 총 결과 수

        //$builder->resetQuery(); // 빌더 초기화
        $builder->join( 'GOODS_IMAGES GI', 'G.G_IDX = GI.I_GOODS_IDX AND I_SORT=1 AND I_IS_ORIGIN = \'Y\'', 'left' );
        $builder->join( 'GOODS_BRAND BR', 'BR.C_IDX = G.G_BRAND_IDX', 'left' );


        if( !empty( _elm( $param, 'G_NAME' ) ) ){
            $builder->groupStart();
            $builder->like('G.G_NAME', _elm( $param, 'G_NAME' ), 'both' );
            $builder->orLike('G.G_NAME_ENG', _elm( $param, 'G_NAME_ENG' ), 'both' );
            $builder->groupEnd();
        }


        if( empty( _elm( $param, 'MB_USERID' ) ) === false ){
            $builder->like( 'MB.MB_USERID', _elm( $param, 'MB_USERID' ), 'both' );
        }
        if( empty( _elm( $param, 'MB_NAME' ) ) === false ){
            $builder->like( 'MB.MB_NM', _elm( $param, 'MB_NAME' ), 'both' );
        }

        if( !empty( _elm( $param, 'S_START_DATE' ) ) && !empty( _elm( $param, 'S_END_DATE' ) ) ){
            $builder->where( 'GRA.A_CREATE_AT >=', _elm( $param, 'S_START_DATE' ) );
            $builder->where( 'GRA.A_CREATE_AT <=', _elm( $param, 'S_END_DATE' ) );
        }

        if( !empty( _elm( $param, 'IS_NOT_CATEGORY' ) ) ){
            $builder->groupStart();
            $builder->where( 'G_CATEGORY_MAIN IS NULL' );
            $builder->orWhere( 'G.G_CATEGORYS IS NULL' );
            $builder->groupEnd();
        }

        if( !empty( _elm( $param, 'G_CATEGORY_MAIN_IDX' ) ) ){
            $builder->where( 'G_CATEGORY_MAIN_IDX', _elm( $param, 'G_CATEGORY_MAIN_IDX' ) );
        }
        $builder->groupBy( 'GRA.A_PRD_IDX' );
        $aReturn['total_count']                     = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음
        $aReturn['search_count']                    = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음

        // 정렬
        if (!empty( _elm( $param, 'order') ) ) {
            $builder->orderBy( _elm( $param, 'order' ) );
        }

        // 페이징 처리
        if (!empty( _elm( $param, 'limit' ) ) ) {
            $builder->limit((int)_elm( $param, 'limit' ), (int)( _elm( $param, 'start' )  ?? 0));
        }

        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();

        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }

        return $aReturn;
    }

    public function getGoodsRestockAlimDetailInfo( $param = [] )
    {
        $aReturn                                     = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_RESTOCK_ALIM GRA' );
        //$builder->select( 'GRA.*, count(GRA.A_MB_IDX) ALIM_CNT, CASE WHEN GRA.A_SEND_AT IS NOT NULL THEN COUNT( GRA.A_SEND_AT ) END SEND_CNT, GRA.A_SEND_AT IS NULL THEN COUNT( GRA.A_SEND_AT ) END NOT_SEND_CNT' );
        $builder->select( 'GRA.*, count(GRA.A_MB_IDX) ALIM_CNT,  COUNT(GRA.A_ALIM_SEND_AT) AS SEND_CNT, COUNT(CASE WHEN GRA.A_ALIM_SEND_AT IS NULL THEN 1 ELSE NULL END) AS NOT_SEND_CNT');
        $builder->select('G.*');
        $builder->select('GI.I_IMG_PATH');
        $builder->select('BR.C_BRAND_NAME');
        $builder->where( 'G.G_DELETE_STATUS',       'N' );
        $builder->join( 'MEMBERSHIP MB', 'MB.MB_IDX=GRA.A_MB_IDX', 'left' );
        $builder->join( 'GOODS G', 'GRA.A_PRD_IDX=G.G_IDX', 'left' );

        // 총 결과 수

        //$builder->resetQuery(); // 빌더 초기화
        $builder->join( 'GOODS_IMAGES GI', 'G.G_IDX = GI.I_GOODS_IDX AND I_SORT=1 AND I_IS_ORIGIN = \'Y\'', 'left' );
        $builder->join( 'GOODS_BRAND BR', 'BR.C_IDX = G.G_BRAND_IDX', 'left' );

        $builder->where( 'GRA.A_PRD_IDX',               _elm( $param, 'A_PRD_IDX' ) );

        $builder->groupBy( 'GRA.A_PRD_IDX' );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getRestockAlimData( $a_idx )
    {
        $aReturn                                    = [];
        if( empty( $a_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_RESTOCK_ALIM GRA' );
        $builder->select( 'GRA.A_CREATE_AT, GRA.A_ALIM_SEND_AT, GRA.A_IDX, GRA.A_PRD_NAME, GRA.A_PRD_IDX, GRA.A_OPTIONS_IDX, GRA.A_OPTIONS_TEXT, A_MB_IDX' );
        $builder->select( 'MB.MB_NM, MB.MB_MOBILE_NUM, MB_USERID' );
        $builder->select( 'G.G_NAME, G.G_OPTION_COMBINATION_FLAG' );
        $builder->join( 'MEMBERSHIP MB', 'MB.MB_IDX=GRA.A_MB_IDX', 'left' );
        $builder->join( 'GOODS G', 'GRA.A_PRD_IDX = G.G_IDX', 'left' );
        $builder->where( 'GRA.A_IDX',               $a_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getGoodsRestockAlimDetailLists( $param = [] )
    {

        $aReturn                                    = [
            'totalCount'                            => 0,
            'lists'                                 => []
        ];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_RESTOCK_ALIM GRA' );
        $builder->select( 'GRA.A_CREATE_AT, GRA.A_ALIM_SEND_AT, GRA.A_IDX' );
        $builder->select( 'MB.MB_NM, MB.MB_MOBILE_NUM, MB_USERID' );
        $builder->select( 'GR.G_NAME' );
        $builder->join( 'MEMBERSHIP MB', 'MB.MB_IDX=GRA.A_MB_IDX', 'left' );
        $builder->join( 'MEMBER_GRADE GR', 'MB.MB_GRADE_IDX= GR.G_IDX', 'left' );
        $builder->where( 'GRA.A_PRD_IDX',               _elm( $param, 'A_PRD_IDX' ) );
        if( empty( _elm( $param, 'A_SEND_GBN' ) ) === false ){
            if( _elm( $param, 'A_SEND_GBN' ) == 'Y' ){
                $builder->where( 'GRA.A_ALIM_SEND_AT IS NOT NULL' );
            }else if( _elm( $param, 'A_SEND_GBN' ) == 'N' ){
                $builder->where( 'GRA.A_ALIM_SEND_AT IS NULL' );
            }
        }

        $aReturn['totalCount']                      = $builder->countAllResults(false); // false는 쿼리 빌더를 초기화하지 않음

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


    public function updateGoodsGroup( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS' );
        $builder->set( 'G_GROUP',                   _elm( $param, 'G_GROUP' ) );
        $builder->set( 'G_GROUP_MAIN',              _elm( $param, 'G_GROUP_MAIN' ) );
        $builder->where( 'G_IDX',                   _elm( $param, 'G_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }


    public function insertGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS' );
        $builder->set( 'G_PRID',                    _elm( $param, 'G_PRID' ) );
        $builder->set( 'G_GROUP',                   _elm( $param, 'G_GROUP' ,NULL, true ) );
        $builder->set( 'G_DAHAE_P_CODE',            _elm( $param, 'G_DAHAE_P_CODE' ,NULL, true ) );
        $builder->set( 'G_CATEGORY_MAIN',           _elm( $param, 'G_CATEGORY_MAIN' ) );
        $builder->set( 'G_CATEGORY_MAIN_IDX',       _elm( $param, 'G_CATEGORY_MAIN_IDX' ) );
        $builder->set( 'G_CATEGORYS',               _elm( $param, 'G_CATEGORYS' ) );
        $builder->set( 'G_NAME',                    _elm( $param, 'G_NAME' ) );
        $builder->set( 'G_NAME_ENG',                _elm( $param, 'G_NAME_ENG' ) );
        $builder->set( 'G_LOCAL_PRID',              _elm( $param, 'G_LOCAL_PRID' ) );
        $builder->set( 'G_SHORT_DESCRIPTION',       _elm( $param, 'G_SHORT_DESCRIPTION' ) );
        $builder->set( 'G_CONTETN_IS_SAME_FLAG',    _elm( $param, 'G_CONTETN_IS_SAME_FLAG' ) );
        $builder->set( 'G_CONTENT_PC',              _elm( $param, 'G_CONTENT_PC' ) );
        $builder->set( 'G_CONTENT_MOBILE',          _elm( $param, 'G_CONTENT_MOBILE' ) );
        $builder->set( 'G_SEARCH_KEYWORD',          _elm( $param, 'G_SEARCH_KEYWORD' ) );
        $builder->set( 'G_ADD_POINT',               _elm( $param, 'G_ADD_POINT' ) );
        $builder->set( 'G_IS_PERFIT_FLAG',          _elm( $param, 'G_IS_PERFIT_FLAG' ) );
        $builder->set( 'G_SELL_PERIOD_START_AT',    _elm( $param, 'G_SELL_PERIOD_START_AT' ) );
        $builder->set( 'G_SELL_PERIOD_END_AT',      _elm( $param, 'G_SELL_PERIOD_END_AT' ) );
        $builder->set( 'G_COLOR',                   _elm( $param, 'G_COLOR' ) );
        $builder->set( 'G_REAL_COLOR',              _elm( $param, 'G_REAL_COLOR' ) );
        $builder->set( 'G_SELL_PRICE',              _elm( $param, 'G_SELL_PRICE' ) );
        $builder->set( 'G_SELL_UNIT',               _elm( $param, 'G_SELL_UNIT' ) );
        $builder->set( 'G_BUY_PRICE',               _elm( $param, 'G_BUY_PRICE' ) );
        $builder->set( 'G_PRICE',                   _elm( $param, 'G_PRICE' ) );
        $builder->set( 'G_PRICE_RATE',              _elm( $param, 'G_PRICE_RATE' ) );
        $builder->set( 'G_TAX_TYPE',                _elm( $param, 'G_TAX_TYPE' ) );
        $builder->set( 'G_DISCOUNT_CD',             _elm( $param, 'G_DISCOUNT_CD' ) );
        $builder->set( 'G_GRADE_DISCOUNT_FLAG',     _elm( $param, 'G_GRADE_DISCOUNT_FLAG' ) );
        $builder->set( 'G_SELL_POINT_FLAG',         _elm( $param, 'G_SELL_POINT_FLAG' ) );
        $builder->set( 'G_RELATION_GOODS_FLAG',     _elm( $param, 'G_RELATION_GOODS_FLAG' ) );
        $builder->set( 'G_RELATION_GOODS',          _elm( $param, 'G_RELATION_GOODS' ) );
        $builder->set( 'G_ADD_GOODS_FLAG',          _elm( $param, 'G_ADD_GOODS_FLAG' ) );
        $builder->set( 'G_ADD_GOODS',               _elm( $param, 'G_ADD_GOODS' ) );
        $builder->set( 'G_OPTION_USE_FLAG',         _elm( $param, 'G_OPTION_USE_FLAG' ) );
        $builder->set( 'G_STOCK_FLAG',              _elm( $param, 'G_STOCK_FLAG' ) );
        $builder->set( 'G_STOCK_CNT',               _elm( $param, 'G_STOCK_CNT' ) );
        $builder->set( 'G_SAFETY_STOCK',            _elm( $param, 'G_SAFETY_STOCK' ) );
        $builder->set( 'G_IS_RESTOCK_ALIM_FLAG',    _elm( $param, 'G_IS_RESTOCK_ALIM_FLAG' ) );
        $builder->set( 'G_TEXT_OPTION',             _elm( $param, 'G_TEXT_OPTION' ) );
        $builder->set( 'G_TEXT_OPTION_USE_FLAG',    _elm( $param, 'G_TEXT_OPTION_USE_FLAG' ) );
        $builder->set( 'G_DELIVERY_PAY_CD',         _elm( $param, 'G_DELIVERY_PAY_CD' ) );
        $builder->set( 'G_PC_OPEN_FLAG',            _elm( $param, 'G_PC_OPEN_FLAG' ) );
        $builder->set( 'G_PC_SELL_FLAG',            _elm( $param, 'G_PC_SELL_FLAG' ) );
        $builder->set( 'G_MOBILE_OPEN_FLAG',        _elm( $param, 'G_MOBILE_OPEN_FLAG' ) );
        $builder->set( 'G_MOBILE_SELL_FLAG',        _elm( $param, 'G_MOBILE_SELL_FLAG' ) );
        $builder->set( 'G_ORIGIN_NAME',             _elm( $param, 'G_ORIGIN_NAME' ) );
        $builder->set( 'G_MAKER_NAME',              _elm( $param, 'G_MAKER_NAME' ) );
        $builder->set( 'G_BRAND_IDX',               _elm( $param, 'G_BRAND_IDX' ) );
        $builder->set( 'G_BRAND_NAME',              _elm( $param, 'G_BRAND_NAME' ) );
        $builder->set( 'G_SEO_TITLE',               _elm( $param, 'G_SEO_TITLE' ) );
        $builder->set( 'G_SEO_DESCRIPTION',         _elm( $param, 'G_SEO_DESCRIPTION' ) );
        $builder->set( 'G_OUT_VIEW',                _elm( $param, 'G_OUT_VIEW' ) );
        $builder->set( 'G_OUT_MAIN_IMG_PATH',       _elm( $param, 'G_OUT_MAIN_IMG_PATH' ) );
        $builder->set( 'G_OUT_MAIN_IMG_NAME',       _elm( $param, 'G_OUT_MAIN_IMG_NAME' ) );
        $builder->set( 'G_OUT_GOODS_NAME',          _elm( $param, 'G_OUT_GOODS_NAME' ) );
        $builder->set( 'G_OUT_EVENT_TXT',           _elm( $param, 'G_OUT_EVENT_TXT' ) );
        $builder->set( 'G_GOODS_CONDITION',         _elm( $param, 'G_GOODS_CONDITION' ) );
        $builder->set( 'G_GOODS_PRODUCT_TYPE',      _elm( $param, 'G_GOODS_PRODUCT_TYPE' ) );
        $builder->set( 'G_IS_SALES_TYPE',           _elm( $param, 'G_IS_SALES_TYPE' ) );
        $builder->set( 'G_MIN_BUY_COUNT',           _elm( $param, 'G_MIN_BUY_COUNT' ) );
        $builder->set( 'G_MEM_MAX_BUY_COUNT',       _elm( $param, 'G_MEM_MAX_BUY_COUNT' ) );
        $builder->set( 'G_IS_ADULT_PRODUCT',        _elm( $param, 'G_IS_ADULT_PRODUCT' ) );
        $builder->set( 'G_CREATE_AT',               _elm( $param, 'G_CREATE_AT' ) );
        $builder->set( 'G_CREATE_IP',               _elm( $param, 'G_CREATE_IP' ) );
        $builder->set( 'G_CREATE_MB_IDX',           _elm( $param, 'G_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function updateGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS' );

        $builder->set( 'G_CATEGORY_MAIN',           _elm( $param, 'G_CATEGORY_MAIN' ) );
        $builder->set( 'G_CATEGORY_MAIN_IDX',       _elm( $param, 'G_CATEGORY_MAIN_IDX' ) );
        $builder->set( 'G_CATEGORYS',               _elm( $param, 'G_CATEGORYS' ) );
        $builder->set( 'G_NAME',                    _elm( $param, 'G_NAME' ) );
        $builder->set( 'G_NAME_ENG',                _elm( $param, 'G_NAME_ENG' ) );
        $builder->set( 'G_LOCAL_PRID',              _elm( $param, 'G_LOCAL_PRID' ) );
        $builder->set( 'G_SHORT_DESCRIPTION',       _elm( $param, 'G_SHORT_DESCRIPTION' ) );
        $builder->set( 'G_CONTETN_IS_SAME_FLAG',    _elm( $param, 'G_CONTETN_IS_SAME_FLAG' ) );
        $builder->set( 'G_CONTENT_PC',              _elm( $param, 'G_CONTENT_PC' ) );
        $builder->set( 'G_CONTENT_MOBILE',          _elm( $param, 'G_CONTENT_MOBILE' ) );
        $builder->set( 'G_SEARCH_KEYWORD',          _elm( $param, 'G_SEARCH_KEYWORD' ) );
        $builder->set( 'G_ADD_POINT',               _elm( $param, 'G_ADD_POINT' ) );
        $builder->set( 'G_IS_PERFIT_FLAG',          _elm( $param, 'G_IS_PERFIT_FLAG' ) );
        $builder->set( 'G_SELL_PERIOD_START_AT',    _elm( $param, 'G_SELL_PERIOD_START_AT' ) );
        $builder->set( 'G_SELL_PERIOD_END_AT',      _elm( $param, 'G_SELL_PERIOD_END_AT' ) );
        $builder->set( 'G_COLOR',                   _elm( $param, 'G_COLOR' ) );
        $builder->set( 'G_SELL_PRICE',              _elm( $param, 'G_SELL_PRICE' ) );
        $builder->set( 'G_SELL_UNIT',               _elm( $param, 'G_SELL_UNIT' ) );
        $builder->set( 'G_BUY_PRICE',               _elm( $param, 'G_BUY_PRICE' ) );
        $builder->set( 'G_PRICE',                   _elm( $param, 'G_PRICE' ) );
        $builder->set( 'G_PRICE_RATE',              _elm( $param, 'G_PRICE_RATE' ) );
        $builder->set( 'G_TAX_TYPE',                _elm( $param, 'G_TAX_TYPE' ) );
        $builder->set( 'G_DISCOUNT_CD',             _elm( $param, 'G_DISCOUNT_CD' ) );
        $builder->set( 'G_GRADE_DISCOUNT_FLAG',     _elm( $param, 'G_GRADE_DISCOUNT_FLAG' ) );
        $builder->set( 'G_SELL_POINT_FLAG',         _elm( $param, 'G_SELL_POINT_FLAG' ) );
        $builder->set( 'G_RELATION_GOODS_FLAG',     _elm( $param, 'G_RELATION_GOODS_FLAG' ) );
        $builder->set( 'G_RELATION_GOODS',          _elm( $param, 'G_RELATION_GOODS' ) );
        $builder->set( 'G_ADD_GOODS_FLAG',          _elm( $param, 'G_ADD_GOODS_FLAG' ) );
        $builder->set( 'G_ADD_GOODS',               _elm( $param, 'G_ADD_GOODS' ) );
        $builder->set( 'G_OPTION_USE_FLAG',         _elm( $param, 'G_OPTION_USE_FLAG' ) );
        $builder->set( 'G_OPTION_NAMES',            _elm( $param, 'G_OPTION_NAMES' ) );
        $builder->set( 'G_OPTION_INFO',             _elm( $param, 'G_OPTION_INFO' ) );
        $builder->set( 'G_OPTION_COMBINATION_FLAG', _elm( $param, 'G_OPTION_COMBINATION_FLAG' ) );
        $builder->set( 'G_STOCK_FLAG',              _elm( $param, 'G_STOCK_FLAG' ) );
        $builder->set( 'G_STOCK_CNT',               _elm( $param, 'G_STOCK_CNT' ) );
        $builder->set( 'G_SAFETY_STOCK',            _elm( $param, 'G_SAFETY_STOCK' ) );
        $builder->set( 'G_IS_RESTOCK_ALIM_FLAG',    _elm( $param, 'G_IS_RESTOCK_ALIM_FLAG' ) );
        $builder->set( 'G_TEXT_OPTION',             _elm( $param, 'G_TEXT_OPTION' ) );
        $builder->set( 'G_TEXT_OPTION_USE_FLAG',    _elm( $param, 'G_TEXT_OPTION_USE_FLAG' ) );
        $builder->set( 'G_DELIVERY_PAY_CD',         _elm( $param, 'G_DELIVERY_PAY_CD' ) );
        $builder->set( 'G_PC_OPEN_FLAG',            _elm( $param, 'G_PC_OPEN_FLAG' ) );
        $builder->set( 'G_PC_SELL_FLAG',            _elm( $param, 'G_PC_SELL_FLAG' ) );
        $builder->set( 'G_MOBILE_OPEN_FLAG',        _elm( $param, 'G_MOBILE_OPEN_FLAG' ) );
        $builder->set( 'G_MOBILE_SELL_FLAG',        _elm( $param, 'G_MOBILE_SELL_FLAG' ) );
        $builder->set( 'G_ORIGIN_NAME',             _elm( $param, 'G_ORIGIN_NAME' ) );
        $builder->set( 'G_MAKER_NAME',              _elm( $param, 'G_MAKER_NAME' ) );
        $builder->set( 'G_BRAND_IDX',               _elm( $param, 'G_BRAND_IDX' ) );
        $builder->set( 'G_BRAND_NAME',              _elm( $param, 'G_BRAND_NAME' ) );
        $builder->set( 'G_SEO_TITLE',               _elm( $param, 'G_SEO_TITLE' ) );
        $builder->set( 'G_SEO_DESCRIPTION',         _elm( $param, 'G_SEO_DESCRIPTION' ) );
        $builder->set( 'G_OUT_VIEW',                _elm( $param, 'G_OUT_VIEW' ) );
        $builder->set( 'G_OUT_MAIN_IMG_PATH',       _elm( $param, 'G_OUT_MAIN_IMG_PATH' ) );
        $builder->set( 'G_OUT_MAIN_IMG_NAME',       _elm( $param, 'G_OUT_MAIN_IMG_NAME' ) );
        $builder->set( 'G_OUT_GOODS_NAME',          _elm( $param, 'G_OUT_GOODS_NAME' ) );
        $builder->set( 'G_OUT_EVENT_TXT',           _elm( $param, 'G_OUT_EVENT_TXT' ) );
        $builder->set( 'G_GOODS_CONDITION',         _elm( $param, 'G_GOODS_CONDITION' ) );
        $builder->set( 'G_GOODS_PRODUCT_TYPE',      _elm( $param, 'G_GOODS_PRODUCT_TYPE' ) );
        $builder->set( 'G_IS_SALES_TYPE',           _elm( $param, 'G_IS_SALES_TYPE' ) );
        $builder->set( 'G_MIN_BUY_COUNT',           _elm( $param, 'G_MIN_BUY_COUNT' ) );
        $builder->set( 'G_MEM_MAX_BUY_COUNT',       _elm( $param, 'G_MEM_MAX_BUY_COUNT' ) );
        $builder->set( 'G_IS_ADULT_PRODUCT',        _elm( $param, 'G_IS_ADULT_PRODUCT' ) );
        $builder->set( 'G_UPDATE_AT',               _elm( $param, 'G_UPDATE_AT' ) );
        $builder->set( 'G_UPDATE_IP',               _elm( $param, 'G_UPDATE_IP' ) );
        $builder->set( 'G_UPDATE_MB_IDX',           _elm( $param, 'G_UPDATE_MB_IDX' ) );

        $builder->where( 'G_IDX',                   _elm( $param, 'G_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;
    }

    public function insertGoodsImages( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_IMAGES' );
        $builder->set( 'I_SORT',                    _elm( $param, 'I_SORT' ) );
        $builder->set( 'I_GOODS_IDX',               _elm( $param, 'I_GOODS_IDX' ) );
        $builder->set( 'I_IMG_NAME',                _elm( $param, 'I_IMG_NAME' ) );
        $builder->set( 'I_IMG_PATH',                _elm( $param, 'I_IMG_PATH' ) );
        $builder->set( 'I_IMG_SIZE',                _elm( $param, 'I_IMG_SIZE' ) );
        $builder->set( 'I_IMG_VIEW_SIZE',           _elm( $param, 'I_IMG_VIEW_SIZE' ) );
        $builder->set( 'I_IS_ORIGIN',               _elm( $param, 'I_IS_ORIGIN' ) );
        $builder->set( 'I_IMG_EXT',                 _elm( $param, 'I_IMG_EXT' ) );
        $builder->set( 'I_IMG_MIME_TYPE',           _elm( $param, 'I_IMG_MIME_TYPE' ) );
        $builder->set( 'I_CREATE_AT',               _elm( $param, 'I_CREATE_AT' ) );
        $builder->set( 'I_CREATE_IP',               _elm( $param, 'I_CREATE_IP' ) );
        $builder->set( 'I_CREATE_MB_IDX',           _elm( $param, 'I_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function deleteData( $table, $target = [] )
    {
        $aReturn                                    = [];
        if( empty( $table ) === true || empty( $target ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( $table );
        $builder->where( _elm( $target, 'field' ), _elm( $target, 'idx' ) );
        $aReturn                                    = $builder->delete();
        return $aReturn;
    }

    public function updateGoodsImageSort($goodsIdx, $filename, $newSort)
    {
        return $this->db->table('GOODS_IMAGES')
            ->where('I_GOODS_IDX', $goodsIdx)
            ->where('I_IMG_NAME', $filename)
            ->update(['I_SORT' => $newSort]);
    }

    public function insertDCGroup( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_DISCOUNT_GROUP' );
        $builder->set( 'D_GOODS_IDX',               _elm( $param, 'D_GOODS_IDX' ) );
        $builder->set( 'D_MB_GROUP_IDX',            _elm( $param, 'D_MB_GROUP_IDX' ) );
        $builder->set( 'D_MB_GROUP_NAME',           _elm( $param, 'D_MB_GROUP_NAME' ) );
        $builder->set( 'D_MB_GROUP_SV_AMT',         _elm( $param, 'D_MB_GROUP_SV_AMT' ) );
        $builder->set( 'D_MB_GOURP_SV_UNIT',        _elm( $param, 'D_MB_GOURP_SV_UNIT' ) );
        $builder->set( 'D_DC_PERIOD_START_AT',      _elm( $param, 'D_DC_PERIOD_START_AT' ) );
        $builder->set( 'D_DC_PERIOD_END_AT',        _elm( $param, 'D_DC_PERIOD_END_AT' ) );
        $builder->set( 'D_CREATE_AT',               _elm( $param, 'D_CREATE_AT' ) );
        $builder->set( 'D_CREATE_IP',               _elm( $param, 'D_CREATE_IP' ) );
        $builder->set( 'D_CREATE_MB_IDX',           _elm( $param, 'D_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }
    public function insertGoodsOptions( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        foreach( $param as $key => $val ){
            $builder->set( $key,                    $val );
        }

        $aResult                                    = $builder->insert();
        //echo $this->db->getLastQuery();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }
    public function updateGoodsOptions( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );

        foreach( $param as $key => $val ){
            if( $key == 'O_IDX' ){
                $builder->where( $key,              $val );
            }else{
                $builder->set( $key,                $val );
            }

        }

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function insertGoodsInIcons( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_IN_ICONS' );
        $builder->set( 'I_GOODS_IDX',               _elm( $param, 'I_GOODS_IDX' ) );
        $builder->set( 'I_ICONS_IDX',               _elm( $param, 'I_ICONS_IDX' ) );
        $builder->set( 'I_ICONS_PERIOD_START_AT',   _elm( $param, 'I_ICONS_PERIOD_START_AT' ) );
        $builder->set( 'I_ICONS_PERIOD_END_AT',     _elm( $param, 'I_ICONS_PERIOD_END_AT' ) );
        $builder->set( 'I_ICONS_GBN',               _elm( $param, 'I_ICONS_GBN' ) );
        $builder->set( 'I_CREATE_AT',               _elm( $param, 'I_CREATE_AT' ) );
        $builder->set( 'I_CREATE_IP',               _elm( $param, 'I_CREATE_IP' ) );
        $builder->set( 'I_CREATE_MB_IDX',           _elm( $param, 'I_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;


    }
}