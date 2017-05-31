<?php
namespace Common\Lib\Weixin;

class Msg extends Base {
    
//    private $_appid  = '';
//    private $_secret = '';
//    protected $errorInfo = '';
//    private $_token  = '';
    private $_model  = null;
    private $_msg = null;
    private $_replySet = null;
            
    function __construct($appid, $secret, $wxid, $siteid=0) {
		parent::__construct($appid, $secret, $wxid, $siteid);
        $this->_model = D('Common/WeixinReplyset');
	}
    
    /**
     * 后台导入自动回复信息 
     * @return boolean 
     */
    public function importReply() {
        $url   = 'https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token='.$this->getToken();
        $con   = toRequest($url);
        $arys  = json_decode($con, 1);
        if(isset($arys['errmsg'])) {
            $this->errorInfo = $arys['errmsg'];
            return FALSE;
        }
//        print_r($arys);
        $data['wxid'] = $this->getWxid();
        $data['addtime'] = $data['updatetime'] = time();
        $data['add_info'] = isset($arys['add_friend_autoreply_info'])? json_encode($arys['add_friend_autoreply_info']): '';
        $data['default_info'] = isset($arys['message_default_autoreply_info'])? json_encode($arys['message_default_autoreply_info']): '';
        $data['keyword_info'] = isset($arys['keyword_autoreply_info'])? json_encode($arys['keyword_autoreply_info']['list']): '';
        $data['sid'] = $this->getSiteid();
        if(!$data['sid']) {
            $this->errorInfo = '所属网站不能为空';
            return false;
        }
        
        $row = $this->_model->where(array('wxid'=>  $this->getWxid()))->find();
        if($row) {
            $this->_model->where(array('id'=>$row['id']))->save($data);
        } else {
            $rs = $this->_model->add($data);
            if(!$rs) {
                $this->errorInfo = $this->_model->getDbError();
                return false;
            }
        }
        return TRUE;
    }
    
    /**
     * 获取用户发送的消息内容
     * @return boolean
     */
    public function getMsg() {
        /*
        $this->_msg = array(
            'MsgType' => 'text',
            'Content' => I('get.msg', '', 'trim'),
            'FromUserName' => 'oU29Qt3yFNjvJuxWMdj5r2_IvvsI',
            'ToUserName' => 'gh_73f915e0fdc0',
        );
        return $this->_msg;
        */
        $xml = $GLOBALS["HTTP_RAW_POST_DATA"];
        //$this->toLogs($postStr);
        if (!empty($xml)) {
            $this->_msg = json_decode(json_encode((array) simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
//            file_put_contents('/data/www/test/test/nick/weixin/logs_'.date('Ymd').'.txt', json_encode($this->_msg), FILE_APPEND);
            D('Common/WeixinMsg')->addNew($this->_msg, $this->getSiteid(), $this->getWxid());
            return $this->_msg;
        } else {
            $this->errorInfo = '获取信息失败';
        }
        return false;
    }
    
    private function _getReplySet() {
        if($this->_replySet) { return $this->_replySet; }
        $this->_replySet = $this->_model
                ->where(array('wxid'=> $this->getWxid(), 'sid'=> $this->getSiteid()))
                ->find();
        return $this->_replySet;
    }

    
    /**
     * 关注公众号时的回复信息
     * @return string
     */
    public function replyAddMsg() {
        $this->_getReplySet();
        if(!$this->_replySet || !$this->_replySet['add_info']) { return ''; }
        $text = json_decode($this->_replySet['add_info'], 1);
        
        $this->_addNewUser();
        if($text['type'] == 'text') {
            return $this->_makeText($text['content']);
        } else {
            return '';
        }
    }
    
    public function replyAutoReply($content) {
        $this->_getReplySet();
        if(!$this->_replySet || !$this->_replySet['add_info']) { return ''; }
        $kws = json_decode($this->_replySet['keyword_info'], 1);
        $this->_addNewUser(TRUE);
        $con = '';
        foreach ($kws as $kw) {
            $con = $this->_keywordReply($content, $kw);
            if($con) {
                return $this->_makeText($con);
            }
        }
        
        if(!$con) {
            $text = json_decode($this->_replySet['default_info'], 1);
            if($text['type'] == 'text') {
                return $this->_makeText($text['content']);
            } else {
                return '';
            }
        }
    }
    
    private function _addNewUser($check=FALSE) {
        $model = D('Common/WeixinUsers');
        if($check) {
            $row = $model->where(array('wxid'=>  $this->getWxid(), 'openid'=>$this->_msg['FromUserName']))->find();
            if($row) { return TRUE; }
        }
        
        $user = new \Common\Lib\Weixin\User($this->getAppid(), $this->getSecret(), $this->getWxid(), $this->getSiteid());
        $info = json_decode($user->getUserInfo($this->getToken(), $this->_msg['FromUserName']), true);
        if(!isset($info['errcode'])) {
            $time = time();
            $data = array(
                'wxid' => $this->getWxid(),
                'nickname' => $info['nickname'],
                'openid' => $info['openid'],
                'sex' => $info['sex'],
                'city' => $info['city'],
                'province' => $info['province'],
                'country' => $info['country'],
                'province' => $info['headimgurl'],
                'subscribe_time' => $info['subscribe_time'],
                'remark' => $info['remark'],
                'groupid' => $info['groupid'],
                'addtime' => $time,
                'updatetime' => $time,
                'sid' => $this->_replySet['sid'],
            );
            return $model->add($data);
        }
        return FALSE;
    }
    private function _keywordReply($content, $sets) {
        
        foreach ($sets['keyword_list_info'] as $word) {
            if($word['type'] != 'text') { continue; }
            if($sets['match_mode'] == 'equal') {
//                echo $content.'='.$word['content'].'<br/>';
                if($word['content'] == $content) {
                    return $this->_getKeywordReply($sets['reply_list_info'], $sets['reply_mode']);
                }
            } else {
//                echo $content;print_r($sets);exit(date('Y-m-d H:i:s'));
//                echo $content.'='.$word['content'].'<br/>';
                if (strpos($content, $word['content']) !== false) {
                     return $this->_getKeywordReply($sets['reply_list_info'], $sets['reply_mode']);
                }
            }
        }
    }
    
    private function _getKeywordReply($replys, $all = 'reply_all') {
        
        $content = '';
        if($all == 'reply_all') {
            foreach($replys as $reply) {
                if($reply['type'] != 'text') { continue; }
                $content .= $this->_makeText($reply['content']);
            }
        } else {
            $count = count($replys);
            $index = mt_rand(0, $count-1);
            //if($replys['type']!= 'text')
            $content = $replys[$index]['content'];
        }
        return $content;
    }


    /**
     * 回复文本消息
     * @param type $text
     * @return String
     */
    private function _makeText($text='')
    {
        $FuncFlag = $this->setFlag ? 1 : 0;
        $textTpl = '<xml>
            <ToUserName><![CDATA['.$this->_msg['FromUserName'].']]></ToUserName>
            <FromUserName><![CDATA['.$this->_msg['ToUserName'].']]></FromUserName>
            <CreateTime>'.time().'</CreateTime>
            <MsgType><![CDATA['.$this->_msg['MsgType'].']]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%s</FuncFlag>
            </xml>';
        return sprintf($textTpl,$text,$FuncFlag);
    }

    //根据数组参数回复图文消息
    protected function makeNews($newsData=array())
    {
        $CreateTime = time();
        $FuncFlag = $this->setFlag ? 1 : 0;
        $newTplHeader = '<xml>
            <ToUserName><![CDATA['.$this->_msg['FromUserName'].']]></ToUserName>
            <FromUserName><![CDATA['.$this->_msg['ToUserName'].']]></FromUserName>
            <CreateTime>'.$CreateTime.'</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <ArticleCount>%s</ArticleCount><Articles>';
        $newTplItem = '<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>';
        $newTplFoot = '</Articles>
            <FuncFlag>%s</FuncFlag>
            </xml>';
        $Content = '';
        $itemsCount = count($newsData['items']);
        $itemsCount = $itemsCount < 10 ? $itemsCount : 10;//微信公众平台图文回复的消息一次最多10条
        if ($itemsCount) {
            foreach ($newsData['items'] as $key => $item) {
                if ($key<=9) {
                    $Content .= sprintf($newTplItem,$item['title'],$item['description'],$item['picurl'],$item['url']);
                }
            }
        }
        $header = sprintf($newTplHeader,$newsData['content'],$itemsCount);
        $footer = sprintf($newTplFoot,$FuncFlag);
        return $header . $Content . $footer;
    }
    
    protected function reply($data)
    {
        $this->toLogs($data);
        echo $data;
    }
    
    
    protected function search(){
       $record = array();
       /*$list = $this->search($this->keyword); //普通的根据关键词查询数据库的操作  代码就不用分享了
　　　　 if(is_array($list) && !empty($list)){                 
               foreach($list as $msg){ 
　　　　　　　　　　　　　　　　$record[]=array(//以下代码，将数据库中查询返回的数组格式化为微信返回消息能接收的数组形式，即title、description、picurl、url 详见微信官方的文档描述
                    'title' =>$msg['title'],
                    'description' =>$msg['discription'],
                    'picurl' => $msg['pic_url'],
                    'url' =>$msg['url']
                );
　　　　　　　　}
　　　　}*/
       return $record;
    }
    
    protected function toLogs($log, $level = 'DEBUG'){//记录调试信息
        //\Think\Log::record($log, $level);
    }
}

