<?php
namespace Common\Library\Weixin;
/**
 * Description of Material
 *
 * @author Administrator
 */
class Material extends Base {
    
    public function upload() {
        //上传图片素材，不算入素材数量中，小于1M
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxe9196434f60eb841&secret=21a579faa1d6e30d2825ad6345c2d7e1';
        $con = file_get_contents($url);
        $aryToken = json_decode($con, 1);
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
