<?php
$sVIEW = '';

if (empty($file) === false)
{
    $sVIEW = $this->view($file);
}


echo $sVIEW;
