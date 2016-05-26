<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class QqconnectController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
	}

	public function login(){
		require_once $_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/qqConnect/API/qqConnectAPI.php';
		$qc = new \QC();
		$qc->qq_login();
	}
	public function callback(){
		require_once $_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/qqConnect/API/qqConnectAPI.php';
		$qc = new \QC();
		$access_token = $qc->qq_callback();
		$openid = $qc->get_openid();
		$qc = new \QC($access_token, $openid);
		$weiboArr = array(
			'content' => C('WEIBO_CONTENT'),
			'pic' => C('WEIBO_PIC')
		);
		$return = $qc->add_pic_t($weiboArr);
		$Model = M('qqconnect_ext');
		$userModel = M('user');
		$result = $Model->where("openid='".$openid."'")->find();
		if(!$result){
			
			$info = $qc->get_user_info();
			$data['openid'] = $openid;
			$data['access_token'] = $access_token;
			$data['update_time'] = time();
			$data['nickname'] = $info['nickname'];
			$data['gender'] = $info['gender'];
			$data['photo'] = $info['figureurl_qq_2'];//100*100
			$Model->startTrans();
			$qqId = $Model->add($data);
			$photo = $this->downFile($info['figureurl_qq_2'], 'qq');
			$userData['nick_name'] = trim($info['nickname']).'_qq'.$qqId;
			$userData['login_type'] = 'qq';
			$userData['photo'] = $photo;
			$userData['create_time'] = time();
			$userData['last_login'] = time();
			$userData['login_ip'] = get_client_ip();
			$userData['qq_id'] = $qqId;
			//var_dump($userData);exit;
			$userId = $userModel->add($userData);
			if($qqId && $userId){
				$Model->commit();
				$_SESSION['_user_id'] = $qqId;//用于选择老师或学生时用，选择后销毁
				$_SESSION['open_login'] = 'qq';
				redirect('/qqconnect/seltype');
			}else{
				$Model->rollback();
			}
		}else{
			$user = $userModel->where("qq_id=".$result['id'])->find();
			if($user){
				if($user['type']==0){
					$_SESSION['_user_id'] = $result['id'];//用于选择老师或学生时用，选择后销毁
					$_SESSION['open_login'] = 'qq';
					redirect('/qqconnect/seltype');
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