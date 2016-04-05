<?php
namespace Common\Library\Weixin;
use Admin\Model\CacheTokenModel;
use Admin\Model\UsermpsetModel;
/**
 * Description of Token
 *
 * @author Administrator
 */
class Token extends Base {
    private $wid = 0;
    
    private function _createTokenUrl() {
        
        $xp = new UsermpsetModel;
        $info = $xp->getInfo($this->wid, 'appid,appsecret');
        if(!$info) {
            self::$errors = 'record not found';
            return FALSE;
        }
        $aryUrl['appid'] = $info['appid'];
        $aryUrl['secret'] = $info['appsecret'];
        if(!$aryUrl['appid'] || !$aryUrl['secret']) {
            self::$errors = 'appid或secret不能为空';
            return FALSE;
        }
        $aryUrl['grant_type'] = 'client_credential';
        return 'https://api.weixin.qq.com/cgi-bin/token?'.$this->urlParams($aryUrl);
    }
    
    public function getToken($wid) {
        $this->wid = $wid;
        $obj = new CacheTokenModel;
        $info = $obj->getOne($wid);
        if($info && $info['timeOut'] > time()) {
            return $info['token'];
        }
        $add = $info? TRUE: FALSE;
        $url  = $this->_createTokenUrl();
        if(self::$errors) { return FALSE; }
        $con = file_get_contents($url);
        $ary = json_decode($con, TRUE);
        if(isset($ary['access_token'])) { 
            if($add) {
                $rs = $obj->addNew($wid, $ary['access_token'], $ary['expires_in']);
            } else {
                $rs = $obj->update($wid, $ary['access_token'], $ary['expires_in']);
            }
            if($rs !== FALSE) {
                return $ary['access_token'];
            }
            else {
                self::$errors = '写入数据失败';
                return FALSE;
            }
        }

        self::$errors = isset($ary['errmsg'])? $ary['errcode'].':'.$ary['errmsg']: '网络问题或接口无响应';
        return FALSE;
    }
}
