<?php
namespace App\Libraries;

use Config\Talk;
use App\Models\KakaoModel;

class KakaoLib
{
    public function sendATalk( $param = [])
    {

        $config                                     = new Talk();
        $talk                                       = _elm( $config->talk , 'item' );

        $kakao_model                                = new KakaoModel();

        $template                                   = $kakao_model->getTemplate( _elm( $param, 'TEMPLATE_CODE') );



        foreach( _elm( $param, 'typeValues' )  as $k => $v){
            if(in_array($k, $talk)){
                $replace_txt=array_search($k, $talk);
                $template['TEMPLATE_CONTENT']       = str_replace($replace_txt, $v,$template['TEMPLATE_CONTENT']);
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

        $USERCODE                                   = empty( $param['USERCODE'] ) === true ? 'sungwoobook1' : $param['USERCODE'];
        $RECEIVER_NAME                              = empty( $param['RECEIVER_NAME'] ) === true ? '산사유람' : $param['RECEIVER_NAME'];
        $DEPTCODE                                   = empty( $param['DEPTCODE'] ) === true ? 'CD-MG3-DF' : $param['DEPTCODE'];
        $REQPHONE                                   = empty( $param['REQPHONE'] ) === true ? '0313898800' : $param['REQPHONE'];
        $CALLNAME                                   = empty( $param['CALLNAME'] ) === true ? '산수유람' : $param['CALLNAME'];
        $SUBJECT                                    = empty( $param['SUBJECT'] ) === true ? NULL : $param['SUBJECT'];
        $REQTIME                                    = empty( $param['REQTIME'] ) === true ? NULL : $param['REQTIME'];
        $YELLOWID_KEY                               = empty( $param['YELLOWID_KEY'] ) === true ? $template['SENDER_KEY'] : $param['YELLOWID_KEY'];
        $MESSAGE                                    = $template['TEMPLATE_CONTENT'];
        $CALLPHONE                                  = $param['CALLPHONE'];
        $REQTIME                                    = $param['REQTIME'] ?? '00000000000000';
        $RESULT                                     = $REQTIME == '00000000000000' ? 0 : 'R';
        $KIND                                       = 'T';
        $TEMPLATECODE                               = $param['TEMPLATE_CODE'];
        $RESEND                                     = empty( $param['RESEND'] ) === false ?$param['RESEND'] :'Y';
        $AGENT_FLAG                                 = empty( $param['AGENT_FLAG'] ) === true ? rand(0,4) : $param['AGENT_FLAG'] ;

        $data                                       = array(
            'USERCODE'                              => $USERCODE,
            'BIZTYPE'                               => 'at',
            'DEPTCODE'                              => $DEPTCODE,
            'YELLOWID_KEY'                          => $YELLOWID_KEY,
            'REQNAME'                               => $RECEIVER_NAME,
            'REQPHONE'                              => $REQPHONE,
            'CALLNAME'                              => $CALLNAME,
            'CALLPHONE'                             => $CALLPHONE,
            'SUBJECT'                               => $SUBJECT,
            'MSG'                                   => $MESSAGE,
            'REQTIME'                               => $REQTIME,
            'RESULT'                                => $RESULT,
            'KIND'                                  => $KIND,
            'TEMPLATECODE'                          => $TEMPLATECODE,
            'RESEND'                                => $RESEND,
            'AGENT_FLAG'                            => $AGENT_FLAG

        );


        $data                                       = array_merge($data , $buttons);
        $return_value                               = $kakao_model->INSERT_ATALK($data);
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