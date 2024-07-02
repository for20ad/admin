<?php
#------------------------------------------------------------------
# ApiController.php
# api 최상위 컨트롤러
# 김우진
# 2024-05-13 17:06:19
# @Desc :
#------------------------------------------------------------------
namespace Module\core\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use App\Libraries\OwensCache;
use Module\setting\Libraries\Member;
use Module\core\Models\LogModel;

class ApiController extends ResourceController
{
    protected $_encryption_key = 'ZIeAzykfMrqDKwtKTjk573t0ovtQS1Xn'; // 256bit
    use ResponseTrait;

    protected $helpers = ['owens', 'owens_url', 'owens_convert' , 'owens_validation'];
    public $owensCache = null;
    protected $_userInfo = [];
    protected $LogModel = null;
    public $session;
    public $uri;
    public $baseURL;

    public function __construct()
    {
        $this->response = Services::response();
        $this->request = Services::request();
        $this->session = Services::session();
        $this->uri = Services::uri();
        $this->LogModel = new LogModel();
        $this->baseURL = base_url();

        helper($this->helpers);

        $this->owensCache = new OwensCache();
        #------------------------------------------------------------------
        # TODO: 요청 API 데이터 저장
        #------------------------------------------------------------------
        $reciveParam                               = [];

        $reciveParam['REQUEST_URI']                = (string) current_url(true);
        $reciveParam['METHOD']                     = $this->request->getMethod();
        $_params                                   = json_encode( $this->request->getVar(), JSON_UNESCAPED_UNICODE) ;
        $_params                                   = (array)json_decode( str_replace( '\n____', '', $_params ) );


        $reciveParam['PARAMETERS']                 = json_encode( $_params, JSON_UNESCAPED_UNICODE );

        $reciveParam['SENDER_IP']                  = $this->request->getIPAddress();
        $reciveParam['AGENT']                      = (string) $this->request->getUserAgent();
        $reciveParam['SEND_AT']                    = date( 'Y-m-d H:i:s' );

        $this->LogModel->setRequestApiLog( $reciveParam );


        //$this->member = new Member();
    }

    protected function _returnArrayResponse($data, $statusCode = 200)
    {
        return $this->response->setBody($data)->setStatusCode($statusCode);
    }

    public function setUserInfo( $data )
    {
        $this->_userInfo = $data;
    }

    #------------------------------------------------------------------
    # TODO:  Override the respond method to use JSON_NUMERIC_CHECK option
    #   @param mixed  $data
    #  @param int    $status
    #  @param string $message
    #  @return \CodeIgniter\HTTP\Response
    #------------------------------------------------------------------
    public function respond($data = null, int $status = null, string $message = '')
    {
        // If the data is already an array, we can safely apply json_encode with JSON_NUMERIC_CHECK
        if (is_array($data) || is_object($data)) {
            $data = json_decode(json_encode($data, JSON_NUMERIC_CHECK), true);
        }

        return parent::respond($data, $status, $message);
    }

    protected function _initApiResponse()
    {
        $response                                  = [];
        $response['status']                        = 400; // 500, 200, 201
        $response['error']                         = null;
        $response['messages']                      = null;
        //$response['time']     = time();
        //$response['data']    = [];

        return $response;
    }

    protected function _initResponse()
    {
        $response                                  = [];
        $response['status']                        = false;
        $response['messages']                      = [];
        $response['alert']                         = '';
        $response['redirect_url']                  = '';
        $response['replace_url']                   = '';
        $response['goback']                        = false;
        $response['reload']                        = false;
        $response['time']                          = time();

        return $response;
    }

    protected function _getSegment($offset = 0)
    {
        return _elm($this->uri->getSegments(), $offset);
    }

    protected function _upload_put($file, $_config)
    {
        $config = [
            'upload_path' => WRITEPATH . 'uploads/' . _elm($_config, 'path'), // 파일이 업로드될 경로
            'allowed_types' => _elm($_config, 'mimes') ?? 'jpg|jpeg|png|gif|csv|xls|xlsx|zip|hwp|pdf|svg', // 허용된 파일 확장자
            'max_size' => _elm($_config, 'max_size') ?? '10240', // 업로드할 파일의 최대 크기 (KB)
        ];

        // 파일 크기 검사를 수행합니다.
        if ($file['size'] > ($config['max_size'] * 1024)) { // KB 단위로 비교
            $return['status'] = false;
            $return['error'] = '최대 사이즈 오류발생 ' . $config['max_size'] . ' KB';
            return $return;
        }

        // 파일이 업로드될 경로가 존재하지 않는 경우 폴더를 생성합니다.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $return['org_name'] = $file['name'];
        $realFilename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $uploadPath = $config['upload_path'] . '/' . $realFilename;

        // 파일을 업로드합니다.
        if (is_uploaded_file($file['tmp_name']) || file_exists($file['tmp_name'])) {
            if (move_uploaded_file($file['tmp_name'], $uploadPath) || rename($file['tmp_name'], $uploadPath)) {
                $return['url'] = base_url() . 'uploads/' . _elm($_config, 'path') . '/' . $realFilename;
                $return['uploaded_path'] = 'uploads/' . _elm($_config, 'path') . '/' . $realFilename;
                $return['status'] = true;
                $return['type'] = mime_content_type($uploadPath);
                $return['size'] = filesize($uploadPath); // in bytes
                $return['ext'] = pathinfo($uploadPath, PATHINFO_EXTENSION);
            } else {
                $return['status'] = false;
                $return['error'] = '파일 업로드 실패';
            }
        } else {
            $return['status'] = false;
            $return['error'] = '파일 업로드 중 오류 발생';
        }

        return $return;
    }



    protected function _upload( $file , $_config  )
    {
        $uploader                                  = Services::upload();

        $config                                    = [
            'upload_path'                          => WRITEPATH.'uploads/'._elm( $_config, 'path' ), // 파일이 업로드될 경로
            'allowed_types'                        => _elm( $_config, 'mimes' )??'jpg|jpeg|png|gif|csv|xls|xlsx|zip|hwp|pdf|svg', // 허용된 파일 확장자
            'max_size'                             => _elm( $_config, 'max_size' )??'10240', // 업로드할 파일의 최대 크기 (KB)
        ];

        // 파일 크기 검사를 수행합니다.
        if ($file->getSize() > ($config['max_size']*1024) )  { // KB 단위로 비교
            $return['status'] = false;
            $return['error'] = '최대 사이즈 오류발생 ' . $config['max_size'] . ' KB';
            return $return;
        }
        if (is_dir($config['upload_path']) === false)
        {
            mkdir($config['upload_path'], 0777, true);
        }

        // 파일이 업로드될 경로가 존재하지 않는 경우 폴더를 생성합니다.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $return['org_name']                        = $file->getName();
        $realFilename                              = $file->getRandomName();

        // 파일을 업로드합니다.
        $file->move($config['upload_path'], $realFilename);

        $return['url']                             = base_url().'uploads/'._elm( $_config, 'path' ).'/'.$realFilename;
        $return['uploaded_path']                   = 'uploads/'._elm( $_config, 'path' ).'/'.$realFilename;
        $return['status']                          = 'true';

        $return['type']                        = mime_content_type( $return['uploaded_path'] );
        $return['size']                        = filesize( $return['uploaded_path'] ); // in bytes
        $return['ext']                         = pathinfo( $return['uploaded_path'] , PATHINFO_EXTENSION );


        return $return;
    }


    protected function _upload_base64( $file_data , $org_name , $_config  )
    {
        $uploader                                  = Services::upload();

        $config                                    = [
            'upload_path'                          => WRITEPATH.'uploads/'._elm( $_config, 'path' ), // 파일이 업로드될 경로
            'allowed_types'                        => _elm( $_config, 'mimes' )??'jpg|jpeg|png|gif|csv|xls|xlsx|zip|hwp|pdf|svg', // 허용된 파일 확장자
            'max_size'                             => _elm( $_config, 'max_size' )??'10240', // 업로드할 파일의 최대 크기 (KB)
        ];

        if (is_dir($config['upload_path']) === false)
        {
            mkdir($config['upload_path'], 0777, true);
        }

        // 파일이 업로드될 경로가 존재하지 않는 경우 폴더를 생성합니다.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $return['org_name']                        = $org_name;


        $fileName                                  = uniqid() . '.jpg'; // 파일 이름 생성
        $filepath                                  = $config['upload_path'].'/'.$fileName;

        $bStatus                                   = file_put_contents($filepath, $file_data);
        if( $bStatus ){
            $return['url']                         = base_url().'uploads/'._elm( $_config, 'path' ).'/'.$fileName;
            $return['uploaded_path']               = 'uploads/'._elm( $_config, 'path' ).'/'.$fileName;
            $return['status']                      = true;

            $return['type']                        = mime_content_type($filepath);
            $return['size']                        = filesize($filepath); // in bytes
            $return['ext']                         = pathinfo($filepath, PATHINFO_EXTENSION);

        }else{
            $return['status']                      = false;
        }


        return $return;
    }

    protected function _aesEncrypt($string = '')
    {
        if (empty($string) === true)
        {
            return $string;
        }

        $key = $this->_encryption_key;

        if (empty($key) === true)
        {
            return $string;
        }

        return '[' . _encrypt($string, $key, '', 'AES-256-ECB');
    }

    // decrypt
    protected function _aesDecrypt($string = '')
    {
        if (empty($string) === true)
        {
            return $string;
        }

        $key = $this->_encryption_key;

        if (empty($key) === true)
        {
            return $string;
        }

        return _decrypt(substr($string, 1), $key, '', 'AES-256-ECB');
    }
}
