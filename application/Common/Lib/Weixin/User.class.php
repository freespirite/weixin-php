<?php
namespace Common\Lib\Weixin;

class User extends Base {
    
    private $_token  = '';
    private $_model  = null;
            
    function __construct($appid, $secret, $wxid, $siteid=0) {
		parent::__construct($appid, $secret, $wxid, $siteid);
        $this->_model = D('Common/WeixinUsers');
	}
    
    public function importUsers($nextid='') {
        $count = $this->_model->where(array('wxid'=>  $this->getWxid()))->count();
        $isUpdate = $count? TRUE: FALSE;
        $token = $this->getToken();
        $url   = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$token.'&next_openid='.$nextid;
        $con   = toRequest($url);
        $users = json_decode($con, 1);
        if(isset($users['errmsg'])) {
            $this->errorInfo = $users['errmsg'];
            return FALSE;
        }
        
        if(!$users['count']) { return true; }
        $addTime = time();
        foreach($users['data']['openid'] as $openid) {
            $user = $this->getUserInfo($token, $openid);
            $user['openid'] = $openid;
            $user['addtime'] = $user['updatetime'] = $addTime;
            $isUpdate? $this->updateUser($user): $this->saveUser($user);
        }
        if($users['count'] >= 10000) { $this->importUsers($users['next_openid']); }
        return TRUE;
    }
    
    
    public function getUserInfo($token, $openid) {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $token . '&openid=' . $openid . '&lang=zh_CN';
        $con = toRequest($url);
        $ary = json_decode($con, 1);
        if (isset($ary['errmsg'])) {
            if(in_array($ary['errcode'], array(40001, 40014))) {
                $token = $this->getToken();
                return $this->getUserInfo($token, $openid);
            } else {
                $this->errorInfo = 'weixin api error: '.$ary['errcode'].': '.$ary['errmsg'];
                return FALSE;
            }
        } else if(!$ary) {
            $this->errorInfo = 'call api return error: '.$con;
            return FALSE;
        }
        $ary['nickname'] = preg_replace('|[^a-zA-Z0-9\x{4e00}-\x{9fa5}]|u', '', $ary['nickname']);
        if(!$ary['nickname']) {
            $ary['nickname'] = 'user'.mt_rand(1000, 9999);
        }
        return $ary;
    }
    
    public function getTags() {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->getToken();
        $con = toRequest($url);
        $ary = json_decode($con, 1);
        if (isset($ary['errmsg'])) {
            $this->errorInfo = 'weixin api error: '.$ary['errcode'].': '.$ary['errmsg'];
            return FALSE;
        } else if(!$ary) {
            $this->errorInfo = 'call api return error: '.$con;
            return FALSE;
        }
        return $ary;
    }


    private function saveUser($user) {
        $user['wxid'] = $this->getWxid();
        try {
            $this->_model->add($user);
        } catch (\Exception $ex) {
            //echo $ex->getMessage() . '<br/>';
        }
        
    }
    
    private function updateUser($user) {
        //exit($this->_model->tablePrefix);
        unset($user['addtime']);
        try {
            $this->_model->where(array('wxid'=>$this->getWxid(), 'openid'=>$user['openid']))->save($user);
        } catch (\Exception $ex) {
            //echo $ex->getMessage() . '<br/>';
        }
        
    }
}

