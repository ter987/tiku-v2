<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class ExamController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
		$course_data = parent::getCourse();
		$this->assign('course_data',$course_data);
	}
    public function index(){
        $this->display();
	}
	public function ceping(){
		$id = I('get.id');
		if(empty($id)){//404
			redirect('/');
		}
		
		$cepingModel = M('ceping');
		$data = $cepingModel->field("ceping.*,tiku_course.course_name,tiku_course.course_type")
		->join('`ceping_jon` ON `ceping`.id=`ceping_jon`.`ceping_id`')
		->join("tiku_course ON tiku_course.id=ceping.course_id")
		->where("ceping_jon.student=".$_SESSION['user_id']." AND ceping.type=1 AND ceping.id=$id ")->find();
		//echo $joinModel->getLastSql();
		if(!$data){//404
			redirect('/');
		}
		//更改开始时间
		$jonModel = M('ceping_jon');
		$time = time();
		$jonModel->where("ceping_id=$id AND student=".$_SESSION['user_id']." AND start_time IS NULL")->save(array('start_time'=>$time));
		
		$_SESSION['my_ceping'] = $id;
		$shiti = json_decode($data['shiti'],true);
		
		$o = 1;
		foreach($shiti as $key=> $val){
			$shiti[$key]['childs'] = $this->_getTikuInfo($val['childs'],$o);
			$type_arr[]['id'] = $val['type_id'];
			$type_arr[]['type_name'] = $val['type_name'];
		}
		//var_dump($shiti);
		$this->assign('shiti',$shiti);
		$this->assign('type_arr',$type_arr);
		$this->assign('data',$data);
		$this->setMetaTitle('在线测评'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
	}
	public function zuoye(){
		
	}
	public function ajaxAnswer(){
		$shiti_id = I('get.shiti_id');
		$answer = I('get.val');
		if(empty($shiti_id)  || empty($_SESSION['my_ceping'])){
			$this->ajaxReturn(array('status'=>'error','msg'=>'服务器出错'));
		}else{
			$Model = M('ceping_answer');
			if($result = $Model->where("ceping_id=".$_SESSION['my_ceping']." AND tiku_id=".$shiti_id." AND student=".$_SESSION['user_id'])->find()){
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
				$data['ceping_id'] = $_SESSION['my_ceping'];
				$data['student'] = $_SESSION['user_id'];
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
		$jonModel = M('ceping_jon');
		$time = time();
		$id = $_SESSION['my_ceping'];
		$result = $jonModel->where("ceping_id=$id AND student=".$_SESSION['user_id']." AND end_time IS NULL")->find();
		if($result){
			$jonModel->startTrans();
			$result_1 = $jonModel->where("id=".$result['id'])->save(array('end_time'=>$time));
			$cepingModel = M('ceping');
			$result_2 = $cepingModel->where("id=$id")->setDec('unjoined_num',1);
			if($result_1 && $result_2){
				$jonModel->commit();
				$this->ajaxReturn(array('status'=>'ok'));
			}else{
				$jonModel->rollback();
				$this->ajaxReturn(array('status'=>'error','msg'=>'提交失败，请重试！'));
			}
		}else{
			$this->ajaxReturn(array('status'=>'error','msg'=>'不可重复交卷！'));
		}
	}
	public function _getTikuInfo($id_arr,&$o){
		$Model = M('tiku');
		$answerModel = M('ceping_answer');
		foreach($id_arr as $key=>$val){
			$rs = $Model->field("id,content,options,answer,analysis")->where("id=$val")->find();
			$result = $answerModel->where("ceping_id=".$_SESSION['my_ceping']." AND tiku_id=".$rs['id']." AND student=".$_SESSION['user_id'])->find();
			
			if($result){
				//echo $answerModel->getLastSql();exit;
				$rs['s_answer'] = $result['s_answer'];
			}else{
				$rs['s_answer'] = '';
			}
			$rs['order_char'] = $o;
			$tiku[] = $rs;
			$o++;
		}
		return $tiku;
	}
}