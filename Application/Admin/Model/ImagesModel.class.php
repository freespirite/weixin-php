<?php
namespace Admin\Model;
use Think\Model;
/**
 * Description of ImagesModel
 *
 * @author Administrator
 */
class ImagesModel extends Model {
    
    protected $tableName = 'images';
    
    protected $_validate = array (
        array('uid', 'number', '用户ID错误', self::MODEL_INSERT),
        array('wid', 'number', '公众号ID错误', self::MODEL_INSERT),
        array('mediaid', 'require', '素材文件ID不能为空'),
        array('url', 'require', '素材文件地址不能为空', self::MODEL_BOTH,'length'),
    );
    
    
}
