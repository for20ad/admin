<?php
namespace App\Libraries;

use Config\Talk;
use App\Models\LicenseModel;

class LicenseLib
{
    public function getUserLicense($param = [])
    {
        $return_value = [];
        $model = new LicenseModel();
        $userData = _elm($GLOBALS, 'userInfo');



        if (!empty($param)) {
            $userData = $param;
        }



        $cData = $model->getEduCategory();

        if (!empty($cData)) {

            foreach ($cData as $key => $cate) {

                $return_value[_elm($cate, 'C_CODE')] = [];

                $dataParam = [];
                $dataParam['I_BR_MB_IDX']                   = _elm($userData, 'IDX');
                $dataParam['CP_L_CATE_IDX']                 = _elm($cate, 'C_IDX');

                /**
                 * 실수 방지위해 쿼리 분리...
                 * 1.사용중인 쿠폰중 카테고리의 전부 시청 가능한 쿠폰이 있으면 데이터 출력
                 * 2.사용중인 쿠폰중 일부만 가능한 쿠폰있으면 데이터 합쳐서 출력
                 */

                $useAllData = $model->getUserLicenseCateAllUseList($dataParam);

                /**
                 * 전부 시청 가능한 쿠폰 데이터 있으면
                 */
                if( empty( $useAllData ) === false ){
                    foreach( $useAllData as $alicense ){
                        $return_value[_elm($cate, 'C_CODE')]['cateName']    = _elm($alicense, 'C_NAME');
                        $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($alicense, 'I_CODE');
                        $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($alicense, 'CP_LECTURE_IDX');
                        $return_value[_elm($cate, 'C_CODE')]['startDate']   = _elm($alicense, 'I_START_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['endDate']     = _elm($alicense, 'I_END_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['regGubun']    = _elm($alicense, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                    }
                }
                /**
                 * 전부 시청 가능한 쿠폰 데이터 없으면 단건 데이터
                 */
                else{
                    $usePickData = $model->getUserLicenseCatePickUseList($dataParam);
                    /**
                     * 데이터 있으면 모두 돌림
                     */
                    if( empty( $usePickData ) === false ){
                        $aPickLecture = [];
                        foreach( $usePickData as $key => $plicense ){
                            $aPickLecture[] =  _elm($plicense, 'CP_LECTURE_IDX');
                            $return_value[_elm($cate, 'C_CODE')]['cateName']    = _elm($plicense, 'C_NAME');
                            $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($plicense, 'I_CODE');
                            $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($plicense, 'CP_LECTURE_IDX');
                            $return_value[_elm($cate, 'C_CODE')]['startDate']   = _elm($plicense, 'I_START_DATE');
                            $return_value[_elm($cate, 'C_CODE')]['endDate']     = _elm($plicense, 'I_END_DATE');
                            $return_value[_elm($cate, 'C_CODE')]['regGubun']    = _elm($plicense, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                        }
                        if( empty( $aPickLecture ) === false ) {
                            $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = join(',', $aPickLecture );
                        }
                    }

                }

                /**
                 * 사용하지 않은 쿠폰이 있을때 배열 재정의 하기위해 다시 데이터 뽑는다.
                 * 1.미사용 쿠폰중 카테고리의 전부 시청 가능한 쿠폰이 있으면 데이터 출력
                 * 2.만약 전체 시청 가능한 쿠폰 있으면 해당 카테고리 데이터는 미사용 데이터로 치환.
                 * 3.미사용 쿠폰중 일부만 가능한 쿠폰있으면 데이터 합쳐서 출력
                 * 4.자동 사용등록
                */

                $notUseAllData = $model->getUserLicenseCateAllNotUseList($dataParam);


                /**
                 * 전부 시청 가능한 쿠폰 데이터 있으면
                 */
                if( empty( $useAllData ) === true && empty( $notUseAllData ) === false ){

                    /**
                     * 사용 등록하고 기존 단건 라이선스는 의미 없으므로 해당 카테고리는 재정의 한다.
                    */
                    foreach( $notUseAllData as $nalicense ){
                        $modelParam = [];
                        $modelParam['I_IDX']                    = _elm($nalicense, 'I_IDX');
                        $modelParam['I_BR_MB_IDX']              = _elm($nalicense, 'I_BR_MB_IDX');
                        $modelParam['I_BR_MB_UID']              = _elm($nalicense, 'I_BR_MB_UID');
                        $modelParam['I_STATUS']                 = 1;
                        $useDays                                = strtotime("+" . _elm($nalicense, 'CP_USE_DAYS') . " days", time());
                        $modelParam['I_START_DATE']             = date('Y-m-d');
                        $modelParam['I_END_DATE']               = date('Y-m-d', $useDays);
                        $modelParam['I_USE_DATE']               = date('Y-m-d H:i:s');
                        $modelParam['I_USE_GUBUN']              = 1;
                        $modelParam['I_USE_MB_IDX']             = _elm($userData, 'IDX');

                        $model->licenseUseRegist($modelParam);

                        $useOrgPick                                         =  $return_value[_elm($cate, 'C_CODE')]['pickLecture'] ?? 0 ;

                        $return_value[_elm($cate, 'C_CODE')]['cateName']    = _elm($nalicense, 'C_NAME');
                        $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($nalicense, 'I_CODE');
                        $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($nalicense, 'CP_LECTURE_IDX');
                        $return_value[_elm($cate, 'C_CODE')]['startDate']   = _elm($nalicense, 'I_START_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['endDate']     = date('Y-m-d', $useDays);
                        $return_value[_elm($cate, 'C_CODE')]['regGubun']    = _elm($nalicense, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                        $return_value[_elm($cate, 'C_CODE')]['etc']         = '미사용 쿠폰으로 대체:'.$useOrgPick;
                    }

                }else if( empty( $notUseAllData ) === true ){
                    /**
                     * 미사용 단건 조회하여 기존 정의된 배열에서 없는것들만 사용 등록해준다.
                    */
                    $notUsePickData = $model->getUserLicenseCatePickNotUseList($dataParam);

                    /**
                     * 데이터 있으면 모두 돌림
                     */



                    if( empty( $notUsePickData ) === false ){
                        $chkPickLectureArray                    = explode( ',', $return_value[_elm($cate, 'C_CODE')]['pickLecture'] );
                        $naPickLecture                          = $chkPickLectureArray;
                        foreach( $notUsePickData as $key => $nplicense ){

                            if(!in_array(_elm($nplicense, 'CP_LECTURE_IDX'), $chkPickLectureArray)){
                                $modelParam = [];
                                $modelParam['I_IDX']                    = _elm($nplicense, 'I_IDX');
                                $modelParam['I_BR_MB_IDX']              = _elm($nplicense, 'IDX');
                                $modelParam['I_BR_MB_UID']              = _elm($nplicense, 'UID');
                                $modelParam['I_STATUS']                 = 1;
                                $useDays                                = strtotime("+" . _elm($nplicense, 'CP_USE_DAYS') . " days", time());
                                $modelParam['I_START_DATE']             = date('Y-m-d');
                                $modelParam['I_END_DATE']               = date('Y-m-d', $useDays);
                                $modelParam['I_USE_DATE']               = date('Y-m-d H:i:s');
                                $modelParam['I_USE_GUBUN']              = 1;
                                $modelParam['I_USE_MB_IDX']             = _elm($userData, 'IDX');

                                $model->licenseUseRegist($modelParam);


                                $aPickLecture[] =  _elm($nplicense, 'CP_LECTURE_IDX');

                                $return_value[_elm($cate, 'C_CODE')]['cateName']    = _elm($nplicense, 'C_NAME');
                                $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($nplicense, 'I_CODE');
                                $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($nplicense, 'CP_LECTURE_IDX');
                                $return_value[_elm($cate, 'C_CODE')]['startDate']   = _elm($nplicense, 'I_START_DATE');
                                $return_value[_elm($cate, 'C_CODE')]['endDate']     = date('Y-m-d', $useDays);
                                $return_value[_elm($cate, 'C_CODE')]['regGubun']    = _elm($nplicense, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                                $return_value[_elm($cate, 'C_CODE')]['etc']         = '미사용 쿠폰으로 추가:';
                            }

                        }
                        if( empty( $naPickLecture ) === false ) {
                            $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = join(',', $naPickLecture );
                        }

                    }
                }





            }

        }

        return $return_value;
    }

    public function getUserLicenseWait( $param = [] )
    {
        $return_value = [];
        $model = new LicenseModel();
        $userData = _elm($GLOBALS, 'userInfo');

        if (!empty($param)) {
            $userData = $param;
        }

        $cData = $model->getEduCategory();

        if (!empty($cData)) {
            foreach ($cData as $key => $cate) {
                $return_value[_elm($cate, 'C_CODE')] = [];

                $dataParam = [];
                $dataParam['I_BR_MB_IDX']                   = _elm($userData, 'IDX');
                $dataParam['CP_L_CATE_IDX']                 = _elm($cate, 'C_IDX');

                $waitData = $model->getUserLicenseCateWaitList($dataParam);

                if( empty( $waitData ) === false ){
                    foreach( $waitData as $waitLicense ){
                        $return_value[_elm($cate, 'C_CODE')]['cateName']    = _elm($waitLicense, 'C_NAME');
                        $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($waitLicense, 'I_CODE');
                        $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($waitLicense, 'CP_LECTURE_IDX');
                        $return_value[_elm($cate, 'C_CODE')]['startDate']   = _elm($waitLicense, 'I_START_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['endDate']     = _elm($waitLicense, 'I_END_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['regGubun']    = _elm($waitLicense, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                    }
                }
            }
        }

        return $return_value;
    }


    public function getUserLicense2($param = [])
    {
        $return_value = [];
        $model = new LicenseModel();
        $userData = _elm($GLOBALS, 'userInfo');

        if (!empty($param)) {
            $userData = $param;
        }

        $cData = $model->getEduCategory();

        if (!empty($cData)) {
            foreach ($cData as $key => $cate) {
                $return_value[_elm($cate, 'C_CODE')] = [];

                $useParam = [];
                $useParam['I_BR_MB_IDX'] = _elm($userData, 'IDX');
                $useParam['CP_L_CATE_IDX'] = _elm($cate, 'C_IDX');


                $lData = $model->getUserLicenseUseList($useParam);

                $aPickLecture = [];
                if (!empty($lData)) {

                    /*
                     * echo "===사용중인 쿠폰===";
                     * 쿠폰이 하나이면 그 쿠폰을 넣으면 되지만 2개 이상의 row가 나오면 체크해야한다.
                     */
                    if( count( $lData ) > 1 ){

                        foreach ($lData as $idx => $license) {
                            $endDate = _elm($license, 'I_END_DATE') != null ? date('Ymd', strtotime(_elm($license, 'I_END_DATE'))) : '';
                            $curDate = date('Ymd');
                            if ($endDate >= $curDate) {
                                /*
                                * 1. 사용중인 쿠폰 리스트중 CP_LECTURE_IDX 가 없으면 해당 강좌 모두 시청 가능한 쿠폰이므로 멈춤.
                                * 2. 만약 사용중인 쿠폰 리스트중 CP_LECTURE_IDX 가 있으면 loop를 돌려 해당 강좌의 CP_LECTURE_IDX를 join(',') 하여 하나로 묶어준다.
                                */
                                if( empty( _elm($license, 'CP_LECTURE_IDX') ) == true ){

                                    $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($license, 'I_CODE');
                                    $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($license, 'CP_LECTURE_IDX');
                                    $return_value[_elm($cate, 'C_CODE')]['startDate'] = _elm($license, 'I_START_DATE');
                                    $return_value[_elm($cate, 'C_CODE')]['endDate'] = _elm($license, 'I_END_DATE');
                                    $return_value[_elm($cate, 'C_CODE')]['regGubun'] = _elm($license, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                                    break;
                                }else{
                                    $aPickLecture[] =  _elm($license, 'CP_LECTURE_IDX');
                                    $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($license, 'I_CODE');
                                    $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($license, 'CP_LECTURE_IDX');
                                    $return_value[_elm($cate, 'C_CODE')]['startDate'] = _elm($license, 'I_START_DATE');
                                    $return_value[_elm($cate, 'C_CODE')]['endDate'] = _elm($license, 'I_END_DATE');
                                    $return_value[_elm($cate, 'C_CODE')]['regGubun'] = _elm($license, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                                }

                            }

                        }
                        /*
                         * $aPickLecture가 빈 배열이 아니면 넣어준다.
                        */
                        if( empty( $aPickLecture ) === false ) {
                            $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = join(',', $aPickLecture );
                        }

                    }else{
                        foreach ($lData as $idx => $license) {
                            $endDate = _elm($license, 'I_END_DATE') != null ? date('Ymd', strtotime(_elm($license, 'I_END_DATE'))) : '';
                            $curDate = date('Ymd');

                            if ($endDate >= $curDate) {
                                $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($license, 'I_CODE');
                                $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($license, 'CP_LECTURE_IDX');
                                $return_value[_elm($cate, 'C_CODE')]['startDate'] = _elm($license, 'I_START_DATE');
                                $return_value[_elm($cate, 'C_CODE')]['endDate'] = _elm($license, 'I_END_DATE');
                                $return_value[_elm($cate, 'C_CODE')]['regGubun'] = _elm($license, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                                break;
                            }
                        }

                    }

                } else {
                    /*
                     * echo "  echo "===미!!!사용중인 쿠폰===";";
                     * 쿠폰이 하나이면 그 쿠폰을 넣으면 되지만 2개 이상의 row가 나오면 체크해야한다.
                     */
                    $emptyParam = [];
                    $emptyParam['I_BR_MB_IDX'] = _elm($userData, 'IDX');
                    $emptyParam['CP_L_CATE_IDX'] = _elm($cate, 'C_IDX');


                    $eData = $model->getUserLicenseNotUseList($emptyParam);

                    if (!empty($eData)) {
                        /*
                         * 만약 CP_LECTURE_IDX가 없는 즉 해당 카테고리 강좌 모두 시청할 수 있는 쿠폰이면 멈추면 되고
                         * CP_LECTURE_IDX가 있으면 모두 등록하도록 한다.
                         */

                        $modelParam = [];
                        $modelParam['I_IDX'] = _elm(_elm($eData, 0), 'I_IDX');
                        $modelParam['I_BR_MB_IDX'] = _elm($userData, 'IDX');
                        $modelParam['I_BR_MB_UID'] = _elm($userData, 'UID');
                        $modelParam['I_STATUS'] = 1;
                        $useDays = strtotime("+" . _elm(_elm($eData, 0), 'CP_USE_DAYS') . " days", time());
                        $modelParam['I_START_DATE'] = date('Y-m-d');
                        $modelParam['I_END_DATE'] = date('Y-m-d', $useDays);
                        $modelParam['I_USE_DATE'] = date('Y-m-d H:i:s');
                        $modelParam['I_USE_GUBUN'] = 1;
                        $modelParam['I_USE_MB_IDX'] = _elm($userData, 'IDX');

                        if (empty(_elm(_elm($eData, 0), 'I_END_DATE')) === true) {
                            $model->licenseUseRegist($modelParam);
                        }

                        $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm(_elm($eData, 0), 'I_CODE');
                        $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm(_elm($eData, 0), 'CP_LECTURE_IDX');
                        $return_value[_elm($cate, 'C_CODE')]['startDate'] = _elm($modelParam, 'I_START_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['endDate'] = _elm($modelParam, 'I_END_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['regGubun'] = _elm(_elm($eData, 0), ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                    }
                }
            }
        }

        return $return_value;
    }


    public function getUserLicense_org($param = [])
    {
        $return_value = [];
        $model = new LicenseModel();
        $userData = _elm($GLOBALS, 'userInfo');

        if (!empty($param)) {
            $userData = $param;
        }

        $cData = $model->getEduCategory();

        if (!empty($cData)) {
            foreach ($cData as $key => $cate) {
                $return_value[_elm($cate, 'C_CODE')] = [];

                $useParam = [];
                $useParam['I_BR_MB_IDX'] = _elm($userData, 'IDX');
                $useParam['CP_L_CATE_IDX'] = _elm($cate, 'C_IDX');
                $useParam['CHKUSE'] = true;

                $lData = $model->getUserLicenseUseList($useParam);

                if (!empty($lData)) {
                    echo "===사용중인 쿠폰===";
                    $pickLectures = [];

                    foreach ($lData as $idx => $license) {
                        $endDate = _elm($license, 'I_END_DATE') != null ? date('Ymd', strtotime(_elm($license, 'I_END_DATE'))) : '';
                        $curDate = date('Ymd');

                        if ($endDate >= $curDate) {
                            if (empty(_elm($license, 'CP_LECTURE_IDX')) === true) {
                                $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($license, 'I_CODE');
                                $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($license, 'CP_LECTURE_IDX');
                                $return_value[_elm($cate, 'C_CODE')]['startDate'] = _elm($license, 'I_START_DATE');
                                $return_value[_elm($cate, 'C_CODE')]['endDate'] = _elm($license, 'I_END_DATE');
                                $return_value[_elm($cate, 'C_CODE')]['regGubun'] = _elm($license, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                                break;
                            } else {
                                if (empty($return_value[_elm($cate, 'C_CODE')]['licenseCode']) === false) {
                                    echo "licenseCode:::" . $return_value[_elm($cate, 'C_CODE')]['licenseCode'];
                                    if ($return_value[_elm($cate, 'C_CODE')]['licenseCode'] == _elm($license, 'I_CODE')) {
                                        echo "pick::" . _elm($license, 'CP_LECTURE_IDX');
                                        $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($license, 'I_CODE');
                                        $pickLectures[] = _elm($license, 'CP_LECTURE_IDX');
                                        $return_value[_elm($cate, 'C_CODE')]['startDate'] = _elm($license, 'I_START_DATE');
                                        $return_value[_elm($cate, 'C_CODE')]['endDate'] = _elm($license, 'I_END_DATE');
                                        $return_value[_elm($cate, 'C_CODE')]['regGubun'] = _elm($license, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                                    }
                                } else {
                                    $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm($license, 'I_CODE');
                                    $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm($license, 'CP_LECTURE_IDX');
                                    $return_value[_elm($cate, 'C_CODE')]['startDate'] = _elm($license, 'I_START_DATE');
                                    $return_value[_elm($cate, 'C_CODE')]['endDate'] = _elm($license, 'I_END_DATE');
                                    $return_value[_elm($cate, 'C_CODE')]['regGubun'] = _elm($license, ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                                    break;
                                }
                            }
                        }
                    }

                    // 수정된 부분: pickLecture에 값을 추가
                    $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = implode(',', $pickLectures);

                } else {
                    $emptyParam = [];
                    $emptyParam['I_BR_MB_IDX'] = _elm($userData, 'IDX');
                    $emptyParam['CP_L_CATE_IDX'] = _elm($cate, 'C_IDX');
                    $emptyParam['CHKUSE'] = false;

                    $eData = $model->getUserLicenseNotUseList($emptyParam);

                    if (!empty($eData)) {
                        echo "===미!!!사용중인 쿠폰===";

                        $modelParam = [];
                        $modelParam['I_IDX'] = _elm(_elm($eData, 0), 'I_IDX');
                        $modelParam['I_BR_MB_IDX'] = _elm($userData, 'IDX');
                        $modelParam['I_BR_MB_UID'] = _elm($userData, 'UID');
                        $modelParam['I_STATUS'] = 1;
                        $useDays = strtotime("+" . _elm(_elm($eData, 0), 'CP_USE_DAYS') . " days", time());
                        $modelParam['I_START_DATE'] = date('Y-m-d');
                        $modelParam['I_END_DATE'] = date('Y-m-d', $useDays);
                        $modelParam['I_USE_DATE'] = date('Y-m-d H:i:s');
                        $modelParam['I_USE_GUBUN'] = 1;
                        $modelParam['I_USE_MB_IDX'] = _elm($userData, 'IDX');

                        if (empty(_elm(_elm($eData, 0), 'I_END_DATE')) === true) {
                            $model->licenseUseRegist($modelParam);
                        }

                        $return_value[_elm($cate, 'C_CODE')]['licenseCode'] = _elm(_elm($eData, 0), 'I_CODE');
                        $return_value[_elm($cate, 'C_CODE')]['pickLecture'] = _elm(_elm($eData, 0), 'CP_LECTURE_IDX');
                        $return_value[_elm($cate, 'C_CODE')]['startDate'] = _elm($modelParam, 'I_START_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['endDate'] = _elm($modelParam, 'I_END_DATE');
                        $return_value[_elm($cate, 'C_CODE')]['regGubun'] = _elm(_elm($eData, 0), ' I_USE_GUBUN') == 1 ? '사용자' : '관리자';
                    }
                }
            }
        }

        // 배열의 값들을 문자열로 변환
        foreach ($return_value as $cateCode => &$data) {
            if (isset($data['pickLecture']) && is_array($data['pickLecture']) && count($data['pickLecture']) > 0) {
                $data['pickLecture'] = implode(',', $data['pickLecture']);
            } else {
                $data['pickLecture'] = ''; // 또는 다른 기본값으로 설정
            }
        }

        return $return_value;
    }




}