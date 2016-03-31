<?php
namespace Admin\Model;
use Think\Model;
/**
 * Description of CacheTokenModel
 *
 * @author Administrator
 */
class CacheTokenModel extends Model {
    
    protected $tableName = 'cache_token';
    
    protected $_validate = array (
        array('wid','number','操作ID错误', self::MODEL_INSERT),
        array('uid','number','用户ID错误', self::MODEL_INSERT),
        array('token','require','token值不能为空', self::MODEL_BOTH),
        array('timeOut','number','时间不能为空',self::MODEL_BOTH),

    );
    
    protected $updateFields = 'token,timeOut';
    
    public function getList($where, $fields='*') {
        return $this->field($fields)
                ->where($where)
                ->order('id desc')
                ->select();
        //return $this->page($page,$size)->order('pid desc')->select();
    }
    
    public function getOne($wid) {
        return $this->where('wid=%d AND uid='.session(C('ADMIN_SESSION'))['uid'], array($wid))->find();
    }

    public function addNew($wid, $token, $time) {
        $data = array(
            'wid' => $wid,
            'uid' => session(C('ADMIN_SESSION'))['uid'],
            'token' => $token,
            'timeOut' => time() + $time,
        );
        return $this->add($data);
    }
    
    public function update($wid, $token, $time) {
        $data = array(
            'token' => $token,
            'timeOut' => time() + $time,
        );
        return $this->where('wid=%d AND uid='.session(C('ADMIN_SESSION'))['uid'], array($wid))->save($data);
    }
}
