<?php
namespace App\Libraries;

class OwensCache
{
    private $gcPercent = 20;
    private $cacheDir  = WRITEPATH . 'cache/';

	public function __construct()
    {
        if ($this->probability($this->gcPercent) == 1)
        {
            $this->gc();
        }
    }

    private function probability($p)
    {
        $c = 0;
        $n = $p * 10000;

        if ($n > 1000000) $n = 1000000;
        if ($n < 1) $n = 0;

        $t = mt_rand(0, 1000000);

        if ($t <= $n) $c = 1;

        return $c;
    }

    private function getKey($key)
    {
        $key = strtolower(strtok($key, '_')) . '_' . md5($key);

        return $key;
    }

    public function set($key, $value, $ttl = 0)
    {
        $key       = $this->getKey($key);
        $cacheFile = $this->cacheDir . $key . '_owens.cache';

        $aCachedDatas               = [];
        $aCachedDatas['expireTime'] = (empty($ttl) === true) ? 0 : time() + $ttl;
        $aCachedDatas['data']       = $value;

        $sCachedData = json_encode($aCachedDatas, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        if (file_put_contents($cacheFile, $sCachedData) !== false)
        {
            return true;
        }

        return false;
    }

    public function get($key, $default = null)
    {
        $key       = $this->getKey($key);
        $cacheFile = $this->cacheDir . $key . '_owens.cache';

        if (is_file($cacheFile) === true)
        {
            $sCachedData = file_get_contents($cacheFile);
        }
        else
        {
            return $default;
        }

        $aCachedDatas = json_decode($sCachedData, true);

        if (empty($aCachedDatas['expireTime']) === false)
        {
            if ($aCachedDatas['expireTime'] < time())
            {
                $this->del($key);

                return $default;
            }
        }

        return $aCachedDatas['data'] ?? '';
    }

    public function del($key)
    {
        $key       = $this->getKey($key);
        $cacheFile = $this->cacheDir . $key . '_owens.cache';

        if (is_file($cacheFile) === true)
        {
            return unlink($cacheFile);
        }

        return false;
    }

    public function has($key)
    {
        $key       = $this->getKey($key);
        $cacheFile = $this->cacheDir . $key . '_owens.cache';

        if (is_file($cacheFile) === true)
        {
            $sCachedData = file_get_contents($cacheFile);
        }
        else
        {
            return false;
        }

        $aCachedDatas = json_decode($sCachedData, true);

        if (empty($aCachedDatas['expireTime']) === false)
        {
            if ($aCachedDatas['expireTime'] < time())
            {
                return false;
            }
        }

        return true;
    }

    public function clear($prefix = '')
    {
        if ($handle = opendir($this->cacheDir))
        {
            while (false !== ($entry = readdir($handle)))
            {
                if (strpos($entry, '_owens.cache') !== false)
                {
                    if (empty($prefix) === false)
                    {
                        if (strpos(strtolower($entry), strtolower($prefix) . '_') === false)
                        {
                            continue;
                        }
                    }

                    if (is_file($this->cacheDir . $entry) === true)
                    {
                        unlink($this->cacheDir . $entry);
                    }
                }
            }

            closedir($handle);
        }
    }

    public function gc($gcPercent = 0)
    {
        $files     = new \DirectoryIterator($this->cacheDir);
        $fileCount = iterator_count($files);

        if (empty($gcPercent) === true)
        {
            $gcPercent = rand(10, $this->gcPercent);
        }

        $gcCount = ceil($fileCount * $gcPercent * 0.01);

        foreach ($files as $k => $file)
        {
            if (strpos($file, '_owens.cache') !== false)
            {
                $cacheFile = $this->cacheDir . $file;

                if (is_file($cacheFile) === true)
                {
                    $sCachedData  = file_get_contents($cacheFile);
                    $aCachedDatas = json_decode($sCachedData, true);

                    if (empty($aCachedDatas['expireTime']) === false)
                    {
                        if ($aCachedDatas['expireTime'] < time())
                        {
                            unlink($cacheFile);
                        }
                    }
                }
            }

            if ($k >= $gcCount)
            {
                break;
            }
        }
    }

    public function gets($keys, $default = null)
    {
        $aCachedDatas = [];

        if (is_array($keys) === true)
        {
            foreach ($keys as $vKey)
            {
                $aCachedDatas[$vKey] = $this->get($vKey, $default);
            }
        }
        else
        {
            $aCachedDatas[$keys] = $this->get($keys, $default);
        }

        return $aCachedDatas;
    }

    public function dels($keys)
    {
        if (is_array($keys) === true)
        {
            foreach ($keys as $vKey)
            {
                $this->del($vKey);
            }
        }
        else
        {
            $this->del($keys);
        }
    }
}
