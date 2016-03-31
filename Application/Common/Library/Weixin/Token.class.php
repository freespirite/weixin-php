<?php
namespace Common\Library\Weixin;
use Admin\Model\CacheTokenModel;
/**
 * Description of Token
 *
 * @author Administrator
 */
class Token extends Base {
    
    private function _createTokenUrl() {
        $aryUrl['appid'] = self::$wxconf['appid'];
        $aryUrl['secret'] = self::$wxconf['appsecret'];
        if(!$aryUrl['appid'] || !$aryUrl['secret']) {
            return $this->result('appid或secret不能为空', 0);
        }
        $aryUrl['grant_type'] = 'client_credential';
        return $this->result('https://api.weixin.qq.com/cgi-bin/token?'.$this->urlParams($aryUrl));
    }
    
    public function getToken($wid) {
        $obj = new CacheTokenModel;
        $info = $obj->getOne($wid);
        if($info && $info['timeOut'] > time()) {
            return $this->result($info['token']);
        }
        $add = $info? TRUE: FALSE;
        $rs  = $this->_createTokenUrl();
        if(!$rs[0]) { return $rs; }
        $con = file_get_contents($rs[1]);
        $ary = json_decode($con);
        if(isset($ary['access_token'])) { 
            if($add) {
                $rs = $obj->addNew($wid, $ary['access_token'], $ary['expires_in']);
            } else {
                $rs = $obj->update($wid, $ary['access_token'], $ary['expires_in']);
            }
            return $rs!==FALSE? $this->result($ary['access_token']): $this->result('写入数据失败', 0);;
        }
        else if(isset($ary['errmsg'])) {
            return $this->result($ary['errmsg'], $ary['errcode']);
        }
        else {
            return $this->result('网络问题或接口无响应',0);
        }
    }
}
