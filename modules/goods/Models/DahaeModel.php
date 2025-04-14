<?php
namespace Module\goods\Models;

use Config\Services;
use CodeIgniter\Model;

class DahaeModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }

    public function goodsDataByDahaeCode( $d_code )
    {
        $aReturn                                    = [];
        if( empty( $d_code ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS' );
        $builder->where( 'G_DAHAE_P_CODE',          $d_code);

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function deleteOptionsData( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        $builder->where( 'O_PRD_BARCODE', _elm( $param, 'O_PRD_BARCODE' ) );
        $aReturn                                    = $builder->delete();
        echo $this->db->getLastQuery();
        return $aReturn;
    }

    public function updateOptionValues( $param = [] )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS_OPTIONS' );
        $builder->where( 'O_PRD_BARCODE', _elm( $param, 'O_PRD_BARCODE' ) );
        $builder->set( 'O_VALUES', _elm( $param, 'O_VALUES' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function sameChk( $code )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'GOODS' );
        $builder->where( 'G_DAHAE_P_CODE',          $code );

        return $builder->countAllResults(false);
    }

    public function updateRealGoodsContents( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS' );
        $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );
        $builder->set( 'G_CONTENT_PC', _elm( $param, 'G_CONTENT_PC' ) );
        if( empty( _elm( $param, 'G_CONTENT_MOBILE' ) ) === false ){
            $builder->set( 'G_CONTENT_MOBILE', _elm( $param, 'G_CONTENT_MOBILE' ) );
        }

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getRealGoodsLists( $param = [] )
    {

        $aReturn                                    = [
            'lists'                                 => [],
            'total_count'                           => 0,
        ];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'GOODS' );
        $builder->select( 'G_IDX,G_CONTENT_PC, G_CONTENT_MOBILE' );

        if( empty( _elm( $param, 'G_IDX' ) ) === false ){
            $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );
        }
        $builder->like( 'G_CONTENT_PC', 'iframe', 'both' );

        $builder->orderBy( 'G_IDX', 'ASC' );

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


    public function truncateTmp()
    {
        $aReturn                                    = false;
        $aReturn = $this->db->table('__GOODS_IMAGES_122301')->truncate();

        return $aReturn;
    }

    public function insertSamiTmpImage( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '_GOODS_IMAGES' );
        $aResult                                    = $builder->insert( $param );
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function getAllTmpImage()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( '__GOODS_IMAGES_122301' );
        $builder->select( 'I_SORT, I_GOODS_IDX, I_IMG_NAME, I_IMG_PATH, I_IMG_SIZE, I_IMG_VIEW_SIZE, I_IMG_EXT, I_IMG_MIME_TYPE, I_IS_ORIGIN, I_CREATE_AT, I_CREATE_IP, I_CREATE_MB_IDX, I_UPDATE_AT, I_UPDATE_IP, I_UPDATE_MB_IDX' );
        $builder->orderBy( 'I_IDX', 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getEmptyGoodsConetent()
    {
        $aReturn = [];
        $builder = $this->db->table('GOODS');

        // 조건을 그룹화하여 정확한 우선순위 설정
        $builder->groupStart()
                ->where('TRIM(G_CONTENT_PC) ', '')
               // ->orWhere('G_CONTENT_PC IS NULL')
                ->groupEnd();

        $query = $builder->get();

        // 쿼리 디버깅
        echo $this->db->getLastQuery();

        // SELECT 결과 가져오기
        $aReturn = $query->getResultArray();

        return $aReturn;

    }


    public function getGoodsLists( $param = [] )
    {
        $aReturn                                    = [
            'lists'                                 => [],
            'total_count'                           => 0,
        ];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '__GOODS_122301' );
        $builder->select( 'G_IDX,G_GODO_GOODSNO' );
        //$builder->where( 'G_PRID IS NULL');
        if( empty( _elm( $param, 'G_IDX' ) ) === false ){
            $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );
        }

        $builder->orderBy( 'G_IDX', 'ASC' );

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
        echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }

        return $aReturn;
    }

    public function insertIConData( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '__GOODS_IN_ICONS_122301' );
        $builder->set( 'I_GOODS_IDX', _elm( $param, 'I_GOODS_IDX' ) );
        $builder->set( 'I_ICONS_IDX', _elm( $param, 'I_ICONS_IDX' ) );
        $builder->set( 'I_ICONS_GBN', _elm( $param, 'I_ICONS_GBN' ) );
        $builder->set( 'I_CREATE_AT', _elm( $param, 'I_CREATE_AT' ) );
        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function getIconMatchCd( $iconCd )
    {
        $aReturn                                    = [];
        if( empty( $iconCd ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '_es_icon_match' );
        $builder->select( 'matchIdx' );
        $builder->where( 'iconCd', $iconCd );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aResult                                = $query->getRowArray();
            $aReturn                                = _elm( $aResult, 'matchIdx' );
        }
        return $aReturn;

    }
    public function getUniquePrid($prid)
    {
        if (empty($prid)) {
            return false; // 입력값이 없을 경우 false 반환
        }

        // 트랜잭션 외부에서 고유성을 확인하도록 설정

        $builder = $this->db->table('__GOODS_122301');

        // 고유성 확인
        $count = $builder->where('G_PRID', $prid)->countAllResults();

        return $count > 0; // 중복된 경우 true 반환
    }

    public function insertAddRequiredInfo($param = [])
    {
        $aReturn                                    = [
            'status' => false,
            'inserted_count' => 0,
            'error' => ''
        ];

        // 데이터가 비어있으면 반환
        if (empty($param)) {
            $aReturn['error']                       = 'No data provided';
            return $aReturn;
        }

        // 데이터베이스 빌더 초기화
        $builder                                    = $this->db->table('__GOODS_ADD_REQUIRED_INFO_122301');


        try {
            // insertBatch 실행
            $insertedCount                          = $builder->insertBatch($param);

            // 결과 반환
            if ($insertedCount) {
                $aReturn['status']                  = true;
                $aReturn['inserted_count']          = $insertedCount;
            } else {
                $aReturn['error']                   = 'Insert batch failed';
            }
        } catch (Exception $e) {
            // 예외 처리
            $aReturn['error']                       = $e->getMessage();
        }

        return $aReturn;
    }

    public function setGoodsPrid( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '__GOODS_122301' );
        $builder->set( 'G_PRID', _elm( $param, 'G_PRID' ) );
        $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );

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

    public function getGodoImages( $godoIdx )
    {
        $aReturn                                    = [];
        if( empty( $godoIdx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '_es_goodsImage' );
        $builder->where( 'goodsNo', $godoIdx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function updateTmpGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '__GOODS_122301' );

        foreach( $param as $key => $val ){
            if( $key == 'G_IDX' ){
                $builder->where( $key, $val );
            }else{
                $builder->set( $key,  $val );
            }
        }
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function insertTmpGoods( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '__GOODS_122301' );

        foreach( $param as $key => $val ){
            $builder->set( $key,  $val );
        }

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }

    public function insertTmpGoodsOptions( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '__GOODS_OPTIONS_122301' );

        foreach( $param as $key => $val ){
            $builder->set( $key,  $val );
        }

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }
    public function getEsSeoInfo( $sno )
    {
        $aReturn                                    = [];
        if( empty( $sno ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( '_es_seoTag' );
        $builder->where( 'sno',                     $sno );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getOptionGroupColor( $optionName )
    {
        $aReturn                                    = [];
        if( empty( $optionName ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '_D_OPTION_CODES' );
        $builder->select( 'd_result' );
        $builder->where( 'd_option_name',           $optionName );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aResult                                = $query->getRowArray();
            $aReturn                                = _elm( $aResult, 'd_result' );
        }
        return $aReturn;

    }

    public function getOptionGroupName( $optionName )
    {
        $aReturn                                    = [];
        if( empty( $optionName ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '_D_OPTION_CODES' );
        $builder->select( 'd_option_g_name' );
        $builder->where( 'd_option_name',           $optionName );
        $builder->orderBy( 'idx', 'asc' );
        $builder->limit(1);

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aResult                                = $query->getRowArray();
            $aReturn                                = _elm( $aResult, 'd_option_g_name' );
        }
        return $aReturn;

    }

    public function getMemberInfo( $memId )
    {
        $aReturn = [];
        $builder = $this->db->table( '_es_member' );
        $builder->select('*');
        $builder->where( 'memId', $memId );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        //echo $this->db->getLastQuery();
        return $aReturn;

    }

    public function getEsGoodsByIdx( $idx ){
        $aReturn                                    = [];
        if( empty( $idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( '_es_goods' );
        $builder->where( 'goodsNo',                 $idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function updateRealGoodsContent( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'GOODS' );

        $builder->set( 'G_CONTENT_PC', _elm( $param, 'G_CONTENT_PC' ) );
        $builder->set( 'G_CONTENT_MOBILE', _elm( $param, 'G_CONTENT_MOBILE' ) );
        $builder->where( 'G_IDX', _elm( $param, 'G_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }


}