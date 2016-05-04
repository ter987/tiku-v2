<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class SmartController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
	}
	public function index(){
		//var_dump($_SESSION['smart']);exit;
		unset($_SESSION['shijuan']);
		unset($_SESSION['smart']);
		unset($_SESSION['cart']);
		$_SESSION['difficulty'] = 3;
		//后期改成从用户信息中选取course
		if(empty($_SESSION['course_id'])){
			$courseModel = M('tiku_course');
			$courseData = $courseModel->find();
			$_SESSION['course_id'] = $courseData['id'];
			$_SESSION['course_type'] = $courseData['course_type'];
			$_SESSION['course_name'] = $courseData['course_name'];
		}
		$this->assign('this_course_type',$_SESSION['course_type']);
		$this->assign('this_course_id',$_SESSION['course_id']);
		$this->assign('this_course_name',$_SESSION['course_name']);
		$courseModel = M('tiku_course');
		$course_data  = $courseModel->where("id=".$_SESSION['course_id'])->find();
		$grade = $course_data['course_type']==1?'高中':'初中';
		$title = $grade.$course_data['course_name'].'在线测评('.date('Ymd').')';
		$this->assign('title',$title);
		$this->assign('current_course',$_SESSION['course_id']);
		//获取难度
		$diffData = $this->getTikuDifficulty();
		$this->assign('diff_data',$diffData);
		//获取题型
		$tiku_type = $this->getTikuType($_SESSION['course_id']);
		$this->assign('tiku_type',$tiku_type);
		
		if($_SESSION['smart_type']=='jc'){
			//获取版本
			$version_data = $this->getVersions($_SESSION['course_id']);
			$version_id = $version_data[0]['id'];
			//获取教材
			$book_data = $this->getBooks($version_id);
			$book_id = $book_data[0]['id'];
			//获取章节
			$chapter_data = $this->getChapters($book_id);
			$this->assign('chapter_data',$chapter_data);
			$this->assign('smart_type','jc');
		}else{
			$pointData = $this->getFirstAndSecondPoint($_SESSION['course_id']);
			$this->assign('smart_type','zsd');
			$this->assign('point_data',$pointData);
		}
		//var_dump($pointData);
		if($_GET['for']=='lx'){
			$this->assign('this_module','lianxi');
			$this->assign('this_name','开始练习');
			$this->setMetaTitle('在线练习'.C('TITLE_SUFFIX'));
		}else{
			$this->assign('this_module','smart');
			$this->assign('this_name','开始组卷');
			$this->setMetaTitle('智能组卷'.C('TITLE_SUFFIX'));
		}
		
		$this->addCss(array('xf.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->assign('jumpto','smart');
        $this->display();
	}
	public function ajaxChange(){
		$type = I('get.type');
		if(empty($type)){
			$this->ajaxReturn(array('status'=>'error'));
		}else{
			$_SESSION['smart_type'] = $type;
			$this->ajaxReturn(array('status'=>'ok'));
		}
		
	}
	public function start(){
		if(empty($_SESSION['smart'])){
			$this->ajaxReturn(array('status'=>'error','msg'=>'请选择知识点'));
		}
		$Model = M('tiku');
		$pointModel = M('tiku_point');
		$new_data = array();
		if($_SESSION['smart_type']=='jc'){
			foreach($_SESSION['smart'] as $key=>$val){
				foreach($val['type'] as $k=>$v){
					if($v['num']>0){
						$data = $Model->field("tiku.id,tiku_type.type_name,tiku.type_id")
						->join("tiku_type on tiku.type_id=tiku_type.id")
						->join("tiku_to_chapter on tiku_to_chapter.tiku_id=tiku.id")
						->where("tiku_to_chapter.chapter_id = $key AND tiku.difficulty_id=".$_SESSION['difficulty']." AND type_id=$k")->select();
						shuffle($data);
						if($data){
							$data = array_slice($data,0,$v['num']);
							$new_data = array_merge($new_data,$data);
						}
						
					}
				}
			}
		}else{
			foreach($_SESSION['smart'] as $key=>$val){
				foreach($val['type'] as $k=>$v){
					if($v['num']>0){
						$points = $pointModel->where("parent_id=$key")->select();
						if($points){
							foreach($points as $p){
								$pids .= $p['id'].',';
							}
						}
						$pids .= $key;
						$data = $Model->field("tiku.id,tiku_type.type_name,tiku.type_id")
						->join("tiku_type on tiku.type_id=tiku_type.id")
						->join("tiku_to_point on tiku_to_point.tiku_id=tiku.id")
						->where("tiku_to_point.point_id IN($pids) AND tiku.difficulty_id=".$_SESSION['difficulty']." AND type_id=$k")->select();
						shuffle($data);
						if($data){
							$data = array_slice($data,0,$v['num']);
							$new_data = array_merge($new_data,$data);
						}
						
					}
				}
			}
		}
		if(empty($new_data)){
			$this->ajaxReturn(array('status'=>'error','msg'=>'没有试题！'));
		}
		foreach($new_data as $k=>$v){
			$_SESSION['cart'][$v['id']] = $v; 
		}
		$this->ajaxReturn(array('status'=>'ok'));
	}
	public function ajaxRemove(){
		$id = I('get.id');
		if($_SESSION['smart'][$id]){
			unset($_SESSION['smart'][$id]);
			$html = '';
			$tiku_type = $this->getTikuType($_SESSION['course_id']);
			foreach($_SESSION['smart'] as $key=> $val){
				
				$html .= '<tr class="param_tr">
							<td>
								<span onclick="del_p('.$key.')"></span>'.$val['point_name'].'
							</td>
							<td class="sum_tr">
								'.$val['total'].'
							</td>
							';
				foreach($tiku_type as $v){
					$html .= '<td class="mn">
								<p>
									<input value="'.$val['type'][$v['id']]['num'].'" type="text"><b><i class="up" onclick="jia($(this),'.$key.','.$v['id'].')"></i><em class="down" onclick="jian($(this),'.$key.','.$v['id'].')"></em></b>/<sapn class="max">'.$val['type'][$v['id']]['total'].'</sapn>
								</p>
							</td>';	
				}
								
				$html 	.=	'</tr>';
			}
			$p_count = count($_SESSION['smart']);
			$this->ajaxReturn(array('status'=>'ok','data'=>array('html'=>$html,'p_count'=>$p_count)));
		}else{
			$this->ajaxReturn(array('status'=>'error'));
		}
	}
	public function ajaxSelectPoint(){
		//unset($_SESSION['smart']);
		$tiku_type = $this->getTikuType($_SESSION['course_id']);
		$id = I('get.id');
		if(empty($_SESSION['smart_type'])||$_SESSION['smart_type']=='zsd'){
			$pointModel = M('tiku_point');
			$result = $pointModel->where("course_id=".$_SESSION['course_id']." AND level=2 AND id=$id")->find();
		}else{
			$chapterModel = M('chapter');
			$result = $chapterModel->where("id=$id")->find();
		}
		
		//echo "course_id=".$_SESSION['course_id']." AND level=2 AND id=$id";
		if(!$result){
			$this->ajaxReturn(array('status'=>'error','msg'=>'请重新选择！'));
		}
		if($_SESSION['smart'][$id]){
			unset($_SESSION['smart'][$id]);
		}else{
			if(empty($_SESSION['smart_type'])||$_SESSION['smart_type']=='zsd'){
				$tikuModel = M('tiku');
				foreach($tiku_type as $val){
					$_result = $pointModel->where("parent_id=$id")->select();
					if($_result){
						foreach($_result as $v){
							$ids .= $v['id'].',';
						}
					}
					$ids .= $id;
					$count = $tikuModel
					->join("tiku_to_point on tiku_to_point.tiku_id=tiku.id")
					->where("tiku.course_id=".$_SESSION['course_id']." AND type_id=".$val['id']." AND tiku.difficulty_id=".$_SESSION['difficulty']." AND tiku_to_point.point_id IN($ids)")->count();
					$new['type'][$val['id']] = array('total'=>$count,'num'=>0);
				}
				$new['total'] = 0;
				$new['point_name'] = $result['point_name'];
				$_SESSION['smart'][$id] = $new;
			}else{
				$tikuModel = M('tiku');
				foreach($tiku_type as $val){
					$count = $tikuModel
					->join("tiku_to_chapter on tiku_to_chapter.tiku_id=tiku.id")
					->where("tiku.course_id=".$_SESSION['course_id']." AND type_id=".$val['id']." AND tiku.difficulty_id=".$_SESSION['difficulty']." AND tiku_to_chapter.chapter_id =$id")->count();
					$new['type'][$val['id']] = array('total'=>$count,'num'=>0);
				}
				$new['total'] = 0;
				$new['point_name'] = $result['chapter_name'];
				$_SESSION['smart'][$id] = $new;
			}
			
		}
		//var_dump($_SESSION['smart']);exit;
		if(!empty($_SESSION['smart'])){
			foreach($_SESSION['smart'] as $key=> $val){
				
				$html .= '<tr class="param_tr">
							<td>
								<span onclick="del_p('.$key.')"></span>'.$val['point_name'].'
							</td>
							<td class="sum_tr">
								'.$val['total'].'
							</td>
							';
				foreach($tiku_type as $v){
					$html .= '<td class="mn">
								<p>
									<input value="'.$val['type'][$v['id']]['num'].'" type="text"><b><i class="up" onclick="jia($(this),'.$key.','.$v['id'].')"></i><em class="down" onclick="jian($(this),'.$key.','.$v['id'].')"></em></b>/<sapn class="max">'.$val['type'][$v['id']]['total'].'</sapn>
								</p>
							</td>';	
				}
								
				$html 	.=	'</tr>';
			}
		}
		$p_count = count($_SESSION['smart']);
		$this->ajaxReturn(array('status'=>'ok','data'=>array('html'=>$html,'p_count'=>$p_count)));
	}
	public function ajaxChangeNums(){
		$point_id = I('get.point_id');
		$type_id = I('get.type_id');
		$num = I('get.num');
		if(empty($point_id) || empty($type_id)){
			$this->ajaxReturn(array('status'=>'error','msg'=>'服务器出错'));
		}
		if(empty($_SESSION['smart'][$point_id]['type'][$type_id])){
			$this->ajaxReturn(array('status'=>'error','msg'=>'服务器出错'));
		}
		$_SESSION['smart'][$point_id]['type'][$type_id]['num'] = $num;
		//var_dump($_SESSION['smart']);
		$this->ajaxReturn(array('status'=>'ok'));
	}
}
?>