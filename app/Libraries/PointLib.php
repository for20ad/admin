<?php
namespace App\Libraries;

use Config\Talk;

use App\Models\PointModel;

class PointLib
{
    public $PointModel;
    public $db;
    private $helpers = ['owens', 'owens_url'];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->PointModel = new PointModel();
        helper($this->helpers);
    }

    public function getPoint( array $param )
    {
        $response = [];

        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            $response['status']         = 400;
            $response['error']          = 400;
            $response['messages'][]     = 'empty member idx';
            return $response;
        }

        $point                          = $this->PointModel->getPointBalance( _elm( $param, 'MEMBER_IDX' ) );

        $response['status']             = 200;
        $response['messages'][]         = 'success';
        $response['data']               = $point;
        return $response;
    }

    public function savePoint( array $param )
    {
        $response = [];

        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            $response['status']         = 400;
            $response['error']          = 400;
            $response['messages'][]     = 'empty member idx';
            return $response;
        }

        $_point                         = $this->getPoint($param);

        $point                          = _elm( _elm( $_point, 'data' ), 'BALANCE');
        $save_point                     = _elm( $param, 'SAVE_POINT' );

        $new_balance                    = $point + $save_point;

        $historyParam                   = [];
        $historyParam['MEMBER_IDX']     = _elm($param, 'MEMBER_IDX');
        $historyParam['ORDER_ID']       = _elm( $param, 'ORDER_ID' , '' );
        $historyParam['TYPE']           = 2;
        $historyParam['COMMENT']        = _elm($param, 'COMMENT');
        $historyParam['AMOUNT']         = $save_point;
        $historyParam['BALANCE']        = $new_balance;
        $historyParam['CREATED_AT']     = date('Y-m-d H:i:s');

        // ------------------------------------------------------
        // 히스토리 입력
        // ------------------------------------------------------
        $bIDX                       = $this->PointModel->insertPointHistory( $historyParam );

        if ($this->db->transStatus() === false ||$bIDX === false) {
            $this->db->transRollback();
            $response['status']         = 400;
            $response['messages'][]     = '포인트 히스토리 처리중 오류발생.. 다시 시도해주세요.';
            return  $response;
        }

        // ------------------------------------------------------
        // BALANCE 수정
        // ------------------------------------------------------

        $balanceParam               = [];
        $balanceParam['BALANCE']    = $new_balance;
        $balanceParam['MEMBER_IDX'] = _elm($param, 'MEMBER_IDX');

        $aStatus                    = $this->PointModel->updateBalance( $balanceParam );
        if ($this->db->transStatus() === false ||$aStatus === false) {
            $this->db->transRollback();
            $response['status']         = 400;
            $response['messages'][]     = '포인트 싱크 처리중 오류발생.. 다시 시도해주세요.';
            return  $response;
        }

        $response['status']             = 200;
        $response['messages'][]         = 'success';
        $response['data']               = $point;
        return $response;
    }

    public function usePoint( array $param )
    {
        $response = [];

        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            $response['status']         = 400;
            $response['error']          = 400;
            $response['messages'][]     = 'empty member idx';
            return $response;
        }

        $_point                         = $this->getPoint($param);

        $point                          = _elm( _elm( $_point, 'data' ), 'BALANCE');
        $used_point                     = _elm( $param, 'USE_POINT' );

        $new_balance                    = $point - $used_point;

        $historyParam                   = [];
        $historyParam['MEMBER_IDX']     = _elm($param, 'MEMBER_IDX');
        $historyParam['TYPE']           = 1;
        $historyParam['SEND_IDX']       = _elm($param, 'SEND_IDX');
        $historyParam['COMMENT']        = _elm($param, 'COMMENT');
        $historyParam['AMOUNT']         = $used_point;
        $historyParam['BALANCE']        = $new_balance;
        $historyParam['CREATED_AT']     = date('Y-m-d H:i:s');

        // ------------------------------------------------------
        // 히스토리 입력
        // ------------------------------------------------------
        $bIDX                       = $this->PointModel->insertPointHistory( $historyParam );

        if ($this->db->transStatus() === false ||$bIDX === false) {
            $this->db->transRollback();
            $response['status']         = 400;
            $response['messages'][]     = '포인트 히스토리 처리중 오류발생.. 다시 시도해주세요.';
            return  $response;
        }

        // ------------------------------------------------------
        // BALANCE 수정
        // ------------------------------------------------------

        $balanceParam               = [];
        $balanceParam['BALANCE']    = $new_balance;
        $balanceParam['MEMBER_IDX'] = _elm($param, 'MEMBER_IDX');

        $aStatus                    = $this->PointModel->updateBalance( $balanceParam );
        if ($this->db->transStatus() === false ||$aStatus === false) {
            $this->db->transRollback();
            $response['status']         = 400;
            $response['messages'][]     = '포인트 싱크 처리중 오류발생.. 다시 시도해주세요.';
            return  $response;
        }

        $response['status']             = 200;
        $response['messages'][]         = 'success';
        $response['data']               = $point;
        return $response;
    }

}