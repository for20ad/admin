<?php
namespace App\Libraries;

use CodeIgniter\View\View as BaseView;
use Psr\Log\LoggerInterface;

class OwensView extends BaseView
{
    private $_sSiteLayout = '';

    private $_aHeaderMeta      = [];
    private $_aHeaderScriptVar = [];
    private $_aHeaderCss       = [];
    private $_aHeaderJs        = [];
    private $_aFooterJs        = [];
    private $_aFooterScript    = [];
    private $_aViewDatas       = [];
    private $_aViewClass       = [];

    public function __construct($config = null, string $viewPath = null, $loader = null, bool $debug = null, LoggerInterface $logger = null)
    {
        if (is_null($config))
        {
            $config = new \Config\View();
        }

        if (is_null($viewPath))
        {
            $paths = config('Paths');

            $viewPath = $paths->viewDirectory;
        }

        parent::__construct($config, $viewPath, $loader, $debug, $logger);

        $this->session = \Config\Services::session();

        helper('owens');
    }

    public function view(string $name, array $data = [], array $options = []): string
    {
        $saveData = config(View::class)->saveData ?? true;


        if (array_key_exists('saveData', $options))
        {
            $saveData = (bool) $options['saveData'];
            unset($options['saveData']);
        }

        return $this->setData($data, 'raw')->render($name, $options, $saveData);
    }

    private function getAbsolutePath($path)
    {
        $path  = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');

        $absolutes = [];

        foreach ($parts as $part)
        {
            if ('.' == $part) continue;
            if ('..' == $part) array_pop($absolutes);
            else $absolutes[] = $part;
        }

        return ((substr($path, 0, 1) == DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : '') . implode(DIRECTORY_SEPARATOR, $absolutes);
    }

    private function getFileVersion($file = '')
    {
        $sReturn = '';

        $file = ltrim($file, '/');

        if (empty($file) === true)
        {
            return $sReturn;
        }

        $fullpath = ROOTPATH . 'public/' . $file;

        if (is_file($fullpath) === true)
        {
            $filemtime = filemtime($fullpath);
            $sReturn = '?v=' . date("ymdH", $filemtime);
        }

        return $sReturn;
    }

    public function setSiteLayout($layout = '')
    {
        $this->_sSiteLayout = $layout;
    }

    public function getSiteLayout()
    {
        return $this->_sSiteLayout;
    }

    public function setHeaderScriptVar($key = '', $value = '')
    {
        if (empty($key) === false)
        {
            $this->_aHeaderScriptVar[$key] = $value;
        }
    }

    public function getHeaderScriptVar($key = '')
    {
        $sReturn = '';

        if (empty($key) === false)
        {
            if (empty($this->_aHeaderScriptVar[$key]) === false)
            {
                $sReturn = "var " . $key . " = '" . $this->_aHeaderScriptVar[$key] . "';" . PHP_EOL;
            }
        }
        else
        {
            foreach (array_unique($this->_aHeaderScriptVar) as $key => $value)
            {
                $sReturn .= "var " . $key . " = '" . $value . "';" . PHP_EOL;
            }
        }

        return $sReturn;
    }

    public function setHeaderCss($file = '')
    {
        if (empty($file) === false)
        {
            $this->_aHeaderCss[] = $file;
        }
    }

    public function getHeaderCss()
    {
        $sReturn = '';

        foreach (array_unique($this->_aHeaderCss) as $file)
        {
            $v = $this->getFileVersion($file);

            $sReturn .= '<link rel="stylesheet" type="text/css" href="' . $file . $v . '" />' . PHP_EOL;
        }

        return $sReturn;
    }

    public function setHeaderJs($file = '')
    {
        if (empty($file) === false)
        {
            $this->_aHeaderJs[] = $file;
        }
    }

    public function getHeaderJs()
    {
        $sReturn = '';

        foreach (array_unique($this->_aHeaderJs) as $file)
        {
            $v = $this->getFileVersion($file);

            $sReturn .= '<script type="text/javascript" src="' . $file . $v . '"></script>' . PHP_EOL;
        }

        return $sReturn;
    }

    public function setFooterJs($file = '', $priority = 5)
    {
        if (empty($file) === false)
        {
            if (isset($this->_aFooterJs[$priority]) === false)
            {
                $this->_aFooterJs[$priority] = [];
            }

            $this->_aFooterJs[$priority][] = $file;
        }
    }

    public function getFooterJs()
    {
        arsort($this->_aFooterJs);

        $aFiles = [];

        foreach ($this->_aFooterJs as $files)
        {
            foreach ($files as $file)
            {
                $v = $this->getFileVersion($file);

                $aFiles[] = '<script type="text/javascript" src="' . $file . $v . '"></script>';
            }
        }

        return implode(PHP_EOL, array_unique($aFiles));
    }

    public function setFooterScript($script = '')
    {
        if (empty($script) === false)
        {
            $this->_aFooterScript[] = $script;
        }
    }

    public function getFooterScript()
    {
        $sReturn = implode(PHP_EOL, $this->_aFooterScript);

        return $sReturn;
    }

    public function setViewData($key = '', $value = null)
    {
        if (empty($key) === false)
        {
            $this->_aViewDatas[$key] = $value;
        }
    }

    public function getViewData($key = '')
    {
        $vReturn = null;

        if (empty($key) === false)
        {
            $vReturn = _elm($this->_aViewDatas, $key);
        }

        return $vReturn;
    }

    public function setViewDatas($vars = [])
    {
        $this->_aViewDatas = array_replace($this->_aViewDatas, $vars);
    }

    public function getViewDatas()
    {
        return $this->_aViewDatas;
    }

    public function isViewFile($module = '', $page = '', $ext = '.php')
    {
        $bReturn = false;

        if (empty($page) === true)
        {
            return $bReturn;
        }

        if (empty($module) === true)
        {
            $file = APPPATH . '../Views/' . $page . $ext;
            $file = $this->getAbsolutePath($file);

            if (is_file($file) === true)
            {
                $bReturn = true;
            }
        }
        else
        {
            $file = __DIR__ . '/../Views/' . $page . $ext;
            $file = $this->getAbsolutePath($file);

            if (is_file($file) === true)
            {
                $bReturn = true;
            }
            else
            {
                $file = ROOTPATH . 'modules/' . $module . '/Views/' . $page . $ext;
                $file = $this->getAbsolutePath($file);

                if (is_file($file) === true)
                {
                    $bReturn = true;
                }
            }
        }

        return $bReturn;
    }

    public function loadView($view, $data = [], $options = [])
    {
        return $this->view($view, $data, $options);
    }

    public function loadPublicView($view, $data = [], $options = null)
    {
        $this->viewPath = ROOTPATH . 'public/';
        $this->setData($data);

        return $this->render($view, $options);
    }

    public function loadLayoutView($pageParam = [], $htmlReturn = false)
    {
        $siteLayout = _elm($pageParam, 'siteLayout');
        $pageLayout = _elm($pageParam, 'pageLayout');

        if (empty($siteLayout) === true)
        {
            $siteLayout = $this->getSiteLayout();

            if (empty($siteLayout) === true)
            {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                exit;
            }
        }

        if (empty($pageLayout) === false)
        {
            $pageLayout = '_' . $pageLayout;
        }

        $pageLayout              = 'layout' . $pageLayout;
        $pageParam['pageLayout'] = $pageLayout;

        $sReturn = $this->view($siteLayout . '\\' . $pageLayout, $pageParam);


        // ---------------------------------------------------------------------
        // 리턴/출력
        // ---------------------------------------------------------------------
        if ($htmlReturn === true)
        {
            return $sReturn;
        }

        echo $sReturn;
    }

    public function setViewClass($key = '', $value = null)
    {
        if (empty($key) === false)
        {
            $this->_aViewClass[$key] = $value;
        }
    }

    public function getViewClass($key = '')
    {
        if (empty($key) === false)
        {
            return $this->_aViewClass[$key] ?? null;
        }
    }
}
