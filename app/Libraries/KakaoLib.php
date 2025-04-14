<?php
namespace App\Libraries;

use Config\Talk;
use App\Models\KakaoModel;
use Shared\Config as SharedConfig;
use App\Libraries\FcmLib;
use App\Models\LicenseModel;

class KakaoLib
{
    private $sharedConfig;
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->sharedConfig                        = new SharedConfig();
    }

    public function sendATalk( $param = [])
    {

        $config = $this->sharedConfig::$talk;

        $talk   = _elm( $config , 'item' );

        $kakao_model = new KakaoModel();

        $template = $kakao_model->getTemplate( _elm( $param, 'TEMPLATE_CODE') );

        foreach( _elm( $param, 'typeValues' )  as $k => $v){
            if(in_array($k, $talk)){
                $replace_txt=array_search($k, $talk);
                $template['TEMPLATE_CONTENT'] =str_replace($replace_txt, $v,$template['TEMPLATE_CONTENT']);
            }
        }


        $buttons = [];
        if(empty($template['BUTTONS']) === false ){
            $_button = json_decode( $template['BUTTONS'] , true );
            $buttons['BTN_NM_0'.$_button['ordering'] ] = $_button['name'];
            $buttons['BTN_TYPE_0'.$_button['ordering'] ] = $_button['linkType'];

            foreach($param['typeValues'] as $k => $v){
                if(in_array($k, $talk)){
                    $replace_txt=array_search($k, $talk);
                }
            }
            $buttons['BTN_0'.$_button['ordering'].'_URL_01' ] = $_button['linkMo'];
            $buttons['BTN_0'.$_button['ordering'].'_URL_02' ] = $_button['linkPc'];
        }

        $USERCODE      = empty( $param['USERCODE'] ) === true ? 'timber' : $param['USERCODE'];
        $RECEIVER_NAME = empty( $param['RECEIVER_NAME'] ) === true ? '산사유람' : $param['RECEIVER_NAME'];
        $DEPTCODE      = empty( $param['DEPTCODE'] ) === true ? 'AE-OD0-2Q' : $param['DEPTCODE'];
        $REQPHONE      = empty( $param['REQPHONE'] ) === true ? '15775584' : $param['REQPHONE'];
        $CALLNAME      = empty( $param['CALLNAME'] ) === true ? '산수유람' : $param['CALLNAME'];
        $SUBJECT       = empty( $param['SUBJECT'] ) === true ? NULL : $param['SUBJECT'];
        $REQTIME       = empty( $param['REQTIME'] ) === true ? NULL : $param['REQTIME'];
        $YELLOWID_KEY  = empty( $param['YELLOWID_KEY'] ) === true ? $template['SENDER_KEY'] : $param['YELLOWID_KEY'];
        $MESSAGE       = $template['TEMPLATE_CONTENT'];
        $CALLPHONE     = $param['CALLPHONE'];
        $REQTIME       = $param['REQTIME'] ?? '00000000000000';
        $RESULT        = $REQTIME == '00000000000000' ? 0 : 'R';
        $KIND          = 'T';
        $TEMPLATECODE  = $param['TEMPLATE_CODE'];
        $RESEND        = empty( $param['RESEND'] ) === false ?$param['RESEND'] :'Y';
        $AGENT_FLAG    = empty( $param['AGENT_FLAG'] ) === true ? rand(0,4) : $param['AGENT_FLAG'] ;

        $data = array(
            'USERCODE'  => $USERCODE,
            'BIZTYPE'   => 'at',
            'DEPTCODE'  => $DEPTCODE,
            'YELLOWID_KEY' => $YELLOWID_KEY,
            'REQNAME'   => $RECEIVER_NAME,
            'REQPHONE'  => $REQPHONE,
            'CALLNAME'  => $CALLNAME,
            'CALLPHONE' => $CALLPHONE,
            'SUBJECT'   => $SUBJECT,
            'MSG'       => $MESSAGE,
            'REQTIME'   => $REQTIME,
            'RESULT'    => $RESULT,
            'KIND'      => $KIND,
            'TEMPLATECODE'=> $TEMPLATECODE,
            'RESEND' => $RESEND,
            'AGENT_FLAG' => $AGENT_FLAG

        );


        $data = array_merge($data , $buttons);


        $return_value = $kakao_model->INSERT_ATALK($data);


        #------------------------------------------------------------------
        # TODO: FCM 메시지 발송등록
        #------------------------------------------------------------------
        try{
            if( empty( $param['FCM_SEND'] ) === false && $param['FCM_SEND'] === true ){

                $fcm                                    = new FcmLib;
                $licenseModel                           = new LicenseModel();

                $deviceLists                            = $licenseModel->getUserDeviceLists( $param['MB_IDX'] );


                if( empty( $deviceLists ) === false ){
                    foreach( $deviceLists as $dKey => $device ){
                        $oParam                         = [];

                        $fcm->sendPushNotification( _elm( $device, 'DI_TOKEN' ), _elm( $template, 'TEMPLATE_NAME'), $MESSAGE );
                    }
                }

            }
        } catch ( \Exception $e ){

            #------------------------------------------------------------------
            # TODO: 에러리턴
            #------------------------------------------------------------------
            $response['status']                          = 400;
            $response['error']                           = 400;
            $response['messages']                        = $e->getMessage();

        }



        return $return_value;

    }







    // public function getTemplateData( $code )
    // {
    //     $CI =& get_instance();
    //     $CI->load->database();
    //     $CI->load->model('kakao_model');

    //     $template = [];
    //     $template = $CI->kakao_model->getTemplate($code);

    //     return $template;
    // }

}