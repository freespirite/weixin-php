<?php
/**
 * Msg(消息管理)
 */
namespace Weixin\Controller;

class MenuController extends BaseController {

    
    protected $weixinMsg;
    private $_model = null;
    //protected $auth_rule_model;

    public function _initialize() {
        parent::_initialize();
        
//        $this->_model = D('Common/WeixinMenu');
    }

    /**
     * 公众号自定义菜单列表
     */
    public function index() {
        
        $rows = D('Common/WeixinConf')->where('sid='.session('siteid').' and pass > -1')->select();
        if(!$rows) {
            $this->assign('confs', array());
            $this->assign('menus', array());
            $this->assign('wxid', 0);
            $this->display();
            return;
        }
        $confs = array();
        $defaultid = 0;
        foreach ($rows as $row) {
            $confs[$row['id']] = $row;
            if(!$defaultid) { $defaultid = $row['id']; }
        }
        unset($rows);
        
        $wxid = I('get.wxid', 0, 'intval');
        if ($wxid && !isset($confs[$wxid])) {
            $this->redirect('Menu/index');
        }
        $wxid  = $wxid? $wxid: $defaultid;
        $menus = array();
        $token = '';
        if($wxid) {
//            $row = $this->_model->where(array('wxid' => $wxid, 'sid'=> session('siteid')))->find();
            $o = new \Common\Lib\Weixin\Menu($confs[$wxid]['key'], $confs[$wxid]['secret'], $confs[$wxid]['id']);
            $menus = $o->getMenus();
            $token = $o->getToken();
        }
//        exit($token);
//        $menus = $row? json_decode($row['content'], 1): array();
        $lists = '';
        foreach($menus as $k=>$menu) {
            $hasSub = isset($menu['sub_button'])? TRUE: FALSE;
            $lists .= '<tr>';
            $lists .= '<td>'.$menu['name'].'</td>';
            if($hasSub) {
                $lists .= '<td>&nbsp;</td>';
                $lists .= '<td>&nbsp;</td>';
                $delMsg = '该操作导致子菜单也将被删除，确认？';
            } else {
//                $lists .= '<td>'.($menu['type']=='view'?'链接': '其他').'</td>';
//                $lists .= '<td>'.($menu['type']=='view'?$menu['url']: $menu['value']).'</td>';
                $lists .= $this->_getMenuContent($menu, $token, $confs[$wxid]['pass']);
                $delMsg = '菜单将被删除，确认？';
            }
            $lists .= '<td><a href="'.U('Menu/edit',array('wxid'=>$wxid, 'pid'=>$k)).'">修改</a>';
            $lists .= $hasSub? '':' | <a href="'.U('Menu/del',array('wxid'=>$wxid, 'pid'=>$k)).'" class="js-ajax-dialog-btn" data-msg="'.$delMsg.'">删除</a>';
            $lists .= $hasSub && count($menu['sub_button']['list'])>4? '': ' | <a href="'.U('Menu/add',array('wxid'=>$wxid, 'pid'=>$k)).'">添加子菜单</a>';
            $lists .= '</td></tr>';
            
            $lists .= $hasSub? $this->_getSubMenus($menu['sub_button']['list'], $wxid, $k, $token, $confs[$wxid]['pass']): '';
        }
//        print_r($menus);
//        echo count($menus);exit;
        
        $this->assign('confs', $confs);
        $this->assign('lists', $lists);
        $this->assign('wxid', $wxid);
        $this->assign('add', count($menus)>2? 0: 1);
        unset($menus);
        $this->display();
    }
    
    public function add() {
        
        $wxid = I('request.wxid', 0, 'intval');
        if(!$wxid) { E('参数错误'); }
        $pid = I('request.pid', '', 'trim');
        $row = D('Common/WeixinConf')->where(array('id' => $wxid, 'sid'=> session('siteid')))->find();
        if(!$row) { E('公众号不存在'); }
        $o = new \Common\Lib\Weixin\Menu($row['key'], $row['secret'], $row['id']);
        $menus = $o->getMenus();
        if($pid && !isset($menus[$pid])) { E('操作错误'); }
        
        if (IS_POST) {
            $name = I('post.name','', 'trim');
            $url = I('post.url','', 'trim');
            if(!$name || !$url) { $this->error('菜单名和跳转地址不能为空'); }
            if($pid!='') {
                $menus[$pid]['sub_button']['list'][] = array(
                    'type' => 'view',
                    'name' => $name,
                    'url' => $url,
                );
                unset($menus[$pid]['type'], $menus[$pid]['url']);
            } else {
                $menus[] = array(
                    'type' => 'view',
                    'name' => $name,
                    'url' => $url,
                );
            }
//            print_r($menus);exit;
            $menus = $this->_tourlencode($menus);
            $data = array('button'=> $menus);
            $data = json_encode($data);
            $data = urldecode($data);
//            exit($o->getToken());
//            print_r($data);exit;
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$o->getToken();
            $curl = curl_init();  
            curl_setopt($curl,CURLOPT_URL,$url);  
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);  
            curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($curl,CURLOPT_POST,1);  
            curl_setopt($curl,CURLOPT_POSTFIELDS,$data);  
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);  
            $json = curl_exec($curl);  
            curl_close($curl);  
            $rs = json_decode($json, 1);
            if($rs['errcode']) {
                if($rs['errcode'] == 40018) {
                    $this->error('字数不超过4个汉字或8个字母');
                } else {
                    $this->error('菜单更新失败，错误号：'.$rs['errcode'].';'.$rs['errmsg']);
                }
            } else {
                $this->success('菜单更新成功', U('Menu/index', array('wxid'=> $wxid)));
            }
            
            
        }  else {
            
            $parent = '';
            if($pid!='') {
                if(!isset($menus[$pid])) { E('菜单不存在'); }
                $parent = $menus[$pid]['name'];
            }
            
            $this->assign('parent', $parent);
            $this->assign('wxname', $row['name']);
            $this->assign('pid', $pid);
            $this->assign('wxid', $wxid);
            $this->display();
        }
    }
    
    public function edit() {
        
        $wxid = I('request.wxid', 0, 'intval');
        if(!$wxid) { E('参数错误'); }
        $pid = I('request.pid', 0, 'intval');
        $row = D('Common/WeixinConf')->where(array('id' => $wxid, 'sid'=> session('siteid')))->find();
        if(!$row) { E('公众号不存在'); }
        $o = new \Common\Lib\Weixin\Menu($row['key'], $row['secret'], $row['id']);
        $menus = $o->getMenus();
        $sid = I('request.sid');
        if(!isset($menus[$pid])) { E('菜单不存在'); }
        
        if (IS_POST) {
            $name = I('post.name','', 'trim');
            $url = I('post.url','', 'trim');
            if(!$name || !$url) { $this->error('菜单名和跳转地址不能为空'); }
            if($sid!='' && !isset($menus[$pid]['sub_button'])) { E('子菜单不存在'); }
            if($sid!='' && !isset($menus[$pid]['sub_button']['list'][$sid])) { E('子菜单不存在'); }
            if($sid!='') {
                $menus[$pid]['sub_button']['list'][$sid] = array(
                                                                'name' => $name,
                                                                'url'  => $url,
                                                                'type' => 'view',
                                                            );
            } else {
                $menus[$pid] = array(
                    'name' => $name,
                    'url'  => $url,
                    'type' => 'view',
                );
            }
//            print_r($menus);
            $menus = $this->_tourlencode($menus);
            $data = array('button'=> $menus);
            $data = json_encode($data);
            $data = urldecode($data);
//            exit($o->getToken());
//            print_r($data);exit;
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$o->getToken();
            $curl = curl_init();  
            curl_setopt($curl,CURLOPT_URL,$url);  
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);  
            curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($curl,CURLOPT_POST,1);  
            curl_setopt($curl,CURLOPT_POSTFIELDS,$data);  
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);  
            $json = curl_exec($curl);  
            curl_close($curl);  
            $rs = json_decode($json, 1);
            if($rs['errcode']) {
                if($rs['errcode'] == 40018) {
                    $this->error('字数不超过4个汉字或8个字母');
                } else {
                    $this->error('菜单更新失败，错误号：'.$rs['errcode'].';'.$rs['errmsg']);
                }
            } else {
                $this->success('菜单更新成功');
            }
            
            
        }  else {

            
    //        print_r($row);exit;
            
            $menu = $menus[$pid];
            $parent = '';
            if($sid!='') {
                if(!isset($menus[$pid]['sub_button'])) { E('菜单不存在'); }
                $menu = $menus[$pid]['sub_button']['list'][$sid];
                $parent = $menus[$pid]['name'];
            }
            
            if(!$menu) { $this->redirect(U('Menu/index', array('wxid'=>$wxid))); }
            $this->assign('menu', $menu);
            $this->assign('parent', $parent);
            $this->assign('wxname', $row['name']);
            $this->assign('pid', $pid);
            $this->assign('sid', $sid);
            $this->assign('wxid', $wxid);
            $this->assign('hassub', isset($menu['sub_button'])? 1: 0);
            $this->display();
        }
    }
    
    public function del() {
        
        $wxid = I('request.wxid', 0, 'intval');
        if(!$wxid) { $this->error('参数错误'); }
        $pid = I('request.pid');
        $row = D('Common/WeixinConf')->where(array('id' => $wxid, 'sid'=> session('siteid')))->find();
        if(!$row) { $this->error('公众号不存在'); }
        $o = new \Common\Lib\Weixin\Menu($row['key'], $row['secret'], $row['id']);
        $menus = $o->getMenus();
        $sid = I('request.sid');
        if(!isset($menus[$pid])) { $this->error('菜单不存在'); }
        
        $menu = $menus[$pid];
        $parent = '';
        if ($sid!='') {
            if (!isset($menus[$pid]['sub_button']['list'][$sid])) {
                $this->error('菜单不存在');
            }
            if(count($menus[$pid]['sub_button']['list']) > 1) {
                unset($menus[$pid]['sub_button']['list'][$sid]);
                $menus[$pid]['sub_button']['list'] = array_values($menus[$pid]['sub_button']['list']);
            } else {
                unset($menus[$pid]);
                $menus = array_values($menus);
            }
        } else {
            unset($menus[$pid]);
            $menus = array_values($menus);
        }
//print_r($menus);exit;
        $menus = $this->_tourlencode($menus);
        $data = array('button' => $menus);
        $data = json_encode($data);
        $data = urldecode($data);
//            exit($o->getToken());
//            print_r($data);exit;
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $o->getToken();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($curl);
        curl_close($curl);
        $rs = json_decode($json, 1);
        if ($rs['errcode']) {
            $this->error('菜单更新失败，错误号：' . $rs['errcode'] . ';' . $rs['errmsg']);
        } else {
            $this->success('菜单更新成功');
        }
    }
    
    private function _tourlencode($ms) {
        foreach($ms as $k=>$m) {
            $ms[$k]['name'] = urlencode($m['name']);
            if(isset($m['sub_button'])) {
                $ms[$k]['sub_button'] = $this->_tourlencode($ms[$k]['sub_button']['list']);
            }
            else if(isset($m['news_info'])) {
                $ms[$k]['type'] = 'view';
                $ms[$k]['url'] =  $m['news_info']['list'][0]['content_url'];
//                foreach ($m['news_info']['list'] as $kk=>$vv) {
//                    $ms[$k]['news_info'][$kk]['title'] = urlencode($vv['title']);
//                    $ms[$k]['news_info'][$kk]['author'] = urlencode($vv['author']);
//                    $ms[$k]['news_info'][$kk]['digest'] = urlencode($vv['digest']);
//                    $ms[$k]['news_info'][$kk]['show_cover'] = $vv['show_cover'];
//                    $ms[$k]['news_info'][$kk]['cover_url'] = $vv['cover_url'];
//                    $ms[$k]['news_info'][$kk]['content_url'] = $vv['content_url'];
//                    $ms[$k]['news_info'][$kk]['source_url'] = $vv['source_url'];
//                }
                unset($ms[$k]['news_info'], $ms[$k]['value']);
            }
            else if($m['type']!='view') {
                unset($ms[$k]);
            }
        }
        $ms = array_values($ms);
        return $ms;
    }

    private function _getMenuContent($menu, $token, $pass) {
        
        switch ($menu['type']) {
            case 'view':
                $type = '链接';
                $con  = '<a href="'.$menu['url'].'" target="_blank">查看</a>';
                break;
            case 'news':
                $type = '图文';
                $con  = '<a href="'.$menu['content_url'].'" target="_blank">'.$menu['title'].'</a>';
                $con  = $this->_getNewsLink($menu['news_info']['list'][0]);
                break;
            case 'video':
                $type = '视频';
                $con  = '<a href="'.$menu['value'].'" target="_blank">查看</a>';
                break;
            case 'img':
                if($pass > 0) {
                    $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$menu['value'];
                    $con = '<img src="'.$url.'" style="height:80px;cursor: pointer;"  onclick="parent.image_preview_dialog(this.src);">';
                } else {
                    $con = '<font color="red">通过认证后可见</font>';
                }
                $type = '图片';
                break;
            case 'voice':
                if($pass) {
//                    $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$menu['value'];
//                    $url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.$token;
//                    $parms = array('access_token'=>$token, 'media_id'=>$menu['value']);
//                    $return = $this->toPost($parms, $url);
//                    print_r($return);exit;
//                    $con = '<audio src="'.$url.'" height="30px">您的浏览器不支持 audio 标签</audio>';
                    $con = '暂不支持播放';
                } else {
                    $con = '<font color="red">通过认证后可见</font>';
                }
                $type = '声音';
                break;
            case 'text':
                $type = '文字';
                $con  = $menu['value'];
                break;
            default :
                $type = '其他';
                $con  = '';
                break;
        }
        return '<td>'.$type.'</td><td>'.$con.'</td>';
    }
    
    private function _getNewsLink($ary) {
        $link  = '<a href="'.$ary['content_url'].'" target="_blank">'.$ary['digest'].'</a>';
        $link .= $ary['source_url']? ' 【<a href="'.$ary['source_url'].'" target="_blank">查看原文</a>】': '';
        return $link;
    }

    private function _getSubMenus($ary, $wxid, $pid, $token, $pass) {
        $lists  = '';
        $count  = count($ary);
        $delMsg = $count > 1? '操作不可恢复，确认？': '该操作会将上级菜单也一并删除，确认？';
        $count -= $count? 1: 0;
        foreach($ary as $k=>$menu) {
            $lists .= '<tr>';
            $lists .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($k==$count? '└': '├').'── '.$menu['name'].'</td>';
            
//            $lists .= '<td>'.($menu['type']=='view'?'链接': '其他').'</td>';
//            $lists .= '<td>'.($menu['type']=='view'?$menu['url']: $menu['value']).'</td>';
            $lists .= $this->_getMenuContent($menu, $token, $pass);
            
            $lists .= '<td><a href="'.U('Menu/edit',array('wxid'=>$wxid, 'pid'=> $pid, 'sid'=>$k)).'">修改</a>';
            $lists .= ' | <a href="'.U('Menu/del',array('wxid'=>$wxid, 'pid'=> $pid, 'sid'=>$k)).'" class="js-ajax-dialog-btn" data-msg="'.$delMsg.'">删除</a>';
            $lists .= '</td></tr>';
        }
        return $lists;
    }

    /**
     * 导入自定义菜单项
     * @return boolean/json
     */
    public function import() {
        $wxid = I('get.wxid',0,'intval');
        $ajax = I('get.ajax',0,'intval');
        if(!$wxid) { 
            $this->error('操作错误');
        }
        
        $row  = $this->weixinConf->where(array('id'=> $wxid, 'sid' => session('siteid')))->find();
        if(!$row) { $this->error('公众号不存在'); }
        $o = new \Common\Lib\Weixin\Menu($row['key'], $row['secret'], $row['id']);
        $rs = $o->import($wxid); 
        $rs? $this->success('导入成功', U('Menu/index', array('wxid'=>$wxid))): $this->error($o->getErrorInfo());

    }


    /**
	 * 公众号列表内容拼装
	 * @param array $where 查询条件
	 */
	private function _lists($where=array()){
		$wxid = I('request.wxid',0,'intval');
		
		if(!empty($wxid)){
		    $where['wxid'] = $wxid;
		}
		
		$startTime = I('request.startTime');
		if(!empty($startTime)){
		    $where['create_time'] = array(
		        array('EGT',$startTime)
		    );
		}
		
		$endTime = I('request.endTime');
		if(!empty($endTime)){
		    if(empty($where['create_time'])){
		        $where['create_time'] = array();
		    }
		    array_push($where['create_time'], array('ELT',$endTime));
		}
		
		$keyword = I('request.keyword');
		if(!empty($keyword)){
		    $where['content'] = array('like','%'.$keyword.'%');
		}
        
        $rows = D('Common/WeixinConf')->where(array('sid'=> session('siteid')))->select();
        $confs = array();
        //----------------------------
        $defaultid = 0;
        foreach ($rows as $row) {
            $confs[$row['id']] = $row;
            if(!$defaultid) { $defaultid = $row['id']; }
        }
        if ($wxid && !isset($confs[$wxid])) {
            $this->redirect('Msg/index');
        }
        $wxid = $wxid? $wxid: $defaultid;
        //----------------------------
		unset($rows);
        $prefix = C('DB_PREFIX');
        $count = M()->table(array($prefix.'weixin_msg'=>'wm',$prefix.'weixin_users'=>'wu'))
                ->where('wm.wxid='.$wxid.' and wm.from_user_name=wu.openid')
                ->count();
        
//		$count = $this->weixinMsg->where($where)->count();
		$page = $this->page($count, 10);
//		$msgs = $this->weixinMsg
//                ->where($where)
//                ->limit($page->firstRow , $page->listRows)
//                ->order("id DESC")
//                ->select();
        $msgs = M()->table(array($prefix.'weixin_msg'=>'wm',$prefix.'weixin_users'=>'wu'))
                ->field('wm.*,wu.nickname,wu.headimgurl')
                ->where('wm.wxid='.$wxid.' and wm.from_user_name=wu.openid')
                ->limit($page->firstRow , $page->listRows)
                ->order('id DESC')
                ->select();
		$html = '';
        
        $token = '';
        if($wxid) {
            $obj  = new \Common\Lib\Weixin\Base($confs[$wxid]['key'], $confs[$wxid]['secret'], $confs[$wxid]['id'], session('siteid'));
            $token = $obj->getToken();
        }
        foreach($msgs as $row) {
            
            $html .= '<tr id="node-'.$row['id'].'">
                        <td rowspan="2" width="50"><img src="'.$row['headimgurl'].'" width="48" height="48"></td>
                        <td>'.$row['nickname'].'</td>
                        <td>'.(isset($confs[$row['wxid']])? $confs[$row['wxid']]['name']: '').'</td>
                        <td>'.date('Y-m-d H:i:s').'</td>
                        <td>回复</td></tr>
                       <tr><td colspan="4">'.$this->_getContent($row['content'], $row['msg_type'], $token).'</td></tr>';
            
        }
		
		$this->assign('page', $page->show('Admin'));
		$this->assign('wxid', $wxid);
        $this->assign('confs', $confs);
		$this->assign('lists', $html);
	}
    
    private function _getContent($json, $type, $token) {
        if(!$json) { return ''; }
        $ary = json_decode($json, 1);
        $str = '';
        switch ($type) {
            case 1:
                $str = $ary['Content'];
                break;
            case 2:
                $img = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$ary['MediaId'];
                $str = '<a href="'.$img.'" target="_blank"><img src="'.$img.'" style="height:80px;"></a>';
                break;
            case 6:
                $str = '定位：'.$ary['Label'];
                break;
            default :
                $str = '';
                break;
        }
        return $str;
    }
}
