<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class WeixinconnectController extends GlobalController {
	/**
	 * 初始化
	 */
	public $appId;
	public $appSecret;
	function _initialize()
	{
		$this->appId = 'wxcfc52333ca07035a';
		$this->appSecret = 'bb8d40f5fc2ab93701811f23a50eff33';
		parent::_initialize();
	}

	public function login(){
		$redirect_uri = urlencode("http://www.haxueku.com/weixinconnect/callback");
		$_SESSION['state'] = uniqid();
		$url = 'https://open.weixin.qq.com/connect/qrconnect?appid='.$this->appId.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_login&state='.$_SESSION['state'].'#wechat_redirect';
		redirect($url);
	}
	public function callback(){
		if(($_GET['state'] != $_SESSION['state']) || empty($_GET['code'])){
			redirect('/');
		}
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appId."&secret=".$this->appSecret."&code=".$_GET['code']."&grant_type=authorization_code";
		$result = file_get_contents($url);
		$token = json_decode($result,true);
		
		
		//var_dump($userInfo);
		$Model = M('weixinconnect_ext');
		$userModel = M('user');
		$result = $Model->where("unionid='".$token['unionid']."'")->find();
		if(!$result){
			$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token['access_token'].'&openid='.$token['openid'];
			$userInfo = file_get_contents($url);
			$userInfo = json_decode($userInfo,true);
			$data['access_token'] = $token['access_token'];
			$data['unionid'] = $token['unionid'];
			$data['update_time'] = time();
			$data['nickname'] = $userInfo['nickname'];
			$data['sex'] = $userInfo['sex'];
			$data['sex'] = $userInfo['openid'];
			$data['province'] = $userInfo['province'];
			$data['city'] = $userInfo['city'];
			$data['photo'] = $userInfo['headimgurl'];//180*180
			$Model->startTrans();
			$weixinId = $Model->add($data);
			$photo = $this->downFile(substr($userInfo['headimgurl'],0,strrpos($userInfo['headimgurl'],'0')).'132', 'weixin');
			$userData['nick_name'] = trim($userInfo['nickname']).'_wx'.$weixinId;
			$userData['photo'] = $photo;
			$userData['create_time'] = time();
			$userData['last_login'] = time();
			$userData['login_ip'] = get_client_ip();
			$userData['login_type'] = 'wx';
			$userData['sina_id'] = $weixinId;
			//var_dump($userData);exit;
			$userId = $userModel->add($userData);
			if($weixinId && $userId){
				$Model->commit();
				$_SESSION['_user_id'] = $userId;//用于选择老师或学生时用，选择后销毁
				redirect('/weixinconnect/seltype');
			}else{
				$Model->rollback();
			}
		}else{
			$user = $userModel->where("wx_id=".$result['id'])->find();
			if($user){
				if($user['type']==0){
					$_SESSION['_user_id'] = $result['id'];//用于选择老师或学生时用，选择后销毁
					redirect('/weixinconnect/seltype');
				}else{
					$_SESSION['nick_name'] = $user['nick_name'];
					$_SESSION['user_type'] = $user['type'];
					$_SESSION['user_id'] = $user['id'];
					redirect('/member/index');
				}
			}else{
				redirect('/');
			}
			
		}
	}
	
	public function selType(){
		if(!isset($_SESSION['_user_id'])){
			redirect('/');
		}
		$this->display();
	}
	public function downFile($file_path,$login_type)
	{	
		if(!file_exists('Public/photo/'.$login_type)){
			mkdir('Public/photo/'.$login_type);
		}
		if(!file_exists('Public/photo/'.$login_type.'/'.date('m'))){
			mkdir('Public/photo/'.$login_type.'/'.date('m'));
		}

		$filename = uniqid().'.jpg';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $file_path);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$stream = curl_exec($ch);
		$new_file = 'Public/photo/'.$login_type.'/'.date('m').'/'.$filename;
		$handle = @fopen($new_file, 'w');
		fwrite($handle, $stream);
		curl_close($ch);
		fclose($handle);
		return '/'.$new_file;
	}
	/**
	 * 发送带图片的微博
	 */
	public function add_pic_t(){
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, "https://graph.qq.com/t/add_pic_t");
		// curl_setopt($ch, CURLOPT_POSTFIELDS, array('content'=>C('WEIBO_CONTENT'),'pic'=>C('WEIBO_PIC')));
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		// $data = curl_exec($ch);
		// curl_close($ch);
		// $data = json_decode($data,true);
		// var_dump($data);exit;
	}
}