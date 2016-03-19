<?php
/**
 * Description of MaterialController
 *
 * @author Administrator
 */
namespace Admin\Controller;


class MaterialController extends BaseController {
    
    public function index()
    {
        //gallery
        $this->assign('pageSet', 'gallery');
        $this->display();
    }
}
