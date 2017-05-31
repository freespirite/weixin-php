<?php

/* * 
 * 菜单
 */
namespace Common\Model;
use Common\Model\CommonModel;
class WeixinConfModel extends CommonModel {

    //自动验证
    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('name', 'require', '菜单名称不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('account', 'require', '微信号不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('level', 'require', '模块名称不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('key', 'require', 'AppId不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('secret', '32', 'AppSecret不正确！', 0, 'length', CommonModel:: MODEL_BOTH ),
        array('token', '/^[A-Za-z0-9]{3,32}$/', 'Token不正确！', 1, 'regex', CommonModel:: MODEL_BOTH ),
        array('key', 'checkAppId', '公众号记录已经存在！', 1, 'callback', CommonModel:: MODEL_INSERT ),
        array('sid', 'checkMax', '目前只能管理一个公众号！', 1, 'callback', CommonModel:: MODEL_INSERT ),
        
    );
    //自动完成
    protected $_auto = array(
	    array('addtime','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
        //array('uid','mGetUid',CommonModel:: MODEL_INSERT,'callback'),
        array('sid','mGetSid',CommonModel:: MODEL_INSERT,'callback'),
        array('remark','mGetSubStr',CommonModel:: MODEL_BOTH,'callback'),
        array('pass', 'checkPass', CommonModel:: MODEL_BOTH,'callback'),
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
    
    public function mGetSubStr($str) {
        return msubstr($str, 0, 100, 'utf-8', false);
    }

    //验证菜单是否超出三级
    public function checkMax() {return true;
        $count = $this->where(array('sid' => session('siteid')))->count();
//        $count = $this->count();
        if ($count >= C('WEIXIN.LIMIT')) {
            return false;
        }
        return true;
    }
    
    public function checkPass() {
        $obj   = new \Common\Lib\Weixin\Base(I('post.key','','trim'), I('post.secret','','trim'), 'tmp', session('siteid'));
        $token = $obj->getToken(FALSE, TRUE);
        if(!$token) { return -1; }
        return $obj->checkAuthorized(FALSE)? 1: 0;
    }
    

    //验证action是否重复添加
    public function checkAppId($key) {
        //检查是否重复添加
//        $find = $this->where(array('uid' => session('ADMIN_ID'), 'key'=> $key))->find();
        $find = $this->where(array('sid' => session('siteid'), 'key'=> $key))->find();
        if ($find) {
            return false;
        }
        return true;
    }

}