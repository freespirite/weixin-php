<?php
/**
 * Description of MaterialController
 *
 * @author Administrator
 */
namespace Admin\Controller;
use Admin\Model\UsermpsetModel;
use Common\Library\Weixin\Token;

class MaterialController extends BaseController {
    private $_aryType = array(
        'index' => array('图文素材', 'fa-book'),
        'images' => array('图片素材', 'fa-picture-o'),
    );

    public function index()
    {
//        echo __APP__ ;exit;
        $this->_showtpl(__FUNCTION__);
    }
    
    public function images()
    {
//        echo __APP__ ;exit;
        $obj = new UsermpsetModel;
        $this->assign('pageSet', 'gallery');
        $this->assign('aryWx', $obj->getList('uid='.session(C('ADMIN_SESSION'))['uid'], 'id,name,appid'));
        $this->_showtpl(__FUNCTION__);
    }
    
    private function _showtpl($type = 'index') {
        if(!isset($this->_aryType[$type])) {
            $type = 'index';
        }
        arsort($this->_aryType);
        $this->assign('type', $type);
        $this->assign('aryType', $this->_aryType);
//        $this->assign('tplFile', THINK_PATH .'../Application/Admin/View/Material/index_'.$type.C('TMPL_TEMPLATE_SUFFIX'));
        $this->display('index');
    }
    
    public function upload(){
        $type = I('get.dir');
        $wid  = intval(I('post.wxapid'));
        if(I('get.dir') != 'image') { exit(json_encode(array('error'=>1, 'message'=> '上传文件类型错误'))); }
        if(!$wid) { exit(json_encode(array('error'=>1, 'message'=> '公众号未选择'))); }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     5242880 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            exit(json_encode(array('error'=>1, 'message'=>$upload->getError())));
        }else{// 上传成功
            foreach($info as $file){
                $url = $upload->rootPath.$file['savepath'].$file['savename'];
            }
            $objWX = new UsermpsetModel;
            $wxInfo = $objWX->getInfo($id);
            if(!$wxInfo) {
                exit(json_encode(array('error'=>1, 'message'=>'对应操作的账号信息没有找到')));
            }
            $conf = array(
                'appid' => $wxInfo['appid'], 
                'appsecret' => $wxInfo['appsecret'],
            );
            exit(json_encode(array('error'=>0, 'url'=>__APP__.substr($url,1))));
        }
    }
}
