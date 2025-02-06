<?php
namespace Module\board\Models;

use Config\Services;
use CodeIgniter\Model;

class BoardModel extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function deletePostsFile( $f_idx  )
    {
        $aReturn                                    = false;
        if( empty( $f_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_IDX',                   $f_idx );
        $aReturn                                    = $builder->delete();
        return $aReturn;

    }

    public function getPostsFileDataByIdx( $f_idx )
    {
        $aReturn                                    = [];
        if( empty( $f_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_IDX',                   $f_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getPostsFilesData( $p_idx )
    {
        $aReturn                                    = [];
        if( empty( $p_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_P_IDX',                 $p_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getPostsDataByIdx( $p_idx )
    {
        $aReturn                                    = [];
        if( empty( $p_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD_POSTS PS' );
        $builder->select( 'PS.*' );
        $builder->select( 'ADM.MB_USERID, ADM.MB_USERNAME' );
        $builder->join( 'ADMIN_MEMBER ADM', 'PS.P_ANSWER_MB_IDX=ADM.MB_IDX', 'left' );
        $builder->where( 'PS.P_IDX',                   $p_idx );

        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }


    public function setFileDatas( $param = [] )
    {
        $aReturn = false;

        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder = $this->db->table( 'WJ_BOARD_FILES' );
        foreach( $param as $key => $value ){
            $builder->set( $key, $value );
        }

        $aReturn = $builder->insert();

        return $aReturn;
    }

    public function insertPosts( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_POSTS' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }

    public function updatePosts( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_POSTS' );
        foreach( $param as $key => $val ){
            if( $key == 'P_IDX' ){
                $builder->where( $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }

        $aReturn                                    = $builder->update();
        return $aReturn;
    }

    public function getPostsFiles( $p_idx )
    {
        $aReturn                                    = [];
        if( empty( $p_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD_FILES' );
        $builder->where( 'F_P_IDX',                 $p_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function updatePostsComment( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_COMMENTS' );
        foreach( $param as $key => $val ){
            if( $key == 'C_IDX' ){
                $builder->where( $key,              $val );
            }else{
                $builder->set( $key,                $val );
            }

        }

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function insertPostsComment( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_COMMENTS' );

        foreach( $param as $key => $val ){
            $builder->set( $key,                    $val );
        }
        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;


    }

    public function getParentComment( $p_idx )
    {
        $aReturn                                    = [];
        if( empty( $p_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_COMMENTS' );
        $builder->where( 'C_IDX',                   $p_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }


    public function getPostsCommentLists($param)
    {
        $aReturn = [
            'total_count' => 0, // 전체 댓글 수 (부모 + 자식)
            'parent_count' => 0, // 부모 댓글 수
            'lists' => [],
        ];

        if (empty($param)) {
            return $aReturn;
        }

        // 댓글 테이블
        $builder = $this->db->table('WJ_BOARD_COMMENTS');

        // 전체 댓글 수 (부모 + 자식)
        $builder->where('C_B_IDX', _elm($param, 'C_B_IDX'));
        //$builder->whereIn( 'C_STATUS', [1,9] );
        $aReturn['total_count'] = $builder->countAllResults(false);

        // 부모 댓글만 추출
        $builder->resetQuery();
        $builder->select('*')
            ->where('C_B_IDX', _elm($param, 'C_B_IDX'))
            ->where('C_PARENT_IDX', 0) // 부모 댓글만
            ->orderBy('C_REG_AT', 'DESC');

        // 부모 댓글 총 개수 (페이징용)
        $aReturn['parent_count'] = $builder->countAllResults(false);

        // 부모 댓글에만 페이징 적용
        $builder->limit((int)(_elm($param, 'limit') ?? 10), (int)(_elm($param, 'start') ?? 0));
        $parents = $builder->get()->getResultArray();

        if (empty($parents)) {
            return $aReturn;
        }

        // 전체 댓글 가져오기 (부모 + 자식)
        $builder->resetQuery();
        $builder->select('*')
            ->where('C_B_IDX', _elm($param, 'C_B_IDX'))
        //   ->whereIn('C_STATUS', [1,9])
            ->orderBy('C_PARENT_IDX', 'ASC')
            ->orderBy('C_REG_AT', 'DESC');

        $comments = $builder->get()->getResultArray();

        // 부모 댓글과 자식 댓글 계층화
        $aReturn['lists'] = array_map(function ($parent) use ($comments) {
            return $this->buildRecursiveComments($parent, $comments);
        }, $parents);

        return $aReturn;
    }



    private function buildRecursiveComments($parent, $comments)
    {
        // 자식 댓글 필터링
        $children = array_filter($comments, function ($comment) use ($parent) {
            return $comment['C_PARENT_IDX'] == $parent['C_IDX'];
        });

        // 자식 댓글이 있을 경우 재귀 호출
        if (!empty($children)) {
            $parent['children'] = array_map(function ($child) use ($comments) {
                return $this->buildRecursiveComments($child, $comments);
            }, $children);
        } else {
            $parent['children'] = [];
        }

        return $parent;
    }

    public function getPostsCommentsCnt( $p_idx )
    {
        $aReturn                                    = [];
        if( empty( $p_idx ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD_COMMENTS BC' );
        $builder->where( 'BC.C_B_IDX',              $p_idx );

        $aReturn                                    = $builder->countAllResults(false);
        return $aReturn;
    }

    public function getPostsCountByBidInMbIdx( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD_POSTS' );

        $builder->where( 'P_B_ID',                  _elm( $param, 'P_B_ID' ) );
        $builder->where( 'P_WRITER_IDX',            _elm( $param, 'P_WRITER_IDX' ) );
        $builder->groupStart();
        $builder->where( 'P_NOTI',                  'N' );
        $builder->orWhere( 'P_STAY',                'N' );
        $builder->groupEnd();
        return $builder->countAllResults(false);

    }

    public function getLatestQnaListByUserIdx( $mb_idx )
    {
        $aReturn                                    = [];
        if( empty( $mb_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD_POSTS PS ' );
        $builder->select( 'PS.*' );
        $builder->select('ADM.MB_USERNAME as ADM_NAME');
        $builder->join('ADMIN_MEMBER ADM', 'PS.P_ANSWER_MB_IDX = ADM.MB_IDX', 'left');
        $builder->where( 'PS.P_B_ID',               'QNA' );
        $builder->where( 'PS.P_WRITER_IDX',         $mb_idx );

        $builder->groupStart();
        $builder->where( 'PS.P_NOTI',                  'N' );
        $builder->orWhere( 'PS.P_STAY',                'N' );
        $builder->groupEnd();


        $builder->orderBy( 'P_CREATE_AT', 'DESC' );

        $builder->limit(5);

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;

    }

    public function getPostsLists( $param = [] )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'WJ_BOARD_POSTS PS' );
        $builder->select( 'PS.*' );
        $builder->select( 'MB.MB_NM' );
        $builder->join( 'MEMBERSHIP MB', 'MB.MB_IDX = PS.P_WRITER_IDX', 'left' );
        $builder->where( 'PS.P_B_ID',               _elm( $param, 'P_B_ID' ) );
        if( empty( _elm( $param, 'P_STATUS' ) ) === false ){
            $builder->where( 'PS.P_STATUS',         _elm( $param, 'P_STATUS' ) );
        }
        if( empty( _elm( $param, 'P_ANSWER_STATUS' ) ) === false ){
            $builder->where( 'PS.P_ANSWER_STATUS',  _elm( $param, 'P_ANSWER_STATUS' ) );
        }

        if( empty( _elm( $param, 'P_TITLE' ) ) === false ){
            $builder->like( 'PS.P_TITLE',           _elm( $param, 'P_TITLE' ), 'both' );
        }
        if( empty( _elm( $param, 'P_CONTENT' ) ) === false ){
            $builder->like( 'PS.P_CONTENT',         _elm( $param, 'P_CONTENT' ), 'both' );
        }
        if( empty( _elm( $param, 'P_WRITER_NAME' ) ) === false ){
            $builder->like( 'PS.P_WRITER_NAME',     _elm( $param, 'P_WRITER_NAME' ), 'both' );
        }
        if( empty( _elm( $param, 'P_WRITER_IDX' ) ) === false ){
            $builder->where( 'PS.P_WRITER_IDX',     _elm( $param, 'P_WRITER_IDX' ) );
        }

        if( empty( _elm( $param, 'N_START' ) ) === false && empty( _elm( $param, 'N_END' ) ) === false ){
            $builder->where( 'DATE_FORMAT( PS.P_CREATE_AT, \'%Y-%m-%d\' ) >=', _elm( $param, 'N_START' ) );
            $builder->where( 'DATE_FORMAT( PS.P_CREATE_AT, \'%Y-%m-%d\' ) <=', _elm( $param, 'N_END' ) );
        }
        if( empty( _elm( $param, 'P_NOTI' ) ) === false ){
            $builder->where( 'PS.P_NOTI',      _elm( $param, 'P_NOTI' ) );
        }
        if( empty( _elm( $param, 'P_STAY' ) ) === false ){
            $builder->where( 'PS.P_STAY',       _elm( $param, 'P_STAY' ) );
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



    public function getboardDataByIdx( $b_idx )
    {
        $aReturn                                    = [];
        if( empty( $b_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD' );
        $builder->where( 'B_IDX',                   $b_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getboardDataById( $b_id )
    {
        $aReturn                                    = [];
        if( empty( $b_id ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD' );
        $builder->where( 'B_ID',                    $b_id );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function getBoardLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'WJ_BOARD B' );
        $builder->select('B.*');
        $builder->select( 'IFNULL(P.CNT, 0 ) POSTS_CNT' );
        $builder->join( '( SELECT P_B_ID, COUNT( P_IDX ) CNT FROM WJ_BOARD_POSTS WHERE 1=1 GROUP BY P_B_ID ) P ', 'P.P_B_ID = B.B_ID', 'left' );
        if( empty( _elm( $param, 'B_STATUS' ) ) === false ){
            $builder->where( 'B_STATUS',            _elm( $param, 'B_STATUS' ) );
        }
        if( empty( _elm( $param, 'B_TITLE' ) ) === false ){
            $builder->like( 'B_TITLE',              _elm( $param, 'B_TITLE' ), 'both' );
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

    public function deleteBoardIcon( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD' );
        $builder->set( 'B_ICON_PATH',               _elm( $param, 'B_ICON_PATH' ) );
        $builder->where( 'B_IDX',                   _elm( $param, 'B_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }


    public function deleteBoard( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD' );
        $builder->set( 'B_STATUS',                  '3' );
        $builder->where( 'B_IDX',                   _elm( $param, 'B_IDX' ) );

        $aReturn                                    = $builder->update();
        return $aReturn;

    }

    public function getBoardGroup()
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'WJ_BOARD_GROUP' );
        $builder->where( 'G_STATUS',                'Y'  );
        $builder->orderBy( 'G_CREATE_AT',           'ASC' );

        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
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
        $builder->where( 'B_STATUS', '1' );
        $builder->orderBy( 'B_SORT',  'DESC' );

        $query = $builder->get();

        if ($this->db->affectedRows())
        {
            $aResult = $query->getRowArray();
            $aReturn = _elm( $aResult, 'B_SORT' );
        }
        return $aReturn;
    }

    public function updateBoard( $param = [] )
    {
        $aReturn                                      = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_BOARD' );
        $builder->set( 'B_TITLE',                   _elm( $param, 'B_TITLE' ) );
        $builder->set( 'B_ID',                      _elm( $param, 'B_ID' ) );
        $builder->set( 'B_GROUP',                   _elm( $param, 'B_GROUP' ) );
        $builder->set( 'B_STATUS',                  _elm( $param, 'B_STATUS' ) );
        $builder->set( 'B_IS_FREE',                 _elm( $param, 'B_IS_FREE' ) );
        $builder->set( 'B_HITS',                    _elm( $param, 'B_HITS' ) );
        $builder->set( 'B_SECRET',                  _elm( $param, 'B_SECRET' ) );
        $builder->set( 'B_COMMENT',                 _elm( $param, 'B_COMMENT' ) );
        if( empty( _elm( $param, 'B_ICON_PATH' ) ) === false ){
            $builder->set( 'B_ICON_PATH', _elm( $param, 'B_ICON_PATH' ) );
        }

        $builder->set( 'B_UPDATE_AT',               _elm( $param, 'B_UPDATE_AT' ) );
        $builder->set( 'B_UPDATE_IP',               _elm( $param, 'B_UPDATE_IP' ) );
        $builder->set( 'B_UPDATE_MB_IDX',           _elm( $param, 'B_UPDATE_MB_IDX' ) );

        $builder->where( 'B_IDX',                   _elm( $param, 'B_IDX' ) );

        $aReturn                                    = $builder->update();

        return $aReturn;

    }

    public function insertBoard( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_BOARD' );
        $builder->set( 'B_TITLE', _elm( $param, 'B_TITLE' ) );
        $builder->set( 'B_ID', _elm( $param, 'B_ID' ) );
        $builder->set( 'B_GROUP', _elm( $param, 'B_GROUP' ) );
        $builder->set( 'B_STATUS', _elm( $param, 'B_STATUS' ) );
        $builder->set( 'B_SORT', _elm( $param, 'B_SORT' ) );
        $builder->set( 'B_IS_FREE', _elm( $param, 'B_IS_FREE' ) );
        $builder->set( 'B_HITS', _elm( $param, 'B_HITS' ) );
        $builder->set( 'B_SECRET', _elm( $param, 'B_SECRET' ) );
        $builder->set( 'B_COMMENT', _elm( $param, 'B_COMMENT' ) );
        $builder->set( 'B_ICON_PATH', _elm( $param, 'B_ICON_PATH' ) );
        $builder->set( 'B_CREATE_AT', _elm( $param, 'B_CREATE_AT' ) );
        $builder->set( 'B_CREATE_IP', _elm( $param, 'B_CREATE_IP' ) );
        $builder->set( 'B_CREATE_MB_IDX', _elm( $param, 'B_CREATE_MB_IDX' ) );

        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;

    }

    public function insertNotice( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_NOTICE' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $aResult                                    = $builder->insert();
        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }
    public function updateNotice( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_NOTICE' );
        foreach( $param as $key => $val ){
            if( $key == 'N_IDX' ){
                $builder->where(  $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }

        $aReturn                                    = $builder->update();
        return $aReturn;

    }
    public function getNoticeLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'WJ_NOTICE N' );
        $builder->select('N.*');
        $builder->select( 'MB1.MB_USERNAME CRT_MB' );
        $builder->select( 'MB2.MB_USERNAME UDT_MB' );
        $builder->join( 'ADMIN_MEMBER MB1', 'N.N_CREATE_MB_IDX = MB1.MB_IDX', 'left' );
        $builder->join( 'ADMIN_MEMBER MB2', 'N.N_UPDATE_MB_IDX = MB2.MB_IDX', 'left' );
        if( empty( _elm( $param, 'N_STATUS' ) ) === false ){
            $builder->where( 'N_STATUS',            _elm( $param, 'N_STATUS' ) );
        }
        if( empty( _elm( $param, 'N_TITLE' ) ) === false ){
            $builder->like( 'N_TITLE',              _elm( $param, 'N_TITLE' ), 'both' );
        }
        if( empty( _elm( $param, 'N_CONTENT' ) ) === false ){
            $builder->like( 'N_CONTENT',            _elm( $param, 'N_CONTENT' ), 'both' );
        }
        if( empty( _elm( $param, 'N_START' ) ) === false && empty( _elm( $param, 'N_END' ) ) === false ){
            $builder->where( 'DATE_FORMAT( N_CREATE_AT, \'%Y-%m-%d\' ) >=', _elm( $param, 'N_START' ) );
            $builder->where( 'DATE_FORMAT( N_CREATE_AT, \'%Y-%m-%d\' ) <=', _elm( $param, 'N_END' ) );
        }
        if( empty( _elm( $param, 'N_IS_NOTICE' ) ) === false ){
            $builder->where( 'N_IS_NOTICE',         _elm( $param, 'N_IS_NOTICE' ) );
        }
        if( empty( _elm( $param, 'N_IS_STAY' ) ) === false ){
            $builder->where( 'N_IS_STAY',           _elm( $param, 'N_IS_STAY' ) );
        }

        if (!empty(_elm($param, 'N_VISIBLES'))) {
            $nVisibles                              = _elm($param, 'N_VISIBLES');
            $builder->groupStart();  // 괄호 그룹 시작
            foreach ($nVisibles as $visible) {
                $builder->orWhere("FIND_IN_SET('$visible', N_IS_VISIBLE) > 0");
            }
            $builder->groupEnd();  // 괄호 그룹 종료
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

    public function getNoticeDataByIdx( $n_idx )
    {
        $aReturn                                    = [];
        if( empty( $n_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_NOTICE' );
        $builder->where( 'N_IDX',                   $n_idx );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function deleteNotice( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_NOTICE' );
        $builder->where( 'N_IDX',                   _elm( $param, 'N_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;

    }



    public function getFaqCode( $param = [] )
    {
        $aReturn                                    = [];
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'CODE' );
        $builder->where( 'C_PARENT_IDX',            _elm( $param, 'C_PARENT_IDX' ) );
        $builder->where( 'C_STATUS',                1 );
        $builder->orderBy( 'C_SORT',                'ASC' );

        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getResultArray();
        }
        return $aReturn;


    }


    public function getFaqLists( $param = [] )
    {
        $aReturn                                    = [
            'total_count'                           => 0,
            'lists'                                 => [],
        ];

        $builder                                    = $this->db->table( 'WJ_FAQ F' );
        $builder->select('F.*');
        $builder->select( 'MB1.MB_USERNAME CRT_MB' );
        $builder->join( 'ADMIN_MEMBER MB1', 'F.F_CREATE_MB_IDX = MB1.MB_IDX', 'left' );

        if( empty( _elm( $param, 'F_STATUS' ) ) === false ){
            $builder->where( 'F_STATUS',            _elm( $param, 'F_STATUS' ) );
        }
        if( empty( _elm( $param, 'F_TITLE' ) ) === false ){
            $builder->like( 'F_TITLE',              _elm( $param, 'F_TITLE' ), 'both' );
        }
        if( empty( _elm( $param, 'F_CONTENT' ) ) === false ){
            $builder->like( 'F_CONTENT',            _elm( $param, 'F_CONTENT' ), 'both' );
        }
        if( empty( _elm( $param, 'F_ANSWER' ) ) === false ){
            $builder->like( 'F_ANSWER',             _elm( $param, 'F_ANSWER' ), 'both' );
        }

        if( empty( _elm( $param, 'F_IS_BEST' ) ) === false ){
            $builder->where( 'F_IS_BEST',           _elm( $param, 'F_IS_BEST' ) );
        }
        if( empty( _elm( $param, 'F_CATE' ) ) === false ){
            $builder->where( 'F_CATE',              _elm( $param, 'F_CATE' ) );
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
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn['lists']                       = $query->getResultArray();
        }

        return $aReturn;

    }

    public function getFaqMaxSort( $param = [] )
    {
        $aReturn                                    = [];

        $builder                                    = $this->db->table( 'WJ_FAQ' );
        $builder->select( 'MAX( F_SORT ) max' );
        if( empty( _elm( $param, 'F_CATE' ) ) === false ){
            $builder->where( 'F_CATE',              _elm( $param, 'F_CATE' ) );
        }
        $query                                      = $builder->get();
        if ($this->db->affectedRows())
        {
            $aResult                                = $query->getRowArray();
            $aReturn                                = _elm( $aResult, 'max' ) + 1 ?? 1;
        }
        return $aReturn;


    }

    public function insertFaq( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_FAQ' );
        foreach( $param as $key => $val ){
            $builder->set( $key, $val );
        }
        $aResult                                    = $builder->insert();

        if( $aResult ){
            $aReturn                                = $this->db->insertID();
        }
        return $aReturn;
    }
    public function updateFaq( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }
        $builder                                    = $this->db->table( 'WJ_FAQ' );
        foreach( $param as $key => $val ){
            if( $key == 'F_IDX' ){
                $builder->where(  $key, $val );
            }else{
                $builder->set( $key, $val );
            }
        }

        $aReturn                                    = $builder->update();

        return $aReturn;

    }

    public function getFaqDataByIdx( $f_idx )
    {
        $aReturn                                    = [];
        if( empty( $f_idx ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_FAQ' );
        $builder->where( 'F_IDX',                   $f_idx );

        $query                                      = $builder->get();
        //echo $this->db->getLastQuery();
        if ($this->db->affectedRows())
        {
            $aReturn                                = $query->getRowArray();
        }
        return $aReturn;

    }

    public function deleteFaq( $param = [] )
    {
        $aReturn                                    = false;
        if( empty( $param ) === true ){
            return $aReturn;
        }

        $builder                                    = $this->db->table( 'WJ_FAQ' );
        $builder->where( 'F_IDX',                   _elm( $param, 'F_IDX' ) );

        $aReturn                                    = $builder->delete();
        return $aReturn;

    }
}