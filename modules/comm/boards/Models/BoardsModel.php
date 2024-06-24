<?php
namespace Module\comm\boards\Models;

use Config\Services;
use CodeIgniter\Model;

class BoardsModel extends Model
{
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }
    public function getPostsLogs( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_POSTS_LOG' );
        if( empty( _elm( $param, 'L_MB_IDX' ) ) === false ){
            $builder->where( 'L_MB_IDX', _elm( $param, 'L_MB_IDX' ) );
        }
        if( empty( _elm( $param, 'L_CREATE_IP' ) ) === false ){
            $builder->where( 'L_CREATE_IP', _elm( $param, 'L_CREATE_IP' ) );
        }

        $builder->where( 'L_POSTS_IDX', _elm( $param, 'L_POSTS_IDX' ) );
        $builder->where( 'L_GBN', _elm( $param, 'L_GBN' ) );
        $builder->orderBy( 'L_CREATE_AT',  'DESC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }

        return $aReturn;

    }
    public function getBoardLists( $param = [] )
    {
        $aReturn = [];

        $aReturn['lists'] = [];
        $aReturn['totalCount'] = 0;

        $builder = $this->db->table( 'WJ_BOARD' );
        $builder->where( 'B_STATUS', 1 );
        $builder->orderBy( 'B_SORT',  'ASC' );

        $aReturn['totalCount'] = $builder->countAllResults(false);
        if( empty( _elm( $param, 'LIMIT' ) ) === false ){
            $builder->limit( _elm( $param, 'LIMIT' ), _elm( $param, 'START' ) );
        }


        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn['lists'] = $query->getResultArray();
        }
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

    public function getBoardsInfoById( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD' );
        $builder->where( 'B_ID', _elm( $param, 'B_ID' ) );
        $builder->where( 'B_STATUS', 1 );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getPostsLists( $param = [] )
    {
        $aReturn = [];

        $aReturn['lists'] = [];
        $aReturn['totalCount'] = 0;

        $builder = $this->db->table( 'WJ_BOARD_POSTS PT' );
        $builder->select( 'IFNULL(CM.COMMENT_CNT, 0) COMMENT_CNT' );
        $builder->select( 'PT.P_IDX, PT.P_B_ID, PT.P_NOTI, PT.P_STAY,P_LINK_URL, PT.P_TITLE, PT.P_HITS, PT.P_START_DATE, PT.P_END_DATE, PT.P_CREATE_AT, PT.P_UP_AT, PT.P_SECRET' );
        $builder->select( 'MB.MB_USERID, MB.MB_NM, MB.MB_NICK_NM' );
        $builder->select( ' ( SELECT IFNULL(COUNT( F_P_IDX ), 0 ) FROM WJ_BOARD_FILES WHERE F_P_IDX = PT.P_IDX ) F_CNT' );
        $builder->join( 'MEMBERSHIP MB', 'PT.P_WRITER_IDX = MB.MB_IDX', 'left' );
        $builder->join( ' ( SELECT COUNT( C_IDX ) COMMENT_CNT, C_B_IDX  FROM WJ_BOARD_COMMENTS WHERE  C_STATUS=1) CM', ' CM.C_B_IDX = PT.P_IDX ', 'left' );
        $builder->where( 'PT.P_STATUS', 1 );
        $builder->where( 'PT.P_B_ID', _elm( $param, 'P_B_ID' ) );

        if( empty( _elm( $param, 'P_C_CODE' ) ) === false ){
            $builder->where( 'PT.P_C_CODE', _elm( $param, 'P_C_CODE' ) );
        }
        if( empty( _elm( $param, 'P_START_DATE' ) ) === false && empty( _elm( $param, 'P_END_DATE' ) ) === false ){
            $builder->where( 'DATE_FORMAT(PT.P_REG_AT, \'%Y-%m-%d\' ) <=', _elm( $param, 'P_START_DATE' ) );
            $builder->where( 'DATE_FORMAT(PT.P_REG_AT, \'%Y-%m-%d\' ) >=', _elm( $param, 'P_END_DATE' ) );
        }
        if( empty( _elm( $param, 'u_name' ) ) === false ){
            $builder->like( 'MB.MB_NM', _elm( $param, 'u_name' ), 'both' );
        }
        if( empty( _elm( $param, 'u_userid' ) ) === false ){
            $builder->like( 'MB.MB_USERID', _elm( $param, 'u_userid' ), 'both' );
        }
        if( empty( _elm( $param, 'u_nick' ) ) === false ){
            $builder->like( 'MB.MB_NICK_NM', _elm( $param, 'u_nick' ), 'both' );
        }
        if( empty( _elm( $param, 'u_title' ) ) === false ){
            $builder->like( 'PT.P_TITLE', _elm( $param, 'u_title' ), 'both' );
        }
        if( empty( _elm( $param, 'u_content' ) ) === false ){
            $builder->like( 'PT.P_CONTENT', _elm( $param, 'u_content' ), 'both' );
        }

        $builder->orderBy( 'PT.P_IDX',  'DESC' );

        $aReturn['totalCount'] = $builder->countAllResults(false);

        if( empty( _elm( $param, 'LIMIT' ) ) === false ){
            $builder->limit( _elm( $param, 'LIMIT' ), _elm( $param, 'START' ) );
        }

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn['lists'] = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getCateName( $code )
    {
        $aReturn = [];
        if( empty( $code ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'CODE' );
        $builder->select( 'C_NAME' );
        $builder->where( 'C_CODE', $code );
        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getPostsInfo( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_POSTS PT' );
        $builder->select( 'PT.*' );
        $builder->select( 'MB.MB_USERID' );
        $builder->join( 'MEMBERSHIP MB', 'MB.MB_IDX = PT.P_WRITER_IDX', 'left' );
        $builder->where( 'PT.P_IDX', _elm( $param, 'P_IDX' ) );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;

    }
    public function getFileInfo( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_P_IDX', _elm( $param, 'F_P_IDX' ) );
        $builder->where( 'F_SORT', _elm( $param, 'F_SORT' ) );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;
    }

    public function getPostsInFiles( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_P_IDX', _elm( $param, 'P_IDX' ) );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;
    }

    public function getPostsComments( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_COMMENTS CM' );
        $builder->select( 'CM.C_IDX, CM.C_B_IDX, CM.C_PARENT_IDX, CM.C_DEPTH,  CM.C_REG_AT' );
        if( _elm( $param, 'SECRET_VIEW' ) == true ){
            $builder->select( 'CM.C_COMMENT' );
        }else{
            $builder->select( "CASE WHEN CM.C_WRITER_IDX = '"._elm( $param, 'F_IDX' )."' THEN  CM.C_COMMENT ELSE '비밀글입니다.' END C_COMMEN" );
        }

        $builder->select( 'MB.MB_USERID' );
        $builder->join( 'MEMBERSHIP MB', 'CM.C_WRITER_IDX = MB.MB_IDX', 'left' );
        $builder->where( 'CM.C_B_IDX', _elm( $param, 'P_IDX' ) );
        $builder->where( 'C_STATUS', _elm( $param, 'C_STATUS', 1 , true ) );
        $builder->orderBy( 'C_REG_AT',  'ASC' );
        $builder->orderBy( 'CM.C_DEPTH', 'ASC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getPostsCommentData( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_COMMENTS CM' );
        $builder->select( 'CM.C_IDX, CM.C_B_IDX, CM.C_SECRET, CM.C_PASSWORD, CM.C_REG_AT, CM.C_COMMENT ' );
        $builder->select( 'MB.MB_USERID' );
        $builder->join( 'MEMBERSHIP MB', 'CM.C_WRITER_IDX = MB.MB_IDX', 'left' );
        $builder->where( 'CM.C_IDX', _elm( $param, 'C_IDX' ) );
        $builder->where( 'C_STATUS', _elm( $param, 'C_STATUS', 1 , true ) );
        $builder->orderBy( 'C_REG_AT',  'ASC' );
        $builder->orderBy( 'CM.C_DEPTH', 'ASC' );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
        return $aReturn;

    }
}