<?php
namespace Common\Library\Weixin;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Token
 *
 * @author Administrator
 */
class Token extends Base {
    
    private function _createTokenUrl() {
        $aryUrl['appid'] = self::$wxappid;
        $aryUrl['secret'] = self::$wxappsecret;
        $aryUrl['grant_type'] = 'client_credential';
        return 'https://api.weixin.qq.com/cgi-bin/token?'.$this->urlParams($aryUrl);
    }
    
    public function getToken() {
        $con = file_get_contents($this->_createTokenUrl());
        $ary = json_decode($con);
        if(isset($ary['access_token'])) { 
            return $ary;
        }
        else if(isset($ary['errmsg'])) {
            $this->sysExit($ary['errmsg'], $ary['errcode']);
        }
        else {
            $this->sysExit('网络问题或接口无响应');
        }
    }
}
