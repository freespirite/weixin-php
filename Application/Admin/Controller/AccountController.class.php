<?php
/**
 * Description of AccountController
 *
 * @author Administrator
 */
namespace Admin\Controller;
use Admin\Model\Usermpset;

class AccountController extends BaseController {
    
    public function index() {
        if (!IS_POST){
            $this->assign('pageSet', 'wizards_validations');
            $this->display();
            return;
        }
        $data['appid'] = trim(I('post.appid'));
        $data['appsecret'] = trim(I('post.appsecret'));
        $rs = D('mp_set')->add($data);
        if($rs) {
            exit('success');
        }
        else {
            exit('error');
        }
    }
}
