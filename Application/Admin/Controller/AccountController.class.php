<?php
/**
 * Description of AccountController
 *
 * @author Administrator
 */
namespace Admin\Controller;
use Admin\Model\UsersModel;

class AccountController extends BaseController {
    
    public function index() {
        //echo CONTROLLER_NAME.' > '.ACTION_NAME;exit;
        $this->assign('pageSet', 'forms');
        $this->display();
    }
}
