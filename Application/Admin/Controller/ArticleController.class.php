<?php
/**
 * Description of AccountController
 *
 * @author Administrator
 */
namespace Admin\Controller;
use Admin\Model\UsermpsetModel;

class ArticleController extends BaseController {
    
    public function index() {
        $this->display();
    }

    public function ajaxList() {
        $obj = new UsermpsetModel();
        $list = $obj->getList('uid='.  session(C('ADMIN_SESSION'))['uid'], 'id,name,appid,appsecret,remark,createtime');
        $add = $obj->checkUserStatus()? 1: 0;
        $this->ajaxReturn(array('add'=> $add, 'data'=>$list));
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
