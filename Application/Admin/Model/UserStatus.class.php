<?php
namespace Admin\Model;
use Think\Model;
/**
 * Description of UserStatus
 *
 * @author nick
 */
class UserStatus extends Model {
    
    protected $tableName = 'login_status';
    private $_keepLogin  = 3600 * 10;


    /*
     * 平台登录
     * @param $account string 登录账号
     * @param $pwd     string 密码
     * return array
     */
    public function getAccount($auth) {
        $row = $this->where('`auth`="%s"', array($auth))->find();
        if(!$row) { return FALSE; }
        return $row['timeOut'] > time()? $row['account']: '';
    }
    
    
    /*
     * 更新平台管理员登陆密码
     * @param $pwd string 密码
     * @param $account string 登录账号
     * return boolean
     */
    public function updateStatus($account)
    {
        $data = array(
            'auth' => $this->_createAuth($account),
            'timeOut' => $_SERVER['REQUEST_TIME'] + $this->_keepLogin,
        );
        $n = $this->where('account="%s"', array(session(C('ADMIN_SESSION'))))->save($data);
        if(!$n) {
            $data['account'] = $account;
            $n = $this->add($data);
        }
        if($n) {
            cookie('admAuth', $data['auth']);
        }
        return $n;
    }
    
    private function _createAuth($account) {
        return md5($account . C('APP_KEY').$_SERVER['REQUEST_TIME']);
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
