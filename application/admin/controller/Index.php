<?php

namespace app\admin\controller;


use think\Controller;
use think\Request;
use think\Db;
use think\Cookie;
use think\Session;

//引入Admin模型
use app\admin\model\Admin as AdminModel;

class index extends Controller
{
    /**
     * 商铺的整体设置
     * 定义如下：
     * ->
     */
    public $settings;

    /**
     * Auth:全局数组变量，定义主界面左边栏菜单的子项显示
     * 根据登录用户的权限不同，对应子项的值为0或1
     * 该值存储与数据库weshop_admin表的admin_auth列中
     * 若对应值定义为1，即显示对应菜单按钮，为0则不显示
     * 子项定义如下：
     * stat:报表中心            交易报表显示
     * orde:订单管理
     * prod:商品管理
     * gmes:营销中心、消息群发   用于微信的短信群发功能
     * user:用户管理            管理关注本公众号的用户
     * comp:代理分销            代理功能
     * sett:店铺设置、系统维护   设置
     */
    public $Auth = array(
        'stat' => 0,
        'orde' => 0,
        'prod' => 0,
        'gmes' => 0,
        'user' => 0,
        'comp' => 0,
        'sett' => 0
    );

    /**
     * 登录页面入口方法
     *
     */
    public function login(){
        //从数据库获取店铺配置信息
        //TODO
        //$query = Db::name('settings')
        //    ->select();
        //处理query
        //$this->settings = array();
        //foreach ($query as $item){
        //    $this->settings[$item['key']] = $item['value'];
        //}
        //渲染模版
        //模版文件位于view/index/login
        $this->assign('settings', $this->settings);
        return $this->fetch();
    }

    /**
     * 登录处理
     * 检查输入用户名密码是否合法
     * 从login.js跳转至此，从post中获取用户输入的用户名及密码
     */
    public function checkLogin(){
        session_start();

        //从post中获取用户输入的用户名、密码
        //admin_acc:用户输入的用户名
        //admin_pwd:用户输入的密码
        $admin_acc = addslashes(trim(input('post.admin_acc')));
        $admin_pwd = addslashes(trim(input('post.admin_pwd')));
        //保存到Cookie
        Cookie::set('admin_acc', $admin_acc);
        //从数据库中获取用户输入的用户名对应的管理员信息
        $admininfo = AdminModel::get($admin_acc);

        //写入用户的登录记录存储到数据库中
        $IP = request()->ip();  //登录IP
        $time = date("Y-m-d H:i:s");    //登录时间
        Db::name('admin_login_records')
            ->insert(['account' => $admin_acc, 'ip' => $IP, 'ldate' => $time]);

        //校验登录状态
        if ($admininfo){
            //在数据库找到了名称，下面开始校验密码
            //校验密码
            //TODO 使用SHA加密后的密码进行校验
            if ($this->pwdCheck((string)$admininfo['admin_password'], (string)$admin_pwd)){
                //将最后一次登录时间写入admin表中
                $admininfo->admin_last_login = $time;
                $admininfo->admin_ip_address = $IP;
                $admininfo->save();
                //权限密钥，存放到session中，只有拥有该密钥才能进入主界面
                $loginKey = $IP;
                //写入数据到session
                Session::set('loginKey', $loginKey);
                //下放管理员权限表
                Cookie::set('loginKey', $loginKey);
                Cookie::set('adid', $admininfo['id']);
                Cookie::set('adname', $admininfo['admin_name']);
                Cookie::set('auth', $admininfo['admin_auth']);
                //回调到js，允许登录
                $status = array('status' => 1);
                echo json_encode($status, JSON_UNESCAPED_UNICODE);
            }
            else{
                $status = array('status' => 0);
                echo json_encode($status, JSON_UNESCAPED_UNICODE);
            }
        }
        Cookie::delete('admin_acc');
    }

    /**
     * 退出登录处理
     */
    public function logout(){
        //清空session及cookie
        Cookie::clear('');
        Session::clear();
        $this->redirect('index/login');
    }


    /**
     * 主界面
     *
     * @return \think\Response
     */
    public function index()
    {
        if (!AdminModel::checkAdminAuth()){
            $this->redirect('index/logout');
        }

        if (Cookie::has('loginKey')){
            //渲染模版
            //模版文件位于view/index/login
            $this->assign('settings', $this->settings);

            //trace($this->settings);
            //设置主界面左边栏的菜单显示
            $temp = 'stat';
            foreach ($this->Auth as $key=>$item){
                //if ($key = $temp)
                    $this->Auth[$key] = 1;
            }
            $this->assign('Auth', $this->Auth);
            $this->assign('today', date("n月j号"));
            return $this->fetch();
        }
    }

    /**
     * 生成admin加密密文
     * admin的密文采用password+admin_salt组合使用sha384加密
     * admin_salt存储在\admin\config中
     * @global type $config
     * @param type $pwd
     * @return type
     */
    function encryptPassword($pwd) {
        $admin_salt = config('admin_salt');
        return hash('sha384', $pwd . $admin_salt);
    }

    /**
     * 校验登陆提交密码
     * @param string $pwd_db 数据库中存储的密码
     * @param string $pwd_submit 用户登录时提交的密码
     * @return boolean
     */
    function pwdCheck($pwd_db, $pwd_submit) {
        if ($pwd_db == $this->encryptPassword($pwd_submit)){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 获取店铺的全局设置
     */
    function initSettings(){

    }

    /**
     * 初始化函数
     * 新建本控制器实例时调用该方法
     * 本方法实现的功能：从数据库中调取店铺的全局设置
     */
    public function _initialize()
    {
        parent::_initialize();
        //获取店铺全局设置
        //从数据库获取店铺配置信息
        $query = Db::name('settings')
            ->select();
        //处理query
        $this->settings = array();
        foreach ($query as $item){
            $this->settings[$item['key']] = $item['value'];
        }
        //传入参数
        $this->assign('docroot', config('config.docroot'));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
