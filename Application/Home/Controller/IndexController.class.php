<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class IndexController extends GlobalController {
	/**
	 * 初始化
	 */
	public $error_msg;
	function _initialize()
	{
		parent::_initialize();
		$course_data = parent::getCourse();
	}
    public function index(){
    	$gz_shijuan = $this->getShijuan();
		$this->assign('gz_shijuan',$gz_shijuan);
		if(!empty($_SESSION['user_id'])){
			$user_data = $this->getUserInfo();
			$this->assign('user_data',$user_data);
		}
    	//SEO
		$this->setMetaTitle('首页'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword(''.C('TITLE_SUFFIX'));
		$this->setMetaDescription(''.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','home.css'));
		$this->addJs(array('js/banner.js','js/xf.js'));
        $this->display();
	}
	public function getShijuan(){
		$shijuanModel = M('tiku_source');
		$courseModel = M('tiku_course');
		$new_data = array();
		$courseData = $courseModel->where("course_type=1 AND status=1")->select();
		for($i=3;$i>=1;$i--){
			foreach($courseData as $val){
				$data[$i][$val['id']] = $shijuanModel->field("id,source_name,update_time")->where("grade=$i AND course_id=".$val['id'])->order("id desc")->limit(5)->select();
			}
		}
		//var_dump($data);
		return $data;
	}
	public function login(){
		$user = I('post.username');
		if(preg_match('/1\d{10}/',$user)){
			$tel = $user;
		}elseif(preg_match('/\S+@\w+\.\w+/i',$user)){
			$email = $user;
		}else{
			$error_msg = "非法邮箱或手机号码!";
			$this->assign('error_msg',$error_msg);
			$this->display('index');
			return false;
		}
		$password = I('post.password');
		$Model = M('User');
		$result = $Model->where("email='$email' OR telphone='$tel'")->find();
		//echo $Model->getLastSql();exit;
		//var_dump($result);exit;
		if(!$result){
			$error_msg = "用户名不存在!";
			$this->assign('error_msg',$error_msg);
			$this->display('index');
			return false;
		}
		if(md5(md5($password.$result['salt']))!=$result['password']){
			$error_msg = "密码不对!";
			$this->assign('error_msg',$error_msg);
			$this->display('index');
			return false;
		}
		//echo $Model->getLastSql();exit;
		if($error_msg==''){
			$_SESSION['nick_name'] = $result['nick_name'];
			$_SESSION['user_id'] = $result['id'];
			$_SESSION['user_type'] = $result['type'];
			if(I('post.auto_login')){
				setcookie('user_name',$user,time()+C('COOKIE_EXPIRE'),'/');
				setcookie('password',$password,time()+C('COOKIE_EXPIRE'),'/');
			}
			redirect('/');
		}
	}
}