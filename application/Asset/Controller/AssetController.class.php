<?php
namespace Asset\Controller;

use Common\Controller\AdminbaseController;

class AssetController extends AdminbaseController {

    function _initialize() {
    	$adminid=sp_get_current_admin_id();
    	$userid=sp_get_current_userid();
    	if(empty($adminid) && empty($userid)){
    		exit("非法上传！");
    	}
    }
    
    
    // 文件上传
    public function plupload(){
        $upload_setting=sp_get_upload_setting();
        
        $filetypes=array(
            'image'=>array('title'=>'Image files','extensions'=>$upload_setting['image']['extensions']),
            'video'=>array('title'=>'Video files','extensions'=>$upload_setting['video']['extensions']),
            'audio'=>array('title'=>'Audio files','extensions'=>$upload_setting['audio']['extensions']),
            'file'=>array('title'=>'Custom files','extensions'=>$upload_setting['file']['extensions'])
        );
        
        $image_extensions=explode(',', $upload_setting['image']['extensions']);
        
        if (IS_POST) {
            $all_allowed_exts=array();
            foreach ($filetypes as $mfiletype){
                array_push($all_allowed_exts, $mfiletype['extensions']);
            }
            $all_allowed_exts=implode(',', $all_allowed_exts);
            $all_allowed_exts=explode(',', $all_allowed_exts);
            $all_allowed_exts=array_unique($all_allowed_exts);
            
            $file_extension=sp_get_file_extension($_FILES['file']['name']);
            $upload_max_filesize=$upload_setting['upload_max_filesize'][$file_extension];
            $upload_max_filesize=empty($upload_max_filesize)?2097152:$upload_max_filesize;//默认2M
            
            $app=I('post.app/s','');
            if(!in_array($app, C('MODULE_ALLOW_LIST'))){
                $app='default';
            }else{
                $app= strtolower($app);
            }
            
			$savepath=$app.'/'.date('Ymd').'/';
            //上传处理类
            $config=array(
            		'rootPath' => './'.C("UPLOADPATH"),
            		'savePath' => $savepath,
            		'maxSize' => $upload_max_filesize,
            		'saveName'   =>    array('uniqid',''),
            		'exts'       =>    $all_allowed_exts,
            		'autoSub'    =>    false,
            );
			$upload = new \Think\Upload($config);// 
			$info=$upload->upload();
            //开始上传
            if ($info) {
                $wxid = I('request.wxid', 0, 'intval');
//                echo $app.'>'.  join(',', C('MODULE_ALLOW_LIST')).'>'.$wxid;exit;
                if($app == 'weixin' && $wxid) {
//                    echo 'begin';
                    $conf = D("Common/WeixinConf")->where(array('id'=>$wxid, 'sid' => session('siteid')))->find();
                    if(!$conf) {
                        $this->ajaxReturn(array('name'=>'','status'=>0,'message'=>'公众号错误'));
                    }
                    $o = new \Common\Lib\Weixin\Material($conf['key'], $conf['secret'], $wxid);
                    $fp = $info['file']['savepath'].$info['file']['savename'];
                    $url = C('TMPL_PARSE_STRING.__UPLOAD__').$fp;
                    $weixin_info=array(
                        'filename'=> SITE_PATH.C("UPLOADPATH").$fp,  //国片相对于网站根目录的路径
                        'content-type'=> $info['file']['type'],  //文件类型
                        'filelength'=> $info['file']['size']     //图文大小
                    );
                    $rs = $this->_addWeixinMaterial($weixin_info, $o->getToken());
                    if(isset($rs['url'])) {
                        $this->ajaxReturn(array('filepath'=>$rs['media_id'],'url'=>$url,'name'=>$info['file']['name'],'status'=>1,'message'=>'success'));
                    } else {
                        $this->ajaxReturn(array('name'=>'','status'=>0,'message'=>'上传失败:['.$rs['errcode'].']'.$rs['errmsg']));
                    }
                }
                
                //print_r($info);exit;
                //上传成功
                $oriName = $_FILES['file']['name'];
                //写入附件数据库信息
                $first=array_shift($info);
                if(!empty($first['url'])){
                	$url=$first['url'];
                	$storage_setting=sp_get_cmf_settings('storage');
                	$qiniu_setting=$storage_setting['Qiniu']['setting'];
                	$url=preg_replace('/^https/', $qiniu_setting['protocol'], $url);
                	$url=preg_replace('/^http/', $qiniu_setting['protocol'], $url);
                	
                	$preview_url=$url;
                	
                	if(in_array($file_extension, $image_extensions)){
                	    if(C('FILE_UPLOAD_TYPE')=='Qiniu' && $qiniu_setting['enable_picture_protect']){
                	        $preview_url = $url.$qiniu_setting['style_separator'].$qiniu_setting['styles']['thumbnail300x300'];
                	        $url= $url.$qiniu_setting['style_separator'].$qiniu_setting['styles']['watermark'];
                	    }
                	}else{
                	    $preview_url='';
                	    $url=sp_get_file_download_url($first['savepath'].$first['savename'],3600*24*365*50);//过期时间设置为50年
                	}
                	
                }else{
                	$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
                	$preview_url=$url;
                }
                $filepath = $savepath.$first['savename'];
                
				$this->ajaxReturn(array('preview_url'=>$preview_url,'filepath'=>$filepath,'url'=>$url,'name'=>$oriName,'status'=>1,'message'=>'success'));
            } else {
                $this->ajaxReturn(array('name'=>'','status'=>0,'message'=>$upload->getError()));
            }
        } else {
            $filetype = I('get.filetype/s','image');
            $mime_type=array();
            if(array_key_exists($filetype, $filetypes)){
                $mime_type=$filetypes[$filetype];
            }else{
                $this->error('上传文件类型配置错误！');
            }
            
            $multi = I('get.multi',0,'intval');
            $app = I('get.app/s','');
            $wxid = I('get.wxid',0, 'intval');
            if($app == 'Weixin') {
                $upload_setting['image']['upload_max_filesize'] = 2048;//5120;
            }
            //编辑器调用返回json
            $act = I('get.action', '', 'trim');
            if($act == 'config') {
                $this->ajaxReturn($this->_editorConf());
            }
            $upload_max_filesize=$upload_setting[$filetype]['upload_max_filesize'];
            $this->assign('extensions',$upload_setting[$filetype]['extensions']);
            $this->assign('upload_max_filesize',$upload_max_filesize);
            $this->assign('upload_max_filesize_mb',intval($upload_max_filesize/1024));
            $this->assign('mime_type',json_encode($mime_type));
            $this->assign('multi',$multi);
            $this->assign('app',$app);
            $this->assign('wxid',$wxid);
            $this->display(':plupload');
        }
    }
    
    private function _editorConf() {
        $s = '{"imageActionName":"editorimg","imageFieldName":"upfile","imageMaxSize":2048000,"imageAllowFiles":[".png",".jpg",".jpeg",".gif",".bmp"],"imageCompressEnable":true,"imageCompressBorder":1600,"imageInsertAlign":"none","imageUrlPrefix":"","imagePathFormat":"\/ueditor\/php\/upload\/image\/{yyyy}{mm}{dd}\/{time}{rand:6}","scrawlActionName":"uploadscrawl","scrawlFieldName":"upfile","scrawlPathFormat":"\/ueditor\/php\/upload\/image\/{yyyy}{mm}{dd}\/{time}{rand:6}","scrawlMaxSize":2048000,"scrawlUrlPrefix":"","scrawlInsertAlign":"none","snapscreenActionName":"uploadimage","snapscreenPathFormat":"\/ueditor\/php\/upload\/image\/{yyyy}{mm}{dd}\/{time}{rand:6}","snapscreenUrlPrefix":"","snapscreenInsertAlign":"none","catcherLocalDomain":["127.0.0.1","localhost"],"catcherActionName":"catchimage","catcherFieldName":"source","catcherPathFormat":"\/ueditor\/php\/upload\/image\/{yyyy}{mm}{dd}\/{time}{rand:6}","catcherUrlPrefix":"","catcherMaxSize":2048000,"catcherAllowFiles":[".png",".jpg",".jpeg",".gif",".bmp"]}';
        return json_decode($s, 1);
    }


    private function _addWeixinMaterial($file_info, $token){
        /**
         * $file_info=array(
                'filename'=>'/images/1.png',  //国片相对于网站根目录的路径
                'content-type'=>'image/png',  //文件类型
                'filelength'=>'11011'         //图文大小
            );
         */
        
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$token.'&type=image';//永久素材
//        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$token;//临时素材
        $ch1 = curl_init ();
        $timeout = 5;
        $real_path = $file_info['filename'];
        $file_info['filename'] = basename($file_info['filename']);
        //$real_path=str_replace("/", "\\", $real_path);
//        $data= array("media"=>"@{$real_path}",'form-data'=>$file_info);
        $data= array('media'=>'@'.$real_path, 'form-data'=>$file_info);
//        $data= array('media'=>'@'.$real_path);//echo date('Y-m-d H:i:s').'>'.$real_path;
        curl_setopt ( $ch1, CURLOPT_URL, $url );
        curl_setopt ( $ch1, CURLOPT_POST, 1 );
        curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt ( $ch1, CURLOPT_POSTFIELDS, $data );
        $result = curl_exec ( $ch1 );
        curl_close ( $ch1 );
        $result=json_decode($result,true);
        return $result;
        
//        if(isset($result['url'])) {
//            return $result;
//        } else {
//            return FALSE;
//        }
        
    }

}
