<?php
namespace Admin\Model;
use Think\Model;
/**
 * Description of Usermpset
 *
 * @author Administrator
 */
class UsermpsetModel extends Model {
    protected $tableName = 'mp_set';
    
    protected $_validate = array (
                                array('name','require','公众号名称不能为空'),
                                array('appid','require','应用ID不能为空'),
                                array('appsecret',32,'应用秘钥为32为字符串',1,'length'),
                            );
    
    protected $_auto = array ( 
         array('createtime', 'time', self::MODEL_INSERT, 'function') , 
         array('updatetime', 'time', self::MODEL_BOTH, 'function'), 
     );
    
    /*
     * 返回平台列表
     * @param $page int 当前分页数
     * @param $size int 每页显示数
     * return array
     */
    public function getList($where, $fields='*') {
        return $this->field($fields)
                ->where($where)
                ->order('id desc')
                ->select();
        //return $this->page($page,$size)->order('pid desc')->select();
    }
    
    /*
     * 新增或修改公众号
     * return boolean
     */
    public function addNew($data) {
        if(!$data['appid'] && !$data['appsecret']) {
            $this->error = '应用ID或应用秘钥不能为空';
            return false;
        }
        $row = $this->_checkAppid($data['appid']);
        //$data['updatetime'] = time();
        if($row) {
            $rs = $this->where('appid="%s" AND uid=%d',array($row['appid'], session(C('ADMIN_SESSION'))['uid']))
                ->save($data);
            //echo $this->getLastSql();
            if($rs===FALSE) {
                $this->error = '更新失败';
                return FALSE;
            }
            return TRUE;
        }
        //$data['createtime'] = $data['updatetime'];
        if(!$this->checkUserStatus()) { 
            $this->error = '超出绑定账号上限';
            return FALSE;
        }
        $id = $this->add($data);
        if($id) { return TRUE; }
        $this->error = '更新失败';
        return FALSE;
    }
    
    private function _checkAppid($appid) {
        return $this->where('appid="%s"', array($appid))->find();
    }
    
    /*
     * 检查可以绑定的微信号数量是否超出限制
     */
    public function checkUserStatus() {
        
        $exists = $this->where('uid='.session(C('ADMIN_SESSION'))['uid'])->count();
        $status = M('Users')->where('uid='.session(C('ADMIN_SESSION'))['uid'])->getField('status');
        return $exists < C('USER_WX_LIMIT')[$status];
    }
}
