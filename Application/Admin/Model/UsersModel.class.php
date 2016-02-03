<?php
/**
 * Description of AdminModel
 *
 * @author Administrator
 */

namespace Admin\Model;
use Think\Model;

class UsersModel extends Model {
    
    protected $tableName = 'users';
    
    protected $_validate = array (
                                array('account','email','邮箱账号格式错误'),
                                array('account','','帐号名称已经存在！',0,'unique',1),
                                array('pwd','require','密码为必填项'),
                                array('repwd','pwd','2次密码不一致',0,'confirm'),
                            );
    
    protected $_auto = array ( 
         array('pwd', 'createPwd', 3, 'callback'), 
         array('createtime', 'time', 1, 'function') , 
         array('lastlogintime', 'time', 3, 'function'), 
         array('lastloginip','getip',3,'callback'),
     );
    
     public function getip() {
         return get_client_ip(1);
     }
    /*
     * 平台登录
     * @param $account string 登录账号
     * @param $pwd     string 密码
     * return array
     */
    public function login($account, $pwd) {
        $row = $this->where('`account`="%s"', array($account))->field('uid,account,pwd')->find();
        if(!$row) { return FALSE; }
        return $this->createPwd($pwd) === $row['pwd']? $row: 0;
    }
    
    
    /*
     * 更新平台管理员登陆密码
     * @param $pwd string 密码
     * @param $account string 登录账号
     * return boolean
     */
    public function updatePwd($pwd, $account)
    {
        return $this->where('account="%s"', array($account))->save(array('pwd' => $this->createPwd($pwd)));
    }
    
    /*
     * 返回平台密码
     * @param $pwd string 登录密码
     * return string
     */
    public function createPwd($pwd) {
        return md5(C('APP_KEY').$pwd);
    }
    
    
}
