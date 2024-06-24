<?php
namespace Module\auth\Controllers;
use Module\core\Controllers\CoreController;
use Module\auth\Models\AuthModel;
use Config\Services;
use DOMDocument;


use Exception;

class Test extends CoreController
{

    public function __construct()
    {

        parent::__construct();
    }

    public function index()
    {
        $url = "https://sansuyuram.com/board/view.php?&bdId=TNBreview&sno=697";

        $aResult = $this->makeShortUrl($url);
        echo $aResult;
    }
    // public function index()
    // {
    //     $model          = new TestModel();

    //     $url = 'https://www.koreaexim.go.kr/site/program/financial/exchangeJSON?';
    //     //$searchDate = '20240403';
    //     $start_date = '20240401';
    //     $end_date = '20240408';
    //     $authKey = 'xOONm7P51q0yEAJLkLdxY1cHj7pRuKAl';

    //     for ($searchDate = $start_date; strtotime($searchDate) <= strtotime($end_date); $searchDate = date('Ymd', strtotime($searchDate . ' +1 day'))) {

    //         $result = $this->_curl( $url, $authKey, $searchDate );
    //         if( empty( $result ) === false ){
    //             foreach( $result as $key => $data ){
    //                 $modelParam = [];
    //                 $modelParam['RESULT_CD'] = _elm( $data, 'result' );
    //                 $modelParam['F_DATE'] = date('Y-m-d', strtotime( $searchDate ) );
    //                 $_cur_nm = explode( ' ', _elm( $data, 'cur_nm' ) );

    //                 switch(  _elm( $_cur_nm, 0 ) ){
    //                     case '위안화' :
    //                         $modelParam['F_LOC'] = '중국';
    //                         $modelParam['F_CUR_NM'] =  _elm( $_cur_nm, 0 );
    //                         break;
    //                     case '유로':
    //                         $modelParam['F_LOC'] = '유럽';
    //                         $modelParam['F_CUR_NM'] =  _elm( $_cur_nm, 0 );
    //                         break;
    //                     default:
    //                         $modelParam['F_LOC'] = _elm( $_cur_nm, 0 );
    //                         $modelParam['F_CUR_NM'] =  _elm( $_cur_nm, 1 );
    //                         break;

    //                 }
    //                 $modelParam['F_CUR_UNIT'] = _elm( $data, 'cur_unit' );

    //                 $modelParam['F_TTB'] = str_replace( ',', '', _elm( $data, 'ttb' ) );
    //                 $modelParam['F_TTS'] = str_replace( ',', '', _elm( $data, 'tts' ) );
    //                 $modelParam['F_DEAL_BAS_R'] = str_replace( ',', '', _elm( $data, 'deal_bas_r' ) );
    //                 $modelParam['F_BKPR'] = str_replace( ',', '', _elm( $data, 'bkpr' ) );
    //                 $modelParam['F_YY_EFEE_R'] = str_replace( ',', '', _elm( $data, 'yy_efee_r' ) );
    //                 $modelParam['F_TEN_DD_EFEE_R'] = str_replace( ',', '', _elm( $data, 'ten_dd_efee_r' ) );
    //                 $modelParam['F_KFTC_BKPR'] = str_replace( ',', '', _elm( $data, 'kftc_bkpr' ) );
    //                 $modelParam['F_KFTC_DEAL_BAR_R'] = str_replace( ',', '', _elm( $data, 'kftc_deal_bas_r' ) );

    //                 $chked = $model->sameChecked( $modelParam );
    //                 if( $chked < 1 ){
    //                     $model->setExchangeData( $modelParam );
    //                 }

    //             }
    //         }
    //     }

    //     // echo "<pre>";
    //     // print_r( $result );
    //     // echo "</pre>";


    // }

    public function exchangeShinhan()
    {
        $html = file_get_contents("https://naver.com");

        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        echo "<pre>";
        print_r( $dom );
        echo "</pre>";
        $element = $dom->getElementById('account');
        if ($element !== null) {
            $content = $element->nodeValue;
            echo $content;
        } else {
            echo "해당 요소를 찾을 수 없습니다.";
        }
    }



    private function _curl( $_url, $_authKey, $_searchDate)
    {

        $autKey = 'authkey='.$_authKey;
        $options = '&data=AP01';



        $searchDate = '&searchDate='.date('Ymd',strtotime($_searchDate));
        $curl_url= $_url.$autKey.$searchDate.$options;

        echo $curl_url;



        $curl = curl_init();
        // https://www.koreaexim.go.kr/site/program/financial/exchangeJSON?authkey=xOONm7P51q0yEAJLkLdxY1cHj7pRuKAl&searchdate=20240404&data=AP01
        // https://www.koreaexim.go.kr/site/program/financial/exchangeJSON?authkey=xOONm7P51q0yEAJLkLdxY1cHj7pRuKAl&searchDate=20240404&data=AP01
        curl_setopt($curl, CURLOPT_URL, 'https://www.koreaexim.go.kr/site/program/financial/exchangeJSON?authkey=xOONm7P51q0yEAJLkLdxY1cHj7pRuKAl&searchdate='.date('Ymd',strtotime($_searchDate)).'&data=AP01');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POST, false);


        $response = curl_exec($curl);
        curl_close($curl);

        print_r( $response );

        $data = json_decode($response, true);



        return $data;
    }

    protected function makeShortUrl( $url )
    {
        $baseUrl = 'https://api.sansuyuram.com/';
        // UUID 생성
        $uuid = uniqid();
        // URL을 base64로 인코딩 후 md5로 해시하여 그 일부를 6자리 결합
        $hash = substr( md5( base64_encode( $url ) ), 0, 6);

        // UUID 뒤 3자리와 해시를 결합하여 단축 URL 생성
        $lastCd = $hash.substr($uuid,-3);
        $shortURL = $lastCd;
        return  $baseUrl.$shortURL;
    }
}
