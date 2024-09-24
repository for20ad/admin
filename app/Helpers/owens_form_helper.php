<?php
#------------------------------------------------------------------
# owens_form_helper.php
# form input 관련
# 김우진
# 2024-07-03 11:51:13
# @Desc :
#------------------------------------------------------------------
use CodeIgniter\HTTP\IncomingRequest;

use Config\Site as SiteConfig;
if (!function_exists('getPostSearch')) {
    function getPostSearch( $param = [] ){
        // 기본값 설정
        $postHtml = '
            <div id="postLayer" style="z-index:9999; position:fixed; overflow:hidden; display:none;">
                <span class="close" onclick="closeDaumPostcode()" style="cursor:pointer; position:absolute; right:10px; top:10px; z-index:1001; background:#fff; border:1px solid #ddd; border-radius:50%; padding:5px; width:25px; height:25px; text-align:center; line-height:15px;">X</span>
                <div id="layer-content" style="width:100%; height:4vh;"></div>
            </div>

            <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
            <script>
            function execDaumPostcode( zipid, addr) {
                var element_layer = document.getElementById("postLayer");
                new daum.Postcode({
                    oncomplete: function(data) {
                        var fullAddr = data.roadAddress; // 도로명 주소 변수
                        var extraAddr = ""; // 참고 항목 변수

                        // 도로명 조합형 주소가 있을 경우
                        if(data.bname !== "" && /[동|로|가]$/g.test(data.bname)) {
                            extraAddr += data.bname;
                        }
                        // 건물명 조합형 주소가 있을 경우
                        if(data.buildingName !== "" && data.apartment === "Y") {
                            extraAddr += (extraAddr !== "" ? ", " + data.buildingName : data.buildingName);
                        }
                        // 표시할 참고 항목이 있을 경우
                        if(extraAddr !== "") {
                            extraAddr = " (" + extraAddr + ")";
                        }
                        // 조합된 참고 항목을 포함한 주소 변수
                        fullAddr += extraAddr;
                        $("#"+zipid).val(data.zonecode);
                        $("#"+addr).val(fullAddr); // 주소 정보를 해당 필드에 입력

                        // iframe을 넣은 element를 안보이게 한다.
                        element_layer.style.display = "none";
                    },
                    onclose:function(state){
                        if(state === "COMPLETE_CLOSE"){ // 주소 선택으로 인한 close일 경우
                            element_layer.innerHTML = "<span class=\"close\" onclick=\"closeDaumPostcode()\" style=\"cursor:pointer; position:absolute; right:10px; top:10px; z-index:1001; background:#fff; border:1px solid #ddd; border-radius:50%; padding:5px; width:25px; height:25px; text-align:center; line-height:15px;\">X</span><div id=\"layer-content\" style=\"width:100%; height:4vh;\"></div>"; // 레이어 적용 해제
                        }
                    },
                    width : "100%",
                    height : "100%",
                    SuggestItems : 5
                }).embed(element_layer);

                // iframe을 넣은 element를 보이게 한다.
                element_layer.style.display = "block";

                // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
                initLayerPosition();
            }

            function initLayerPosition() {
                var width = 500; //우편번호서비스가 들어갈 element의 width
                var height = 400; //우편번호서비스가 들어갈 element의 height
                var borderWidth = 1; //샘플에서 사용하는 border의 두께

                // 위에서 선언한 값들을 실제 element에 넣는다.
                $("#postLayer").css({
                    "width": width + "px",
                    "height": height + "px",
                    "border": borderWidth + "px solid #ddd",
                    "left": ((window.innerWidth - width) / 2 - borderWidth) + "px",
                    "top": ((window.innerHeight - height) / 2 - borderWidth) + "px"
                });
            }

            function closeDaumPostcode() {
                var element_layer = document.getElementById("postLayer");
                element_layer.style.display = "none";
            }
            </script>
        ';
        return $postHtml;
    }
}

if (!function_exists('getIconAnchor')) {
    function getIconAnchor($param = []) {
        // 기본값 설정
        $defaultValue = [
            'txt' => '',
            'width' => '20',
            'height' => '20',
            'icon' => 'edit',
            'anchorClass' => '',
            'anchorStyle' => '',
            'svgClass' => '',
            'stroke' => '#1D273B',
            'extra' => []
        ];

        // 제공된 매개변수와 기본값 병합
        $param = array_merge($defaultValue, $param);

        // extra 배열을 HTML 속성 문자열로 변환
        $extraAttributes = '';
        if (!empty(_elm($param, 'extra')) && is_array(_elm($param, 'extra'))) {
            foreach (_elm($param, 'extra') as $key => $value) {
                $extraAttributes .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
            }
        }

        // 사이트 설정 및 아이콘 데이터 가져오기
        $site_config = new SiteConfig;
        $iconData = $site_config->buttonIcon[$param['icon']];

        // 앵커 HTML 생성
        $anchor = '<a href="javascript:;" class="' . _elm($param, 'anchorClass') . '" style="' . _elm($param, 'anchorStyle') . '" ' . $extraAttributes . '>';
        $anchor .= '<svg xmlns="http://www.w3.org/2000/svg" class="' . _elm($param, 'svgClass') . '" width="' . _elm($param, 'width') . 'px" height="' . _elm($param, 'height') . 'px" viewBox="0 0 ' . _elm($param, 'width') . ' ' . _elm($param, 'height') . '" fill="none">';
        foreach ($iconData as $info) {
            $anchor .= '<path d="' . $info . '" stroke="' . _elm($param, 'stroke') . '" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />';
        }
        $anchor .= '</svg>';
        $anchor .= _elm($param, 'txt') . '</a>';

        return $anchor;
    }
}
if (!function_exists('getIconButton')) {
    function getIconButton($param = []) {
        // 기본값 설정
        $defaultValue = [
            'txt' => '',
            'width' => '20',
            'height' => '20',
            'icon' => 'edit',
            'buttonClass' => 'btn-md btn-white',
            'buttonStyle' => '',
            'svgClass' => '',
            'stroke' => '#1D273B',
            'extra' => []
        ];

        // 제공된 매개변수와 기본값 병합
        $param = array_merge($defaultValue, $param);

        // extra 배열을 HTML 속성 문자열로 변환
        $extraAttributes = '';
        if (!empty( _elm($param,'extra') ) && is_array( _elm( $param, 'extra' ) ) ) {
            foreach ( _elm($param,'extra') as $key => $value) {
                $extraAttributes .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
            }
        }

        // 사이트 설정 및 아이콘 데이터 가져오기
        $site_config = new SiteConfig;
        $iconData = $site_config->buttonIcon[ $param['icon'] ];

        // 버튼 HTML 생성
        $button = '<button class="' . _elm( $param, 'buttonClass' ) . '" style="' . _elm( $param, 'buttonStyle' ) . '" ' . $extraAttributes . ' type="button">';
        $button .= '<svg xmlns="http://www.w3.org/2000/svg" class="'._elm( $param, 'svgClass' ).'"  width="' . _elm( $param, 'width' ) . 'px" height="' . _elm( $param, 'height' ) . 'px" viewBox="0 0 ' . _elm( $param, 'width') . ' ' . _elm( $param, 'height' ) . '" fill="none" style="margin-right: 4px">';
        foreach ($iconData as $info) {
            $button .= '<path d="' . $info . '" stroke="' . _elm( $param, 'stroke' ) . '" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />';
        }
        $button .= '</svg>';
        $button .= _elm( $param, 'txt' ) . '</button>';

        return $button;
    }
}

if (!function_exists('getButton')) {
    /*
    @param array $param
    @param 'text' => 'Button',
    @param 'class' => 'btn-md btn-secondary',
    @param 'style' => '',
    @param 'extra' => []
    @return string
    */
    function getButton($param = []) {
        // 기본값 설정
        $defaultValue = [
            'text' => 'Button',
            'class' => 'btn-md btn-secondary',
            'style' => '',
            'extra' => []
        ];

        // 제공된 매개변수와 기본값 병합
        $param = array_merge($defaultValue, $param);

        // extra 배열을 HTML 속성 문자열로 변환
        $extraAttributes = '';
        if (!empty( _elm( $param, 'extra' ) ) && is_array( _elm( $param, 'extra') ) ) {
            foreach ( _elm( $param, 'extra' ) as $key => $value) {
                $extraAttributes .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
            }
        }

        // 버튼 HTML 생성
        $button = '<button class="' . _elm( $param, 'class' ) . '" style="' . _elm( $param, 'style' ) . '" ' . $extraAttributes . ' type="button">';
        $button .= $param['text'];
        $button .= '</button>';

        return $button;
    }
}

if (!function_exists('getRadioButton')) {
    function getRadioButton($extras = []) {
        $html = '';

        // 기본값 설정
        $defaultValues = [
            'name' => '',
            'value' => '',
            'label' => '',
            'checked' => false,
            'extraAttributes' => []
        ];

        // extras 배열을 기본값과 병합
        $extras = array_merge($defaultValues, $extras);

        // 추가 속성을 HTML 속성 문자열로 변환
        $extraAttributes = '';
        $classAdd = '';
        if (!empty($extras['extraAttributes']) && is_array($extras['extraAttributes'])) {
            foreach ($extras['extraAttributes'] as $attrKey => $attrValue) {
                if( $attrKey == 'class' ){
                    $classAdd .= ' '. htmlspecialchars($attrValue, ENT_QUOTES);
                }else{
                    $extraAttributes .= $attrKey . '="' . htmlspecialchars($attrValue, ENT_QUOTES) . '" ';
                }

            }
        }

        // checked 상태 설정
        $checkedAttr = $extras['checked'] ? 'checked' : '';

        $html .= '<label class="form-check form-check-inline">';
        $html .= '<input class="form-check-input'.$classAdd.'" type="radio" id="'._elm( $extras,'id' ).'"  name="' . htmlspecialchars(  _elm( $extras, 'name' ), ENT_QUOTES) . '" value="' . htmlspecialchars( _elm( $extras, 'value' ), ENT_QUOTES) . '" ' . $checkedAttr . ' ' . $extraAttributes . '>';
        $html .= '<span class="form-check-label">' . htmlspecialchars( _elm( $extras, 'label' ), ENT_QUOTES) . '</span>';
        $html .= '</label>';

        return $html;
    }
}

if (!function_exists('getCheckBox')) {
    function getCheckBox($extras = []) {
        $html = '';

        // 기본값 설정
        $defaultValues = [
            'name' => '',
            'value' => '',
            'label' => '',
            'checked' => false,
            'extraAttributes' => []
        ];

        // extras 배열을 기본값과 병합
        $extras = array_merge($defaultValues, $extras);

        // 추가 속성을 HTML 속성 문자열로 변환
        $extraAttributes = '';
        $classAdd = '';
        if (!empty($extras['extraAttributes']) && is_array($extras['extraAttributes'])) {
            foreach ($extras['extraAttributes'] as $attrKey => $attrValue) {
                if( $attrKey == 'class' ){
                    $classAdd .= ' '. htmlspecialchars($attrValue, ENT_QUOTES);
                }else{
                    $extraAttributes .= $attrKey . '="' . htmlspecialchars($attrValue, ENT_QUOTES) . '" ';
                }

            }
        }

        // checked 상태 설정
        $checkedAttr = $extras['checked'] ? 'checked' : '';

        $html .= '<label class="form-check form-check-inline">';
        $html .= '<input class="form-check-input'.$classAdd.'" type="checkbox" id="'._elm( $extras,'id' ).'"  name="' . htmlspecialchars(  _elm( $extras, 'name' ), ENT_QUOTES) . '" value="' . htmlspecialchars( _elm( $extras, 'value' ), ENT_QUOTES) . '" ' . $checkedAttr . ' ' . $extraAttributes . '>';
        $html .= '<span class="form-check-label">' . htmlspecialchars( _elm( $extras, 'label' ), ENT_QUOTES) . '</span>';
        $html .= '</label>';

        return $html;
    }
}

if ( ! function_exists('getSelectBox'))
{
	/**
	 * Drop-down Menu
	 *
	 * @param	mixed	$data
	 * @param	mixed	$options
	 * @param	mixed	$selected
	 * @param	mixed	$extra
	 * @return	string
	 */
	function getSelectBox($data = '', $options = array(), $selected = array(), $extra = '')
	{
		$defaults = array();

		if (is_array($data))
		{
			if (isset($data['selected']))
			{
				$selected = $data['selected'];
				unset($data['selected']); // select tags don't have a selected attribute
			}

			if (isset($data['options']))
			{
				$options = $data['options'];
				unset($data['options']); // select tags don't use an options attribute
			}
		}
		else
		{
			$defaults = array('name' => $data);
		}

		is_array($selected) OR $selected = array($selected);
		is_array($options) OR $options = array($options);

		// If no selected state was submitted we will attempt to set it automatically
		if (empty($selected))
		{
			if (is_array($data))
			{
				if (isset($data['name'], $_POST[$data['name']]))
				{
					$selected = array($_POST[$data['name']]);
				}
			}
			elseif (isset($_POST[$data]))
			{
				$selected = array($_POST[$data]);
			}
		}

		$extra = _attributes_to_string($extra);

		$multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select '.rtrim(_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val))
			{
				if (empty($val))
				{
					continue;
				}

				$form .= '<optgroup label="'.$key."\">\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
					$form .= '<option value="'.html_escape($optgroup_key).'"'.$sel.'>'
						.(string) $optgroup_val."</option>\n";
				}

				$form .= "</optgroup>\n";
			}
			else
			{
				$form .= '<option value="'.html_escape($key).'"'
					.(in_array($key, $selected) ? ' selected="selected"' : '').'>'
					.(string) $val."</option>\n";
			}
		}

		return $form."</select>\n";
	}
}
if ( ! function_exists('html_escape'))
{
	/**
	 * Returns HTML escaped variable.
	 *
	 * @param	mixed	$var		The input string or array of strings to be escaped.
	 * @param	bool	$double_encode	$double_encode set to FALSE prevents escaping twice.
	 * @return	mixed			The escaped string or array of strings as a result.
	 */
	function html_escape($var, $double_encode = TRUE)
	{
		if (empty($var))
		{
			return $var;
		}

		if (is_array($var))
		{
			foreach (array_keys($var) as $key)
			{
				$var[$key] = html_escape($var[$key], $double_encode);
			}

			return $var;
		}

		return htmlspecialchars($var, ENT_QUOTES, 'UTF-8', $double_encode);
	}
}

if ( ! function_exists('_parse_form_attributes'))
{
	/**
	 * Parse the form attributes
	 *
	 * Helper function used by some of the form helpers
	 *
	 * @param	array	$attributes	List of attributes
	 * @param	array	$default	Default values
	 * @return	string
	 */
	function _parse_form_attributes($attributes, $default)
	{
		if (is_array($attributes))
		{
			foreach ($default as $key => $val)
			{
				if (isset($attributes[$key]))
				{
					$default[$key] = $attributes[$key];
					unset($attributes[$key]);
				}
			}

			if (count($attributes) > 0)
			{
				$default = array_merge($default, $attributes);
			}
		}

		$att = '';

		foreach ($default as $key => $val)
		{
			if ($key === 'value')
			{
				$val = html_escape($val);
			}
			elseif ($key === 'name' && ! strlen($default['name']))
			{
				continue;
			}

			$att .= $key.'="'.$val.'" ';
		}

		return $att;
	}
}


if ( ! function_exists('_attributes_to_string'))
{
	/**
	 * Attributes To String
	 *
	 * Helper function used by some of the form helpers
	 *
	 * @param	mixed
	 * @return	string
	 */
	function _attributes_to_string($attributes)
	{
		if (empty($attributes))
		{
			return '';
		}

		if (is_object($attributes))
		{
			$attributes = (array) $attributes;
		}

		if (is_array($attributes))
		{
			$atts = '';

			foreach ($attributes as $key => $val)
			{
				$atts .= ' '.$key.'="'.$val.'"';
			}

			return $atts;
		}

		if (is_string($attributes))
		{
			return ' '.$attributes;
		}

		return FALSE;
	}
}
