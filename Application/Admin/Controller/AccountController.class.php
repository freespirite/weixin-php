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
            //$this->assign('pageSet', 'wizards_validations');
            //$this->_list();
            $this->assign('addNew', $obj->checkUserStatus());
            $this->display();
            return;
        }
//        $data['id']      = intval(I('post.id'));
//        $data['uid']       = session(C('ADMIN_SESSION'))['uid'];
//        $data['appid']     = trim(I('post.appid'));
//        $data['appsecret'] = trim(I('post.appsecret'));
//        $data['name']      = trim(I('post.name'));
//        $data['remark']    = trim(I('post.remark'));
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

    public function ajaxList() {
        $obj = new UsermpsetModel();
        $list = $obj->getList('uid='.  session(C('ADMIN_SESSION'))['uid'], 'id,name,appid,appsecret,remark,createtime');
        $add = $obj->checkUserStatus()? 1: 0;
        $this->ajaxReturn(array('add'=> $add, 'data'=>$list));
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
    /*
     * 返回平台列表
     */
    private function _list() {
        //$page = intval(I('get.p'));
        //$page = $page? $page: 1;
        //$size = in_array(I('get.size'), array(10, 15, 30))? $size: 10;
        $obj = new UsermpsetModel();
        $list = $obj->getList('uid='.  session(C('ADMIN_SESSION'))['uid']);
        $this->assign('list', $list);
        
        //$objPage = $this->getpage($obj->getCount(), $size);
        //$this->assign('page', $objPage->showAce('page/plist'));// 赋值分页输出
        //$this->display('plist');
    }
}
