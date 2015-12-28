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
    
    /*
     * 平台登录
     * @param $account string 登录账号
     * @param $pwd     string 密码
     * return array
     */
    public function login($account, $pwd) {
        $row = $this->where('`account`="%s"', array($account))->find();
        if(!$row) { return FALSE; }
        return $this->createPwd($pwd) === $row['pwd']? $row['account']: 0;
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
    private function createPwd($pwd) {
        return md5(C('APP_KEY').$pwd);
    }
}
