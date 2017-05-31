<?php

/* * 
 * 菜单
 */
namespace Common\Model;
use Common\Model\CommonModel;
class WeixinMsgModel extends CommonModel {
    
    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('wxid', 'require', '微信ID不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('to_user_name', 'require', '开发者微信号不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('from_user_name', 'require', '发送方openid不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('create_time', 'require', '发送时间不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('msg_type', '1,7', '消息类型值不正确！', 1, 'between', CommonModel:: MODEL_BOTH ),
        array('content', 'require', '消息内容不正确！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('msg_id', 'require', '消息的ID不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT   ),
        array('sid', 'require', '网站ID不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT   ),
    );
    //自动完成
    protected $_auto = array(
//	    array('addtime','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
//        array('uid','mGetUid',CommonModel:: MODEL_INSERT,'callback'),
//        array('sid','mGetSid',CommonModel:: MODEL_INSERT,'callback'),
//        array('msg_type','mGetSubStr',CommonModel:: MODEL_BOTH,'callback'),
	);
    
    //用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	public function mGetDate() {
		return date('Y-m-d H:i:s');
	}
    
    public function mGetUid() {
		return session('ADMIN_ID');
	}
    
    public function mGetSid() {
		return session('siteid');
	}
    
    public function addNew($xml, $sid, $wxid) {
        $aryType = C('WEIXIN.MSG_TYPE');
        $data = array(
            'to_user_name' => $xml['ToUserName'],
            'from_user_name' => $xml['FromUserName'],
            'create_time' => $xml['CreateTime'],
            'msg_type' => $aryType[$xml['MsgType']],
            'msg_id' => $xml['MsgId'],
            'sid' => $sid,
            'wxid' => $wxid
        );
        unset($xml['ToUserName'], $xml['FromUserName'], $xml['CreateTime'], $xml['MsgType'], $xml['MsgId']);
        $data['content'] = json_encode($xml);
        $this->add($data);
    }
            
}