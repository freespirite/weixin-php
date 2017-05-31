<?php
/**
 * User(用户管理)
 */
namespace Weixin\Controller;

class UserController extends BaseController {

    
    protected $weixin_msg;
    //protected $auth_rule_model;

    public function _initialize() {
        parent::_initialize();
        
        $this->weixinUser = D("Common/WeixinUsers");
        
        //$this->auth_rule_model = D("Common/AuthRule");
    }

    /**
     * 微信用户列表
     */
    public function index() {
        //$this->_importUser();
        $this->_lists();
        $this->display();
    }
    
    public function getList() {
        $wxid = I('request.wxid',0,'intval');
        if(!$wxid) {
            $this->ajaxReturn(array('status' => 0, 'message' => '操作错误'));
        }
        $conf = $this->weixinConf->where(array('id'=> $wxid,'sid' => session('siteid')))->find();
        if(!$conf) {
            $this->ajaxReturn(array('status' => 0, 'message' => '公众号错误'));
        }
        
        $where['wxid'] = $wxid;
        $count = $this->weixinUser->where($where)->count();
        $page = $this->page($count, 18);
        $users = $this->weixinUser
                ->where($where)
                ->field('nickname,openid')
                ->limit($page->firstRow, $page->listRows)
                ->order("id DESC")
                ->select();
//        print_r($users);exit;
        $this->ajaxReturn(array('status' => 1, 'message' => '', 'data'=>$users));
        
    }
    
    public function getTags() {
        $wxid = I('request.wxid',0,'intval');
        if(!$wxid){
		    $this->ajaxReturn(array('status' => 0, 'message' => '操作错误'));
		}
        $conf = $this->weixinConf->where(array('id'=>$wxid,'sid' => session('siteid')))->find();
        if(!$conf) {
            $this->ajaxReturn(array('status' => 0, 'message' => '操作错误'));
        }
        $user  = new \Common\Lib\Weixin\User($conf['key'], $conf['secret'], $wxid);
        $ary = $user->getTags();
//        print_r($ary);
        if(!$ary) {
            $data = array(
                'status'=> 0,
                'message'=> $user->getErrorInfo(),
            );
        } else {
            $data = array(
                'status'=> 1,
                'message'=> 'ok',
                'data'=> $ary['tags']
            );
        }
        $this->ajaxReturn($data);
    }

    
    /**
	 * 微信用户列表
	 * @param array $where 查询条件
	 */
	private function _lists($where=array()){
		$wxid = I('request.wxid',0,'intval');
		
		if(!empty($wxid)){
		    $where['wxid'] = $wxid;
		}
		
		$startTime = I('request.startTime');
		if(!empty($startTime)){
		    $where['create_time'] = array(
		        array('EGT',$startTime)
		    );
		}
		
		$endTime = I('request.endTime');
		if(!empty($endTime)){
		    if(empty($where['create_time'])){
		        $where['create_time'] = array();
		    }
		    array_push($where['create_time'], array('ELT',$endTime));
		}
		
		$keyword = I('request.keyword');
		if(!empty($keyword)){
		    $where['content'] = array('like',"%$keyword%");
		}
        
		$wxconfs = $this->weixinConf->where(array('sid' => session('siteid')))->field('id,name,pass')->select();
        $defaultid = 0;
        $confs  = array();
        foreach($wxconfs as $row) {
            $confs[$row['id']] = $row;
            if(!$defaultid) { $defaultid = $row['id']; }
        }
        unset($wxconfs);
        if ($wxid && !isset($confs[$wxid])) {
            $this->redirect('User/index');
        }
        $wxid = $wxid? $wxid: $defaultid;
        
        if($wxid) {
            $count  = $this->weixinUser->where($where)->count();
            $page = $this->page($count, 15);
            $posts  = $this->weixinUser
            ->where($where)
            ->limit($page->firstRow , $page->listRows)
            ->order("id DESC")
            ->select();
        } else {
            $count = 0;
            $posts = array();
            $page = $this->page($count, 15);
        }
        
//        $page   = $this->page($count, 15);
        $this->assign('confs', $confs);
        $this->assign('wxid', $wxid);
		$this->assign('page', $page->show('Admin'));
//		$this->assign("formget",array_merge($_GET,$_POST));
		$this->assign('posts', $posts);
	}
    
    /*
     * 导入微信公众号用户信息
     */
    private function _importUser() {
        
        $user  = new \Common\Lib\Weixin\User('wx2e0b5c6f75eb78ef', '041b67aa62d32a01dff8202cd9adf753', 2);
        $rs = $user->importUsers();
        return $rs? TRUE: FALSE;
        
    }
}
