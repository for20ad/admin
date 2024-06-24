<?php
namespace Module\musinsa\Controllers;
use Module\core\Controllers\CoreController;
use Module\musinsa\Models\MusinsaModel;
use Config\Services;
use DOMDocument;

use CodeIgniter\Format\XMLFormatter;

use CodeIgniter\Debug\Toolbar\Collectors\Views;

use Exception;

class Musinsa extends CoreController
{
    protected $id              = 'timber';
    protected $api_key         = '9227cfe5453e49940f108782fa150b68';


    public function __construct()
    {

        parent::__construct();
    }

    public function index()
    {

        #------------------------------------------------------------------
        # TODO: 브랜드 리스트
        #------------------------------------------------------------------
        // $url                        = 'https://bizest.musinsa.com/api/bizest/rest/brand.php';

        // $optionsParam               = [];
        // $optionsParam['ctype']      = 'xml';
        // $optionsParam['charset']    = 'utf-8';
        // $optionsParam['use_yn']     = 'Y';

        #------------------------------------------------------------------
        # TODO: 싱픔장보
        #------------------------------------------------------------------
        $url                                     = 'https://bizest.musinsa.com/api/bizest/rest/goods.php';

        $optionsParam                            = [];
        $optionsParam['ctype']                   = 'xml';
        $optionsParam['charset']                 = 'utf-8';
        $optionsParam['c']                       = 'info';
        $optionsParam['biz_goods_info']          = '4011805-2|^,4011805-3|^';

        #------------------------------------------------------------------
        # TODO: 싱픔 속성 정보
        #------------------------------------------------------------------
        // $url                                     = 'https://bizest.musinsa.com/api/bizest/rest/goods_attribute.php';

        // $optionsParam                            = [];
        // $optionsParam['ctype']                   = 'xml';
        // $optionsParam['charset']                 = 'utf-8';
        // $optionsParam['c']                       = 'list';





        $datas = $this->_curl( $url, $optionsParam );

        #------------------------------------------------------------------
        # TODO: 리턴 데이터가 xml밖에 없음. xml데이터를 배열로 변경
        #------------------------------------------------------------------
        $resultArray = [];
        if( empty( $datas ) === false ){
            foreach ($datas as $xmlElement) {
                $resultArray[] = json_decode(json_encode($xmlElement), true);
            }
        }

        $formmater = new XMLFormatter();


        $dd = [
            'aa'=>['aa1'=>'aa-1','aa2'=>'aa-2'],
            'bb'=>['bb1'=>'bb-1','bb2'=>'bb-2'],
        ];

        $aa = $formmater->format( $dd );




        #------------------------------------------------------------------
        # TODO: 페이징
        #------------------------------------------------------------------
        $pageDatas                  = [];
        $pageDatas['lists']         = $resultArray;

        #------------------------------------------------------------------
        # TODO: 메인 뷰 처리
        #------------------------------------------------------------------

        $pageParam                  = [];
        $pageParam['file']          = "\Module\musinsa\Views\index";
        $pageParam['pageLayout']    = '';
        $pageParam['pageDatas']     = $pageDatas;

        $this->owensView->loadLayoutView( $pageParam );

    }

    private function makeHtmlFormReplce( $data )
    {
        $result = str_replace('<html>', '<?xml version="1.0" encoding="UTF-8"?>',$data);
        $result = str_replace('<head></head>', '',$data);
        $result = str_replace('<body>', '<result>',$data);
        $result = str_replace('</body>','', $data);
        $result = str_replace('</html>','</result>',$data);

        return $result;
    }

    private function convertToXml( $data )
    {
        $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $xml;
    }



    private function _curl( $_url, $_options = [] , $method = true)
    {

        $autKey     = 'key='.$this->api_key;
        $id         = 'id='.$this->id;
        $options    = [];

        if( empty( $_options ) === false ){
            foreach( $_options as $key => $val ){
                $options[] = $key.'='.$val;
            }
        }



        $_curl_init = $_url.'?'.$id.'&'.$autKey;


        if( empty( $options ) === false ){
            $_curl_init .= '&'.join( '&', $options );
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/xml','Accept:application/xml']);
        curl_setopt($curl, CURLOPT_URL, $_curl_init  );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POST, $method);


        $response = curl_exec($curl);
        curl_close($curl);

        #------------------------------------------------------------------
        # TODO:  , 'SimpleXMLElement', LIBXML_NOCDATA 옵션 없이 사용시
        # html 태그가 나와 걸러야 한다.
        #------------------------------------------------------------------
        //$_response = $this->makeHtmlFormReplce($response);

        $xml       = $this->convertToXml($response);
        $data      = $xml;



        return $data;
    }


}