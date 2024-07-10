<?php
namespace App\Models;

use CodeIgniter\Model;

class LicenseModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->db                                   = \Config\Database::connect();
    }


    public function getEduCategory()
    {
        $aReturn                                     = [];

        $builder                                     = $this->db->table('EDU_CATEGORY');
        $builder->where( 'C_STATUS', '2' );
        $query                                       = $builder->get();
        if ($this->db->affectedRows())
        {
            $result                                  = $query->getResultArray();
            $aReturn                                 = $result;
        }
        return $aReturn;
    }

    public function getUserLicenseCateWaitList( $param = [] )
    {
        $aReturn                                     = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                     = $this->db->table('COUPON_ISSUE CI');
        $builder->select( 'CI.*' );
        $builder->select( 'CP.CP_L_CATE_IDX, CP.CP_LECTURE_IDX, CP.CP_STATUS, CP.CP_START_DATE, CP.CP_END_DATE, CP.CP_USE_DAYS' );
        $builder->select('EDC.C_NAME');
        $builder->join( 'COUPON CP', 'CI.I_CP_IDX = CP.CP_IDX', 'INNER' );
        $builder->join( 'EDU_CATEGORY EDC', 'CP.CP_L_CATE_IDX = EDC.C_IDX', 'LEFT' );

        $builder->where( 'CI.I_BR_MB_IDX', _elm( $param, 'I_BR_MB_IDX' ) );
        $builder->where( 'CP.CP_L_CATE_IDX', _elm( $param, 'CP_L_CATE_IDX' ) );
        $builder->where( 'CI.I_STATUS <>', '2' );
        $builder->where( 'CI.I_START_DATE IS NULL' );
        $builder->where( 'CI.I_END_DATE IS NULL' );
        $builder->where( 'CI.I_USE_DATE IS NULL' );

        $builder->orderBy('CI.I_REG_DATE', 'DESC');

        $query                                       = $builder->get();

        if ($this->db->affectedRows())
        {
            $result                                  = $query->getResultArray();
            $aReturn                                 = $result;
        }
        //echo $this->db->getLastQuery();

        return $aReturn;
    }


    public function getUserLicenseCatePickUseList( $param = [] )
    {
        $aReturn                                     = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                     = $this->db->table('COUPON_ISSUE CI');
        $builder->select( 'CI.*' );
        $builder->select( 'CP.CP_L_CATE_IDX, CP.CP_LECTURE_IDX, CP.CP_STATUS, CP.CP_START_DATE, CP.CP_END_DATE, CP.CP_USE_DAYS' );
        $builder->select('EDC.C_NAME');
        $builder->join( 'COUPON CP', 'CI.I_CP_IDX = CP.CP_IDX', 'INNER' );
        $builder->join( 'EDU_CATEGORY EDC', 'CP.CP_L_CATE_IDX = EDC.C_IDX', 'LEFT' );

        $builder->where( 'CI.I_BR_MB_IDX', _elm( $param, 'I_BR_MB_IDX' ) );
        $builder->where( 'CP.CP_L_CATE_IDX', _elm( $param, 'CP_L_CATE_IDX' ) );
        $builder->where( 'CI.I_STATUS <>', '2' );
        $builder->where('CP_LECTURE_IDX <>' , '0');

        $builder->where( 'CI.I_END_DATE >= ', 'DATE(NOW())', false );

        $builder->orderBy('CI.I_REG_DATE', 'DESC');

        $query                                       = $builder->get();

        if ($this->db->affectedRows())
        {
            $result                                  = $query->getResultArray();
            $aReturn                                 = $result;
        }
        return $aReturn;
    }



    public function getUserLicenseCateAllUseList( $param = [] )
    {
        $aReturn                                     = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                     = $this->db->table('COUPON_ISSUE CI');
        $builder->select( 'CI.*' );
        $builder->select( 'CP.CP_L_CATE_IDX, CP.CP_LECTURE_IDX, CP.CP_STATUS, CP.CP_START_DATE, CP.CP_END_DATE, CP.CP_USE_DAYS' );
        $builder->select('EDC.C_NAME');
        $builder->join( 'COUPON CP', 'CI.I_CP_IDX = CP.CP_IDX', 'INNER' );
        $builder->join( 'EDU_CATEGORY EDC', 'CP.CP_L_CATE_IDX = EDC.C_IDX', 'LEFT' );

        $builder->where( 'CI.I_BR_MB_IDX', _elm( $param, 'I_BR_MB_IDX' ) );
        $builder->where( 'CP.CP_L_CATE_IDX', _elm( $param, 'CP_L_CATE_IDX' ) );
        $builder->where( 'CI.I_STATUS <>', '2' );
        $builder->where('CP_LECTURE_IDX' , '0');

        $builder->where( 'CI.I_END_DATE >= ', 'DATE(NOW())', false );

        $builder->orderBy('CI.I_REG_DATE', 'DESC');

        $builder->limit(1);

        $query                                       = $builder->get();

        if ($this->db->affectedRows())
        {
            $result                                  = $query->getResultArray();
            $aReturn                                 = $result;
        }
        return $aReturn;
    }


    public function getUserLicenseCatePickNotUseList( $param = [] )
    {
        $aReturn                                     = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                     = $this->db->table('COUPON_ISSUE CI');
        $builder->select( 'CI.*' );
        $builder->select( 'CP.CP_L_CATE_IDX, CP.CP_LECTURE_IDX, CP.CP_STATUS, CP.CP_START_DATE, CP.CP_END_DATE, CP.CP_USE_DAYS' );
        $builder->select('EDC.C_NAME');
        $builder->join( 'COUPON CP', 'CI.I_CP_IDX = CP.CP_IDX', 'INNER' );
        $builder->join( 'EDU_CATEGORY EDC', 'CP.CP_L_CATE_IDX = EDC.C_IDX', 'LEFT' );
        $builder->where( 'CI.I_BR_MB_IDX', _elm( $param, 'I_BR_MB_IDX' ) );
        $builder->where( 'CP.CP_L_CATE_IDX', _elm( $param, 'CP_L_CATE_IDX' ) );
        $builder->where( 'CI.I_STATUS <>', '2' );
        $builder->where('CP_LECTURE_IDX <>' , '0');

        $builder->where( 'CI.I_END_DATE IS NULL' );

        $builder->orderBy('CI.I_REG_DATE', 'DESC');

        $query                                       = $builder->get();

        if ($this->db->affectedRows())
        {
            $result                                  = $query->getResultArray();
            $aReturn                                 = $result;
        }
        return $aReturn;
    }



    public function getUserLicenseCateAllNotUseList( $param = [] )
    {
        $aReturn                                     = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                     = $this->db->table('COUPON_ISSUE CI');
        $builder->select( 'CI.*' );
        $builder->select( 'CP.CP_L_CATE_IDX, CP.CP_LECTURE_IDX, CP.CP_STATUS, CP.CP_START_DATE, CP.CP_END_DATE, CP.CP_USE_DAYS' );
        $builder->select('EDC.C_NAME');
        $builder->join( 'COUPON CP', 'CI.I_CP_IDX = CP.CP_IDX', 'INNER' );
        $builder->join( 'EDU_CATEGORY EDC', 'CP.CP_L_CATE_IDX = EDC.C_IDX', 'LEFT' );
        $builder->where( 'CI.I_BR_MB_IDX', _elm( $param, 'I_BR_MB_IDX' ) );
        $builder->where( 'CP.CP_L_CATE_IDX', _elm( $param, 'CP_L_CATE_IDX' ) );

        $builder->where('CP_LECTURE_IDX' , 0);
        $builder->groupStart();
        $builder->where( 'CI.I_STATUS <>', 2 );
        $builder->orWhere('CI.I_STATUS IS NULL');
        $builder->groupEnd();

        $builder->where( 'CI.I_END_DATE IS NULL ');

        $builder->orderBy('CI.I_REG_DATE', 'DESC');

        $builder->limit(1);

        $query                                       = $builder->get();

        if ($this->db->affectedRows())
        {
            $result                                  = $query->getResultArray();
            $aReturn                                 = $result;
        }

        return $aReturn;
    }

    public function getUserLicenseUseList( $param = [] )
    {

        $aReturn                                     = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                     = $this->db->table('COUPON_ISSUE CI');
        $builder->select( 'CI.*' );
        $builder->select( 'CP.CP_L_CATE_IDX, CP.CP_LECTURE_IDX, CP.CP_STATUS, CP.CP_START_DATE, CP.CP_END_DATE, CP.CP_USE_DAYS' );
        $builder->join( 'COUPON CP', 'CI.I_CP_IDX = CP.CP_IDX', 'INNER' );
        $builder->where( 'CI.I_BR_MB_IDX', _elm( $param, 'I_BR_MB_IDX' ) );
        $builder->where( 'CP.CP_L_CATE_IDX', _elm( $param, 'CP_L_CATE_IDX' ) );
        $builder->where( 'CI.I_STATUS <>', '2' );

        $builder->where( 'CI.I_END_DATE >= ', 'now()', false );

        $builder->orderBy('CI.I_REG_DATE', 'DESC');
        $query                                       = $builder->get();

        if ($this->db->affectedRows())
        {
            $result                                  = $query->getResultArray();
            $aReturn                                 = $result;
        }

        return $aReturn;
    }

    public function getUserLicenseNotUseList( $param = [] )
    {

        $aReturn                                     = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                     = $this->db->table('COUPON_ISSUE CI');
        $builder->select( 'CI.*' );
        $builder->select( 'CP.CP_L_CATE_IDX, CP.CP_LECTURE_IDX, CP.CP_STATUS, CP.CP_START_DATE, CP.CP_END_DATE, CP.CP_USE_DAYS' );
        $builder->join( 'COUPON CP', 'CI.I_CP_IDX = CP.CP_IDX', 'INNER' );
        $builder->where( 'CI.I_BR_MB_IDX', _elm( $param, 'I_BR_MB_IDX' ) );
        $builder->where( 'CP.CP_L_CATE_IDX', _elm( $param, 'CP_L_CATE_IDX' ) );
        $builder->where( 'CI.I_STATUS <>', '2' );

        $builder->where( 'CI.I_END_DATE IS NULL' );
        $builder->limit(1);



        $builder->orderBy('CI.I_REG_DATE', 'DESC');
        $query                                       = $builder->get();

        if ($this->db->affectedRows())
        {
            $result                                  = $query->getResultArray();
            $aReturn                                 = $result;
        }
        return $aReturn;
    }

    public function licenseUseRegist( $param = [] )
    {
        $aReturn                                     = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                     = $this->db->table('COUPON_ISSUE');

        foreach( $param as $key => $value ){
            if( $key == 'I_IDX' ){
                $builder->where( $key , $value );
            }else{
                $builder->set( $key, $value );
            }
        }

        $aReturn                                     = $builder->update();

        return $aReturn;
    }

}

