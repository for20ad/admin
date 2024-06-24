<?php
$sVIEW = '';

if (empty($file) === false)
{
    $sVIEW = $this->view($file);
}

echo $this->view('\Module\core\Views\layouts\default\header');
echo $this->view('\Module\core\Views\layouts\default\header_sub');

echo $sVIEW;

echo $this->view('\Module\core\Views\layouts\default\footer_sub');
echo $this->view('\Module\core\Views\layouts\default\footer');
