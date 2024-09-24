<?php
#------------------------------------------------------------------
# PopupApi.php
# 팝업 API
# 김우진
# 2024-08-06 13:45:46
# @Desc :
#------------------------------------------------------------------
namespace Module\design\Controllers\apis;

use Module\core\Controllers\ApiController;
use App\Libraries\MemberLib;
use App\Libraries\OwensView;

class DesignApi extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function writeImage()
    {
        $response                                   = $this->_initResponse();
        $requests                                   = json_decode(file_get_contents('php://input'), true);


        $aConfig                                    = [
            "path"      => _elm( $requests, 'path' ),
            'mimes'     => 'gif|jpg|jpeg|png|svg',
        ];
        $data                                       = '';
        $data                                       = _elm( $requests, 'image' );
        $data                                       = base64_decode($data);

        $file_return                                = $this->_upload_tmp_base64( $data , 'image'  , $aConfig );

        $response['status']                         = 200;
        $response['url']                            = _elm( $file_return, 'url' );


        return $this->respond($response);

    }
}