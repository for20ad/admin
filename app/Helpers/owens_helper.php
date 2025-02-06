<?php
use CodeIgniter\HTTP\IncomingRequest;
/**
 * @File   : owens_helper.php
 * @Date   : 2024-04-02 18:05:02
 * @Desc   : owens 공용 helper
*/

use  Module\setting\Models\MemberModel;
use  App\Libraries\MenuLib;
use Config\Site as SiteConfig;



if(!function_exists('getMemuData')){
    function getMemuData()
    {
        $session = \Config\Services::session();
        $menuLib = new MenuLib();
        $menu    = [];
        $site_config = new SiteConfig;
        if (!empty($session->get('_memberInfo')))
        {
            $admin_group = _elm( $session->get('_memberInfo'), 'member_group_idx' );
            $_menus        = $menuLib->getAdminGroupMenu( $admin_group );
            if( empty( $_menus ) === false ){
                foreach( $_menus as  $key => $_menu ){

                    $menuConf = [];
                    $menuConf = _elm($site_config->adminMenuRules , _elm( $_menu, 'MENU_GROUP_ID') );
                    $_menus[$key]['WIDTH'] = _elm( $menuConf, 'width' );
                    $_menus[$key]['HEIGHT'] = _elm( $menuConf, 'height' );
                    $_menus[$key]['VIEWBOX'] = _elm( $menuConf, 'viewBox' );
                    $_menus[$key]['PATH'] = _elm( $menuConf, 'path' );

                }
            }
            $menu = $_menus;
        }
        return $menu;
    }
}



if(!function_exists('getGrantName')){
    function getGrantName()
    {
        $session = \Config\Services::session();
        $levelTxt = '';
        if (!empty($session->get('_memberInfo')))
        {
            $admin_level = _elm( $session->get('_memberInfo'), 'member_level' );
            $memberModel = new MemberModel();
            $levelTxt    = $memberModel->getAdminMemberGroup($admin_level);


        }
        return $levelTxt;
    }
}

if (!function_exists('contains_banned_words')) {
    function contains_banned_words($text){
        $bannedWords = getenv('BANNED_WORDS');

        if (!$bannedWords) {
            return false;
        }

        $bannedWordsArray = explode(',', $bannedWords);

        foreach ($bannedWordsArray as $bannedWord) {
            if (stripos($text, trim($bannedWord)) !== false) {
                return true;
            }
        }
        return false;
    }
}

if( ! function_exists( '_parseJsonFormData' ) )
{
    function _parseJsonFormData($debug = false) {
        $request = \Config\Services::request();
        #------------------------------------------------------------------
        # TODO: GET파라메터가 있으면 GET 리턴 아니면 json 리턴
        #------------------------------------------------------------------
        $contentType = $request->getHeaderLine('Content-Type');
        //echo "contentType:: ".$contentType;

        if (!empty($request->getGet())) {
            $returnData = _trim($request->getGet());
        } else if (!empty($request->getPost()) && strpos($contentType, 'application/x-www-form-urlencoded') == -1) {
            $returnData = _trim($request->getPost());
        } else {
            $data = [];
            #------------------------------------------------------------------
            # TODO: method에 따라 데이터 파싱방법 변경
            #------------------------------------------------------------------
            if (strpos($contentType, 'multipart/form-data') !== false) {

                $input = file_get_contents('php://input');

                if( empty( $input ) === true ){

                    $data = _trim($request->getPost());
                }
                else if (preg_match('/boundary=(.*)$/', $contentType, $matches)) {
                    $boundary = $matches[1];
                    $blocks = preg_split("/-+$boundary/", $input);
                    array_pop($blocks);

                    foreach ($blocks as $block) {
                        if (empty($block)) continue;
                        if (strpos($block, 'Content-Disposition: form-data;') !== false) {
                            list($header, $body) = explode("\r\n\r\n", $block, 2);
                            $body = trim($body);
                            if (preg_match('/name="([^"]*)"/', $header, $matches)) {
                                $name = $matches[1];
                                if (preg_match('/filename="([^"]*)"/', $header, $matches)) {
                                    // 파일 처리
                                    $filename = $matches[1];
                                    $tmpName = tempnam(sys_get_temp_dir(), 'php');
                                    file_put_contents($tmpName, $body);
                                    $data[$name] = [
                                        'name' => $filename,
                                        'full_path' => $filename, // full_path 추가
                                        'type' => preg_match('/Content-Type: ([^;]+)/', $header, $matches) ? $matches[1] : 'application/octet-stream',
                                        'tmp_name' => $tmpName,
                                        'error' => 0,
                                        'size' => strlen($body)
                                    ];
                                } else {
                                    // 일반 데이터 처리
                                    $data[$name] = $body;
                                }
                            }
                        }
                    }
                } else {
                    // 바운더리가 없는 경우
                    $input = file_get_contents('php://input');
                    $data = json_decode($input, true);
                }
            } elseif (strpos($contentType, 'application/json') !== false) {
                #------------------------------------------------------------------
                # TODO: request로 받을때
                #------------------------------------------------------------------

                $data = $request->getJSON(true);
            }else{
                $data = $request->getJSON(true);

            }

            #------------------------------------------------------------------
            # TODO: 데이터가 배열이 아닌 경우 배열로 변환
            #------------------------------------------------------------------
            if (!is_array($data)) {
                $data = [$data];
            }

            #------------------------------------------------------------------
            # TODO: 배열의 첫 번째 요소가 다시 배열인지 확인하여 처리
            #------------------------------------------------------------------
            if (isset($data[0]) && is_array($data[0])) {
                #------------------------------------------------------------------
                # TODO: 배열이 1개 초과시 배열로 반환
                #------------------------------------------------------------------
                if (count($data) > 1) {
                    $returnData = array_map('_trim', $data);
                } else {
                    #------------------------------------------------------------------
                    # TODO: 배열 내 첫 번째 요소 반환
                    #------------------------------------------------------------------
                    $returnData = _trim($data[0]);
                }
            } else {
                #------------------------------------------------------------------
                # TODO: 배열 형태가 아닌 경우 (단일 객체 형태)
                #------------------------------------------------------------------
                $returnData = _trim($data);
            }
        }

        #------------------------------------------------------------------
        # TODO: Debug 모드
        #------------------------------------------------------------------
        if ($debug == true) {
            echo '<pre>';
            print_r($returnData);
            echo '</pre>';
        }

        return $returnData;
    }


}

#------------------------------------------------------------------
# TODO: 스트링 형식을 bool 형식으로 true인것들만 변경
#------------------------------------------------------------------
if (! function_exists('_stringToBool'))
{
    function _stringToBool($text) {
        $text = strtolower($text);
        return in_array($text, array("true", "1", "yes", "on"), true);
    }
}
// 2024-04-08 16:31:25 - string 일 경우만 trim 처리
// 20202-04-15 16:09:33 - trim 오류 수정
#------------------------------------------------------------------
# TODO: 배열 공백제거
#------------------------------------------------------------------
if (! function_exists('_trim'))
{
    function _trim($data)
    {
        if (is_array($data) === true)
        {
            array_walk_recursive($data, function(&$v) {
                if (is_string($v) === true)
                {
                    $v = trim($v);
                }
            });
            return $data;
        }
        else
        {
            return (is_string($data) === true) ? trim($data) : $data;
        }
    }
}
#------------------------------------------------------------------
# TODO: 배열 태그제거
#------------------------------------------------------------------
if (! function_exists('_strip_tags'))
{
    function _strip_tags($data, $aExcludeKeys = [])
    {
        if (is_array($data) === true)
        {
            array_walk_recursive($data, function(&$v, $k) use ($aExcludeKeys) {
                if (in_array($k, $aExcludeKeys) === false)
                {
                    $v = strip_tags($v);
                }
            });
            return $data;
        }
        else
        {
            return strip_tags($data);
        }
    }
}

#------------------------------------------------------------------
# TODO: 배열 - 빈값 체크
#------------------------------------------------------------------
if (! function_exists('_empty'))
{
    function _empty($data, $bAllowZero = false, $sTag = null)
    {
        if (is_string($data) === true || is_numeric($data) === true)
        {
            // $data = str_replace('&nbsp;', ' ', $data);
            $data = html_entity_decode($data);
            $data = str_replace(' ', ' ', $data);
            $data = strip_tags($data, $sTag);
        }

        $data = _trim($data);

        if (is_numeric($data) === true && $data == 0)
        {
            if ($bAllowZero === true)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            if (empty($data) === true)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}

// 2024-04-18 13:38:01 - return 시 _trim 제거
// 2024-05-10 16:30:39 - $bEmptyReplaceValue = true 시 _empty가 true 일 경우 $defaultValue 리턴

if (! function_exists('_elm'))
{
    function _elm($aHaystack, $needle, $defaultValue = NULL, $bEmptyReplaceValue = false)
    {
        if (is_array($aHaystack) === true)
        {
            if (array_key_exists($needle, $aHaystack) === true)
            {
                if ($bEmptyReplaceValue === true)
                {
                    if (_empty($aHaystack[$needle]) === true)
                    {
                        return $defaultValue;
                    }
                    else
                    {
                        return $aHaystack[$needle];
                    }
                }
                else
                {
                    return $aHaystack[$needle];
                }
            }
            else
            {
                return $defaultValue;
            }
        }
        else
        {
            if ($needle == $aHaystack)
            {
                if ($bEmptyReplaceValue === true)
                {
                    if (_empty($needle) === true)
                    {
                        return $defaultValue;
                    }
                    else
                    {
                        return $needle;
                    }
                }
                else
                {
                    return $needle;
                }
            }
            else
            {
                return $defaultValue;
            }
        }
    }
}

if (! function_exists('getSelectStatusGroupOptions'))
{
    function getSelectStatusGroupOptions($orderTitle, $orderStatus, $orderBtns) {
        $output = "";
        foreach ($orderTitle as $key => $title) {
            $output .= "<optgroup label=\"{$title}\">\n";
            if (isset($orderStatus[$key])) {
                foreach ($orderStatus[$key] as $statusKey) {
                    if (isset($orderBtns[$statusKey])) {
                        $output .= "<option value=\"{$statusKey}\">{$orderBtns[$statusKey]}</option>\n";
                    }
                }
            }
            $output .= "</optgroup>\n";
        }
        return $output;
    }
}

#------------------------------------------------------------------
# TODO: json 출력
#------------------------------------------------------------------
if (! function_exists('_print_json'))
{
    /**
     * 결과 json 리턴
     *
     * @param array $param
     * @return
     */
    function _print_json($param = [], $return = false)
    {
        if (empty($return) === true)
        {
            $return = false;
        }

        $result = json_encode($param, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        // $this->output->set_content_type('application/json')->set_output(json_encode($param, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));

        if ($return === true)
        {
            return $result;
        }
        header('Content-Type: application/json');
        echo $result;

        return true;
    }
}

#------------------------------------------------------------------
# TODO: 트리메뉴 사용
#------------------------------------------------------------------
if (! function_exists('_build_tree'))
{
    function _build_tree($elements = [], $parentIdx = 0, $id = 'MENU_IDX', $parentId = 'MENU_PARENT_IDX', $childId = 'CHILD')
    {
        $branch = [];

        foreach ($elements as $element)
        {
            if (_elm($element, $parentId) == $parentIdx)
            {
                $child = _build_tree($elements, _elm($element, $id), $id, $parentId, $childId);

                if (empty($child) === false)
                {
                    $element[$childId] = $child;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }
}

if (! function_exists('_build_option'))
{
    function _build_option($elements = [], $key = 'KEY', $value = 'VALUE', $childId = 'CHILD', $depth = 0)
    {
        $branch = [];
        $depth++;
        foreach ($elements as $element)
        {
            $branch[_elm($element, $key)] = _elm($element, $value);

            if (empty($element[$childId]) === false)
            {
                $child = _build_option($element[$childId], $key, $value, $childId, $depth);

                foreach ($child as $kKEY => $vVALUE)
                {
                    $branch[$kKEY] = _elm($element, 'BRD_CATEGORY_NAME') . ' > '. $vVALUE;
                }
            }
        }

        return $branch;
    }
}

if (! function_exists('_starts_with'))
{
    function _starts_with($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
}

if (! function_exists('_ends_with'))
{
    function _ends_with($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }
}

if (! function_exists('_uniqid'))
{
    function _uniqid($length = 16, $readable = false, $type = 'string')
    {
        $sReturn = '';

        if( $type=='string' ){

            if ($readable === false)
            {
                // uniqid gives 13 chars, but you could adjust it to your needs.
                if (function_exists("random_bytes"))
                {
                    $bytes = random_bytes(ceil($length / 2));
                }
                else if (function_exists("openssl_random_pseudo_bytes"))
                {
                    $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
                }
                else
                {
                    $bytes = '';
                }

                $sReturn = substr(bin2hex($bytes), 0, $length);
            }
            else
            {
                $characters = '23456789abcdehkmnpswxABCDEFGHKLMNPQRSTWXZ';

                $sReturn = substr(str_shuffle(str_repeat($characters, mt_rand(1, $length))), 1, $length);

                // $max = strlen($characters) - 1;

                // for ($i = 0; $i < $length; $i++) {
                //     $sReturn .= $characters[rand(0, $max)];
                // }
            }
        }else{
            $sReturn = (int) (microtime(true) * 10000) + random_int(1000, 9999);
        }


        return $sReturn;
    }
}
if (! function_exists('_uniqid2'))
{
    function _uniqid2($length = 16, $readable = false, $type = 'string')
    {
        $sReturn = '';

        if ($type == 'string') {
            if ($readable === false) {
                // 더 안전한 난수 생성
                if (function_exists("random_bytes")) {
                    $bytes = random_bytes(ceil($length / 2));
                } else if (function_exists("openssl_random_pseudo_bytes")) {
                    $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
                } else {
                    $bytes = uniqid(mt_rand(), true);
                }
                $sReturn = substr(bin2hex($bytes), 0, $length);
            } else {
                // 사람이 읽기 쉬운 문자열
                $characters = '23456789abcdehkmnpswxABCDEFGHKLMNPQRSTWXZ';
                $max = strlen($characters) - 1;

                $sReturn = '';
                for ($i = 0; $i < $length; $i++) {
                    $sReturn .= $characters[random_int(0, $max)];
                }
            }
        } else {
            // 숫자 기반 고유 ID 생성
            $timePart = (int)(microtime(true) * 10000); // 초 단위로 정밀도 강화
            $randomPart = random_int(10000, 99999); // 더 큰 범위의 난수 추가
            $sReturn = $timePart + $randomPart;
        }

        return $sReturn;
    }
}

if (! function_exists('_is_json'))
{
    function _is_json($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (! function_exists('_find_in_set'))
{
    function _find_in_set($needle, $haystack, $sSeprateStr = ',')
    {
        $aHaystack = _trim(explode($sSeprateStr, $haystack));

        if (in_array($needle, $aHaystack) === true)
        {
            return true;
        }

        return false;
    }
}
if ( ! function_exists('random_string'))
{
	/**
	 * Create a "Random" String
	 *
	 * @param	string	type of random string.  basic, alpha, alnum, numeric, nozero, unique, md5, encrypt and sha1
	 * @param	int	number of characters
	 * @return	string
	 */
	function random_string($type = 'alnum', $len = 8)
	{
		switch ($type)
		{
			case 'basic':
				return mt_rand();
			case 'alnum':
			case 'numeric':
			case 'nozero':
			case 'alpha':
				switch ($type)
				{
					case 'alpha':
						$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'alnum':
						$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'numeric':
						$pool = '0123456789';
						break;
					case 'nozero':
						$pool = '123456789';
						break;
				}
				return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
			case 'unique': // todo: remove in 3.1+
			case 'md5':
				return md5(uniqid(mt_rand()));
			case 'encrypt': // todo: remove in 3.1+
			case 'sha1':
				return sha1(uniqid(mt_rand(), TRUE));
		}
	}
}

if (! function_exists('_set_unit_cutting'))
{
    function _set_unit_cutting( int $unit = 0, int $cut = 10000)
    {
        return floor( $unit / $cut);
    }
}

if (! function_exists('convert_to_won')) {
    function convert_to_won($amount)
    {
        $amount_in_won = $amount * 10000;

        return $amount_in_won;
    }
}

if (! function_exists('_crypt'))
{
    function _crypt($type, $p_sSTRING, $p_sPASSWORD = '', $p_sIV = '', $p_sMETHOD = '')
    {
        if (empty($p_sSTRING) === true) return $p_sSTRING;
        if (empty($p_sPASSWORD) === true) $p_sPASSWORD = 'owens';
        if (empty($p_sIV) === true) $p_sIV = 'owens_crypt_001';
        if (empty($p_sMETHOD) === true) $p_sMETHOD = 'AES-256-CBC';

        $i_iIV_LENGTH = openssl_cipher_iv_length($p_sMETHOD);

        if ($i_iIV_LENGTH === false)
        {
            $p_sMETHOD    = 'AES-256-CBC';
            $i_iIV_LENGTH = openssl_cipher_iv_length($p_sMETHOD);
        }

        $p_sIV = substr(str_pad($p_sIV, $i_iIV_LENGTH, '0'), 0, $i_iIV_LENGTH);

        if ($type == 'encrypt')
        {
            return openssl_encrypt($p_sSTRING, $p_sMETHOD, $p_sPASSWORD, false, $p_sIV);
        }
        else if ($type == 'decrypt')
        {
            return openssl_decrypt($p_sSTRING, $p_sMETHOD, $p_sPASSWORD, false, $p_sIV);
        }
    }
}

if (! function_exists('_encrypt'))
{
    function _encrypt($p_sSTRING, $p_sPASSWORD = '', $p_sIV = '', $p_sMETHOD = '')
    {
        return _crypt('encrypt', $p_sSTRING, $p_sPASSWORD, $p_sIV, $p_sMETHOD);
    }
}

if (! function_exists('_decrypt'))
{
    function _decrypt($p_sSTRING, $p_sPASSWORD = '', $p_sIV = '', $p_sMETHOD = '')
    {
        return _crypt('decrypt', $p_sSTRING, $p_sPASSWORD, $p_sIV, $p_sMETHOD);
    }
}