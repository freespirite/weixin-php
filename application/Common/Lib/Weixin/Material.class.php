<?php
namespace Common\Lib\Weixin;

class Material extends Base {
    
    private $_appid  = '';
    private $_secret = '';
//    protected $errorInfo = '';
    private $_token  = '';
    private $_model  = null;
            
    function __construct($appid, $secret, $wxid, $siteid=0) {
		parent::__construct($appid, $secret, $wxid, $siteid);
        //$this->_model = D('Common/WeixinReplyset');
	}
    
    /**
     * 根据素材ID获取地址 
     * 格式
     * @param type $meidiaId 媒体id
     * @return array 
     */
    public function getImageUrl($meidiaId) {
        $url   = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getToken().'&media_id='.$meidiaId;
        return;
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
     * 获取素材总数
     * @return string
     */
    public function getMaterialCount() {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token='.$this->getToken();
        $con = toRequest($url);
        return $con;
    }
    
    
    public function preview($openid, $mediaId) {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$this->getToken();
        $data = '{"touser":"'.$openid.'","mpnews":{"media_id":"'.$mediaId.'"},"msgtype":"mpnews"}';
//        echo $data;exit;
        return toRequest($url, $data);
    }
    
    public function sendall($tagid, $mediaId) {
        $url   = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->getToken();
        $toAll = $tagid? 'false': 'true';
        $data  = '{"filter":{"is_to_all":'.$toAll.',"tag_id":'.$tagid.'},"mpnews":{"media_id":"'.$mediaId.'"},"msgtype":"mpnews","send_ignore_reprint":0}';
//        echo $data;exit;
        return toRequest($url, $data);
    }


    public function addNews() {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$this->getToken();
        $news = array(
            'title'=> I('post.title'),
            'thumb_media_id'=> I('post.mid'),
            'author'=> I('post.author'),
            'digest'=> I('post.digest'),
            'show_cover_pic'=> 1,
            'content'=> I('post.content'),
            'content_source_url'=> I('post.url','','trim')
        );
        
        $data = $this->_uploadArticles(array($news));
        if(!$data) { return FALSE; }
//        print_r($data);exit;
        $ch1 = curl_init ();
        $timeout = 5;
        curl_setopt ( $ch1, CURLOPT_URL, $url );
        curl_setopt ( $ch1, CURLOPT_POST, 1 );
        curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch1, CURLOPT_POSTFIELDS, $data );
        $result = curl_exec ( $ch1 );
        curl_close ( $ch1 );
//        $result=json_decode($result,true);
//        if(isset($result['url'])) {
//            return $result;
//        } else {
//            return FALSE;
//        }
        return json_decode($result,true);
    }
    
    private function _matchImage($content) {
        $p = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
        preg_match_all($p, $content, $match);
//        echo $content;
//        print_r($match);
        if (!isset($match[1])) {
            return $content;
        }
        $api = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$this->getToken();
        foreach ($match[1] as $img) {
//            if(strpos($img, 'mmbiz.qpic.cn')) { continue; }
            $newimg = $this->_upload2weixin($img, $api);
            if(!$newimg) {
//                $this->errorInfo = '上传图片到微信失败！';
                return FALSE;
            }
            $content = str_replace($img, $newimg, $content);
        }
        return $content;
    }

    private function _upload2weixin($img, $url) {
        
        $aryf  = explode('.', basename($img));
        $ext  = $aryf[count($aryf)-1];
        $fpath = TEMP_PATH.time().'_'.  mt_rand(100000, 999999).'.'.$ext;
        $con = toRequest($img);
        if(!$con) { return FALSE; }
        $n = file_put_contents($fpath, $con);
        if(!$n) { return FALSE; }
        $ary = get_headers($img, TRUE);
        
        $info = array(
            'filename'=> basename($fpath),  //国片相对于网站根目录的路径
            'content-type'=> $ary['Content-Type'],  //文件类型
            'filelength'=> $ary['Content-Length']
        );
        //header('Content-type:text/html; charset=utf-8');  //声明编码  
        $ch = curl_init();  
        $timeout = 5;
        $data = array('media'=>'@'.$fpath, 'form-data'=>$info);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $rs = curl_exec ( $ch );
        curl_close ( $ch );
        $rs = json_decode($rs,true);
        unlink($fpath);
//        print_r($rs);exit;
//        if(!isset($rs['url'])) { print_r($rs);exit; }
        if(isset($rs['errmsg'])) {
            $this->errorInfo = '上传错误：('.$rs['errcode'].')'.$rs['errmsg'];
            return FALSE;
        }
        return isset($rs['url'])? $rs['url']: FALSE;
    }
    
    /**
     * 上传图文消息素材
     * @param array $data 消息结构{"articles":[{...}]}
     * @上传的内容含有html需要转义，编码，解码处理变成html标签
     * return boolean|array
     */
    private function _uploadArticles($data) {

//        foreach ($data as $index=>$item) {
//            foreach ($item as $k => $v) {
//                //将""转化为单引号，转化为html实体后编码
//                if ($k == 'content') {
//                    $data[$index][$k] = urlencode(htmlspecialchars(str_replace("\"", "'", $v)));
//                } else {
//                    //$data[$index][$k] = urlencode($v);
//                }
//            }
//        }
        //转化为json
        $datas = '{"articles":[';
        foreach ($data as $key => $value) {
            $datas = $datas . '{';
            $datas = $datas . "\"thumb_media_id\":" . "\"" . $value['thumb_media_id'] . "\",";
            $datas = $datas . "\"author\":" . "\"" . $value['author'] . "\",";
            $datas = $datas . "\"title\":" . "\"" . $value['title'] . "\",";
            $datas = $datas . "\"content_source_url\":" . "\"" . $value['content_source_url'] . "\",";
            
            
            $value['content'] = urldecode($value['content']);
//            echo $value['content'];
            
//            $value['content'] = str_replace('&lt;', '<', $value['content']);
//            $value['content'] = str_replace('&gt;', '>', $value['content']);
            $value['content'] = str_replace(array('&lt;', '&gt;', '&amp;', '&quot;', '&apos;'), array('<', '>', '&', '"', '\''), $value['content']);
            $value['content'] = $this->_matchImage($value['content']);
            if(!$value['content']) { return FALSE; }
//            exit($value['content']);
            $value['content'] = str_replace('"', '\"', $value['content']);
//            echo "\n\n\n";
//            echo $value['content'];exit("\n\n".date('Y-m-d H:i:s'));
            
            $datas = $datas . "\"content\":" . "\"" . $value['content'] . "\",";
            $datas = $datas . "\"digest\":" . "\"" . $value['digest'] . "\",";
//            $datas = $datas . "\"show_cover_pic\":" . "\"" . $value['show_cover_pic'] . "\"";
            $datas = $datas . "\"show_cover_pic\":" . "\"1\"";
            $datas = $datas . '},';
        }
        $datas = trim($datas, ',');
        $datas = $datas . ']}';
        //上传之前对内容进行urldecode解码，将html实体转成html标签
        //$datas = urldecode($datas);
        //$datas = htmlspecialchars_decode($datas);
//        $result = $this->http_post(self::API_URL_PREFIX . self::MEDIA_UPLOADNEWS_URL . 'access_token=' . $this->access_token, $datas);
//        if ($result) {
//            $json = json_decode($result, true);
//            if (!$json || !empty($json['errcode'])) {
//                $this->errCode = $json['errcode'];
//                $this->errMsg = ErrCode::getErrText($json['errcode']);
//                $this->resetAuth($this->appid);
//                return false;
//            }
//            return $json;
//        }
//        return false;
        return $datas;
    }

    /**
     * 获取素材列表
     * @param string $type
     * @param int $offset
     * @param int $count
     * @return string
     */
    public function getMaterial($type="news",$offset=0,$count=20){
        
        $url   = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->getToken();
        $filedata = array(
            'type' => $type,
            'offset' => $offset,
            'count' => $count
        );
        
        $result = toRequest($url, json_encode($filedata));
        $result = json_decode($result, true);
        
//        print_r($filedata);
//        echo "\n\n".$url."\n\n";
//        print_r($result);exit;
        
        $data   = array();
        foreach($result['item'] as $row) {
            $data[] = $this->_getDetail($row, $type);
        }
        $result['item'] = $data;
//        print_r($result);exit;
        return $result;
    }
    
    /**
     * 返回素材的详细信息数组
     * @param array $row
     * @param string $type
     * @return array
     */
    public function _getDetail($row, $type) {
        $data = null;
        switch ($type) {
            case 'news':
                $info = $row['content']['news_item'][0];
                $data = array(
                    'title' => $info['title'],
                    'url' => $info['url'],
                    'thumb' => $info['thumb_url'],
                    'time' => date('Y-m-d H:i:s', $row['content']['update_time']),
                    'media_id' => $row['media_id'],
                );
                break;
            default:
                $data = array(
                    'title' => $row['name'],
                    'url' => $row['url'],
                    'thumb' => '',
                    'time' => date('Y-m-d H:i:s', $row['update_time']),
                    'media_id' => $row['media_id'],
                );
                break;
        }
        return $data;
    }

    /**
     * 删除永久素材
     * @param string $mid
     * @return boolean
     */
    public function delMaterial($mid){
        $url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.$this->getToken();
        if(empty($mid)){
            $this->errorInfo = '素材ID不能为空';
            return FALSE;
        }
        $filedata = array( 'media_id'=> $mid);
        $filedata = json_encode($filedata);
        $result = toRequest($url, $filedata);
        $result = json_decode($result, true);
        if($result['errcode']==0){
            return TRUE;
        }elseif ($result['errcode']==40007) {
            $this->errorInfo = '素材ID不正确';
            return FALSE;
        }else if($result['errcode']==48005) {
            $this->errorInfo = '不能删除被自动回复和自定义菜单引用的素材';
            return FALSE;
        }else {
            $this->errorInfo = '['.$result['errcode'].'] '.$result['errmsg'];
            return FALSE;
        }
    }

    //https请求（支持GET和POST）
    protected function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    
}

