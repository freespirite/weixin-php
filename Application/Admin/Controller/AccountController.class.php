<?php
/**
 * Description of AccountController
 *
 * @author Administrator
 */
namespace Admin\Controller;
use Admin\Model\Usermpset;

class AccountController extends BaseController {
    
    public function index() {
        if (!IS_POST){
            //$this->assign('pageSet', 'wizards_validations');
            //$this->_list();
            $this->display();
            return;
        }
        $data['uid'] = session(C('ADMIN_SESSION'))['uid'];
        $data['appid'] = trim(I('post.appid'));
        $data['appsecret'] = trim(I('post.appsecret'));
        $data['name'] = trim(I('post.name'));
        $data['remark'] = trim(I('post.remark'));
        $obj = new Usermpset();
        $obj->create($data);
        $rs = $obj->addNew($data);
        if($obj->addNew($data)) {
            $this->ajaxReturn(array('code' => $rs, 'msg' => 'ok'));
        }
        else {
            $this->ajaxReturn(array('code' => $rs, 'msg' => $obj->getError()));
        }
    }

    public function ajaxList() {
        $obj = new Usermpset();
        $list = $obj->getList('uid='.  session(C('ADMIN_SESSION'))['uid'], 'id,name,appid,appsecret,remark,createtime');
        $this->ajaxReturn($list);
    }
    
    /*
     * 返回平台列表
     */
    private function _list() {
        //$page = intval(I('get.p'));
        //$page = $page? $page: 1;
        //$size = in_array(I('get.size'), array(10, 15, 30))? $size: 10;
        $obj = new Usermpset();
        $list = $obj->getList('uid='.  session(C('ADMIN_SESSION'))['uid']);
        $this->assign('list', $list);
        
        //$objPage = $this->getpage($obj->getCount(), $size);
        //$this->assign('page', $objPage->showAce('page/plist'));// 赋值分页输出
        //$this->display('plist');
    }
}
