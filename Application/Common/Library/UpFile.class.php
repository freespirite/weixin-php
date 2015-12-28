<?php
/**
 * Created by netBeans.
 * User: nick
 * Date: 2015/8/22
 * Time: 16:53
 */

namespace Common\Library;

include APP_PATH . '/Common/Library/ucloud/proxy.php';

class UpFile {
    
    private $_field  = '';
    private $_path   = '';
    private $_bucket = '';
    private $_maxSize = 1000000;
    private $_type    = '';
    private $_size    = 0;
    private $_ext     = '';

    public function __construct($bucket) {
        $this->_bucket = $bucket;
    }

     private function _uploadToUcloud() {
        $key    = $this->_path.time().mt_rand(1000, 9999).'.'.$this->_ext;
        list($data, $error) = UCloud_PutFile($this->_bucket, $key, $_FILES[$this->_field]['tmp_name']);
        //echo __CLASS__.'>'.__LINE__.'>';print_r($data);exit;
        if(!isset($data['ETag'])) { return $this->_alert($error['ErrMsg']); }
        return json_encode(array('error' => 0, 'url' => $key));
    }
    
    private function _checkError() {
        //PHP上传失败
        $error = '';
        if (!empty($_FILES[$this->_field]['error'])) {
            switch($_FILES[$this->_field]['error']){
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
        }
        return $error;
    }
    public function deleteFile($key) {
        //list($data, $err) = UCloud_Delete($bucket, $key);
        UCloud_Delete($this->_bucket, $key);
    }

    public function uploadFile($field, $path) {
        $this->_field  = $field;
        $this->_path   = $path;
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        $error = $this->_checkError();
        if($error) { return $this->_alert($error); }
        //有上传文件时
        if (empty($_FILES) === false) {
            //原文件名
            $file_name = $_FILES[$this->_field]['name'];
            //服务器上临时文件名
            $tmp_name = $_FILES[$this->_field]['tmp_name'];
            //文件大小
            $this->_size = $_FILES[$this->_field]['size'];
            //检查文件名
            if (!$file_name) {
                return $this->_alert("请选择文件。");
            }
            //检查是否已上传
            if (@is_uploaded_file($tmp_name) === false) {
                return $this->_alert("上传失败。");
            }
            //检查文件大小
            if ($this->_size > $this->_maxSize) {
                return $this->_alert("上传文件大小超过限制。");
            }
            //检查目录名
            $dir_name = empty(I('get.dir')) ? 'image' : trim(I('get.dir'));
            if (empty($ext_arr[$dir_name])) {
                return $this->_alert("目录名不正确。");
            }
            //获得文件扩展名
            $this->_ext = pathinfo($_FILES[$this->_field]['name'], PATHINFO_EXTENSION);
            //检查扩展名
            if (in_array($this->_ext, $ext_arr[$dir_name]) === false) {
                return $this->_alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
            }
            return $this->_uploadToUcloud();
        }
        else {
            return $this->_alert('上传失败');
        }
    }

    private function _alert($msg) {
        //header('Content-type: text/html; charset=UTF-8');
        return json_encode(array('error' => 1, 'message' => $msg));
        //exit;
    }
}










