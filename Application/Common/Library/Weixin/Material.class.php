<?php
namespace Common\Library\Weixin;
/**
 * Description of Material
 *
 * @author Administrator
 */
class Material extends Base {
    
    public function upload() {
        $rsToken = new Token;
        if(!isset($aryToken['access_token'])) { exit('error: '.$con); }

        $file = '/data/www/weixin/demo.jpg'; //要上传的文件
        $url  = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$aryToken['access_token'];//target url

        $fields['media'] = '@'.$file;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields );

        $response = curl_exec( $ch );

        if ($error = curl_error($ch) ) {
               die($error);
        }
        curl_close($ch);
        var_dump($response);
    }


    private function _alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
    }
}
