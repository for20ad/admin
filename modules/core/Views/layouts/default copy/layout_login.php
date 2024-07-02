<?php
$sVIEW = '';

if (empty($file) === false)
{
    $sVIEW = $this->view($file);
}

echo $this->view('\Module\core\Views\layouts\default\header');


echo $sVIEW;

echo $this->view('\Module\core\Views\layouts\default\footer');
