<?php
/**
 * @File   : owens_validation_helper.php
 * @Date   : 2024-04-02 18:08:19
 * @Desc   : 유효성 체크 공용 helper
*/

/**
 * datetime 형식 체크
 *
 * @param [type] $date
 * @param string $format
 * @return boolean
 */
if (! function_exists('_is_valid_datetime'))
{
    function _is_valid_datetime($date, $format = 'Y-m-d H:i:s'): bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}

/*
* 아이디 형식 확인
* 알파벳 시작 영소문자 + 숫자 조합
*/
if (! function_exists('_is_valid_userid'))
{
    function _is_valid_userid($userId, $minLength = 4, $maxLength = 16): bool
    {
        if (empty($userId) === true)
        {
            return false;
        }

        if (preg_match('/^[a-z]+[a-z0-9]{' . ($minLength - 1) . ',' . ($maxLength - 1). '}$/', $userId))
        {
            return true;
        }

        return false;
    }
}

/*
비밀번호 형식 확인
영대문자/영소문자/숫자/특수문자 조합 확인
영소문자 + 숫자 = bit 5
영소문자 + 숫자 + 그외 = bit 13
*/
if (! function_exists('_is_valid_password'))
{
    function _is_valid_password($passwd, $minLength = 8, $maxLength = 20, $bit = 15): bool
    {
        if (empty($passwd) === true)
        {
            return false;
        }

        if (!preg_match('/^.{' . $minLength . ',' . $maxLength . '}$/', $passwd))
        {
            return false;
        }

        $combiCount = 0;
        // 영소문자 // 1
        if (preg_match('/[a-z]/', $passwd) && (1 & $bit)) { $combiCount += 1; }
        // 영대문자 // 2
        if (preg_match('/[A-Z]/', $passwd) && (2 & $bit)) { $combiCount += 2; }
        // 숫자 // 4
        if (preg_match('/[0-9]/', $passwd) && (4 & $bit)) { $combiCount += 4; }
        // 그외 // 8
        if (preg_match('/[^0-9a-zA-Z]/', $passwd) && (8 & $bit)) { $combiCount += 8; }

        if ($combiCount == $bit)
        {
            return true;
        }

        return false;
    }
}

/*
닉네임 형식 확인
한글, 영소문자, 숫자, _, - 조합
*/
if (! function_exists('_is_valid_nickname'))
{
    function _is_valid_string($string, $minLength = 4, $maxLength = 16, $bit = 31): bool
    {
        if (empty($string) === true)
        {
            return false;
        }

        if (!preg_match('/^.{' . $minLength . ',' . $maxLength . '}$/u', $string))
        {
            return false;
        }

        // 영소문자
        if (1 & $bit) { $string = preg_replace('/[a-z]/', '', $string); }
        // 영소문자
        if (2 & $bit) { $string = preg_replace('/[a-z]/', '', $string); }
        // 숫자
        if (4 & $bit) { $string = preg_replace('/[0-9]/', '', $string); }
        // _.
        if (8 & $bit) { $string = preg_replace('/[_\.]/', '', $string); }
        // 한글
        if (16 & $bit) { $string = preg_replace('/[가-힣]/u', '', $string); }

        if (empty($string) === true)
        {
            return true;
        }

        return false;
    }
}

// 주민등록번호 확인
if (! function_exists('is_juminno'))
{
    function is_juminno($juminno)
    {
        $juminno = trim($juminno);
        $juminno = str_replace('-', '', $juminno);

        if (empty($juminno) === true) return false;

        if (preg_match('/^([0-9]+)$/', $juminno) != true || preg_match('/\d{2}([0]\d|[1][0-2])([0][1-9]|[1-2]\d|[3][0-1])[-]*[0-9]\d{6}/', $juminno) != true)
        {
            return false;
        }

        return true;
    }
}

// 휴대폰번호 확인
if (! function_exists('is_mobileno'))
{
    function is_mobileno($mobileno)
    {
        $mobileno = trim($mobileno);
        $mobileno = str_replace('-', '', $mobileno);

        if (empty($mobileno) === true) return false;

        if (preg_match('/^([0-9]+)$/', $mobileno) != true || preg_match('/^01[016789]{1}?([0-9]{3,4})?[0-9]{4}$/', $mobileno) != true)
        {
            return false;
        }

        return true;
    }
}

// 사업자등록번호 확인
if (! function_exists('_is_valid_biz_num'))
{
    function _is_valid_biz_num($num): bool
    {
        if (empty($num) === true) return false;

        $num = preg_replace('/[^0-9]/', '', $num);

        if (strlen($num) != 10)
        {
            return false;
        }

        $att = 0;
        $sum = 0;
        $arr = array(1, 3, 7, 1, 3, 7, 1, 3, 5);
        $cnt = count($arr);

        for($i=0; $i<$cnt; $i++)
        {
            $sum += ($num[$i] * $arr[$i]);
        }

        $sum += intval(($num[8] * 5) / 10);

        $at = $sum % 10;
        if ($at != 0)
        {
            $att = 10 - $at;
        }

        if ($num[9] != $att)
        {
            return false;
        }

        return true;
    }
}
