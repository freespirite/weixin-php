<?php
/**
 * Reply(回复管理)
 */
namespace Weixin\Controller;

class ReplyController extends BaseController {

    
//    protected $weixin_msg;
    private $_mreply = null;

    public function _initialize() {
        parent::_initialize();
        
        $this->_mreply = D("Common/WeixinReplyset");
        
        //$this->auth_rule_model = D("Common/AuthRule");
    }

    /**
     * 微信关注公众号自动回复设置显示
     */
    public function index() {
        //$this->_importUser();
        $this->_showInfo();
        $this->display();
    }
    
    /**
     * 微信公众号自动回复设置显示
     */
    public function auto() {
        //$this->_importUser();
        $this->_showInfo(2);
        $this->display();
    }
    
    /**
     * 微信公众号关键字回复设置显示
     */
    public function kw() {
        //$this->_importUser();
        $this->_showKwList();
        $this->display();
    }
    
    /**
     * 微信公众号关键字回复设置新增
     */
    public function kwAdd() {
        //$this->_importUser();
        $wxid = I('get.wxid',0,'intval');
        $this->assign('wxid', $wxid);
        $this->display();
    }
    
    /**
     * 微信公众号关键字回复设置新增
     */
    public function kwDel() {
        //$this->_importUser();
        $wxid = I('get.wxid',0,'intval');
        $id = I('get.id',0,'intval');
        if(!$wxid || !$id) {  }
        $row = $this->_mreply
                ->field('id,keyword_info')
                ->where(array('wxid' => $wxid, 'sid' => session('siteid')))
                ->find();
        if(!$row || !$row['keyword_info']) { $this->error('提交的数据有误！'); }
        $kws = json_decode($row['keyword_info'], 1);
        $data = array();
        $id -= 1;
        foreach($kws as $k=>$v) {
            if($k != $id) {
                $data[] = $v;
            }
        }
        $rs = $this->_mreply
                ->where(array('wxid' => $wxid, 'sid' => session('siteid')))
                ->save(array('keyword_info' => json_encode($data)));
        
        if ($rs) {
            $this->success('删除成功！');
        } else {
            $this->error('操作失败！');
        }
    }
    
    /**
     * 微信公众号关键字回复设置修改
     */
    public function kwEdit() {
        //$this->_importUser();
        $this->_showKw();
        $this->display();
    }
    
    /**
     * 关键字回复设置保存
     */
    public function kwPost() {
        if (!IS_POST) { E('operation error!'); }
        $id = I('post.id',0,'intval');
        $wxid = I('post.wxid',0,'intval');
        if(!$wxid) { $this->error('提交的数据有误！'); }
        
        $row = array();
        if($id) {
            $row = $this->_mreply
                ->field('id,keyword_info')
                ->where(array('wxid' => $wxid, 'sid' => session('siteid')))
                ->find();
            if(!$row || !$row['keyword_info']) { $this->error('提交的数据有误！'); }
        }
        
        $json['rule_name'] = I('post.rule_name','','trim');
        $json['reply_mode'] = I('post.reply_mode','','trim') == 'reply_all'? 'reply_all': 'random_one';
        $json['create_time'] = time(); 
        $json['keyword_list_info'] = array();
        $json['reply_list_info'] = array();
        $match_mode = I('post.match_mode')=='equal'? 'equal': 'contain';
        $json['match_mode']  = $match_mode;
        $kws = split("\n", I('post.keyword','','trim'));
        foreach($kws as $kw) {
            if(!$kw) { continue; }
            $json['keyword_list_info'][] = array(
                'type' => 'text',
                'match_mode' => $match_mode,
                'content' => $kw,
            );
        }
        $replys = I('post.reply');
        foreach($replys as $reply) {
            if(!$reply) { continue; }
            $json['reply_list_info'][] = array(
                'type' => 'text',
                'content' => $reply,
            );
        }
        
        $add = FALSE;
        if($id) {
            $kws = json_decode($row['keyword_info'], 1);
            $id -= 1;
            if(!isset($kws[$id])) { $this->error('提交的数据有误！'); }
            $kws[$id] = $json;
        } else {
            $row = $this->_mreply
                ->field('id,keyword_info')
                ->where(array('wxid' => $wxid, 'sid' => session('siteid')))
                ->find();
            if($row) {
                $kws = json_decode($row['keyword_info'], 1);
                $kws[] = $json;
            } else {
                $kws = array($json);
                $add = TRUE;
            }
        }
        
        if(!$json['rule_name']) { $this->error('规则名称不能为空！'); }
        if (!$json['keyword_list_info'] || !$json['reply_list_info']) {
            $this->error('关键字和回复不能为空！');
        }
        if($add) {
            
            $data = array(
                'wxid' => $wxid,
                'add_info' => '',
                'default_info' => '',
                'keyword_info' => json_encode($kws),
                'addtime' => $json['create_time'],
                'updatetime' => $json['create_time'],
                'sid' => session('siteid')
            );
            $rs = $this->_mreply->add($data);
        } else {
            $data = array(
                'keyword_info' => json_encode($kws),
                'updatetime' => $json['create_time'],
            );
            $rs = $this->_mreply->where(array('wxid' => $wxid, 'sid' => session('siteid')))->save($data);
        }
        
        if ($rs) {
            $this->success('保存成功！', U('Reply/kw',array('wxid'=>$wxid)));
        } else {
            $this->error('保存失败！');
        }
    }
    
    /**
     * 保存微信关注公众号自动回复设置显示
     */
    public function infoPost() {
        $aryField = array(1=> 'add_info', 2=> 'default_info');
    	if (IS_POST) {
            $cate = I('post.cate',0,'intval');
            if(!isset($aryField[$cate])) { $this->error('提交的数据有误！'); }
            $field = $aryField[$cate];
            $data['wxid'] = I('post.wxid',0,'intval');
            $data[$field] = json_encode(array('type'=>'text', 'content'=>I('post.info','','trim')));
            $data['updatetime'] = time();
            $row = $this->_mreply
                    ->field('id')
                    ->where(array('wxid'=> $data['wxid'], 'sid'=> session('siteid')))
                    ->find();
            
            $rs = null;
            if($row) {
                $rs = $this->_mreply->where('id='.$row['id'])->save($data);
            } else {
                $data['wxid']         = I('post.wxid',0,'intval');
                $data['add_info']     = isset($data['add_info'])? $data['add_info']: '';
                $data['default_info'] = isset($data['default_info'])? $data['default_info']: '';
                $data['keyword_info'] = '';
                $data['addtime'] = $data['updatetime'];
                $data['sid']     = session('siteid');
                $rs = $this->_mreply->add($data);
            }
    		if ($rs) {
                $this->success('保存成功！');
    		} else {
    			$this->error('保存失败！');
    		}
    	}
    }
    
    /**
	 * 显示自动回复信息列表
	 * @param array $where 查询条件
	 */
	private function _showInfo($cate=1){
        $sid  = session('siteid');
        $rows = $this->weixinConf->where(array('sid' => $sid))->field('id,name')->select();
        if(!$rows) { 
            $this->assign('wxid', 0);
            return;
//            $this->redirect('Msg/index'); 
            
        }
        
        $data = array();
        $defaultid = 0;
        foreach ($rows as $row) {
            $data[$row['id']] = $row['id'];
            if(!$defaultid) { $defaultid = $row['id']; }
        }
        
        $wxid = I('get.wxid', 0, 'intval');
        if ($wxid && !isset($data[$wxid])) {
            $this->redirect('Reply/index');
        }
        $wxid = $wxid? $wxid: $defaultid;
//        print_r($rows);exit;
        if($cate == 2) {
            $field = 'default_info';
            $info = '';
        } else if($cate == 3) {
            $field = 'keyword_info';
            $info = array();
        } else {
            $field = 'add_info';
            $info = '';
        }
        
        $row = NULL;
        if($wxid) {
            $row = D('Common/WeixinReplyset')->where('wxid=' . $wxid . ' and sid=' . $sid)->find();
            if ($row && $row[$field]) {
                $ary = json_decode($row[$field], 1);
                if($cate == 3) {
                    $info = $ary;
                } else if ($ary['type'] == 'text') {
                    $info = $ary['content'];
                }
            }
        }
//        $this->assign('id', isset($data[$wxid])? $data[$wxid]: 0);
        $this->assign('wxid', $wxid);
        $this->assign('rows', $rows);
        $this->assign('cate', $cate);
        $this->assign('addInfo', $info);
    }
    
    
    /**
     * 微信公众号关键字回复设置保存
     */
    private function _showKwList() {
        $sid  = session('siteid');
        $rows = $this->weixinConf->where(array('sid' => $sid))->field('id,name,key,secret')->select();
        if(!$rows) { 
            $this->assign('wxid', 0);
            return;
        }
        
        $data = array();
        $defaultid = 0;
        foreach ($rows as $row) {
            $data[$row['id']] = $row;
            if(!$defaultid) { $defaultid = $row['id']; }
        }
        
        $wxid = I('get.wxid', 0, 'intval');
        if ($wxid && !isset($data[$wxid])) {
            $this->redirect('Reply/index');
        }
        $wxid = $wxid? $wxid: $defaultid;
        
        //----------------------------------------
        //$material  = new \Common\Lib\Weixin\Material($data[$wxid]['key'], $data[$wxid]['secret'], $data[$wxid]['id'], session('siteid'));
        //$token = $material->getToken();
        //echo $token.'<br/>';
        //----------------------------------------
        $kws  = array();
        $list = '';
        if($wxid) {
            $row = D('Common/WeixinReplyset')->field('keyword_info')->where('wxid=' . $wxid . ' and sid=' . $sid)->find();
            if ($row && $row['keyword_info']) {
                $kws = json_decode($row['keyword_info'], 1);
                /*
                foreach($kws as $k=>$v) {
                    $list .= '<tr><td>'.date('Y-m-d H:i:s',$v['create_time']).'</td>';
                    $list .= '<td>';
                    $list .= '<a href="'.U('Reply/kwEdit', array('id'=>$key,'wxid'=>$wxid)).'">修改</a> |';
                    $list .= '<a class="js-ajax-delete" href="'.U('Reply/kwDel',array('id'=>$key,'wxid'=>$wxid)).'">删除</a>';
                    $list .= '</td></tr>';
                }
                */
            }
        }
        
        $this->assign('record', $data[$wxid]);
        $this->assign('wxid', $wxid);
        $this->assign('rows', $rows);
        $this->assign('kws', $kws);
    }
    
    /**
     * 微信公众号关键字回复设置修改界面显示
     */
    private function _showKw() {
        $wxid = I('get.wxid', 0, 'intval');
        $id   = I('get.id', 0, 'intval') - 1;
        if (!$wxid) { $this->redirect('Reply/kw'); }
        
        $row = D('Common/WeixinReplyset')->field('keyword_info')->where('wxid=' . $wxid . ' and sid=' . session('siteid'))->find();
        if (!$row || !$row['keyword_info']) { $this->error('没有对应的规则，请先添加', U('Reply/kw'), 2); }
        $kws = json_decode($row['keyword_info'], 1);
        //print_r($kws);exit(__LINE__);
        if(!isset($kws[$id])) { $this->error('没有对应的规则，请先添加', U('Reply/kw'), 2); }
        
        $ary = $kws[$id]['reply_list_info'];
        $new = array();
        
        foreach($ary as $reply) {
            if($reply['type'] == 'text') {
                $new[] = $reply['content'];
            }
        }
        
        $kws[$id]['reply_list_info'] = $new;
        $this->assign('wxid', $wxid);
        $this->assign('kws', $kws[$id]);
        $this->assign('id', $id+1);
//        print_r($kws[$id]);
    }
}
