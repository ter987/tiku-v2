<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class SinaconnectController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
	}

	public function login(){
		require_once $_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/sinaConnect/config.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/sinaConnect/saetv2.ex.class.php';
		$o = new \SaeTOAuthV2( WB_AKEY , WB_SKEY );
		$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
		redirect($code_url);
	}
	public function callback(){
		require_once $_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/sinaConnect/config.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/sinaConnect/saetv2.ex.class.php';
		$o = new \SaeTOAuthV2( WB_AKEY , WB_SKEY );
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $o->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}
		$c = new \SaeTClientV2( WB_AKEY , WB_SKEY , $token['access_token'] );
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$Model = M('sinaconnect_ext');
		$userModel = M('user');
		$result = $Model->where("uid=$uid")->find();
		if(!$result){
			$info = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
			$data['access_token'] = $token['access_token'];
			$data['uid'] = $uid;
			$data['update_time'] = time();
			$data['nickname'] = $info['screen_name'];
			$data['gender'] = $info['gender'];
			$data['location'] = $info['location'];
			$data['photo'] = $info['avatar_hd'];//180*180
			$Model->startTrans();
			$sinaId = $Model->add($data);
			$photo = $this->downFile($info['avatar_hd'], 'sina');
			$userData['nick_name'] = trim($info['screen_name']).'_sina'.$sinaId;
			$userData['photo'] = $photo;
			$userData['create_time'] = time();
			$userData['last_login'] = time();
			$userData['login_ip'] = get_client_ip();
			$userData['login_type'] = 'sina';
			$userData['sina_id'] = $sinaId;
			//var_dump($userData);exit;
			$userId = $userModel->add($userData);
			if($sinaId && $userId){
				$Model->commit();
				$_SESSION['_user_id'] = $userId;//用于选择老师或学生时用，选择后销毁
				$_SESSION['open_login'] = 'sina';
				redirect('/sinaconnect/seltype');
			}else{
				$Model->rollback();
			}
		}else{
			$user = $userModel->where("sina_id=".$result['id'])->find();
			if($user){
				if($user['type']==0){
					$_SESSION['_user_id'] = $result['id'];//用于选择老师或学生时用，选择后销毁
					$_SESSION['open_login'] = 'sina';
					redirect('/sinaconnect/seltype');
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
		$Member = A('Member');
		$Member->selType();
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