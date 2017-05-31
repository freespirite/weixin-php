<?php
/**
 * Msg(消息管理)
 */
namespace Weixin\Controller;

class MsgController extends BaseController {

    
    protected $weixinMsg;
    //protected $auth_rule_model;

    public function _initialize() {
        parent::_initialize();
        
        //$this->weixinMsg = D("Common/WeixinMsg");
        
        //$this->auth_rule_model = D("Common/AuthRule");
    }

    /**
     * 公众号列表
     */
    public function index() {
    	$this->_lists();
		$this->display();
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
        
        $rows = $this->weixinConf->where(array('sid'=> session('siteid')))->select();
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
        
        $this->assign('wxid', $wxid);
        $this->assign('confs', $confs);
		$html = '';
        $token = '';
        if($wxid) {
            $obj  = new \Common\Lib\Weixin\Base($confs[$wxid]['key'], $confs[$wxid]['secret'], $confs[$wxid]['id'], session('siteid'));
            $token = $obj->getToken(FALSE);
            if(!$token) {
                $html .= '<tr id="node-'.$row['id'].'">'.$obj->getErrorInfo().'<td></td></tr>';
                $this->assign('page', '');
                $this->assign('lists', $html);
                return;
            }
        }
        $timeOut = 259200;
        $timeNow = time();
        foreach($msgs as $row) {
            $show = ($timeNow - $row['create_time']) > $timeOut? FALSE: TRUE;
            $html .= '<tr id="node-'.$row['id'].'">
                        <td rowspan="2" width="50"><img src="'.$row['headimgurl'].'" style="width:48px;height:48px;cursor: pointer;" onclick="parent.image_preview_dialog(this.src);"></td>
                        <td>'.$row['nickname'].'</td>
                        <td>'.(isset($confs[$row['wxid']])? $confs[$row['wxid']]['name']: '').'</td>
                        <td>'.date('Y-m-d H:i:s', $row['create_time']).'</td>
                        <td>回复</td></tr>
                       <tr><td colspan="4">'.$this->_getContent($row['content'], $row['msg_type'], $token, $show).'</td></tr>';
            
        }
		
		$this->assign('page', $page->show('Admin'));
		$this->assign('lists', $html);
	}
    
    private function _getContent($json, $type, $token, $show) {
        if(!$json) { return ''; }
        $ary = json_decode($json, 1);
        $str = '';
        switch ($type) {
            case 1:
                $str = $ary['Content'];
                break;
            case 2:
                if($show) {
                    $img = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$ary['MediaId'];
                    $str = '<img src="'.$img.'" style="height:80px;cursor: pointer;"  onclick="parent.image_preview_dialog(this.src);">';
                } else {
                    $str = '【该图片信息已经过期】';
                }
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
