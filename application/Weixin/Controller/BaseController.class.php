<?php
/**
 * 基础类
 */
namespace Weixin\Controller;

use Common\Controller\AdminbaseController;

class BaseController extends AdminbaseController {
    
    protected $weixinConf;
    protected $level = array(
        1 => '订阅号',
        2 => '服务号'
    );
    
    private $_error = '';


    public function _initialize() {
        parent::_initialize();
        $this->weixinConf = D("Common/WeixinConf");
    }
    
    
    
    protected function getError() {
        return $this->_error;
    }
    
    protected function setError($error) {
        $this->_error = $error;
    }
    
    
}
