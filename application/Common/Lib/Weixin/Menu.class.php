<?php
namespace Common\Lib\Weixin;

class Menu extends Base {
    private $_model  = null;
    private $_Menus = null;
            
    function __construct($appid, $secret, $wxid, $siteid=0) {
		parent::__construct($appid, $secret, $wxid, $siteid);
        $this->_model = D('Common/WeixinMenu');
	}
    
    /**
     * 导入自定义菜单项
     * @param int $wxid
     * @return boolean
     */
    public function import($wxid) {
        if(!$wxid) { $this->error('操作错误'); }
        $sid  = session('siteid');
        $row  = D('Common/WeixinConf')->where(array('id'=> $wxid, 'sid' => $sid))->find();
        if(!$row) { $this->errorInfo('公众号不存在');return FALSE; }
        if($row['pass'] < 0) { $this->errorInfo = '该公众号还未对接成功，请检查配置！'; return FALSE; }

        $url = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.$this->getToken();
        $con = toRequest($url);
        $ary = json_decode($con, 1);
        if(!isset($ary['selfmenu_info'])) {
            $this->errorInfo = '操作错误 '.$ary['errmsg'];
            return FALSE;
        }
        $time = time();
        $row = $this->_model->where(array('wxid'=> $wxid, 'sid'=> $sid))->find();
        $data = array(
            'wxid' => $wxid,
            'content' => json_encode($ary['selfmenu_info']['button']),
            'updatetime' => $time,
        );
        if($row) {
            $rs = $this->_model->where(array('wxid'=> $wxid, 'sid'=> $sid))->save($data);
        } else {
            $data['addtime'] = $time;
            $data['sid'] = $sid;
            $rs = $this->_model->add($data);
        }
        
        if(!$rs) {
            $this->errorInfo = $this->_model->getError();
        }
        return $rs;
    }
    
    public function getMenus() {
        
        $url = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.$this->getToken();
        $con = toRequest($url);
//        exit($con);
        $ary = json_decode($con, 1);
        if(!isset($ary['selfmenu_info'])) {
            $this->errorInfo = '操作错误 '.$ary['errmsg'];
            return array();
        }
        
        return $ary['selfmenu_info']['button'];
    }


    /**
     * 获取用户发送的消息内容
     * @return boolean
     */
    public function getMsg() {
        
//        $this->_msg = array(
//            'MsgType' => 'text',
//            'Content' => I('get.msg', '', 'trim'),
//            'FromUserName' => 'fromuser@163.com',
//            'ToUserName' => 'touser@163.com',
//        );
//        return $this->_msg;
        
        $xml = $GLOBALS["HTTP_RAW_POST_DATA"];
        //$this->toLogs($postStr);
        if (!empty($xml)) {
            $this->_msg = json_decode(json_encode((array) simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            //file_put_contents('/data/www/test/test/nick/weixin/logs_'.date('Ymd').'.txt', json_encode($this->_msg), FILE_APPEND);
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

