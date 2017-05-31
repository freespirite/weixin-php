<?php
/**
 * Material(素材管理)
 */
namespace Weixin\Controller;
use \Common\Lib\Weixin\Material;

class MaterialController extends BaseController {

//    protected $weixin_msg;

    public function _initialize() {
        parent::_initialize();
//        $this->weixinUser = D("Common/WeixinUsers");
    }

    /**
     * 微信用户列表
     */
    public function index() {
        //$this->_importUser();
        $this->_lists();
        $this->display();
    }
    
    public function del() {
        $wxid = I('get.wxid',0,'intval');
        $mid  = I('get.mid','','trim');
        if(!$wxid || !$mid) {
            $this->error('操作错误');
        }
        $conf = $this->weixinConf->where(array('id'=>$wxid, 'sid' => session('siteid')))->find();
        if(!$conf) {
            $this->error('公众号不存在');
        }
        $o  = new Material($conf['key'], $conf['secret'], $wxid);
        $rs = $o->delMaterial($mid);
        
        if($rs) {
            $this->success('删除成功');
        } else {
            $this->error($o->getErrorInfo());
        }
    }
    
    
    public function addNews() {
//        print_r($_SESSION);exit;
        if(IS_POST) {
            $wxid = I('post.wxid', 0, 'intval');
            $conf = D("Common/WeixinConf")->where(array('id' => $wxid, 'sid' => session('siteid')))->find();
            if (!$conf) {
                $this->ajaxReturn(array('name' => '', 'status' => 0, 'message' => '公众号错误'));
            }
//            print_r($conf);exit;
            $o = new Material($conf['key'], $conf['secret'], $wxid);
            $rs = $o->addNews();
            if(!$rs) { $this->ajaxReturn(array('name' => '', 'status' => 0, 'message' => $o->getErrorInfo())); }
            $this->ajaxReturn($rs);
        }
        
        $wxid = I('get.wxid',0,'intval');
        if(!$wxid) {
            $this->error('操作错误');
        }
        $conf = $this->weixinConf->where(array('id'=>$wxid, 'sid' => session('siteid')))->find();
        if(!$conf) {
            $this->error('公众号不存在');
        }
        $this->assign('wxid', $wxid);
//        echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];exit;
        $this->assign('domain', ($_SERVER['REQUEST_SCHEME']? $_SERVER['REQUEST_SCHEME']: 'http').'://'.$_SERVER['HTTP_HOST']);
//        print_r($_SERVER);echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].($_SERVER['SERVER_PORT']==80? '':$_SERVER['SERVER_PORT']);exit;
        $this->display('News/index');
    }
    
    /**
     * 发布预览
     * @param
     * @return string json
     */
    public function preview() {
        if(!IS_POST) {
            $this->ajaxReturn(array('status' => 0, 'message' => '操作错误'));
        }
        $wxid = I('request.wxid', 0, 'intval');
        $mediaId = I('request.mid', '', 'trim');
        $openid = I('request.openid', '', 'trim');
        if(!$wxid || !$mediaId || !$openid) {
            $this->ajaxReturn(array('status' => 0, 'message' => '操作错误'));
        }
        
        $conf = D("Common/WeixinConf")->where(array('id' => $wxid, 'sid' => session('siteid')))->find();
        if (!$conf) {
            $this->ajaxReturn(array('status' => 0, 'message' => '公众号错误'));
        }
        
        $o = new Material($conf['key'], $conf['secret'], $wxid);
        $rs = $o->preview($openid, $mediaId);
        $rs = json_decode($rs, 1);
        if(!isset($rs['errcode'])) {
            $this->ajaxReturn(array('status' => 0, 'message' => '发送预览失败'));
        }
        if(!$rs['errcode']) {
            $this->ajaxReturn(array('status' => 1, 'message' => '发送成功，请在手机上查看效果'));
        } else {
            $this->ajaxReturn(array('status' => 0, 'message' => '预览失败:['.$rs['errcode'].']'.$rs['errmsg']));
        }
    }
    
    /**
     * 推文群发
     * @param
     * @return string json
     */
    public function sendall() {
        if(!IS_POST) {
            $this->ajaxReturn(array('status' => 0, 'message' => '操作错误'));
        }
        $wxid    = I('request.wxid', 0, 'intval');
        $mediaId = I('request.mid', '', 'trim');
        $tagid   = I('request.tag', 0, 'intval');
        if(!$wxid || !$mediaId) {
            $this->ajaxReturn(array('status' => 0, 'message' => '操作错误'));
        }
        
        $conf = D("Common/WeixinConf")->where(array('id' => $wxid, 'sid' => session('siteid')))->find();
        if (!$conf) {
            $this->ajaxReturn(array('status' => 0, 'message' => '公众号错误'));
        }
        
        $o = new Material($conf['key'], $conf['secret'], $wxid);
        $rs = $o->sendall($tagid, $mediaId);
        $rs = json_decode($rs, 1);
        if(!isset($rs['errcode'])) {
            $this->ajaxReturn(array('status' => 0, 'message' => '群发失败'));
        }
        if(!$rs['errcode']) {
            $this->ajaxReturn(array('status' => 1, 'message' => '群发成功，请在手机上查看效果'));
        } else {
            $this->ajaxReturn(array('status' => 0, 'message' => '群发失败:['.$rs['errcode'].']'.$rs['errmsg']));
        }
    }

    /**
	 * 微信用户列表
	 * @param array $where 查询条件
	 */
	private function _lists($where=array()){
		$wxid = I('get.wxid',0,'intval');
        $type = I('get.t','','trim');
        $types = array('news'=>'推文', 'image'=>'图片'/*, 'video'=>'视频', 'voice'=>'声音'*/);
        $type  = $type && isset($types[$type])? $type: 'news';
        
		$wxconfs = $this->weixinConf->where(array('sid' => session('siteid')))->select();
        $defaultid = 0;
        $confs  = array();
        foreach($wxconfs as $row) {
            $confs[$row['id']] = $row;
            if(!$defaultid) { $defaultid = $row['id']; }
        }
        unset($wxconfs);
        if ($wxid && !isset($confs[$wxid])) {
            $this->redirect('Material/index');
        }
        $wxid = $wxid? $wxid: $defaultid;
        if($wxid && $confs[$wxid]['pass']>0) {
            $o = new Material($confs[$wxid]['key'], $confs[$wxid]['secret'], $wxid);
            $limit = 15;
            $p = I('get.p', 0, 'intval');
            $p = $p>0? $p -1: 0;
            $begin = $p * $limit;
            $data = $o->getMaterial($type, $begin, $limit);
            $page = $this->page($data['total_count'], 15);
            $page = $page->show('Admin');
            $data = $data['item'];
        } else {
            $data = array();
            $page = '';
        }
        
        $this->assign('confs', $confs);
        $this->assign('wxid', $wxid);
		$this->assign('page', $page);
		$this->assign('data', $data);
        $this->assign('types', $types);
        $this->assign('curtype', $type);
	}
    
    
    public function demo() {
//        echo SITE_PATH;exit('<br/>'.date('Y-m-d H:i:s'));
        $wxid = 2;
        $conf = $this->weixinConf->where(array('id' => $wxid))->find();
        
        $o = new Material($conf['key'], $conf['secret'], $wxid);
        // Create a CURLFile object
        $img = 'http://www.denza.com/tdrive/5/m/1/denza2_south/img/p1_bg.jpg';
        $img = 'https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=3491746499,1170520307&fm=23&gp=0.jpg';
        //$img = 'http://192.168.53.248/logo.png';
        $img = 'http://192.168.53.248:8888/public/weixin/images/mmbiz/iaXDmvibibwTLVbpc1omO9MfKzmXnFfWJ788HBIMNKDBYUXwPQRbgH2FF4bicye227XoORfFibg2HVbXhIia5ic6L4Ddw/0.gif';
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$o->getToken();
//        exit(TEMP_PATH);exit(date('Y-m-d H:i:s'));
        
        $aryf  = explode('.', basename($img));
        $ext  = $aryf[count($aryf)-1];
        $fpath = TEMP_PATH.time().'_'.  mt_rand(100000, 999999).'.'.$ext;
        $con = toRequest($img);
        $n = file_put_contents($fpath, $con);
        
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
        //unlink($fpath);
        print_r($rs);exit;
        return isset($rs['url'])? $rs['url']: '';
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
        return isset($rs['url'])? $rs['url']: FALSE;
    }
    
}
