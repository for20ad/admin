<?php
namespace Module\download\Controllers;
use Module\core\Controllers\ApiController;
use Module\download\Models\DownloadModel;
use Config\Services;
use Exception;

class Download extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        helper( ['jwt'] );
    }

    public function run()
    {

        $downLoadmodel                             =  new DownloadModel();

        #------------------------------------------------------------------
        # TODO: JWT TOKEN 수동 검증.
        #------------------------------------------------------------------
        $uReturn                                   = _getUserInfo();

        $response                                  = $this->_initApiResponse();
        $requests                                  = _parseJsonFormData();

        if( empty( _elm( $requests, 'file' ) ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '파일정보가 없습니다. 다시 시도해주세요.';

            return $this->respond( $response );
        }
        #------------------------------------------------------------------
        # TODO: 파일 정보 불러오기
        #------------------------------------------------------------------
        $aData                                     = $downLoadmodel->getFileData( _elm( $requests, 'file' ) );

        if( empty( $aData ) === true ){
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '파일정보가 없습니다.';

            return $this->respond( $response );
        }

        $boardConfig                               = $downLoadmodel->getBoardConfigByFileInfo( _elm( $aData, 'F_P_IDX' ) );

        if ( empty( $boardConfig ) === true ) {
            $response['status']                    = 400;
            $response['error']                     = 400;
            $response['messages']                  = '존재하지 않는 게시판 입니다.';

            return $this->respond( $response );
        }


        #------------------------------------------------------------------
        # TODO: 다운로드 로그 S
        #------------------------------------------------------------------
        $dLogParam                                 = [];
        $dLogParam['D_FILE_PATH']                  = _elm( $requests, 'file' );
        $dLogParam['D_TOKEN_INFO']                 = json_encode( $uReturn, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        $dLogParam['D_DOWNLOAD_AT']                = date( 'Y-m-d H:i:s' );
        $dLogParam['D_DOWNLOAD_IP']                = _elm( $_SERVER, 'REMOTE_ADDR' );

        @$downLoadmodel->setLogs( $dLogParam );
        #------------------------------------------------------------------
        # TODO: 다운로드 로그 E
        #------------------------------------------------------------------


        #------------------------------------------------------------------
        # TODO: 다운로드 카운트 업데이트 및 다운로드 프로세스 실행
        #------------------------------------------------------------------
        $modelParam                                = [];

        $modelParam['F_DOWNLOAD_CNT']              = _elm( $aData, 'F_DOWNLOAD_CNT' ) + 1;
        $modelParam['F_PATH']                      = _elm( $aData, 'F_PATH' );



        @$downLoadmodel->updateDownloadCnt( $modelParam );



        return $this->downloadFile($modelParam['F_PATH'], $aData['F_NAME'] );


    }

    public function downloadFile_org($filename, $realName)
    {
        $filePath = WRITEPATH . $filename; // 파일 경로 설정

        if (!file_exists($filePath)) {
            return $this->failNotFound('파일이 없습니다. 다시 시도해주세요.');
        }

        // 파일 다운로드
        return $this->response->download($filePath, null)->setFileName($realName);
    }

    public function downloadFile($filename, $realName)
    {
        $filePath = WRITEPATH . $filename; // 파일 경로 설정

        if (!file_exists($filePath)) {
            return $this->failNotFound('파일이 없습니다. 다시 시도해주세요.');
        }

        // 파일 다운로드

       // 파일 다운로드
        return $this->response
        ->setHeader('Content-Type', mime_content_type($filePath))
        ->setHeader('Content-Disposition', 'attachment; filename="' . $realName . '"')
        ->setHeader('Content-Length', filesize($filePath))
        ->setBody(file_get_contents($filePath));

    }


}