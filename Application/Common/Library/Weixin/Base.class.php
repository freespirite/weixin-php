<?php
namespace Common\Library\Weixin;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author Administrator
 */
class Base {
    //put your code here
    private $_conf = array(
            'wxappid' => '', 
            'wxmchid' => '',
            'wxkey'   => '',
            'wxappsecret' => '',
            'sslcert'     => '',
            'sslkey'      => '',
        );
    
    private $_xmlValues = array();
    
    function __construct($conf) {
        
        foreach ($this->_conf as $key => $val) {
            $val = trim($val);
            if(!isset($conf[$key]) || !$val) { exit('config key: '.$key.' not set or value empty'); }
            $this->_conf[$key] = $val;
        }
    }
    
    public function getConf() {
        return $this->_conf;
    }
    
    /**
     * 
     * 拼接签名字符串
     * @param array $urlObj
     * 
     * @return 返回已经拼接好的字符串
     */
    protected function urlParams($aryParams) {
        $buff = '';
        foreach ($aryParams as $k => $v) {
            if($k != 'sign'){
                    $buff .= $k . '=' . $v . '&';
            }
        }
        $buff = trim($buff, '&');
        return $buff;
    }
    
    protected function sysExit($error = '操作错误', $errorNum = '101') {
        //$this->logs($error, $errorNum);
        echo json_encode(array('code' => $errorNum, 'msg' => $error));
        exit;
    }
    
    private function _cacheSetToken($token, $uid, $timeOut = 7200) {
        $cache = \Think\Cache::getInstance('File');
        $cache->set('wx_token_'.$uid, $token, $timeOut);
        return $token;
    }
    
    protected function getToken($uid) {
        $cache = \Think\Cache::getInstance('File');
        $token = $cache->get('wx_token_'.$uid);
        if(!$token) {
            $con = file_get_contents($this->_createTokenUrl());
            $ary = json_decode($con);
            if(isset($ary['access_token'])) { 
                return $this->_cacheSetToken($ary['access_token'], $uid, $ary['expires_in']);
            }
            else if(isset($ary['errmsg'])) {
                $this->sysExit($ary['errmsg'], $ary['errcode']);
            }
            else {
                $this->sysExit('网络问题或接口无响应');
            }
        }
    }
    
    private function _createTokenUrl() {
        $aryUrl['appid'] = $this->_conf['wxappid'];
        $aryUrl['secret'] = $this->_conf['wxappsecret'];
        $aryUrl['grant_type'] = 'client_credential';
        return 'https://api.weixin.qq.com/cgi-bin/token?'.$this->urlParams($aryUrl);
    }

    public function valid()
    {
        $echoStr = isset($_GET["echostr"])? $_GET["echostr"]: '';
        //valid signature , option
        if($this->checkSignature()){
            $aryData = array();
        	//echo $echoStr;
            if($_POST) {
                $echoStr .= 'is post'."\n";
                $aryData = $this->FromXml($_POST);
            }
            else if($HTTP_RAW_POST_DATA) {
                $echoStr .= 'is HTTP_RAW_POST_DATA'."\n";
                $aryData = $this->FromXml($HTTP_RAW_POST_DATA);
            }
            else if($GLOBALS["HTTP_RAW_POST_DATA"]) {
                $echoStr .= 'is GLOBALS[HTTP_RAW_POST_DATA]'."\n";
                $aryData = $this->FromXml($GLOBALS["HTTP_RAW_POST_DATA"]);
            }

            if($aryData) {
                foreach($aryData AS $key => $val) {
                    $echoStr .= $key.' = ' .$val."\n";
                }
            }
            $echoStr .= "\n======================================\n\n";
            $this->_debugLog(__CLASS__.':'.__LINE__."\n".$echoStr);
            $this->responseMsg();
        	//exit;
        }
    }
}
