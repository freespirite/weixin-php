<?php
namespace Api\Controller;

use Common\Controller\AppframeController;

class WeixinController extends AppframeController {

    
    private $_modMsg;
    private $_wxconf;
    //protected $auth_rule_model;

    public function _initialize() {
        //parent::_initialize();
        
        $this->_modMsg = D('Common/WeixinMsg');
        $this->wxconf = D("Common/WeixinConf");
        
        //$this->auth_rule_model = D("Common/AuthRule");
    }

    /**
     * 微信api入口
     */
    public function index() {
        $key  = I('get.key', '', 'trim');
        if(!$key) { $this->_reply(''); }
        
        $conf = D('Common/WeixinConf')->where(array('code'=>$key))->find();
        
        if(!$conf) { $this->_reply(''); }
        $this->_wxconf = $conf;
        $this->valid();
        $reply = '';
        $omsg = new \Common\Lib\Weixin\Msg($this->_wxconf['key'], $this->_wxconf['secret'], $this->_wxconf['id'], $this->_wxconf['sid']);
        $msg = $omsg->getMsg();
        if(!$msg) { $this->_reply(''); }
        $type = strtolower($msg['MsgType']);//消息类型
//        $username = $msg['FromUserName'];//哪个用户给你发的消息,这个$username是微信加密之后的，但是每个用户都是一一对应的
        
        if ($type==='text') {
            //$mconf = D('Common/WeixinMsg');
            
            if ($msg['Content']=='Hello2BizUser') {//微信用户第一次关注你的账号的时候，你的公众账号就会受到一条内容为'Hello2BizUser'的消息
                $this->_reply($omsg->replyAddMsg());
            }else{//这里就是用户输入了文本信息
                
                $this->_reply($omsg->replyAutoReply($msg['Content']));
            }
        }elseif ($type==='location') {
              //用户发送的是位置信息  稍后的文章中会处理                  
        }elseif ($type==='image') {
              //用户发送的是图片 稍后的文章中会处理
        }elseif ($type==='voice') {     
              //用户发送的是声音 稍后的文章中会处理
        }
        
        $this->_reply($reply);

    }
    
    
    private function sendMsg() {
        $postdata ='{"touser":"o5BkRs_vRwfPqAb1ceXHfJDzmQ5o","msgtype":"text","text":{"content":"Hello World"}}';
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'Content-Length' => strlen($postdata),
                'Host' => 'api.weixin.qq.com',
                'Content-Type' => 'application/json',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $result = toRequest('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=你的access_token', true, $context);
        echo $result;
    }
    
    public function responseMsg()
    {
        $postStr = $GLOBALS['HTTP_RAW_POST_DATA'];
        if (!empty($postStr)){
            file_put_contents('./weixin_content.txt', $postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = '<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0<FuncFlag>
            </xml>';
            if(!empty( $keyword ))
            {
                $msgType = 'text';
                $contentStr = date('Y-m-d H:i:s').'你好啊，屌丝';
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo '咋不说哈呢';
            }
        }else {
            echo '咋不说哈呢';
        }
        exit;
    }
    
    /**
     * token验证
     * @return type
     */
    private function valid()
    {
        $echoStr = I('get.echostr');
        if(!$echoStr) { return; }
        echo $echoStr;exit;
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    
    /**
     * 验证接口签名
     * @return boolean
     */
    private function checkSignature()
    {
        $signature = I('get.signature');
        $timestamp = I('get.timestamp');
        $nonce     = I('get.nonce');
        $tmpArr = array($this->_wxconf['token'], $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 输出回复内容信息给用户
     * @param type $info
     */
    private function _reply($info = '') {
        echo $info;exit;
    }
    
}
