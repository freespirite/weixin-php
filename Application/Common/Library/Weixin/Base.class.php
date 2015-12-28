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
    static $wxappid = '';
    static $wxmchid = '';
    static $wxkey = '';
    static $wxappsecret = '';
    static $sslcert = '';
    static $sslkey = '';
    
    function __construct($conf) {
        self::$wxappid = $conf['wxappid'];
        self::$wxmchid = $conf['wxmchid'];
        self::$wxkey   = $conf['wxkey'];
        self::$wxappsecret = $conf['wxappsecret'];
        self::$sslcert     = $conf['sslcert'];
        self::$sslkey      = $conf['sslkey'];
    }
    
    private function _getConf() {
        return array(
            'wxappid' => self::$wxappid, 
            'wxmchid' => self::$wxmchid,
            'wxkey'   => self::$wxkey,
            'wxappsecret' => self::$wxappsecret,
            'sslcert'     => self::$sslcert,
            'sslkey'      => self::$sslkey,
        );
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
        $aryUrl['appid'] = self::$wxappid;
        $aryUrl['secret'] = self::$wxappsecret;
        $aryUrl['grant_type'] = 'client_credential';
        return 'https://api.weixin.qq.com/cgi-bin/token?'.$this->urlParams($aryUrl);
    }
    
    
}
