<?php

namespace Home\Controller;

use Think\Controller;
use Home\Model\CasusModel;

/**
 * Class IndexController
 * @package Home\Controller
 * 前端主页控制器，这个暂时作为预留，默认的控制器改为mainController
 */


class IndexController extends Controller
{


    public function index()
    {
        $obj = new CasusModel();
        $list = $obj->getList(1, 20, 'disable=0');
        $this->assign('list', $list);
        $this->display('index');
    }
}