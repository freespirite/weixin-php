<?php
/**
 * 公众号管理
 */
namespace Weixin\Controller;

class IndexController extends BaseController {

    public function _initialize() {
        parent::_initialize();
        
        //$this->weixin_msg = D("Common/WeixinMsg");
        
        //$this->auth_rule_model = D("Common/AuthRule");
    }

    /**
     * 公众号列表
     */
    public function index() {
    	//session('admin_weixin_index','Index/index');
//        $result = $this->weixinConf->where(array('uid'=> session('ADMIN_ID')))->select();
        $pass = array(
            '-1' => '<font color="red">未接通</font>',
            '0'  => '未认证',
            '1'  => '通过认证'
        );
        $result = $this->weixinConf->where(array('sid' => session('siteid')))->select();
        $lists  = '';
        foreach ($result as $r) {
            $href   = '<a href="'.U('Index/edit', array('id'=> $r['id'])).'">编辑</a> '
                    . '| <a class="js-ajax-delete" href="'.U('Index/delete', array('id'=> $r['id'])).'">删除</a> ';
            if($r['pass'] > -1) {
                $href .= '| <a class="js-ajax-dialog-btn" data-msg="将导入此公众号用户和自动回复配置<br/>如果已经存在用户或自动回复配置，将被覆盖！" href="'.U('Index/import', array('id'=> $r['id'])).'">导入配置</a> ';;
            }
            $lists .= '<tr id="node-'.$r['id'].'">';
            if($r['pic1']) {
                $lists .= '<td><img src="'.sp_get_image_preview_url($r['pic1']).'" style="height:36px;width:36px;cursor:pointer;" onclick="parent.image_preview_dialog(this.src);"></td>';
            } else {
                $lists .= '<td>&nbsp;</td>';
            }
            $lists  .= '<td>'.$r['name'].'</td>
                        <td>'.$this->level[$r['level']].'</td>
                        <td>'.$pass[$r['pass']].'</td>
                        <td>'.C('WEIXIN.SERVER_URL').$r['code'].'</td>
                        <td>'.$href.'</td>
                       </tr>';
        }
        $this->assign('lists', $lists);
        $this->display();
    }

    /**
     * 公众号添加
     */
    public function add() {
    	$select_categorys = '';
        foreach ($this->level as $k => $v) {
            $select_categorys .= '<option value="'.$k.'">'.$v.'</option>';
        }
        $code = md5(session('ADMIN_ID').time().mt_rand(1000, 9999));
        $this->assign('code', $code);
        $this->assign('select_categorys', $select_categorys);
    	$this->display();
    }
    
    /**
     * 保存新增公众号
     */
    public function addPost() {
    	if (IS_POST) {
    		if ($this->weixinConf->create()!==false) {
    			if ($this->weixinConf->add()!==false) {
    				$this->success("新增公众号成功！", U('Index/index'));
    			} else {
    				$this->error("新增公众号失败！");
    			}
    		} else {
    			$this->error($this->weixinConf->getError());
    		}
    	}
    }

    /**
     * 删除公众号
     */
    public function delete() {
        $id = I('get.id',0,'intval');
        $count = D("Common/WeixinMsg")->where(array("wxid" => $id))->count();
        if ($count > 0) {
            $this->error('该公众号下已有内容，不能删除！');
        }
        if ($this->weixinConf->delete($id)!==false) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 编辑公众号
     */
    public function edit() {
        $id = I('get.id',0,'intval');
        $select_categorys = '';
        $row = $this->weixinConf->where(array('id' => $id, 'uid' => session('ADMIN_ID')))->find();
        if(!$row) {
            $this->error("数据不存在！", U('Index/index'), 3);
        }
        foreach ($this->level as $k => $v) {
            $selected = $row['level'] == $k? ' selected': '';
            $select_categorys .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
        }
        
        $this->assign('select_categorys', $select_categorys);
        $this->assign('data', $row);
        $this->display();
    }
    
    /**
     * 保存编辑公众号
     */
    public function editPost() {
    	if (IS_POST) {
    	    $id = I('post.id',0,'intval');
    	    $row = $this->weixinConf->where(array('id' => $id, 'uid' => session('ADMIN_ID')))->find();
            if(!$row) {
                $this->error('操作失败！');
            }
            
    		if ($this->weixinConf->create() ===false) {
                $this->error($this->weixinConf->getError());
            }
            
            if ($this->weixinConf->save() !== false) {
                $this->success('更新成功！', U('Index/index'));
            } else {
                $this->error('更新失败！');
            }
        }
    }
    
    /**
     * 导入公众号用户和自动回复信息配置
     */
    public function import() {
        $id  = I('get.id',0,'intval');
        $row = $this->weixinConf->where(array('id' => $id, 'sid' => session('siteid')))->find();
        if(!$row || $row['pass'] < 0) {
            $this->error('微信账号数据不存在！');
        }
        
        /*自动回复信息设置导入*/
        $msg  = new \Common\Lib\Weixin\Msg($row['key'], $row['secret'], $row['id'], session('siteid'));
        $rs    = $msg->importReply();
        if(!$rs) {
            $this->error('导入自动回复数据失败: '.$this->getError());
        }
        
        /*用户信息导入*/
        if($row['pass'] < 1) {
            $this->success("导入成功！");
        }
        $user  = new \Common\Lib\Weixin\User($row['key'], $row['secret'], $row['id']);
        $rs    = $user->importUsers();
        if(!$rs) {
            $this->error('导入用户数据失败: '.$user->getErrorInfo());
        }
        
        //更新公众号认证状态
        //$user->checkAuthorized();
        
        $this->success("导入成功！");
    }
    
    
    public function demo() {
        $id = I('get.id', 0, 'intval');
        $id = $id? $id: 1;
        $result = $this->weixinConf->where(array('sid' => session('siteid')))->select();
        foreach ($result as $r) {

            if($r['id']==$id) {
                print_r($r);echo "\n\n";
                $o = new \Common\Lib\Weixin\Msg($r['key'], $r['secret'], $r['id']);
                $token = $o->getToken();
                $url = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.$token;//获取菜单信息
                $url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token='.$token;//获取素材总数
                $openid = 'oU29Qt3yFNjvJuxWMdj5r2_IvvsI';
                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';//获取用户个人信息
                $con = toRequest($url);
                $ary = json_decode($con, 1);
                print_r($ary);
                exit('<br/>'.$url);
            }
        }
    }
    
    public function demo2() {
        $id  = I('get.id', 0, 'intval');
        $id  = $id? $id: 1;
        $row = $this->weixinConf->where(array('id'=>$id, 'sid' => session('siteid')))->find();
        if(!$row) { exit('error: '.time()); }
        $o = new \Common\Lib\Weixin\Material($row['key'], $row['secret'], $row['id']);
//        $con = $o->getImagesList();
        $ary = $o->getMaterial('voice');
//        $ary = json_decode($con, 1);
        print_r($ary);
        exit('<br/>' . date('Y-m-d H:i:s'));
    }
    
}
