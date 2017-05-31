<?php

/* * 
 * 菜单
 */
namespace Common\Model;
use Common\Model\CommonModel;
class WeixinReplysetModel extends CommonModel {
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('wxid', 'numgt', '对应的公众号ID不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
    );
    
    
}