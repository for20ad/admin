<?php
namespace App\Libraries;

use Config\Talk;
use App\Libraries\PointLib;
use App\Models\MileageModel;

class MileageLib
{
    public $MileageModel;
    public $db;
    private $helpers = ['owens', 'owens_url'];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->MileageModel = new MileageModel();
        helper($this->helpers);
    }
    public function getMileage( array $param )
    {
        $response = [];

        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            $response['status']         = 400;
            $response['error']          = 400;
            $response['messages'][]     = 'empty member idx';
            return $response;
        }

        $mileage                        = $this->MileageModel->getMileageBalance( _elm( $param, 'MEMBER_IDX' ) );

        $response['status']             = 200;
        $response['messages'][]         = 'success';
        $response['data']               = $mileage;
        return $response;

    }

    public function getMileageHistory( array $param )
    {
        $response = [];

        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            $response['status']         = 400;
            $response['error']          = 400;
            $response['messages'][]     = 'empty member idx';
            return $response;
        }
        $modelParam                     = [];
        $modelParam['MEMBER_IDX']       = _elm( $param, 'MEMBER_IDX' );
        if( empty( _elm( $param, 'START_DATE' ) ) === false && empty( _elm( $param, 'END_DATE' ) ) === false ){
            $modelParam['START']        = date( 'Ym', strtotime(  _elm( $param, 'START_DATE' ).'01' ) );
            $modelParam['END']          = date( 'Ym', strtotime(  _elm( $param, 'END_DATE' ).'01' ) );
        }

        $mileageLists                   = $this->MileageModel->getMileageHistory( $modelParam );

        $response['status']             = 200;
        $response['messages'][]         = 'success';
        $response['data']               = $mileageLists;
        return $response;

    }

    public function setMileage( array $param )
    {
        $response = [];

        if( empty( _elm( $param, 'MEMBER_IDX' ) ) === true ){
            $response['status']   = 400;
            $response['error']    = 400;
            $response['messages'][] = 'empty member idx';
            return $response;
        }

        $modelParam                     = [];
        $modelParam['MEMBER_IDX']       = _elm( $param, 'MEMBER_IDX' );
        $modelParam['BALANCE']          = 2000;
        $modelParam['COMMENT']          = '회원가입 포인트 지급';
        $modelParam['CREATED_AT']       = date('Y-m-d H:i:s');
        $this->db->transBegin();

        $aIDX                           = $this->MileageModel->insertMileageData( $modelParam );
        if ($this->db->transStatus() === false ||$aIDX === false) {
            $this->db->transRollback();
            $response['status']         = 400;
            $response['messages'][]     = '처리중 오류발생.. 다시 시도해주세요.';
            return  $response;
        }
        unset($modelParam['BALANCE']);

        $modelParam                     = [];
        $modelParam['MEMBER_IDX']       = _elm( $param, 'MEMBER_IDX' );
        $modelParam['TYPE']             = 2;
        $modelParam['COMMENT']          = '회원가입 지급';
        $modelParam['AMOUNT']           = 2000;
        $modelParam['BALANCE']          = 2000;
        $modelParam['CREATED_AT']       = date('Y-m-d H:i:s');

        $bIDX                           = $this->MileageModel->insertMileageHistory( $modelParam );

        if ($this->db->transStatus() === false ||$bIDX === false) {
            $this->db->transRollback();
            $response['status']         = 400;
            $response['messages'][]     = '처리중 오류발생.. 다시 시도해주세요.';
            return  $response;
        }

        $this->db->transCommit();

        $response['status']             = 200;
        $response['messages'][]         = 'success';

        return $response;

    }

    public function useMileage( array $param )
    {
        $response = [];
        //현재 포인트에서 차감하되 포인트가 사용금액보다 적을 경우 포인트에서 차감한다.
        if(empty(_elm($param, 'MEMBER_IDX')) === true) {
            $response['status']         = 400;
            $response['error']          = 400;
            $response['messages'][]     = 'empty member idx';
            return $response;
        }

        $mileageParam                   = [];
        $mileageParam['MEMBER_IDX']     = _elm($param, 'MEMBER_IDX');

        $_mileage                        = $this->getMileage($mileageParam);

        $mileage                        = _elm( _elm($_mileage, 'data' ), 'BALANCE');
        $used_mileage                   = _elm($param, 'USED_MILEAGE');
        $this->db->transBegin();

        //--------------------------------------------------------------
        // 포인트 처리
        // -------------------------------------------------------------
        if ($mileage >= $used_mileage) {
            // $new_balance                = $mileage - $used_mileage;
            $new_balance                = 2000;
            $historyParam               = [];
            $historyParam['MEMBER_IDX'] = _elm($param, 'MEMBER_IDX');
            $historyParam['TYPE']       = 1;
            $historyParam['SEND_IDX']   = _elm($param, 'SEND_IDX');
            // $historyParam['COMMENT']    = _elm($param, 'COMMENT');
            $historyParam['COMMENT']    = '포인트차감 주석처리';
            $historyParam['AMOUNT']     = $used_mileage;
            $historyParam['BALANCE']    = $new_balance;
            $historyParam['CREATED_AT'] = date('Y-m-d H:i:s');
            // ------------------------------------------------------
            // 히스토리 입력
            // ------------------------------------------------------
            $bIDX                       = $this->MileageModel->insertMileageHistory( $historyParam );

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

            $aStatus                    = $this->MileageModel->updateBalance( $balanceParam );
            if ($this->db->transStatus() === false ||$aStatus === false) {
                $this->db->transRollback();
                $response['status']         = 400;
                $response['messages'][]     = '포인트 싱크 처리중 오류발생.. 다시 시도해주세요.';
                return  $response;
            }

            $response['status']             = 200;
            $response['messages'][]         = 'success';
        }
        //--------------------------------------------------------------
        // 포인트 부족시 처리
        // -------------------------------------------------------------
        else {
            $PointLib                       = new PointLib();
            $pointParam                     = [];
            $pointParam['MEMBER_IDX']       = _elm($param, 'MEMBER_IDX');
            $_point                         = $PointLib->getPoint( $pointParam );
            $point                          = _elm( _elm( $_point, 'data' ), 'BALANCE', 0 ,true );
            //--------------------------------------------------------------
            // 포인트가 0보다 크면
            // -------------------------------------------------------------
            if( $point > 0 && ( $used_mileage - $mileage ) <= $point  ){
                //--------------------------------------------------------------
                // 마일리지가 0보다 크면 마일리지 먼저 차감
                // -------------------------------------------------------------
                $new_balance                    = 0;
                if( $mileage > 0 ){
                    $new_balance                = 0;
                    $historyParam               = [];
                    $historyParam['MEMBER_IDX'] = _elm($param, 'MEMBER_IDX');
                    $historyParam['TYPE']       = 1;
                    $historyParam['SEND_IDX']   = _elm($param, 'SEND_IDX');
                    $historyParam['COMMENT']    = _elm($param, 'COMMENT').'(잔액부족으로 포인트 차감)';
                    $historyParam['AMOUNT']     = $mileage;
                    $historyParam['CREATED_AT'] = date('Y-m-d H:i:s');
                    // ------------------------------------------------------
                    // 히스토리 입력
                    // ------------------------------------------------------
                    $bIDX                       = $this->MileageModel->insertMileageHistory( $historyParam );

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

                    $aStatus                    = $this->MileageModel->updateBalance( $balanceParam );
                    if ($this->db->transStatus() === false ||$aStatus === false) {
                        $this->db->transRollback();
                        $response['status']         = 400;
                        $response['messages'][]     = '포인트 싱크 처리중 오류발생.. 다시 시도해주세요.';
                        return  $response;
                    }

                }

                // ------------------------------------------------------
                // 포인트 차감
                // ------------------------------------------------------
                $pointParam                 = [];
                $pointParam['MEMBER_IDX']   = _elm($param, 'MEMBER_IDX');
                $pointParam['USE_POINT']    = ( $used_mileage - $mileage );
                $pointParam['COMMENT']      = _elm($param, 'COMMENT');

                $aResult                    = $PointLib->usePoint( $pointParam );
                if ( _elm( $aResult, 'status' ) != 200) {
                    $this->db->transRollback();
                    $response['status']         = 400;
                    $response['messages']       = _elm($aResult, 'messages' );
                    return  $response;
                }
                $response['status']             = 200;
                $response['messages'][]         = 'success';

            }else{
                $response['status']             = 203;
                $response['messages'][]         = 'Not enough points.';
            }
        }

        $this->db->transCommit();

        return $response;
    }
}

