<?php
/**
 * Description of Material
 *
 * @author Administrator
 */
namespace Common\Library\Weixin;
use Admin\Model\ImagesModel;

class Material extends Base {
    
    public $localFile = '';
    public function upload($wid, $localFile) {
        $this->result = 1;
        $url  = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=';
        $response = $this->curlPost($wid, $localFile, $url);
        if(self::$errors) { $this->sysExit(array('error' => 1, 'message' => self::$errors)); }
        $rs = json_decode($response);
        if(isset($rs['url'])) {
            $img = new ImagesModel();
            $data = array(
                'uid' => session(C('ADMIN_SESSION'))['uid'],
                'wid' => $wid,
                'mediaid' => $rs['media_id'],
                'url' => $rs['url'],
                'createtime' => time()
            );
            $rs = $img->add($data);
            if(!$rs) { $this->sysExit(array('error' => 1, 'message' => $img->getError())); }
            $this->sysExit(array('error'=>0, 'url'=> $rs['url']));
        }
    }
    
    public function curlPost($wid, $localFile, $url) {
        $this->localFile = $localFile;
        unset($localFile);
        $obj = new Token;
        $token = $obj->getToken($wid);
        
        if(self::$errors) { return FALSE; }
        if(!file_exists($this->localFile)) {
            self::$errors = $this->localFile.' not found';
            return FALSE;
        }
        $fields['media'] = '@'.$this->localFile;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, FALSE);
        curl_setopt($ch, CURLOPT_URL, $url.$token );
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields );
        $response = curl_exec( $ch );

        if ($error = curl_error($ch) ) {
            self::$errors = $error.'_line:'.__CLASS__.'>'.__LINE__;
            return FALSE;
        }
        curl_close($ch);
        return $response;
    }
    
}
