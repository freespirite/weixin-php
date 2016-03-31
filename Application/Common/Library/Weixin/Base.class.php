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
    public static $wxconf = array(
            'token' => '',
            'appid' => '', 
            'mchid' => '',
            'key'   => '',
            'appsecret' => '',
            'sslcert'     => '',
            'sslkey'      => '',
        );

    public static $result = 0; //中断结果输出数据类型，1=json，0=普通页面输出
        
    protected $xmlValues = array();
    
    protected $values = array();
    
    public function setConf($conf)
    {    
        foreach (self::$wxconf as $key => $val) {
//            if(!isset($conf[$key])) {
//                E('config key: '.$key.' not set');
//                continue;
//            }
            $val = trim($conf[$key]);
            self::$wxconf[$key] = $val;
        }
    }
    
    protected function response()
    {
        $this->_valid();
    }

    private function _valid() {
        if(!$this->_checkSignature()) { E('签名验证失败！'); }
        $this->_fromXml($GLOBALS["HTTP_RAW_POST_DATA"]);
        $rs = $this->response();
        echo $rs? $rs: trim(I('get.echostr'));
    }
    
    private function _response()
    {
	if (!isset($this->values['Content']) || !trim($this->values['Content'])){ 
            $this->_debugLog(__CLASS__.':'.__LINE__.' content empty');
            return false;
        }

        $keyword = trim($this->values['Content']);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";             
        if(empty( $keyword )) { return false; }
        switch($keyword) {
            case 1:
                $contentStr = '001号大厅欢迎你的到来！';
                break;
            case 2:
                $contentStr = '002号大厅欢迎你的到来！';
                break;
            case 3:
                $contentStr = '003号大厅欢迎你的到来！';
                break;
            case 4:
                $contentStr = '004号大厅欢迎你的到来！';
                break;
            default:
                $contentStr = '888号大厅欢迎你的到来！';
                break;
        }
        $msgType = "text";
        //$contentStr = "Welcome to wechat world!";
        $resultStr = sprintf($textTpl, $this->values['FromUserName'], $this->values['ToUserName'], $time, $msgType, $contentStr);
        $this->_debugLog($resultStr);
        return $resultStr;
            
    }
    
    private function _fromXml($xml)
    {	
        if(!$xml){
            E("返回的xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
        return $this->values;
    }
    
    private function _checkSignature()
    {
        if(!self::$wxconf['token']) { E('config token error'); }
        $signature = trim(I('get.signature'));
        $timestamp = trim(I('get.timestamp'));
        $nonce = trim(I('get.nonce'));
        $tmpArr = array(self::$wxconf['token'], $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        return $tmpStr == $signature? TRUE: FALSE;
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
        if(self::$result) {
            echo is_array($error)?json_encode($error): json_encode(array('code' => $errorNum, 'msg' => $error));
            exit;
        }
        else {
            E($errorNum.':'.$error);
        }
        
    }
    
    protected function result($result, $code=1) {
        return array($code, $result);
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
        $aryUrl['appid'] = self::$wxconf['wxappid'];
        $aryUrl['secret'] = self::$wxconf['wxappsecret'];
        $aryUrl['grant_type'] = 'client_credential';
        return 'https://api.weixin.qq.com/cgi-bin/token?'.$this->urlParams($aryUrl);
    }

    public function valid2()
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
    
    protected function debugLog($msg) {
//        foreach($_GET as $key => $val) {
//            $msg .= "\n";
//            $msg .= $key . ' = ' . $val;
//        }
        \Think\Log::record($msg,'INFO');
//        return file_put_contents('./msg_receive.log', $msg."\n", FILE_APPEND);
    }
}
