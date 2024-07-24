<?php

namespace Module\membership\Config;
use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    public $member = [];
    public function __construct()
    {
        $this->member                               = [];
        $this->member['condition']                  = [];
        $this->member['condition']['user_id']       = '아이디';
        $this->member['condition']['user_name']     = '이름';
        $this->member['condition']['mobile']        = '휴대폰';

        $this->member['status']                     = [];
        $this->member['status']['']                 = '전체';
        $this->member['status'][2]                  = '승인';
        $this->member['status'][1]                  = '대기';
        $this->member['status'][3]                  = '중지';
        $this->member['status'][4]                  = '탈퇴';
        $this->member['status'][9]                  = '삭제';

    }
}