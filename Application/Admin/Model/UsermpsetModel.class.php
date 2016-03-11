<?php
namespace Admin\Model;
use Think\Model;
/**
 * Description of Usermpset
 *
 * @author Administrator
 */
class UsermpsetModel extends Model {
//    protected $insertFields = 'name,email'; // 新增数据的时候允许写入name和email字段
//    protected $updateFields = 'email'; // 编辑数据的时候只允许写入email字段
    
    protected $tableName = 'mp_set';
    
    protected $_validate = array (
        array('id','number','修改的公众号ID不正确',self::MODEL_UPDATE),
        array('name','require','公众号名称不能为空'),
        array('appid','require','应用ID不能为空'),
        array('appsecret',32,'应用秘钥为32为字符串',self::MODEL_BOTH,'length'),
        array('remark','0,100','描述字数在100字内',self::MODEL_BOTH,'length'),
        
        array('token','3,32','token为3-32位长度字符串',self::MODEL_BOTH,'length'),
        array('aeskey',43,'EncodingAESKey为43位长度字符串',self::MODEL_BOTH,'length'),
        array('encrypt','1,2,3','消息加密方式错误',self::MODEL_BOTH,'in'),
        array('wxaid',32,'微信公众号唯一标示符生成失败，请联系管理员',self::MODEL_BOTH,'length'),

    );
    
    protected $_auto = array ( 
         array('createtime', 'time', self::MODEL_INSERT, 'function') , 
         array('updatetime', 'time', self::MODEL_BOTH, 'function'), 
     );
    
    protected $updateFields = 'name,appid,appsecret,remark,token,aeskey,encrypt,wxaid,updatetime';
    
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
    public function addNew($id) {
        if($id) {
            $rs = $this->where('id='.$id.' AND uid='.session(C('ADMIN_SESSION'))['uid'])->save();
//            echo $this->getLastSql();
            if($rs===FALSE) {
                $this->error = '更新失败';
                return FALSE;
            }
            return TRUE;
        }
        $row = $this->_checkAppid($this->data['appid']);
        if($row) {
            $this->error = '该公众号APPID已经存在';
            return FALSE;
        }
        if(!$this->checkUserStatus()) { 
            $this->error = '超出绑定账号上限';
            return FALSE;
        }
        $this->data['uid'] = session(C('ADMIN_SESSION'))['uid'];
        $rs = $this->add();
        $this->error = $rs? '': '添加公众号失败';
        return $rs;
    }
    
    public function wxUpdate() {
        $this->error = '更新公众号失败！';
        $row = $this->where('uid='.session(C('ADMIN_SESSION'))['uid'].' AND appid="%s" AND id<>'.$this->data['id'], array($this->data['appid']))->find();
        if($row) {
            $this->error = '该公众号APPID已经存在';
            return FALSE;
        }
        $rs = $this->where('id='.$id.' AND uid='. session(C('ADMIN_SESSION'))['uid'])->save();
        if($rs===FALSE) {
            $this->error = '更新失败';
            return FALSE;
        }
        return TRUE;
    }

    public function wxAdd() {
        $row = $this->_checkAppid($this->data['appid']);
        if($row) {
            $this->error = '该公众号APPID已经存在';
            return FALSE;
        }
        if(!$this->checkUserStatus()) { 
            $this->error = '超出绑定账号上限';
            return FALSE;
        }
        $this->data['uid'] = session(C('ADMIN_SESSION'))['uid'];
        $this->data['wxaid'] = $this->getUserAid($this->data['appid'], $this->data['appsecret']);
        $rs = $this->add();
        $this->error = $rs? '': '添加公众号失败';
        return $rs;
    }

    public function wxDelete($id) {
        $rs = $this->where('id='.$id.' AND uid='.session(C('ADMIN_SESSION'))['uid'])->delete();
        //echo $this->getLastSql();exit;
        if(!$rs) {
            $this->error = '删除失败';
            return FALSE;
        }
        return TRUE;
    }
    
    public function getUserAid($appid, $appsecret) {
        if(!$appid || !$appsecret) { return ''; }
        return md5(session(C('ADMIN_SESSION'))['uid'].$appid.$appsecret.C('APP_KEY').time());
    }

    private function _checkAppid($appid) {
        //return $this->where('appid="%s" AND uid=%d', array($appid, session(C('ADMIN_SESSION'))['uid']))->find();
        return $this->where('uid='.session(C('ADMIN_SESSION'))['uid'].' AND appid="%s"', array($appid))->find();
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
