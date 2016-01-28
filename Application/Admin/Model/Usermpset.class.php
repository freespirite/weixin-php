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
         array('createtime', 'time', 1, 'function') , 
         array('updatetime', 'time', 3, 'function'), 
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
}
