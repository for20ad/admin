<?php
#------------------------------------------------------------------
# AuthModel.php
# 인증관련 모델
# 김우진
# 2024-05-14 16:32:47
# @Desc : 인증과 관련된 모든 쿼리
#------------------------------------------------------------------
namespace Module\auth\Models;

use Config\Services;
use CodeIgniter\Model;

class AuthModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }

    public function setAuthData( $param = [] )
    {
        $aReturn = false;
        $builder = $this->db->table('MEMBER_AUTH_KEY');
        $builder->set( 'MB_IDX', _elm( $param, 'MB_IDX' ) );
        $builder->set( 'MB_TYPE', _elm( $param, 'MB_TYPE' ) );
        $builder->set( 'MB_AK_TYPE', _elm( $param, 'MB_AK_TYPE' ) );
        $builder->set( 'MB_AK_KEY', _elm( $param, 'MB_AK_KEY' ) );
        $builder->set( 'MB_AK_CREATE_AT', _elm( $param, 'MB_AK_CREATE_AT' ) );
        $builder->set( 'MB_AK_CREATE_IP', _elm( $param, 'MB_AK_CREATE_IP' ) );
        $builder->set( 'MB_AK_EXPIRE_AT', _elm( $param, 'MB_AK_EXPIRE_AT' ) );

        $aReturn = $builder->insert();
        //echo $this->db->getLastQuery();
        return $aReturn;
    }

    public function getNoExpireAuthKey( $param = [] )
    {
        $aReturn = [];
        if( empty( _elm( $param, 'MB_IDX' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'MEMBER_AUTH_KEY' );
        $builder->select('*');
        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );
        #------------------------------------------------------------------
        # TODO: 전송과 입력시간등 계산하여 현재의 20초 후 시간과 비교
        #------------------------------------------------------------------
        $builder->where( "date_format( MB_AK_EXPIRE_AT , '%Y-%m-%d %H:%i:%s' ) > ",  date('Y-m-d H:i:s', strtotime('+20 seconds') ) );
        $builder->where( 'MB_AK_USE_AT IS NULL' );
        $builder->orderBy('MB_AK_IDX', 'DESC');

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        //echo $this->db->getLastQuery();
        return $aReturn;

    }

    public function updateAuthData( $param = [] )
    {
        $aReturn = false;
        if( empty( _elm( $param, 'MB_AK_IDX' ) ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table('MEMBER_AUTH_KEY');
        if( empty( _elm($param, 'MB_AK_USE_AT') ) === true ){
            $builder->set( 'MB_AK_EXPIRE_AT', date('Y-m-d H:i:s', strtotime( '+3 minutes' ) ) );
            $builder->set( 'MB_AK_RESEND_CNT', _elm( $param, 'MB_AK_RESEND_CNT' ) );
        }else{
            $builder->set( 'MB_AK_USE_AT', _elm( $param, 'MB_AK_USE_AT' ) );
            $builder->set( 'MB_AK_USE_IP', _elm( $param, 'MB_AK_USE_IP' ) );
        }
        $builder->where( 'MB_AK_IDX', _elm( $param, 'MB_AK_IDX' ) );



        $aReturn = $builder->update();
        return $aReturn;
    }

    public function setExchangeData( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }


        $builder = $this->db->table('EXCHANGE_DATA');
        foreach( $param as $key => $val )
        {
            $builder->set( $key, $val);
        }

        $aReturn = $builder->insert();
        return $aReturn;
    }

    public function sameChecked( $param = [] )
    {
        $aReturn = [];
        $builder = $this->db->table( 'EXCHANGE_DATA' );
        $builder->select('*');
        $builder->where( 'F_DATE', _elm($param, 'F_DATE') );
        $builder->where( 'F_LOC', _elm($param, 'F_LOC') );

        return $builder->countAllResults(false);
    }
}