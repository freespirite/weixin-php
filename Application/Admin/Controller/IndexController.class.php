<?php
/**
 * Class IndexController
 * @package Platform\Controller
 * 平台管理控制器
 * @author Nick tan
 */
namespace Admin\Controller;
use Admin\Model\UsersModel;
use Common\Library\UpFile;

class IndexController extends BaseController {
    
    
    public function index() {
        //$this->checkLogin();
        //$this->display('error_404');
        //echo date('Y-m-d H:i:s');exit;
        //$this->assign('data', array('a'=>'111', 'b'=> '2222'));
        $this->display();
    }
    
    /*
     * administrator login
     */
    public function login() {
        if (IS_POST){
            if(!$this->_checkVerify(I('post.code'))) {
                echo json_encode(array('code' => 3, 'msg'=> '验证码错误！'));
                return;
            }
            $obj = new UsersModel();
            $account = trim(I('post.account'));
            $pwd = trim(I('post.pwd'));
            if(!$account || !$pwd) { echo json_encode(array('code' => 2, 'msg' => '账号或密码不能为空')); return; }
            if(!$obj->login($account, $pwd)) {
                echo json_encode(array('code' => 2, 'msg' => '账号或密码错误'));
            }
            else {
                session(C('ADMIN_SESSION'), $account);
//                if(intval(I('post.nologin')) && intval(C('NO_LOGIN'))) {
//                    cookie(C('ADMIN_SESSION'), $account, array('expire'=>C('NO_LOGIN')*24*3600,'prefix'=>'wxmp_'));
//                }
                echo json_encode(array('code' => 1, 'msg' => 'ok'));
            }
        }
        else {
            if ($this->checkLogin()) {
                $this->index();
                return;
            }
            layout(FALSE);
            $this->display('login');
        }
    }
    
    public function register() {
        if (!IS_POST){
            layout(FALSE);
            $this->display('reg');
            return;
        }
        $obj = new UsersModel();
        if($obj->create()) {
            $uid = $obj->add();
            if(is_numeric($uid)) {
                session(C('ADMIN_SESSION'), I('post.account'));
                $this->ajaxReturn(array('code' => 1, 'msg' => 'ok'));
            }
            else {
                $this->ajaxReturn(array('code' => 2, 'msg' => $obj->getError()));
            }
        }
        $this->ajaxReturn(array('code' => 2, 'msg' => $obj->getError()));
    }


    /*
     * 平台管理员登录密码修改
     */
    public function editadmin() {
        if (!IS_POST){
            $this->display('error');
            return;
        }
        $obj = new AdminModel();
        $obj->updatePwd(trim(I('post.pwd')), session(C('ADMIN_SESSION')));
        echo json_encode($this->successMsg());
    }

    /*
     * 删除案例
     */
    public function del() {
        if (!IS_POST){
            echo json_encode($this->errorReturn('操作错误'));
            exit;
        }
        $id = intval(I('post.id'));
        $key = trim(I('post.key'));
        if(!$id || !$key) { echo json_encode($this->errorReturn('操作错误'));exit; }
        $model = new CasusModel();
        $rs = $model->delete($id);
        $obj = new UpFile('linebar');
        $obj->deleteFile($key);
        echo $rs? json_encode($this->successMsg()): json_encode($this->errorReturn('删除失败'));
    }

    /*
     * 平台信息修改，p2p管理员密码修改
     */
    public function edit() {
        if (!IS_POST){
            $id  = intval(I('get.id'));
            if(!$id) {
                $this->display('error');
                return;
            }
            $obj  = new CasusModel();
            $data = $obj->getOne($id);
            $this->assign('data', $data);
            $this->display('edit');
            return;
        }
//        print_r($_POST);EXIT;
        $id = intval(I('post.id'));
        if(!$id) { $this->_alert('操作错误！'); }
        $data = array(
            'title'   => trim(I('post.title')),
            'ord'     => intval(I('post.ord')),
            'link'   => trim(I('post.link')),
            'disable' => intval(I('post.disable'))? 1: 0,
            'remark'  => trim(I('post.remark')),
        );
        if(!$data['title']) { $this->_alert('案例名称不能为空'); }
        if(!$data['link']) { $this->_alert('案例连接不能为空'); }
        $model = new CasusModel();
        $res = $this->_upload();
        $aryRes = json_decode($res, 1);
        if(!$aryRes['error']) {
            $data['pic'] = trim($aryRes['url']); 
            $row = $model->getOne($id);
            $obj = new UpFile('linebar');
            $obj->deleteFile($row['pic']);
        }
        $model->update($data, $id);
        $this->_alert('修改成功！');
        //$this->_alert('继续修改？', TRUE);
    }
    
    /*
     * 返回平台列表
     */
    public function plist() {
        $page = intval(I('get.p'));
        $page = $page? $page: 1;
        $size = in_array(I('get.size'), array(10, 15, 30))? $size: 10;
        $obj = new CasusModel();
        $list = $obj->getList($page, $size);
        $this->assign('plist', $list);
        
        $objPage = $this->getpage($obj->getCount(), $size);
        $this->assign('page', $objPage->showAce('page/plist'));// 赋值分页输出
        $this->display('plist');
    }
    
    /*
     * 显示接口调用异常列表
     */
    public function payerror() {
        $page = intval(I('get.p'));
        $page = $page? $page: 1;
        $size = in_array(I('get.size'), array(10, 15, 30))? $size: 10;
        $obj = new \Common\Model\BackgroundModel();
        $list = $obj->getList($page, $size);
        $this->assign('list', $list);
        
        $objPage = $this->getpage($obj->getCount(), $size);
        $objPay  = new PayPlatform();
        $this->assign('objPay', $objPay);
        $this->assign('page', $objPage->showAce('page/payerror'));// 赋值分页输出
        $this->display('payerror');
    }
    
    private function _upload() {
        $obj = new UpFile('linebar');
        return $obj->uploadFile('pic', 'anli/'.date('Y').'/');
    }
    /*
     * 平台新增
     */
    public function add() {
        if (!IS_POST){
            $this->display('add');
            return;
        }
        $res = $this->_upload();
        $aryRes = json_decode($res, 1);
        //print_r($aryRes);exit;
        if($aryRes['error']) { $this->_alert($aryRes['message']); }
        $data = array(
            'title'   => trim(I('post.title')),
            'pic'     => trim($aryRes['url']),
            'ord'     => intval(I('post.ord')),
            'link'   => trim(I('post.link')),
            'disable' => intval(I('post.disable'))? 1: 0,
            'remark'  => trim(I('post.remark')),
            'createtime' => date('Y-m-d H:i:s'),
        );
        if(!$data['title']) { $this->_alert('案例名称不能为空'); }
        if(!$data['link']) { $this->_alert('案例连接不能为空'); }
        $obj = new CasusModel();
        $obj->addNew($data)? $this->_alert('继续添加案例？', TRUE): $this->_alert('案例添加失败');
    }
    
    private function _alert($msg, $ok=FALSE) {
        $script[] = '<script>';
        $script[] = $ok? 'if(confirm("'.$msg.'")){top.readd();}else{top.showlist();}': 'alert("'.$msg.'");';
        $script[] = '</script>';
        echo join($script, "\n");exit;
    }
    
    /*
     * 检查平台管理账号、邮箱、电话等是否有重复
     */
    public function checkinfo() {
        echo $this->checkInfoExists()? 'false': 'true';
    }

    /*
     * 登录退出
     */
    public function logout() {
        session(C('ADMIN_SESSION'), NULL);
        //echo '>>>>>>>>>'.ACTION_NAME.'>'.__CLASS__.'>'.__LINE__;//exit();
        redirect(U('admin/Index/login'));
    }
    
    /*
     * 验证码生成
     */
    public function imgcode() {
        $conf = array(
            'fontSize' => 45,
            'length' => 4
        );
        $verify = new \Think\Verify($conf);
        //$verify->useImgBg = true;
        $verify->entry();
    }
    
    /*
     * 检查验证码
     * @param $code string 验证码
     * @param $id   string 暂不用
     * return boolean
     */
    private function _checkVerify($code, $id = '') {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
}