<?php
/**
 * @File   : owens_url_helper.php
 * @Date   : 2024-04-02 18:07:04
 * @Desc   : URL 공용 helper
*/

if (! function_exists('_domain'))
{
    function _domain($url, $bSub = false, $bFirst = false)
    {
        $baseDomain = '';
        $subDomain  = '';

        if (empty($url) === true)
        {
            return '';
        }

        $domain = parse_url((strpos($url, '://') === false ? 'http://' : '') . trim($url), PHP_URL_HOST);

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{0,63}\.[a-z]{2,13}(\.[a-z]{1,2})?)$/i', $domain, $aDomainMatches))
        {
            $baseDomain = $aDomainMatches['domain'];
            $subDomain  = trim(str_replace($baseDomain, '', $domain), '.');
        }

        if ($bFirst === true)
        {
            if (strpos($subDomain, '.') !== false)
            {
                $baseDomain = trim(strstr($subDomain, '.'), '.') . '.' . $baseDomain;
                $subDomain  = strstr($subDomain, '.', true);
            }
        }

        if ($bSub === true)
        {
            return $subDomain;
        }

        return $baseDomain;
    }
}

if (! function_exists('_get_domain'))
{
    function _get_domain($subDomain = '')
    {
        $baseDomain = _domain($_SERVER['HTTP_HOST'] ?? '');

        if (empty($subDomain) === true)
        {
            return $baseDomain;
        }

        return $subDomain . '.' . $baseDomain;
    }
}

if (! function_exists('_link_url'))
{
    /**
     * 도메인에 따른 링크 변경 출력
     *
     * @param string $uri
     * @return string
     */
    function _link_url($uri): string
    {
        $subDomain = _domain($_SERVER['HTTP_HOST'] ?? '', true, true);

        if (empty($subDomain) === false)
        {
            if (strpos($uri, '/' . $subDomain) === 0)
            {
                $uri = substr_replace($uri, '', 0, strlen('/' . $subDomain));

            }
        }

        return $uri;
    }
}

if (! function_exists('_add_query_string'))
{
    function _add_query_string($url, $query)
    {
        if (empty($query) === true)
        {
            return $url;
        }

        $parsedUrl = parse_url($url);

        if (empty($parsedUrl['path']) === true)
        {
            $url .= '';
        }

        $queryString = is_array($query) ? http_build_query($query) : $query;

        if (empty($parsedUrl['query']) === true)
        {
            // remove duplications
            parse_str($queryString, $queryStringArray);
            $url .= '?' . http_build_query($queryStringArray);
        }
        else
        {
            $queryString = $parsedUrl['query'] . '&' . $queryString;

            // remove duplications
            parse_str($queryString, $queryStringArray);

            // place the updated query in the original query position
            $url = substr_replace($url, http_build_query($queryStringArray), strpos($url, $parsedUrl['query']), strlen($parsedUrl['query']));
        }

        return $url;
    }
}

if (! function_exists('_remove_query_string'))
{
    function _remove_query_string($url, $key)
    {
        if (empty($url) === true)
        {
            $url = $_SERVER['REQUEST_URI'];
        }

        $url = preg_replace('~(\?|&)'.$key.'=[^&]*~', '$1', $url);
        $url = substr($url, 0, -1);

        return ($url);
    }
}

if (! function_exists('shop_url')) {
    /**
     * Returns the base URL as defined by the App config.
     * Base URLs are trimmed site URLs without the index page.
     *
     * @param array|string $relativePath URI string or array of URI segments.
     * @param string|null  $scheme       URI scheme. E.g., http, ftp. If empty
     *                                   string '' is set, a protocol-relative
     *                                   link is returned.
     */
    function shop_url($relativePath = '', ?string $scheme = null): string
    {
        $baseURL = 'https://shop.brav.co.kr'; // 기본 URL 설정
        $relativePath = ltrim(is_array($relativePath) ? implode('/', $relativePath) : $relativePath, '/');
        $url = rtrim($baseURL, '/') . '/' . $relativePath;

        if ($scheme !== null) {
            $url = parse_url($url, PHP_URL_SCHEME) . ':' . str_replace(parse_url($url, PHP_URL_SCHEME) . ':', '', $url);
        }

        return $url;
    }
}
