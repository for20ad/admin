<?php
namespace Module\setting\Models;

use Config\Services;
use CodeIgniter\Model;

class MemberModel extends Model
{
    public function __construct()
    {

        $this->db                                   = \Config\Database::connect();
    }

    public function getAdminMemberData( $param = [] )
    {
        $aReturn                                    = [];
        if( empty($param) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ADMIN_MEMBER MB' );
        $builder->select( 'MB.*' );
        $builder->select( 'G.MB_GROUP_NAME' );
        $builder->join( 'ADMIN_MEMBER_GROUP G', 'MB.MB_LEVEL=G.MB_GROUP_IDX', 'left' );
        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );

        $query                                      = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getAdminMemberLists(array $param = [])
    {
        $aReturn                                    = [
            'lists'                                 => [],
            'total_count'                           => 0,
        ];

        if ( empty( $param ) === true ) {
            return $aReturn;
        }

        $builder                                    = $this->db->table('ADMIN_MEMBER MB');
        $builder->select( 'MB.*' );
        $builder->select( 'G.MB_GROUP_NAME' );
        $builder->join( 'ADMIN_MEMBER_GROUP G', 'MB.MB_LEVEL=G.MB_GROUP_IDX', 'left' );

        if( !empty( _elm( $param, 'MB_LEVEL' ) ) ){
            $builder->where('MB_LEVEL', _elm( $param, 'MB_LEVEL' ) );
        }
        if (isset($param['MB_STATUS']) && $param['MB_STATUS'] !== null) {
            $builder->where('MB_STATUS', _elm($param, 'MB_STATUS'));
        }
        if (!empty($param['MB_USERID'])) {
            $builder->where('MB_USERID', _elm( $param, 'MB_USERID' ) );
        }
        if (!empty($param['MB_USERNAME'])) {
            $builder->like('MB_USERNAME', _elm( $param, 'MB_USERNAME' ), 'both' );
        }
        if (!empty($param['MB_MOBILE_NUM'])) {
            $builder->where('MB_MOBILE_NUM', _elm( $param, 'MB_MOBILE_NUM' ) );
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


    public function insertGroupMember( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ADMIN_MEMBER_GROUP_MEMBER' );
        $builder->set( 'MB_GROUP_IDX', _elm( $param, 'MB_GROUP_IDX' ) );
        $builder->set( 'MB_IDX', _elm( $param, 'MB_IDX' ) );
        $builder->set( 'MB_GM_AT', _elm( $param, 'MB_GM_AT' ) );

        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }

        return $aReturn;

    }

    public function updateGroupMember( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ADMIN_MEMBER_GROUP_MEMBER' );
        $builder->set( 'MB_GROUP_IDX', _elm( $param, 'MB_GROUP_IDX' ) );
        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;

    }

    public function updateAdminMemberStatus( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'ADMIN_MEMBER' );

        $builder->set( 'MB_STATUS', _elm( $param, 'MB_STATUS' ) );
        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function insertAdminMember( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ADMIN_MEMBER' );

        $builder->set( 'MB_USERID', _elm( $param, 'MB_USERID' ) );
        $builder->set( 'MB_PASSWORD', _elm( $param, 'MB_PASSWORD' ) );
        $builder->set( 'MB_USERNAME', _elm( $param, 'MB_USERNAME' ) );
        $builder->set( 'MB_MOBILE_NUM', _elm( $param, 'MB_MOBILE_NUM' ) );
        $builder->set( 'MB_LEVEL', _elm( $param, 'MB_LEVEL' ) );
        $builder->set( 'MB_TEL_NUM', _elm( $param, 'MB_TEL_NUM' ) );
        $builder->set( 'MB_EMAIL', _elm( $param, 'MB_EMAIL' ) );
        $builder->set( 'MB_DEPARTMENT', _elm( $param, 'MB_DEPARTMENT' ) );
        $builder->set( 'MB_POSITION', _elm( $param, 'MB_POSITION' ) );
        $builder->set( 'MB_STATUS', _elm( $param, 'MB_STATUS' ) );
        $builder->set( 'MB_CREATE_AT', _elm( $param, 'MB_CREATE_AT' ) );
        $builder->set( 'MB_CREATE_IP', _elm( $param, 'MB_CREATE_IP' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function updateAdminMember( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'ADMIN_MEMBER' );
        $builder->set( 'MB_USERID', _elm( $param, 'MB_USERID' ) );

        if( empty( _elm( $param, 'MB_PASSWORD' ) ) === false ){
            $builder->set( 'MB_PASSWORD', _elm( $param, 'MB_PASSWORD' ) );
        }

        $builder->set( 'MB_USERNAME', _elm( $param, 'MB_USERNAME' ) );
        $builder->set( 'MB_MOBILE_NUM', _elm( $param, 'MB_MOBILE_NUM' ) );
        $builder->set( 'MB_LEVEL', _elm( $param, 'MB_LEVEL' ) );
        $builder->set( 'MB_TEL_NUM', _elm( $param, 'MB_TEL_NUM' ) );
        $builder->set( 'MB_EMAIL', _elm( $param, 'MB_EMAIL' ) );
        $builder->set( 'MB_DEPARTMENT', _elm( $param, 'MB_DEPARTMENT' ) );
        $builder->set( 'MB_POSITION', _elm( $param, 'MB_POSITION' ) );
        $builder->set( 'MB_STATUS', _elm( $param, 'MB_STATUS' ) );
        $builder->set( 'MB_UPDATE_AT', _elm( $param, 'MB_UPDATE_AT' ) );
        $builder->set( 'MB_UPDATE_IP', _elm( $param, 'MB_UPDATE_IP' ) );

        $builder->where( 'MB_IDX', _elm( $param, 'MB_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;
    }


    public function findAdmUserId( $param = [] )
    {
        $aReturn                                    = [];
        $builder                                    = $this->db->table( 'ADMIN_MEMBER' );
        $builder->select( 'MB_USERID' );
        $builder->where( 'MB_USERID', _elm( $param, 'MB_USERID' ) );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }
    public function getMemberGroup()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'ADMIN_MEMBER_GROUP' );
        $builder->select( 'MB_GROUP_IDX, MB_GROUP_NAME' );
        $builder->orderBy( 'MB_GROUP_ORDER', 'ASC' );
        $builder->orderBy( 'MB_GROUP_IDX', 'ASC' );

        $query                                     = $builder->get();

        if ($this->db->affectedRows())
        {
            $aResult                               = $query->getResultArray();
            foreach( $aResult as $vData )
            {
                $aReturn[_elm($vData, 'MB_GROUP_IDX')] = _elm($vData, 'MB_GROUP_NAME');
            }
        }
        return $aReturn;
    }

    public function getAdminMemberGroup( $level = 0 )
    {
        $aReturn                                   = [];

        $builder                                   = $this->db->table( 'ADMIN_MEMBER_GROUP' );
        $builder->select( 'MB_GROUP_IDX, MB_GROUP_NAME' );
        $builder->where( 'MB_GROUP_IDX', $level );
        $builder->orderBy( 'MB_GROUP_ORDER', 'ASC' );
        $builder->orderBy( 'MB_GROUP_IDX', 'ASC' );

        $query                                     = $builder->get();

        if ($this->db->affectedRows())
        {
            $aResult                               = $query->getRowArray();
            $aReturn                               = _elm( $aResult, 'MB_GROUP_NAME' );
        }
        return $aReturn;
    }
}