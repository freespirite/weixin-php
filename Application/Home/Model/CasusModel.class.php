<?php
/**
 * Description of CasusModel
 * 案例信息数据表模型
 * @author Nick tan
 */

namespace Home\Model;
use Think\Model;

class CasusModel extends Model {
    
    protected $tableName = 'casus';
    
    protected $_validate =  array(
        array('title', 'require', '案例名称不能为空！'),
        array('pic', 'require', '案例效果图不能为空！'),
        array('disable', array(0, 1), '状态值的范围不正确！', 2, 'in'), 
    );
    
    protected $_auto        =    array(
        array('createtime','getCurtime',self::MODEL_INSERT,'callback'),
    );

    private function getCurtime() {
        return date('Y-m-d H:i:s');
    }
    
    /*
     * 平台登录
     * @param $account string 登录账号
     * @param $pwd     string 密码
     * return array
     */
    public function dataCount() {
        $row = $this->field('sum(investment_total) as investment_total,sum(interest) as interest,sum(borrow_success) as borrow_success,sum(borrow_total) as borrow_total,count(pid) as platforms')->find();
        $rs = $this->table(C('DB_PREFIX').'user_info')->field('count(*) as countuser')->find();
        $row['users'] = $rs['countuser'];
        return $row;
    }
    

    /*
     * 添加新案例
     * return boolean
     */
    public function addNew($data) {
        $pid = $this->add($data);
        if($pid) { return TRUE; }
        return FALSE;
    }
    

    /*
     * 返回平台列表
     * @param $page int 当前分页数
     * @param $size int 每页显示数
     * return array
     */
    public function getList($page =1, $size=10, $where = '')
    {
        return $this->field('*')
                ->where($where)
                ->page($page,$size)->order('ord desc')
                ->select();
        //return $this->page($page,$size)->order('pid desc')->select();
    }
    
    
    /*
     * 返回当前平台信息
     * @param $pid int 当前平台的id
     * return array
     */
    public function getOne($id)
    {
        return $this->where('id='.$id)->find();
    }
    
    /*
     * 更新平台信息
     * @param $pid int 当前平台的id
     * return array
     */
    public function update($save, $id)
    {
        return $this->where('id='.$id)->save($save);
    }
    
    /*
     * 返回平台统计数
     * @param $status int 当前统计数
     * return int
     */
    public function getCount($status='')
    {
        $where = in_array($status, array(0 ,1))? array('status' => $status): array();
        return $this->where($where)->count();
    }
    
    /*
     * 返回平台密码
     * @param $pwd string 登录密码
     * return string
     */
//    public function createPwd($pwd) {
//        return md5(C('APP_KEY').$pwd);
//    }
}
