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
        $button = '<button class="' . _elm( $param, 'buttonClass' ) . '" style="' . _elm( $param, 'buttonStyle' ) . '" ' . $extraAttributes . '>';
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
        $button = '<button class="' . _elm( $param, 'class' ) . '" style="' . _elm( $param, 'style' ) . '" ' . $extraAttributes . '>';
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
