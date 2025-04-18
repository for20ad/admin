<?php
namespace Module\membership\Models;

use Config\Services;
use CodeIgniter\Model;

class MembershipModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function findMemeberData( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->where( 'MB_NM', _elm( $param, 'MB_NM' ) );
        $builder->orWhere( 'MB_MOBILE_NUM', _elm( $param, 'MB_MOBILE_NUM' ) );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getMemberInfoByCi( $ci )
    {
        $aReturn                                    = [];
        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->where( 'MB_CI',                   $ci );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function insertMemberData( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->set( 'MB_USERID', _elm( $param, 'MB_USERID' ) );


        $builder->set( 'MB_PASSWORD', _elm( $param, 'MB_PASSWORD') );
        $builder->set( 'MB_NM', _elm( $param, 'MB_NM' ) );
        $builder->set('MB_MOBILE_NUM', _elm($param, 'MB_MOBILE_NUM'));
        $builder->set('MB_GRADE_IDX', _elm($param, 'MB_GROUP_IDX', null, true));
        $builder->set('MB_STATUS', _elm($param, 'MB_STATUS'));
        $builder->set('MB_COM_NAME', _elm($param, 'MB_COM_NAME', null, true));
        $builder->set('MB_COM_CEO', _elm($param, 'MB_COM_CEO', null, true));
        $builder->set('MB_BUSINESS_NUMBER', _elm($param, 'MB_BUSINESS_NUMBER', null, true));
        $builder->set('MB_COM_SEVICE', _elm($param, 'MB_COM_SEVICE', null, true));
        $builder->set('MB_COMP_ITEM', _elm($param, 'MB_COMP_ITEM', null, true));
        $builder->set('MB_COM_ZIPCD', _elm($param, 'MB_COM_ZIPCD', null, true));
        $builder->set('MB_COM_ADDR', _elm($param, 'MB_COM_ADDR', null, true));
        $builder->set('MB_COM_ADDR_SUB', _elm($param, 'MB_COM_ADDR_SUB', null, true));
        $builder->set('MB_BIRTH', _elm($param, 'MB_BIRTH', null, true));
        $builder->set('MB_GENDER', _elm($param, 'MB_GENDER', null, true));
        $builder->set('MB_HEIGHT', _elm($param, 'MB_HEIGHT', null, true));
        $builder->set('MB_WEIGHT', _elm($param, 'MB_WEIGHT', null, true));
        $builder->set('MB_FOOT_SIZE', _elm($param, 'MB_FOOT_SIZE', null, true));
        $builder->set('MB_WAIST', _elm($param, 'MB_WAIST', null, true));
        $builder->set('MB_ADM_MEMO', _elm($param, 'MB_ADM_MEMO', null, true));
        $builder->set('MB_REG_AT', _elm($param, 'MB_REG_AT', null, true));

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function updateMemberData( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->set( 'MB_USERID', _elm( $param, 'MB_USERID' ) );

        if( empty( _elm( $param, 'MB_PASSWORD' ) ) === false ){
            $builder->set( 'MB_PASSWORD', _elm( $param, 'MB_PASSWORD' ) );
        }
        if( empty( _elm( $param, 'MB_NM' ) ) === false ){
        $builder->set( 'MB_NM', _elm( $param, 'MB_NM' ) );
        }
        if (!empty(_elm($param, 'MB_MOBILE_NUM'))) {
            $builder->set('MB_MOBILE_NUM', _elm($param, 'MB_MOBILE_NUM'));
        }
        if (!empty(_elm($param, 'MB_GRADE_IDX'))) {
            $builder->set('MB_GRADE_IDX', _elm($param, 'MB_GRADE_IDX'));
        }
        if (!empty(_elm($param, 'MB_STATUS'))) {
            $builder->set('MB_STATUS', _elm($param, 'MB_STATUS'));
        }
        if (!empty(_elm($param, 'MB_COM_NAME'))) {
            $builder->set('MB_COM_NAME', _elm($param, 'MB_COM_NAME'));
        }
        if (!empty(_elm($param, 'MB_COM_CEO'))) {
            $builder->set('MB_COM_CEO', _elm($param, 'MB_COM_CEO'));
        }
        if (!empty(_elm($param, 'MB_BUSINESS_NUMBER'))) {
            $builder->set('MB_BUSINESS_NUMBER', _elm($param, 'MB_BUSINESS_NUMBER'));
        }
        if (!empty(_elm($param, 'MB_COM_SEVICE'))) {
            $builder->set('MB_COM_SEVICE', _elm($param, 'MB_COM_SEVICE'));
        }
        if (!empty(_elm($param, 'MB_COMP_ITEM'))) {
            $builder->set('MB_COMP_ITEM', _elm($param, 'MB_COMP_ITEM'));
        }
        if (!empty(_elm($param, 'MB_COM_ZIPCD'))) {
            $builder->set('MB_COM_ZIPCD', _elm($param, 'MB_COM_ZIPCD'));
        }
        if (!empty(_elm($param, 'MB_COM_ADDR'))) {
            $builder->set('MB_COM_ADDR', _elm($param, 'MB_COM_ADDR'));
        }
        if (!empty(_elm($param, 'MB_COM_ADDR_SUB'))) {
            $builder->set('MB_COM_ADDR_SUB', _elm($param, 'MB_COM_ADDR_SUB'));
        }
        if (!empty(_elm($param, 'MB_BIRTH'))) {
            $builder->set('MB_BIRTH', _elm($param, 'MB_BIRTH'));
        }
        if (!empty(_elm($param, 'MB_GENDER'))) {
            $builder->set('MB_GENDER', _elm($param, 'MB_GENDER'));
        }
        if (!empty(_elm($param, 'MB_HEIGHT'))) {
            $builder->set('MB_HEIGHT', _elm($param, 'MB_HEIGHT'));
        }
        if (!empty(_elm($param, 'MB_WEIGHT'))) {
            $builder->set('MB_WEIGHT', _elm($param, 'MB_WEIGHT'));
        }
        if (!empty(_elm($param, 'MB_FOOT_SIZE'))) {
            $builder->set('MB_FOOT_SIZE', _elm($param, 'MB_FOOT_SIZE'));
        }
        if (!empty(_elm($param, 'MB_WAIST'))) {
            $builder->set('MB_WAIST', _elm($param, 'MB_WAIST'));
        }
        if (!empty(_elm($param, 'MB_ADM_MEMO'))) {
            $builder->set('MB_ADM_MEMO', _elm($param, 'MB_ADM_MEMO'));
        }


        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function updateDefaultDeliveryAddr( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_DELIVERY_ADDR' );
        foreach( $param as $key => $val ){
            if( $key == 'D_IDX' ){
                $builder->where( 'D_IDX', _elm( $param, 'D_IDX' ) );
            }else{
                $builder->set( $key, $val );
            }
        }
        $aReturn                                    = $builder->update();
        return $aReturn;
    }
    public function insertDefaultDeliveryAddr( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBER_DELIVERY_ADDR' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }


    public function getDefaultDeliveryAddr( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_DELIVERY_ADDR' );
        $builder->where( 'D_MB_IDX', _elm( $param, 'D_MB_IDX' ) );
        $builder->where( 'D_DEFAULT', 'Y');
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }
    public function getSimpleSearch( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->select( 'MB_IDX, MB_USERID,MB_NM,MB_MOBILE_NUM' );
        $builder->like( 'MB_USERID',                _elm( $param, 'MB_USERID' ), 'both' );
        $builder->orLike( 'MB_NM',                  _elm( $param, 'MB_NM' ), 'both' );
        if( empty( _elm( $param, 'MB_MOBILE_NUM' ) ) === false ){
            $builder->orLike( 'MB_MOBILE_NUM',          _elm( $param, 'MB_MOBILE_NUM' ), 'both' );
        }
        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getMembershipGrade()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'MEMBER_GRADE' );
        $builder->select( 'G_IDX, G_NAME, G_DC_RATE, G_DC_UNIT' );
        $builder->orderBy( 'G_SORT', 'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;
    }


    public function getMemberShipMileageLists( $param = [] )
    {
        $aReturn                                    = [
            'lists'                                 => [],
            'total_count'                           => 0,
        ];

        if ( empty( $param ) === true ) {
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBERSHIP MB' );

        $builder->select( 'MB.MB_IDX, MB.MB_USERID, MB.MB_NM, MB.MB_NICK_NM, MB.MB_COM_NAME, MB.MB_MOBILE_NUM, MB.MB_GRADE_IDX, MB.MB_STATUS, MB.MB_REG_AT, MB.MB_LAST_LOGIN' );
        $builder->select( 'MS.ADD_MILEAGE, MS.USE_MILEAGE, MS.DED_MILEAGE, MS.EXP_MILEAGE' );
        $builder->join( 'MEMBER_MILEAGE_SUMMERY MS', 'MB.MB_IDX = MS.S_MB_IDX', 'left' );

        if (!empty( _elm( $param, 'MB_STATUS' ) ) ) {
            $builder->where('MB.MB_STATUS', _elm($param, 'MB_STATUS'));
        }

        if (!empty($param['MB_USERID'])) {
            $builder->where('MB.MB_USERID', _elm( $param, 'MB_USERID' ) );
        }
        if (!empty($param['MB_NM'])) {
            $builder->like('MB.MB_NM', _elm( $param, 'MB_NM' ), 'both' );
        }
        if (!empty($param['MB_MOBILE_NUM'])) {
            $builder->where('MB.MB_MOBILE_NUM', _elm( $param, 'MB_MOBILE_NUM' ) );
        }

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

    public function getMemberShipLists( $param = [] )
    {
        $aReturn                                    = [
            'lists'                                 => [],
            'total_count'                           => 0,
        ];

        if ( empty( $param ) === true ) {
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBERSHIP MB' );

        $builder->select( 'MB.MB_IDX, MB.MB_USERID, MB.MB_NM, MB.MB_NICK_NM, MB.MB_COM_NAME, MB.MB_GRADE_IDX, MB.MB_STATUS, MB.MB_REG_AT, MB.MB_LAST_LOGIN' );
        $builder->select( 'GR.G_NAME' );
        $builder->select( 'MS.ADD_MILEAGE, MS.USE_MILEAGE, MS.DED_MILEAGE, MS.EXP_MILEAGE' );
        $builder->join( 'MEMBER_GRADE GR', 'MB.MB_GRADE_IDX= GR.G_IDX', 'left' );
        $builder->join( 'MEMBER_MILEAGE_SUMMERY MS', 'MB.MB_IDX = MS.S_MB_IDX', 'left' );

        if( !empty( _elm( $param, 'MB_GRADE_IDX' ) ) ){
            $builder->where('MB.MB_GRADE_IDX', _elm( $param, 'MB_GRADE_IDX' ) );
        }
        if (!empty( _elm( $param, 'MB_STATUS' ) ) ) {
            $builder->where('MB.MB_STATUS', _elm($param, 'MB_STATUS'));
        }
        if (!empty($param['START_DATE']) && !empty($param['END_DATE'])) {
            $builder->where('MB.MB_REG_AT >= ', _elm( $param, 'START_DATE' ) );
            $builder->where('MB.MB_REG_AT <= ', _elm( $param, 'END_DATE' ) );
        }


        if (!empty($param['MB_USERID'])) {
            $builder->where('MB.MB_USERID', _elm( $param, 'MB_USERID' ) );
        }
        if (!empty($param['MB_NM'])) {
            $builder->like('MB.MB_NM', _elm( $param, 'MB_NM' ), 'both' );
        }
        if (!empty($param['MB_MOBILE_NUM'])) {
            $builder->where('MB.MB_MOBILE_NUM', _elm( $param, 'MB_MOBILE_NUM' ) );
        }

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

    public function insertCounsel( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBERSHIP_COUNSEL' );
        foreach( $param as $key => $val ){
            $builder->set( $key,                    $val );
        }
        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }
    public function deleteCounsel( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBERSHIP_COUNSEL' );
        $builder->where( 'C_IDX',                   _elm( $param, 'C_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;

    }
    public function getLatestCounselListByUserIdx( $mb_idx )
    {
        $aReturn                                    = [];
        if( empty( $mb_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBERSHIP_COUNSEL MC' );
        $builder->select( 'MC.*' );
        $builder->select('ADM.MB_USERNAME as C_WRITER_NAME');
        $builder->join('ADMIN_MEMBER ADM', 'MC.C_CREATE_MB_IDX = ADM.MB_IDX', 'left');
        $builder->where( 'C_MB_IDX',                $mb_idx);
        $builder->orderBy( 'C_CREATE_AT', 'DESC' );

        $builder->limit(5);

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;


    }
    public function getUserCounselCount( $mb_idx )
    {
        $aReturn                                    = [];
        if( empty( $mb_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBERSHIP_COUNSEL' );

        $builder->select( 'count( C_IDX ) as total_counsel_count' );
        $builder->where( 'C_MB_IDX',                $mb_idx);

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getCounselDataByIdx( $c_idx )
    {
        $aReturn                                    = [];
        if( empty( $c_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'MEMBERSHIP_COUNSEL' );
        $builder->where( 'C_IDX',                   $c_idx );
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getCounselLists($mb_idx)
    {
        $aReturn = [];
        if (empty($mb_idx) === true) {
            return $aReturn;
        }

        $builder = $this->db->table('MEMBERSHIP_COUNSEL MC');
        $builder->select('DATE(MC.C_CREATE_AT) as counsel_date');
        $builder->select('MC.*');
        $builder->select('ADM.MB_USERNAME as C_WRITER_NAME');
        $builder->join('ADMIN_MEMBER ADM', 'MC.C_CREATE_MB_IDX = ADM.MB_IDX', 'left');
        $builder->where('MC.C_MB_IDX', $mb_idx);
        $builder->orderBy('MC.C_CREATE_AT', 'ASC'); // 최신순 정렬

        $query = $builder->get();
        if ($this->db->affectedRows()) {
            $rawResults = $query->getResultArray();

            // 날짜별 그룹핑 처리
            foreach ($rawResults as $row) {
                $date = $row['counsel_date']; // 그룹핑 기준 날짜
                if (!isset($aReturn[$date])) {
                    $aReturn[$date] = [];
                }
                $aReturn[$date][] = $row; // 해당 날짜에 속하는 데이터 추가
            }
        }

        return $aReturn;
    }


    public function getMembershipDataByIdx( $mb_idx )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'MEMBERSHIP MB' );
        $builder->select( 'MB.*' );
        $builder->select( 'GR.G_NAME' );
        $builder->select( 'DV.D_ZIP_CD, DV.D_ADDR, DV.D_ADDR_SUB' );
        $builder->join( 'MEMBER_GRADE GR', 'MB.MB_GRADE_IDX= GR.G_IDX', 'left' );
        $builder->join( 'MEMBER_DELIVERY_ADDR DV', 'MB.MB_IDX=DV.D_MB_IDX AND DV.D_DEFAULT = \'Y\'', 'left' );

        $builder->where( 'MB.MB_IDX', $mb_idx );
        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function updateMembershipStatus( $param = [] )
    {
        $aReturn                                    = false;

        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->set( 'MB_STATUS', _elm( $param, 'MB_STATUS' ) );
        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;
    }

    public function getWaitMembershipMembers()
    {
        $aReturn                                    = 0;

        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->select( 'COUNT( MB_IDX ) CNT' );
        $builder->where( 'MB_STATUS', 1 );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aResult                                = $query->getRowArray();
            $aReturn                                = _elm( $aResult, 'CNT' );
        }
        return $aReturn;

    }
    public function setPurchaseAmt( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->set( 'MB_SALES_CNT',              'MB_SALES_CNT+'._elm( $param, 'MB_SALES_CNT' ), false );
        $builder->set( 'MB_SALES_AMT',              'MB_SALES_AMT+'._elm( $param, 'MB_SALES_AMT' ), false );

        $builder->where( 'MB_IDX',                  _elm( $param, 'MB_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function ussetPurchaseAmt( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBERSHIP' );
        $builder->set( 'MB_SALES_CNT',              'MB_SALES_CNT-'._elm( $param, 'MB_SALES_CNT' ), false );
        $builder->set( 'MB_SALES_AMT',              'MB_SALES_AMT-'._elm( $param, 'MB_SALES_AMT' ), false );

        $builder->where( 'MB_IDX',                  _elm( $param, 'MB_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getMemberInfoByIdx( $mb_idx )
    {
        $aReturn = [];
        $builder = $this->db->table( 'MEMBERSHIP MB' );
        $builder->where( 'MB.MB_IDX',               $mb_idx );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }

        return $aReturn;

    }
    public function userPointSummery( $mb_idx )
    {
        $aReturn                                    = [];
        if( empty( $mb_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_MILEAGE_SUMMERY' );
        $builder->select( 'ADD_MILEAGE, USE_MILEAGE, DED_MILEAGE, EXP_MILEAGE, LAST_UPDATE_AT' );
        $builder->where( 'S_MB_IDX',                $mb_idx );
        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;


    }

    public function insertUserPointHistory( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_MILEAGE' );
        $builder->set( 'M_MB_IDX',                  _elm( $param, 'M_MB_IDX' ) );
        $builder->set( 'M_TYPE',                    _elm( $param, 'M_TYPE' ) );
        $builder->set( 'M_TYPE_CD',                 _elm( $param, 'M_TYPE_CD' ) );
        $builder->set( 'M_GBN',                     _elm( $param, 'M_GBN' ) );
        $builder->set( 'M_BEFORE_MILEAGE',          _elm( $param, 'M_BEFORE_MILEAGE' ) );
        $builder->set( 'M_AFTER_MILEAGE',           _elm( $param, 'M_AFTER_MILEAGE' ) );
        $builder->set( 'M_MILEAGE',                 _elm( $param, 'M_MILEAGE' ) );
        $builder->set( 'M_REASON_CD',               _elm( $param, 'M_REASON_CD' ) );
        $builder->set( 'M_REASON_MSG',              _elm( $param, 'M_REASON_MSG' ) );
        $builder->set( 'M_EXPIRE_DATE',             _elm( $param, 'M_EXPIRE_DATE' ) );
        $builder->set( 'M_CREATE_AT',               _elm( $param, 'M_CREATE_AT' ) );
        $builder->set( 'M_CREATE_IP',               _elm( $param, 'M_CREATE_IP' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function updateUserSummeryPoint( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'MEMBER_MILEAGE_SUMMERY' );
        $builder->where( 'S_MB_IDX',                _elm( $param, 'S_MB_IDX' ) );
        $builder->set( 'ADD_MILEAGE',               _elm( $param, 'ADD_MILEAGE' ) );
        $builder->set( 'USE_MILEAGE',               _elm( $param, 'USE_MILEAGE' ) );
        $builder->set( 'DED_MILEAGE',               _elm( $param, 'DED_MILEAGE' ) );
        $builder->set( 'EXP_MILEAGE',               _elm( $param, 'EXP_MILEAGE' ) );
        $builder->set( 'LAST_UPDATE_AT',            _elm( $param, 'LAST_UPDATE_AT' ) );
        $builder->set( 'LAST_UPDATE_IP',            _elm( $param, 'LAST_UPDATE_IP' ) );
        $builder->set( 'LAST_MB_IDX',               _elm( $param, 'LAST_MB_IDX' ) );
        $aReturn                                    = $builder->update();
        return $aReturn;
    }
}