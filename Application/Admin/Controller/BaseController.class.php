<?php
/**
 * Description of PlatformController
 * 
 * @author Nick tan
 */

namespace Admin\Controller;
use Think\Controller;
//use Home\Model\UserModel;
use Admin\Model\UserStatus;
use Common\Library\Curl;

class BaseController extends Controller {

    public function _initialize(){
        if(!in_array(ACTION_NAME, array('login', 'logout', 'imgcode', 'register')) && !$this->checkLogin()) {
            //echo '>>>>>>>>>'.ACTION_NAME.'>'.__CLASS__.'>'.__LINE__;exit();
           redirect(U('/admin/Index/login'));
        }
    }
    
    /*
     * 检查登录状态
     * return boolean
     */
    public function checkLogin()
    {
        $wxadmin = cookie(C('ADMIN_SESSION'));
        if($wxadmin) {
            $obj = new UserStatus;
            $account = $obj->getAccount($wxadmin);
            if($account) {
                session(C('ADMIN_SESSION'), $account);
                return TRUE;
            }
        }
        return session(C('ADMIN_SESSION'))? TRUE: FALSE;
    }
    
    
    

    /*
    * 返回成功数据信息
     * @param $data array  需要返回的数据数组
     * @param $msg  string 成功提示信息
     * return array
    */
    public function successMsg($data=array(), $msg='ok') {
       return array('code' => 1, 'msg' => $msg, 'data' => $data);
    }
   
    /*
     * 返回错误信息数组
     * @param $error string 错误信息
     * return array
     */
    public function errorReturn($error='操作失败') {
       return array('code' => 0, 'msg' => $error);
    }
    
    
    /**
    * TODO 基础分页的相同代码封装，使前台的代码更少
    * @param $count 要分页的总记录数
    * @param int $pagesize 每页查询条数
    * @return \Think\Page
    */
    function getpage($count, $pagesize = 10) {
       $p = new \Think\Page($count, $pagesize);
       $p->setConfig('header', '&nbsp;<li><span style="border-width:0 0 0 1px;">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</span></li>');
       $p->setConfig('prev', '上一页');
       $p->setConfig('next', '下一页');
       $p->setConfig('last', '末页');
       $p->setConfig('first', '首页');
       $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
       $p->setConfig('link','#page/payerror&page=%NOW_PAGE%');
       $p->lastSuffix = false;//最后一页不显示为总页数
       return $p;
    }
   
}


