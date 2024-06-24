<?php
#------------------------------------------------------------------
# MemberModel.php
# 회원모델
# 김우진
# 2024-05-10 14:13:00
# @Desc : 회원관련 모델
#------------------------------------------------------------------

namespace Module\comm\member\Models;

use Config\Services;
use CodeIgniter\Model;

class MemberModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }


    public function getMemberDefaultInfo( $param = [] )
    {
        $aReturn = [];
        $builder = $this->db->table( 'MEMBERSHIP MB' );
        $builder->select( 'MB.MB_IDX, MB.MB_USERID, MB.MB_NM' );
        $builder->where( 'MB.MB_IDX', _elm( $param, 'M_IDX' ) );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        //echo $this->db->getLastQuery();
        return $aReturn;

    }

    public function findUserById( $param = [] )
    {


        $aReturn             = [];
        if( empty( _elm( $param, 'u_id' ) ) === true ){
            return $aReturn;
        }

        if( empty( _elm( $param, 'u_pwd' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table('MEMBERSHIP MB');
        $builder->select( 'MB.MB_IDX, MB.MB_GROUP_IDX, MB.MB_USERID,MB.MB_PASSWORD, MB.MB_NM, MB.MB_NICK_NM, MB.MB_MOBILE_NUM, MB.MB_LOGIN_CNT, MB.MB_SALE_CNT, MB.MB_SALE_AMT' );
        $builder->select( 'MB.PASSWD_CHK' );
        $builder->select( 'MG.G_NAME,MG.G_ICON_PATH' );
        $builder->join( 'MEMBER_GRADE MG', 'MB.MB_GRADE_IDX = MG.G_IDX', 'left' );

        $builder->where( 'MB.MB_USERID', _elm( $param, 'u_id' ) );

        #------------------------------------------------------------------
        # TODO: snslogin의 경우 패스워드없이 로그인 진행한다.
        #------------------------------------------------------------------
        if( ( _elm( $param, 'u_pwd' ) != 'snsSignin' | _elm( $param, 'u_pwd' ) != 'jwtAuth' | _elm( $param, 'u_pwd' ) != 'findId' ) && _elm( $param, 'pass_login' ) !== true ){
            $builder->groupStart();
            $builder->where( 'MB.MB_PASSWORD', _elm( $param, 'u_pwd_enc' ) );
            $builder->orWhere('MB.MB_PASSWORD', "password('"._elm( $param, 'u_pwd' )."')", false);
            $builder->groupEnd();
        }

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        //echo $this->db->getLastQuery();
        return $aReturn;
    }

    public function findUserByMobile( $param = [] )
    {
        $aReturn             = [];
        if( empty( _elm( $param, 'mobileNo' ) ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'MEMBERSHIP MB' );

        $builder->select( 'MB.MB_IDX, MB.MB_GROUP_IDX, MB.MB_USERID,MB.MB_PASSWORD, MB.MB_NM, MB.MB_NICK_NM, MB.MB_MOBILE_NUM, MB.MB_LOGIN_CNT, MB.MB_SALE_CNT, MB.MB_SALE_AMT' );
        $builder->select( 'MB.PASSWD_CHK' );
        $builder->select( 'MG.G_NAME,MG.G_ICON_PATH' );
        $builder->join( 'MEMBER_GRADE MG', 'MB.MB_GRADE_IDX = MG.G_IDX', 'left' );

        $builder->where( 'MB.MB_MOBILE_NUM', _elm( $param, 'mobileNo' ) );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        //echo $this->db->getLastQuery();
        return $aReturn;
    }

    public function memberRegister( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( "MEMBERSHIP" );

        $builder->set( 'MB_USERID', _elm( $param, 'MB_USERID' ) );
        $builder->set( 'MB_PASSWORD', _elm( $param, 'MB_PASSWORD' ) );
        $builder->set( 'MB_NM', _elm( $param, 'MB_NM' ) );
        $builder->set( 'MB_NICK_NM', _elm( $param, 'MB_NICK_NM' ) );
        $builder->set( 'MB_MOBILE_NUM', _elm( $param, 'MB_MOBILE_NUM' ) );
        $builder->set( 'MB_STATUS', _elm( $param, 'MB_STATUS' ) );
        $builder->set( 'MB_SMS_FLAG', _elm( $param, 'MB_SMS_FLAG' ) );
        $builder->set( 'MB_MAILING_FLAG', _elm( $param, 'MB_MAILING_FLAG' ) );
        $builder->set( 'MB_PRIV_APPR', _elm( $param, 'MB_PRIV_APPR' ) );
        $builder->set( 'MB_PRIV_APPR_OPT', _elm( $param, 'MB_PRIV_APPR_OPT' ) );
        $builder->set( 'MB_PRIV_OFFER', _elm( $param, 'MB_PRIV_OFFER' ) );
        $builder->set( 'MB_PRIV_CONS', _elm( $param, 'MB_PRIV_CONS' ) );
        $builder->set( 'MB_REG_AT', _elm( $param, 'MB_REG_AT' ) );
        $builder->set( 'MB_REG_IP', _elm( $param, 'MB_REG_IP' ) );
        $builder->set( 'PASSWD_CHK', _elm( $param, 'PASSWD_CHK' ) );

        $aResult = $builder->insert();
        if( $aResult ){
            $aReturn = $this->db->insertID();
        }
        return $aReturn;
    }


    public function rLoginRegister( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table('R_LOGIN_INFO');

        $builder->set( 'R_LOGIN_ID' , _elm( $param , 'R_LOGIN_ID' ) );
        $builder->set( 'R_EMAIL' , _elm( $param , 'R_EMAIL' ) );
        $builder->set( 'R_NAME' , _elm( $param , 'R_NAME' ) );
        $builder->set( 'R_NICKNAME' , _elm( $param , 'R_NICKNAME' ) );
        $builder->set( 'R_MOBILE' , _elm( $param , 'R_MOBILE' ) );
        $builder->set( 'R_GUBUN' , _elm( $param , 'R_GUBUN' ) );
        $builder->set( 'REG_DATE' , 'now()' , false );

        $builder->insert();
        $str = $this->db->getLastQuery();

        $aReturn = $this->db->insertID();

        return $aReturn;
    }

    public function updateLastLogin( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table('MEMBERSHIP');
        $builder->set( 'MB_LOGIN_CNT', _elm( $param, 'MB_LOGIN_CNT' ) );
        $builder->set( 'MB_LAST_LOGIN', _elm( $param, 'MB_LAST_LOGIN' ) );
        $builder->set( 'MB_LAST_LOGIN_IP', _elm( $param, 'MB_LAST_LOGIN_IP' ) );
        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' )  );

        $aReturn = $builder->update();

        //echo $this->db->getLastQuery();
        return $aReturn;
    }


    public function getRloginInfo( $param = [] )
    {
        $aReturn = [];

        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'R_LOGIN_INFO' );
        $builder->select("R_IDX,M_IDX,R_LOGIN_ID,R_NAME,R_NICKNAME,R_EMAIL,R_MOBILE");

        if( empty( _elm( $param , 'R_LOGIN_ID' ) ) === false ){
            $builder->where( 'R_LOGIN_ID' , _elm( $param , 'R_LOGIN_ID' ) );
        }
        if( empty( _elm( $param , 'R_EMAIL' ) ) === false ){
            $builder->where( 'R_EMAIL' , _elm( $param , 'R_EMAIL' ) );
        }
        if( empty( _elm( $param , 'R_GUBUN' ) ) === false ){
            $builder->where( 'R_GUBUN' , _elm( $param , 'R_GUBUN' ) );
        }
        if( empty( _elm( $param , 'IDX' ) ) === false ){
            $builder->where( 'R_IDX' , _elm( $param , 'IDX' ) );
        }

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $result = $query->getRowArray();

            $aReturn = $result;
        }
        $str = $this->db->getLastQuery();

        return $aReturn;
    }

    public function rloginInfoMemberJoin( $param = [] )
    {
        $aReturn = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'R_LOGIN_INFO' );
        $builder->set( 'M_IDX', _elm( $param, 'M_IDX' ) );
        $builder->where( 'R_IDX', _elm( $param, 'R_IDX' ) );
        $aReturn = $builder->update();
        return $aReturn;
    }

    public function getUserInfoFromSns( $param = [] )
    {
        $aReturn = [];

        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'MEMBERSHIP MB' );
        $builder->select( 'MB_USERID,' );
        $builder->where( 'MB_IDX', _elm( $param, 'u_idx' ) );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        //echo $this->db->getLastQuery();
        return $aReturn;

    }
}