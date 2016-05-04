<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class LianxiController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
	}
	public function index(){
		redirect('/smart/?for=lx');
	}
	public function start(){
		$id = I('get.id');
		$Model = M('lianxi');
		$data = $Model->where("id=$id AND user_id=".$_SESSION['user_id'])->find();
		if(!$data){
			redirect('/');
		}
		$_SESSION['my_lianxi'] = $id;
		$course = $this->getCourseById($data['course_id']);
		$this->assign('course',$course);
		$shiti = json_decode($data['shiti'],true);
		$o = 1;
		foreach($shiti as $key=> $val){
			$shiti[$key]['childs'] = $this->_getTikuInfo($val['childs']);
			$type_arr[]['id'] = $val['type_id'];
			$type_arr[]['type_name'] = $val['type_name'];
		}
		$this->assign('type_arr',$type_arr);
		$this->assign('shiti',$shiti);
		$this->assign('data',$data);
		$this->setMetaTitle('在线练习'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->assign('this_module','lianxi');
		$this->display();
	}
	public function ajaxAnswer(){
		$shiti_id = I('get.shiti_id');
		$answer = I('get.val');
		if(empty($shiti_id)  || empty($_SESSION['my_lianxi'])){
			$this->ajaxReturn(array('status'=>'error','msg'=>'服务器出错'));
		}else{
			$Model = M('lianxi_answer');
			if($result = $Model->where("lianxi_id=".$_SESSION['my_lianxi']." AND tiku_id=".$shiti_id." AND user_id=".$_SESSION['user_id'])->find()){
				if($answer == ''){
					if($Model->where("id=".$result['id'])->delete()){
						$this->ajaxReturn(array('status'=>'ok','action'=>'del'));
					}else{
						$this->ajaxReturn(array('status'=>'error','msg'=>'请重试'));
					}
				}else{
					if($Model->where("id=".$result['id'])->save(array('s_answer'=>$answer,'update_time'=>time()))){
						$this->ajaxReturn(array('status'=>'ok'));
					}else{
						$this->ajaxReturn(array('status'=>'error','msg'=>'请重试'));
					}
				}
			}else{
				$data['lianxi_id'] = $_SESSION['my_lianxi'];
				$data['user_id'] = $_SESSION['user_id'];
				$data['tiku_id'] = $shiti_id;
				$data['s_answer'] = $answer;
				$data['update_time'] = time();
				if($Model->add($data)){
					$this->ajaxReturn(array('status'=>'ok'));
				}else{
					$this->ajaxReturn(array('status'=>'error','msg'=>'请重试'));
				}
			}
		}
	}
	public function ajaxUpload(){
		$dir = 'Public/answer';
		$date = date('Ym');
		//var_dump($_FILES);exit;
		$shiti_id = I('post.shiti_id');
		if(empty($_FILES) || empty($shiti_id)){
			$this->ajaxReturn(array('status'=>'error','msg'=>'没有选择文件'));
		}
		$result = preg_match('/jpg|gif|png|bpm/i',$_FILES['myfile']['name'],$match);
		if(!$result){
			$this->ajaxReturn(array('status'=>'error','msg'=>'只允许上传图片'));
		}
		if($_FILES['myfile']['size']>C('MAX_FILE_LIMIT')){
			$this->ajaxReturn(array('status'=>'error','msg'=>'文件不得超过2M'));
		}
		if(!file_exists($dir)){
			mkdir($dir);
		}
		if(!file_exists($dir.'/'.$date)){
			mkdir($dir.'/'.$date);
		}
		$destination = $dir.'/'.$date.'/'.time().'.'.$match[0];
		if(move_uploaded_file($_FILES['myfile']['tmp_name'], $destination)){
			$this->ajaxReturn(array('status'=>'ok','msg'=>array('image'=>'[img:/'.$destination.']','shiti_id'=>$shiti_id)));
		}else{
			$this->ajaxReturn(array('status'=>'error','msg'=>'文件上传失败'));
		}
	}
	public function ajaxJiaojuan(){
		$Model = M('lianxi');
		$time = time();
		$id = $_SESSION['my_lianxi'];
		$result = $Model->where("id=$id AND user_id=".$_SESSION['user_id']." AND commit_time IS NULL")->find();
		if($result){
			$Model->startTrans();
			$result_1 = $Model->where("id=".$result['id'])->save(array('commit_time'=>$time));
			if($result_1){
				$Model->commit();
				$this->ajaxReturn(array('status'=>'ok'));
			}else{
				$Model->rollback();
				$this->ajaxReturn(array('status'=>'error','msg'=>'提交失败，请重试！'));
			}
		}else{
			$this->ajaxReturn(array('status'=>'error','msg'=>'不可重复交卷！'));
		}
	}
	public function add(){
		if(empty($_SESSION['cart'])){
    		redirect('/');
    	}
		if(!$this->getCourseById($_SESSION['course_id'])){
			redirect('/');
		}
		foreach ($_SESSION['cart'] as $key => $val) {
			if(!in_array($val['type_name'],$arr)){
				$arr[] = $val['type_name'];
			}
		
		}
		//var_dump($arr);exit;
		foreach($arr as $k=>$v){
			$count = 0;
			foreach($_SESSION['cart'] as $key=>$val){
				//if(empty($new_arr[$k]['childs'])) $new_arr[$k]['childs']=array();
				if($v==$val['type_name']){
					$new_arr[$k]['sort_weight'] = $this->_getTypeWeight($val['type_id']);
					$new_arr[$k]['type_name'] = $val['type_name'];
					$new_arr[$k]['type_id'] = $val['type_id'];
					$new_arr[$k]['childs'][] = $val['id'];
				}
				
			}
		}
		rsort($new_arr);
		
		$oa = array(0=>'一',1=>'二',2=>'三',3=>'四',4=>'五',5=>'六',6=>'七');
		$o = 1;
		foreach($new_arr as $key=>$val){
			$new_arr[$key]['order_char'] = $oa[$key];
			$child = array();
			foreach($val['childs'] as $k=>$v){
				$child[$k]['id'] = $v;
				$child[$k]['order_char'] = $o;
				$o++;
			}
			$new_arr[$key]['childs'] = $child;
		}
		//var_dump($new_arr);exit;
		$data['user_id'] = $_SESSION['user_id'];
		$course = $this->getCourseById($_SESSION['course_id']);
		$course_type = $course['course_type']==1?'高中':'初中';
		$data['title'] = $course_type.$course['course_name'].'在线练习-'.date("Ymd");
		$data['shiti_num'] = $o;
		$data['shiti'] = json_encode($new_arr);
		$data['create_time'] = time();
		$data['course_id'] = $_SESSION['course_id'];
		$Model = M('lianxi');
		$id = $Model->add($data);
		if($id){
			redirect('/lianxi/'.$id.'/');
		}else{
			redirect('/');
		}
		
	}
	protected function _getTypeWeight($id){
		$Model = M('tiku_type');
		$result = $Model->where("id=$id")->find();
		return $result['weight'];
	}
	public function _getTikuInfo($id_arr){
		$Model = M('tiku');
		$answerModel = M('lianxi_answer');
		foreach($id_arr as $key=>$val){
			$rs = $Model->field("id,content,options,answer,analysis")->where("id=".$val['id'])->find();
			$result = $answerModel->where("lianxi_id=".$_SESSION['my_lianxi']." AND tiku_id=".$rs['id']." AND user_id=".$_SESSION['user_id'])->find();
			
			if($result){
				$rs['s_answer'] = $result['s_answer'];
			}else{
				$rs['s_answer'] = '';
			}
			$rs['order_char'] = $val['order_char'];
			$tiku[$key] = $rs;
		}
		return $tiku;
	}
}
?>