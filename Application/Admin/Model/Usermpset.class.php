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
                                array('appid','require','应用ID不能为空'),
                                array('appsecret',32,'应用秘钥为32为字符串',1,'length'),
                            );
    
    protected $_auto = array ( 
         array('createtime', 'time', 1, 'function') , 
         array('updatetime', 'time', 3, 'function'), 
     );
}
