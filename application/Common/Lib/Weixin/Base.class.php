<?php
namespace Common\Lib\Weixin;

class Base {
    
    private $_appid      = '';
    private $_secret     = '';
    protected $errorInfo = '';
    private $_wxid       = 0;
    private $_siteid     = 0;
    private $_token      = array();
            
    function __construct($appid, $secret, $wxid, $siteid=0) {
		$this->_appid  = $appid;
        $this->_secret = $secret;
        $this->_siteid = $siteid;
        if(!$this->_appid || !$this->_secret) {
            E('appid 和 appSecret 不能为空');
        }
        if(!$wxid) {
            E('操作的公众账号未选中');
        }
        $this->_wxid  = $wxid;
	}
    
    public function getToken($exit = TRUE, $reget = FALSE) {
        $key = 'c_token_'.session('ADMIN_ID').'_'.$this->_wxid;
        if(!$reget) {
            if(isset($this->_token[$key])) { return $this->_token[$key]; }
            $token = S($key);
            if($token) { 
                $this->_token[$key] = $token;
//                echo __FILE__.'>'.__LINE__.'>'.$con.'<br/><br/>';exit($this->_token[$key]);
                return $this->_token[$key];
            }
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_secret;
        //$con = file_get_contents($url);
        $con = toRequest($url);
//        echo __FILE__.'>'.__LINE__.'>'.$con.'<br/><br/>'.$url;
        $ary = json_decode($con, 1);
        if(isset($ary['errmsg'])) {
            $this->errorInfo = '['.$ary['errcode'].']'.$ary['errmsg'];
            if($exit) { E('error: '. $this->errorInfo); }
//            E('GET weixin token error: '. $ary['errmsg']);
            return false;
        }
        $this->_token[$key] = $ary['access_token'];
        S($key, $this->_token[$key], $ary['expires_in'] - 10);
        return $this->_token[$key];
    }
    
    public function checkAuthorized($update=true) {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->getToken();
        $con = toRequest($url);
        $ary = json_decode($con, 1);
//        $rs  = isset($ary['errcode']) && $ary['errcode'] == 48001? FALSE: TRUE;
        $rs  = isset($ary['errcode'])? FALSE: TRUE;
        if(!$rs && $update) {
            D("Common/WeixinConf")->where(array('id'=>  $this->_wxid))->save(array('pass'=> 0));
        }
        return $rs;
    }
    
    protected function getWxid() {
        return $this->_wxid;
    }
    
    protected function getAppid() {
        return $this->_appid;
    }
    
    protected function getSecret() {
        return $this->_secret;
    }

    public function getErrorInfo() {
        return $this->errorInfo;
    }
    
    public function getSiteid() {
        return $this->_siteid;
    }
}

