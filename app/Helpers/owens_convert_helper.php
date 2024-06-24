<?php
/**
 * @File   : owens_convert_helper.php
 * @Date   : 2024-04-02 17:59:05
 * @Desc   : 자료 변환 공용 helper
*/

// 전화번호 - 추가
if (! function_exists('_add_dash_tel_num'))
{
    function _add_dash_tel_num($iNum = 0)
    {
        if (is_numeric($iNum) === true)
        {
            if (strlen($iNum) == 8)
            {
                // 15xx, 16xx, 18xx, ...
                return preg_replace("/([0-9]{4})([0-9]{4})$/","$1-$2",$iNum);
            }
            else if (in_array(substr($iNum, 0, 2), ['05']) === true)
            {
                // 05xx
                return preg_replace("/([0-9]{4})([0-9]{3,4})([0-9]{4})$/","$1-$2-$3",$iNum);
            }
            else
            {
                // 나머지
                return preg_replace('/^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})([0-9]{3,4})([0-9]{4})$/', '$1-$2-$3', $iNum);
            }
        }

        return $iNum;
    }
}

// 사업자 번호 - 추가
if (! function_exists('_add_dash_biz_num'))
{
    function _add_dash_biz_num($iNum = 0)
    {
        if (is_numeric($iNum) === true)
        {
            return preg_replace('/^([0-9]{3})([0-9]{2})([0-9]{5})$/', '$1-$2-$3', $iNum);
        }

        return $iNum;
    }
}

// 주민등록번호 - 추가
if (! function_exists('_add_dash_juminno'))
{
    function _add_dash_jumin_num($iNum = 0)
    {
        if (is_numeric($iNum) === true)
        {
            if (strlen($iNum) == 13)
            {
                return preg_replace("/([0-9]{6})([0-9]{7})$/","$1-$2",$iNum);
            }
        }

        return $iNum;
    }
}

/*
    오늘일 경우 시:분 리턴
    아닐 경우 년-월-일 리턴
*/
if (! function_exists('_time_to_today_or_not'))
{
    function _time_to_today_or_not($sDateTime)
    {
        if (empty($sDateTime) === true) return '';

        $sReturn = $sDateTime;


        $iTime = strtotime($sDateTime);

        if (date("Y-m-d") == date("Y-m-d", $iTime))
        {

            $iDiffTime = time() - $iTime;
            if( $iDiffTime < 1800 ){
                $sReturn = _time_to_human_time( $sDateTime );
            }else{
                $sReturn = date("H:i", $iTime);
            }
        }
        else
        {
            $sReturn = date("Y-m-d", $iTime);
        }

        return $sReturn;
    }
}

/*
    x일 x시간 x분 x초 전/후 리턴
*/
if (! function_exists('_time_to_human_time'))
{
    function _time_to_human_time($sDateTime, $iMaxDays = 1, $sSuffixText = '전')
    {
        if (empty($sDateTime) === true) return '';

        $sReturn = $sDateTime;
        //echo "sDateTime의 타입: " . gettype($sDateTime) . "\n";

        $iDiffTime = time() - strtotime($sDateTime);

        $iS = 60; //1분 = 60초
        $iH = $iS * 60; //1시간 = 60분
        $iD = $iH * 24; //1일 = 24시간
        $iY = $iD * $iMaxDays; //1년 = 1일 * x일

        if ($iDiffTime < $iS)
        {
            $sReturn = $iDiffTime . '초 ' . $sSuffixText;
        }
        else if ($iH > $iDiffTime && $iDiffTime >= $iS)
        {
            $sReturn = round($iDiffTime / $iS) . '분 ' . $sSuffixText;
        }
        else if ($iD > $iDiffTime && $iDiffTime >= $iH)
        {
            $sReturn = round($iDiffTime / $iH) . '시간 ' . $sSuffixText;
        }
        else if ($iY > $iDiffTime && $iDiffTime >= $iD)
        {
            $sReturn = round($iDiffTime / $iD) . '일 ' . $sSuffixText;
        }

        return $sReturn;
    }
}

/*
    x시간 x분 x초 리턴
*/
if (! function_exists('_time_to_human_time_his'))
{
    function _time_to_human_time_his($sDateTime)
    {
        $sReturn = sprintf("%d시간 %d분 %d초", $sDateTime / 3600, ($sDateTime / 60) % 60, $sDateTime % 60);

        if ($sDateTime < 3600)
        {
            $sReturn = sprintf("%d분 %d초", ($sDateTime / 60) % 60, $sDateTime % 60);
        }

        if ($sDateTime < 60)
        {
            $sReturn = sprintf("%d초", $sDateTime % 60);
        }

        $aBlankText = array(' 0시간', ' 0분', ' 0초');

        $sReturn = trim(str_replace($aBlankText, '', $sReturn));

        return $sReturn;
    }
}

/*
    x시간 x분 x초 리턴
*/
if (! function_exists('_time_to_seconds'))
{
    function _time_to_seconds($time)
    {
        if (strpos($time, ':') === false)
        {
            return 0;
        }

        $arr = explode(':', $time);
        if (count($arr) === 3)
        {
            return $arr[0] * 3600 + $arr[1] * 60 + $arr[2];
        }
        return $arr[0] * 60 + $arr[1];
    }
}

/*
    x초 이후 시간 가져오기
*/
if (! function_exists('_time_secodns_after'))
{
    function _time_secodns_after($iSeconds, $sFormat = '%Y-%M-%D %H:%I:%S')
    {
        if (empty($iSeconds) === true)
        {
            return '00';
        }

        $now      = new DateTime();
        $future   = new DateTime(date("Y-m-d H:i:s", strtotime("+ " . $iSeconds . " seconds")));
        $interval = $future->diff($now);

        return $interval->format($sFormat);
    }
}

/*
    시간 차이 가져오기
*/
if (! function_exists('_time_diff'))
{
    function _time_diff($iDateTime1, $iDateTime2, $sFormat = '%Y-%M-%D %H:%I:%S')
    {
        $originalTime = new DateTimeImmutable($iDateTime1);
        $targedTime   = new DateTimeImmutable($iDateTime2);
        $interval     = $originalTime->diff($targedTime);

        return $interval->format($sFormat);
    }
}

/*
    짧은 단위로 변환
*/
if (! function_exists('_number_format_short'))
{
    function _number_format_short($iNum, $iPrecision = 1)
    {
        $iNum = str_replace(',', '', $iNum);

        if ($iNum < 1000)
        {
            $sNumFormat = number_format($iNum, $iPrecision);
            $sSuffix    = '';
        }
        else if ($iNum < 1000000)
        {
            $sNumFormat = number_format($iNum / 1000, $iPrecision);
            $sSuffix    = 'K+';
        }
        else if ($iNum < 1000000000)
        {
            $sNumFormat = number_format($iNum / 1000000, $iPrecision);
            $sSuffix    = 'M+';
        }
        else if ($iNum < 1000000000000)
        {
            $sNumFormat = number_format($iNum / 1000000000, $iPrecision);
            $sSuffix    = 'B+';
        }
        else
        {
            $sNumFormat = number_format($iNum / 1000000000000, $iPrecision);
            $sSuffix    = 'T+';
        }

        if ($iPrecision > 0)
        {
            $sDotZero   = '.' . str_repeat('0', $iPrecision);
            $sNumFormat = str_replace($sDotZero, '', $sNumFormat);
        }

        return $sNumFormat . $sSuffix;
    }
}

/*
    내용 요약 반환
*/
if (! function_exists('_get_content_summary'))
{
    function _get_content_summary($sContents, $sWord = '', $iWrapNum = 10, $iCutLength = 150)
    {
        if (empty($sContents) === true) return '';

        $sContents = str_replace(array('&lt;', '&gt;'), array('<', '>'), $sContents);
        $sContents = strip_tags($sContents);
        $sContents = preg_replace("/(^[\r]*|[\n]+)+/", "", $sContents);

        if (empty($sWord) === true)
        {
            $tail = '';
            if (mb_strlen($sContents) > $iCutLength)
            {
                $tail = '...';
            }

            return mb_substr($sContents, 0, $iCutLength) . $tail;
        }

        $aWords      = preg_split('/\s+/u', $sContents);
        $aFoundWords = preg_grep("/" . $sWord . ".*/", $aWords);
        $aFoundPos   = array_keys($aFoundWords);

        if(count($aFoundPos) > 0) $i_iPOS = $aFoundPos[0];

        if (isset($i_iPOS))
        {
            $iStart     = ($i_iPOS - $iWrapNum > 0) ? $i_iPOS - $iWrapNum : 0;
            $iLength    = (($i_iPOS + ($iWrapNum + 1) < count($aWords)) ? $i_iPOS + ($iWrapNum + 1) : count($aWords)) - $iStart;
            $aSlice     = array_slice($aWords, $iStart, $iLength);
            $sPostStart = ($iStart > 0) ? "..." : "";
            $sPostEnd   = ($i_iPOS + ($iWrapNum + 1) < count($aWords)) ? "..." : "";
            $sReturn    = implode(' ', $aSlice);

            if (strlen($sReturn) > $iCutLength) $sReturn = mb_substr($sReturn, 0, $iCutLength);
            $sReturn = $sPostStart . $sReturn . $sPostEnd;
        }
        else $sReturn = mb_substr($sContents, 0, $iCutLength);

        return $sReturn;
    }
}

/*
    해쉬 태그 변환
*/
if (! function_exists('_highlight_hash_tag'))
{
    function _highlight_hash_tag($sContents, $sLink = '#', $aAttributes = [])
    {
        $sReturn = $sContents;

        if (empty($sContents) === true) return $sReturn;

        $sAttributes = '';
        if (empty($aAttributes) === false)
        {
            foreach ($aAttributes as $K => $V)
            {
                $sAttributes .= sprintf(' %s="%s"', $K, $V);
            }
        }

        // ---------------------------------------------------------------------
        // HTML 태그 속 태그 제외
        // ---------------------------------------------------------------------
        $sContents = strip_tags($sContents);

        preg_match_all('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u', $sContents, $aMatches); // /#(\w+)/u
        $aTags = array_unique($aMatches[1]);

        foreach ($aTags as $sTag)
        {
            $sReturn = str_replace('#' . $sTag, '<a href="' . $sLink . $sTag . '"' . $sAttributes . '>#' . $sTag . '</a>', $sReturn);
        }

        return $sReturn;
    }
}

/*
    iframe 동영상 허용 링크 변환
*/
if (! function_exists('_convert_allow_video_iframe'))
{
    function _convert_allow_video_iframe($sContents)
    {
        $sReturn = $sContents;

        if (empty($sContents) === true) return $sReturn;

        $aVideoGrantUrlLists   = [];
        $aVideoGrantUrlLists[] = 'www.youtube.com';
        $aVideoGrantUrlLists[] = 'serviceapi.rmcnmv.naver.com';
        $aVideoGrantUrlLists[] = 'play-tv.kakao.com';
        $aVideoGrantUrlLists[] = 'instagram.com';
        $aVideoGrantUrlLists[] = 'www.dailymotion.com';
        $aVideoGrantUrlLists[] = 'player.vimeo.com';
        $aVideoGrantUrlLists[] = 'player.youku.com';

        preg_match_all('/&lt;iframe.+?src=[\'"](https?:)?\/\/(.+?)[\'"].+?&gt;&lt;\/iframe&gt;/', $sContents, $aVideoMatches);

        if (empty($aVideoMatches[2]) === false)
        {
            foreach ($aVideoMatches[2] as $kVideoIdx => $vVidoeUrl)
            {
                $bIsGrantVideo = false;
                foreach ($aVideoGrantUrlLists as $vVideoGrantUrl)
                {
                    if (strpos($vVidoeUrl, $vVideoGrantUrl) !== false)
                    {
                        $bIsGrantVideo = true;
                        break;
                    }
                }

                if ($bIsGrantVideo === true)
                {
                    $sNowVideoScript = $aVideoMatches[0][$kVideoIdx];
                    $sNewVideoScript = str_replace(array('&lt;', '&gt;'), array('<', '>'), $sNowVideoScript);

                    $sReturn = str_replace($sNowVideoScript, $sNewVideoScript, $sReturn);
                }
            }
        }

        return $sReturn;
    }
}

/*
    허용 동영상 링크 iframe 가져오기
*/
if (! function_exists('_get_content_iframe'))
{
    function _get_content_iframe($sContents)
    {
        $sReturn = '';

        if (empty($sContents) === true) return $sReturn;

        $aVideoGrantUrlLists   = [];
        $aVideoGrantUrlLists[] = 'www.youtube.com';
        $aVideoGrantUrlLists[] = 'serviceapi.rmcnmv.naver.com';
        $aVideoGrantUrlLists[] = 'play-tv.kakao.com';
        $aVideoGrantUrlLists[] = 'instagram.com';
        $aVideoGrantUrlLists[] = 'www.dailymotion.com';
        $aVideoGrantUrlLists[] = 'player.vimeo.com';
        $aVideoGrantUrlLists[] = 'player.youku.com';

        preg_match_all('/<iframe.+?src=[\'"](https?:)?\/\/(.+?)[\'"].+?><\/iframe>/', $sContents, $aVideoMatches);

        if (empty($aVideoMatches[2]) === false)
        {
            foreach (_elm($aVideoMatches, 2, []) as $kVideoIdx => $vVideoUrl)
            {
                foreach ($aVideoGrantUrlLists as $vVideoGrantUrl)
                {
                    if (strpos($vVideoUrl, $vVideoGrantUrl) !== false)
                    {
                        $sReturn = _elm(_elm($aVideoMatches, 0, []), $kVideoIdx, '');
                        break;
                    }
                }
            }
        }

        return $sReturn;
    }
}

/*
    문장 내 검색 키워드 강조
*/
if (! function_exists('_highlight_search_word'))
{
    function _highlight_search_word($searchWord, $sContents, $aAttributes = [])
    {
        $sReturn = $sContents;

        if (empty($sContents) === true) return $sReturn;
        if (empty($searchWord) === true) return $sReturn;

        if (is_array($searchWord) === false)
        {
            $aSearchWord = explode(' ', $searchWord);
        }
        else
        {
            $aSearchWord = $searchWord;
        }

        $sAttributes = '';
        if (empty($aAttributes) === false)
        {
            foreach ($aAttributes as $K => $V)
            {
                $sAttributes .= sprintf(' %s="%s"', $K, $V);
            }
        }

        foreach ($aSearchWord as $sSearchWord)
        {
            if (empty($sSearchWord) === true)
            {
                continue;
            }
            $sReplaceWord = '<span' . $sAttributes . '>' . $sSearchWord . '</span>';

            $sReturn = str_ireplace($sSearchWord, $sReplaceWord, $sReturn);
        }

        return $sReturn;
    }
}

/*
    문장 내 선택 문자 *
*/
if (! function_exists('_hidden_abuse_word'))
{
    function _hidden_abuse_word($abuseWord, $sContents)
    {
        $sReturn = $sContents;

        if (empty($sContents) === true) return $sReturn;
        if (empty($abuseWord) === true) return $sReturn;
        if ($abuseWord == 'NOTHING') return $sReturn;

        if (is_array($abuseWord) === false)
        {
            $aAbuseWord = explode(',', $abuseWord);
        }
        else
        {
            $aAbuseWord = $abuseWord;
        }

        foreach ($aAbuseWord as $sAbuseWord)
        {
            if (empty($sAbuseWord) === true)
            {
                continue;
            }

            $sReplaceWord = '<strike>' . str_repeat('*', mb_strlen($sAbuseWord)) . '</strike>';

            $sReturn = str_ireplace($sAbuseWord, $sReplaceWord, $sReturn);
        }

        return $sReturn;
    }
}

/*
    문장내 썸네일 가져오기
*/
if (! function_exists('_get_content_thumbnail'))
{
    function _get_content_thumbnail($sContents, $sType = 'resize', $iWidth = 50, $iHeight = 50)
    {
        $sReturn   = '';
        $sImageUrl = '';

        $sContents = str_ireplace('>', '>' . PHP_EOL, nl2br($sContents));

        preg_match_all('/<img.+?src=[\'"]\/uploads\/([^\'"]+)[\'"].+?thumbnail="Y".*?>/i', $sContents, $aImageMatches);

        if (empty($aImageMatches[1]) === false)
        {
            $sImageUrl = '/uploads/' . current(_elm($aImageMatches, 1));
        }
        else
        {
            preg_match_all('/<img.+?src=[\'"]\/uploads\/([^\'"]+)[\'"].*?>/i', $sContents, $aImageMatches);

            if (empty($aImageMatches[1]) === false)
            {
                $sImageUrl = '/uploads/' . current(_elm($aImageMatches, 1));
            }
        }

        if (empty($sImageUrl) === false)
        {
            $aParseUrl = parse_url($sImageUrl);

            $aQuery = [];
            if (empty($aParseUrl['query']) === false)
            {
                parse_str($aParseUrl['query'], $aQuery);
            }

            $aQuery['w'] = $iWidth;
            $aQuery['h'] = $iHeight;

            if ($sType == 'crop')
            {
                $aQuery['crop'] = '1';
            }
            else
            {
                $aQuery['resize'] = '1';
            }

            $sQuery = http_build_query($aQuery);

            $sReturn = strtok($sImageUrl, '?') . '?' . $sQuery;
        }

        return $sReturn;
    }
}

/*
    문장 차이 비교 후 TEXT 리턴
*/
if (! function_exists('_diff'))
{
    function _diff($old, $new)
    {
        $matrix = array();
        $maxLen = 0;

        foreach($old as $oindex => $ovalue)
        {
            $nkeys = array_keys($new, $ovalue);

            foreach($nkeys as $nindex)
            {
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ? $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if($matrix[$oindex][$nindex] > $maxLen)
                {
                    $maxLen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxLen;
                    $nmax = $nindex + 1 - $maxLen;
                }
            }
        }

        if($maxLen == 0) return array(array('d'=>$old, 'i'=>$new));

        return array_merge(
            _diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
            array_slice($new, $nmax, $maxLen),
            _diff(array_slice($old, $omax + $maxLen), array_slice($new, $nmax + $maxLen)));
    }
}

/*
    문장 차이 비교 후 HTML 리턴
*/
if (! function_exists('_diff_html'))
{
    function _diff_html($old, $new)
    {
        $ret = '';

        $old = str_ireplace('</p>', '</p>' . PHP_EOL, nl2br($old));
        $new = str_ireplace('</p>', '</p>' . PHP_EOL, nl2br($new));

        $diff = _diff(preg_split("/[\s]+/", $old), preg_split("/[\s]+/", $new));

        foreach($diff as $k)
        {
            if(is_array($k))
            {
                $ret .= (!empty($k['d']) ? '<del>' . implode(' ', $k['d']) . '</del> ' : '') . (!empty($k['i']) ? '<ins>' . implode(' ', $k['i']) . '</ins> ' : '');
            }
            else
            {
                $ret .= $k . ' ';
            }
        }
        return $ret;
    }
}
