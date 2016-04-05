<?php
/**
 * Description of AccountController
 *
 * @author Administrator
 */
namespace Admin\Controller;
use Admin\Model\UsermpsetModel;

class AccountController extends BaseController {
    
    public function index() {
        //$obj = new UsermpsetModel();
        $obj = D('Usermpset');
        if (!IS_POST){
            $this->assign('addNew', $obj->checkUserStatus());
            $this->display();
            return;
        }
        $id = intval(I('post.id'));
        if($obj->create()) {
            if($obj->addNew($id)) {
                $this->ajaxReturn(array('code' => 1, 'msg' => 'ok'));
            }
            else {
                $this->ajaxReturn(array('code' => 0, 'msg' => $obj->getError()));
            }
        }
        else {
            $this->ajaxReturn(array('code' => 0, 'msg' => $obj->getError()));
        }
    }
    
    public function ajaxAccountAdd() {
        if (!IS_POST){ $this->ajaxReturn(array('code'=>0, 'msg'=>'opration error')); }
        $obj = new UsermpsetModel();
        if($obj->create()) {
            if($obj->wxAdd()) {
                $this->ajaxReturn(array('code'=>1, 'msg'=> 'ok'));
            }
            else {
                $this->ajaxReturn(array('code'=>0, 'msg'=>$obj->getError()));
            }
        }
        else {
            $this->ajaxReturn(array('code' => 0, 'msg' => $obj->getError()));
        }
    }

    public function add() {
        $this->assign('pageJsInit', 'addWizard.init();');
        $this->assign('pageSet', 'wizards_validations');
        $this->display();
    }

    public function ajaxList() {
        $obj = new UsermpsetModel();
        $list = $obj->getList('uid='.  session(C('ADMIN_SESSION'))['uid'], 'id,name,appid,appsecret,remark,token,aeskey,encrypt,createtime');
        $this->ajaxReturn(array('data'=>$list));
    }
    
    public function ajaxChkAdd() {
        $obj = new UsermpsetModel();
        $add = $obj->checkUserStatus()? 1: 0;
        $this->ajaxReturn(array('add'=> $add));
    }
    
    public function ajaxWxDel() {
        $obj = new UsermpsetModel();
        if($obj->wxDelete(intval(I('post.id')))) {
            $this->ajaxReturn(array('code' => 1, 'msg' => 'ok'));
        }
        else {
            $this->ajaxReturn(array('code' => 0, 'msg' => $obj->getError()));
        }
    }
    
    public function ajaxServiceUrl() {
        $encrypt = UsermpsetModel::getUserAid(trim(I('post.appid')), trim(I('post.appsecret')));
        $url = $encrypt? $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].U('Api/Index/Index','aid='.$encrypt) : '';
        $this->ajaxReturn(array('url' => $url));
    }
    
    
    /*
     * 返回平台列表
     */
    private function _list() {
        $obj = new UsermpsetModel();
        $list = $obj->getList('uid='.  session(C('ADMIN_SESSION'))['uid']);
        $this->assign('list', $list);
    }
}
