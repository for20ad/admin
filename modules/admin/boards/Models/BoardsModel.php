<?php
namespace Module\admin\boards\Models;

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
        $builder->where( 'L_MB_IDX', _elm( $param, 'L_MB_IDX' ) );
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
    public function getFileInfoByPath( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_FILES' );

        $builder->where( 'F_PATH', _elm( $param, 'F_PATH' ) );
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
        $builder->select( 'CM.C_IDX, CM.C_B_IDX, CM.C_PARENT_IDX, CM.C_DEPTH, CM.C_COMMENT, CM.C_REG_AT' );
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

    public function getPostsCommentsTop($param = [])
{
    $aReturn = [];

    if (empty($param)) {
        return $aReturn;
    }

    $postId = _elm($param, 'P_IDX');

    $_query = "
    WITH RECURSIVE CommentTree AS (
        SELECT
            c1.C_IDX, c1.C_B_IDX, c1.C_PARENT_IDX, c1.C_DEPTH, c1.C_COMMENT, c1.C_REG_AT, c1.C_STATUS,
            m1.MB_USERID
        FROM WJ_BOARD_COMMENTS c1
        LEFT JOIN MEMBERSHIP m1 ON c1.C_WRITER_IDX = m1.MB_IDX
        WHERE c1.C_PARENT_IDX = 0

        UNION ALL

        SELECT
            c2.C_IDX, c2.C_B_IDX, c2.C_PARENT_IDX, c2.C_DEPTH, c2.C_COMMENT, c2.C_REG_AT, c2.C_STATUS,
            m2.MB_USERID
        FROM WJ_BOARD_COMMENTS c2
        LEFT JOIN MEMBERSHIP m2 ON c2.C_WRITER_IDX = m2.MB_IDX
        INNER JOIN CommentTree ct ON ct.C_IDX = c2.C_PARENT_IDX
    )
    SELECT * FROM CommentTree
    WHERE C_B_IDX = ?
    AND C_STATUS = '1';
    ";

    $query = $this->db->query($_query, [$postId]);

    if ($query !== false && $query->getNumRows() > 0) {
        $aReturn = $query->getResultArray();
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
        if( empty( _elm( $param, 'P_STATUS' ) ) === false ){
            $builder->where( 'PT.P_STATUS', _elm( $param, 'P_STATUS' ) );
        }

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

    public function getBoardsInfo( $param = [] )
    {
        $aReturn = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD' );
        $builder->where( 'B_IDX', _elm( $param, 'B_IDX' ) );
        $builder->where( 'B_STATUS', 1 );

        $query = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn = $query->getRowArray();
        }
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

    public function sameChecked( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD' );
        $builder->select('*');
        $builder->where( 'B_GROUP', _elm( $param, 'B_GROUP' ) );
        $builder->where( 'B_TITLE', _elm( $param, 'B_TITLE' ) );
        $builder->where( 'B_STATUS', _elm( $param, 'B_STATUS' ) );

        $aReturn = $builder->countAllResults(false);

        return $aReturn;
    }


    public function getLastBoardSortNum( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD' );
        $builder->select( 'B_SORT' );
        $builder->where( 'B_GROUP', _elm( $param, 'B_GROUP' ) );
        $builder->where( 'B_STATUS', _elm( $param, 'B_STATUS' ) );
        $builder->orderBy( 'B_SORT',  'DESC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aResult = $query->getRowArray();
            $aReturn = _elm( $aResult, 'B_SORT' );
        }
        return $aReturn;

    }

    public function shiftSortFindAll( $group, $sort )
    {
        $aReturn = [];
        if( empty( $sort ) === true || empty( $group ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD' );
        $builder->where( 'B_SORT >=', $sort );
        $builder->where( 'B_GROUP', $group );
        $builder->orderBy( 'B_SORT',  'ASC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aReturn = $query->getResultArray();
        }
        return $aReturn;
    }

    public function reSort( $group, $currentSort ,$newSort )
    {
        $aReturn = false;
        if( empty( $group ) === true || empty( $currentSort ) === true || empty( $newSort ) === true ){
            return $aReturn;
        }
        $builder = $this->db->table( 'WJ_BOARD' );
        $builder->where( 'B_GROUP', $group );
        if ($currentSort < $newSort) {
            $builder->where( 'B_SORT > ', $currentSort );
            $builder->where( 'B_SORT <=', $newSort );
            $builder->set( 'B_SORT', 'B_SORT - 1', false );
        }else{
            $builder->where( 'B_SORT < ', $currentSort );
            $builder->where( 'B_SORT >=', $newSort );
            $builder->set( 'B_SORT', 'B_SORT + 1', false );
        }

        $aReturn = $builder->update();

        return $aReturn;
    }


}