<?php
namespace Admin\Model;
use Think\Model;
/**
 * Description of Usermpset
 *
 * @author Administrator
 */
class Usermpset extends Model {
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
    public function getList($where, $page =1, $size=10) {
        return $this->field('*')
                ->where($where)
                ->page($page,$size)->order('id desc')
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
            return 0;
        }
        $row = $this->_checkAppid($data['appid']);
        if($row) {
            $rs = $this->where('appid="%s" AND uid=%d',array($row['appid'], session(C('ADMIN_SESSION'))['uid']))->save($data);
            //echo $this->getLastSql();
            return $rs!==FALSE? 2: 0;
        }
        $id = $this->add($data);
        if($id) { return 1; }
        return 0;
    }
    
    private function _checkAppid($appid) {
        return $this->where('appid="%s"', array($appid))->find();
    }
}
