<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class StudycenterController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
		$course_data = parent::getCourse();
		$this->assign('this_module','studycenter');
		$this->assign('course_data',$course_data);
	}
    public function index(){
        redirect('/studycenter/mycollect/');
	}
	public function myCollect(){
		$Model = M('user_collected');
		$allCourse = $this->getAllCourse();
		$course_id = I('get.cid');
		if(!$course_id){
			$myCourse = $this->getMyCourse();
			$course_id = $myCourse['id'];
		}else{
			$param .= 'cid='.$course_id;
		}
		$this->assign('course_id',$course_id);
		$this->assign('all_course',$allCourse);
		$this->assign('current','collect');
		
		$point = $this->_getCollectPoints($course_id);
		$this->assign('point',$point);
		
		$point_id = I('get.pid');
		if($point_id){
			$lastPoint = $this->getLastLevelPoint($point_id);
			foreach($lastPoint as $val){
				$point_ids .= $val['id'].',';
			}
			$point_ids = trim($point_ids,',');
			$this->assign('point_id',$point_id);
			$where = " AND tiku_to_point.point_id IN($point_ids)";
			$param .= '&pid='.$point_id;
		}else{
			$this->assign('point_id',0);
		}
		$count = $Model
		->join('`tiku` ON `tiku`.id=`user_collected`.`tiku_id`')
		->join("tiku_to_point on tiku_to_point.tiku_id=tiku.id")
		->where("user_collected.user_id=".$_SESSION['user_id']." AND tiku.course_id=$course_id ".$where)->count();
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev',' < 上一页');
		$Page->setConfig('next','下一页  >  ');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->s_show($param);
		$this->assign('page_show',$page_show);
		$data = $Model->field("tiku.id,tiku.content,tiku.analysis,tiku.type_id,tiku.options,tiku_difficulty.*,tiku_source.source_name,tiku_type.type_name")
		->join('`tiku` ON `tiku`.id=`user_collected`.`tiku_id`')
		->join("tiku_to_point on tiku_to_point.tiku_id=tiku.id")
		->join("tiku_source ON tiku_source.id=tiku.source_id")
		->join("tiku_difficulty ON tiku_difficulty.id=tiku.difficulty_id")
		->join("tiku_type on tiku.type_id=tiku_type.id")
		->where("user_collected.user_id=".$_SESSION['user_id']." AND tiku.course_id=$course_id ".$where)->limit($Page->firstRow.','.$Page->listRows)->select();
		//echo $Model->getLastSql();
		
		$this->assign('data',$data);
		//SEO
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
	}
	public function myCuoti(){
		$Model = M('user_collected');
		$allCourse = $this->getAllCourse();
		$course_id = I('get.cid');
		if(!$course_id){
			$myCourse = $this->getMyCourse();
			$course_id = $myCourse['id'];
		}else{
			$param .= 'cid='.$course_id;
		}
		$this->assign('course_id',$course_id);
		$this->assign('all_course',$allCourse);
		$this->assign('current','cuoti');
		
		$point = $this->_getCollectPoints($course_id);
		$this->assign('point',$point);
		
		$point_id = I('get.pid');
		if($point_id){
			$lastPoint = $this->getLastLevelPoint($point_id);
			foreach($lastPoint as $val){
				$point_ids .= $val['id'].',';
			}
			$point_ids = trim($point_ids,',');
			$this->assign('point_id',$point_id);
			$where = " AND tiku_to_point.point_id IN($point_ids)";
			$param .= '&pid='.$point_id;
		}else{
			$this->assign('point_id',0);
		}
		$count = $Model
		->join('`tiku` ON `tiku`.id=`user_collected`.`tiku_id`')
		->join("tiku_to_point on tiku_to_point.tiku_id=tiku.id")
		->where("user_collected.user_id=".$_SESSION['user_id']." AND tiku.course_id=$course_id ".$where)->count();
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev',' < 上一页');
		$Page->setConfig('next','下一页  >  ');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->s_show($param);
		$this->assign('page_show',$page_show);
		$data = $Model->field("tiku.id,tiku.content,tiku.analysis,tiku.type_id,tiku.options,tiku_difficulty.*,tiku_source.source_name,tiku_type.type_name")
		->join('`tiku` ON `tiku`.id=`user_collected`.`tiku_id`')
		->join("tiku_to_point on tiku_to_point.tiku_id=tiku.id")
		->join("tiku_source ON tiku_source.id=tiku.source_id")
		->join("tiku_difficulty ON tiku_difficulty.id=tiku.difficulty_id")
		->join("tiku_type on tiku.type_id=tiku_type.id")
		->where("user_collected.user_id=".$_SESSION['user_id']." AND tiku.course_id=$course_id ".$where)->limit($Page->firstRow.','.$Page->listRows)->select();
		//echo $Model->getLastSql();
		
		$this->assign('data',$data);
		//SEO
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
	}
	protected function _getCollectPoints($course_id){
		$collectModel = M('user_collected');
		$point  = $collectModel->field("tiku_to_point.point_id,tiku_point.id,tiku_point.level,tiku_point.parent_id")
		->join("tiku_to_point on tiku_to_point.tiku_id=user_collected.tiku_id")
		->join("tiku_point on tiku_to_point.point_id=tiku_point.id")
		->join("tiku on tiku.id=user_collected.tiku_id")
		->where("user_collected.user_id=".$_SESSION['user_id']." AND tiku.course_id=".$course_id)->select();
		$pointModel = M('tiku_point');
		foreach($point as $val){
			$level = $val['level'];
			$parent_id = $val['parent_id'];
			$result = $val;
			while($level>1){
				$result = $pointModel->field("id,parent_id,level,point_name")->where("id=".$parent_id)->find();
				$level = $result['level'];
				$parent_id = $result['parent_id'];
			}
			if(!in_array($result, $data)){
				$data[] = $result;
			}
			
		}
		return $data;
	}
	public function myCeping(){
		$joinModel = M('ceping_jon');
		$total = $joinModel->where("student=".$_SESSION['user_id'])->count();
		$this->assign('total',$total);
		$untotal = $joinModel->where("student=".$_SESSION['user_id']." AND end_time IS NULL")->count();
		$this->assign('untotal',$untotal);
		$complete = I('get.com');
		$complete = empty($complete)?'yes':$complete;
		if($complete == 'yes'){
			$this->assign('com','yes');
			$where = " AND end_time IS NOT NULL";
		}else{
			$this->assign('com','no');
			$where = " AND end_time IS NULL";
		}
		$count = $joinModel->where("student=".$_SESSION['user_id'].$where)->count();
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev',' < 上一页');
		$Page->setConfig('next','下一页  >  ');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->_show($params);
		$this->assign('page_show',$page_show);
		$cepignModel = M('ceping');
		$data = $joinModel->field("ceping.*,user.nick_name,tiku_course.course_name,ceping_jon.end_time,tiku_course.course_type")
		->join('`ceping` ON `ceping`.id=`ceping_jon`.`ceping_id`')
		->join("tiku_course ON tiku_course.id=ceping.course_id")
		->join("user ON ceping.teacher=user.id")
		->where("ceping_jon.student=".$_SESSION['user_id']." AND ceping.type=1 ".$where)->limit($Page->firstRow.','.$Page->listRows)->select();
		//echo $joinModel->getLastSql();
		
		$this->assign('data',$data);
		$this->assign('current','ceping');
		//SEO
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
	}
	public function jsCeping(){
		$Model = M('ceping');
		//$total = $Model->where("student=".$_SESSION['user_id'])->count();
		$this->assign('total',$total);
		//$untotal = $joinModel->where("student=".$_SESSION['user_id']." AND end_time IS NULL")->count();
		$this->assign('untotal',$untotal);
		$complete = I('get.com');
		$complete = empty($complete)?'yes':$complete;
		if($complete == 'yes'){
			$this->assign('com','yes');
			$where = " AND end_time IS NOT NULL";
		}else{
			$this->assign('com','no');
			$where = " AND end_time IS NULL";
		}
		$count = $Model->where("teacher=".$_SESSION['user_id'])->count();
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev',' < 上一页');
		$Page->setConfig('next','下一页  >  ');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->_show($params);
		$this->assign('page_show',$page_show);
		$cepignModel = M('ceping');
		$data = $Model->field("ceping.*,tiku_course.course_name,tiku_course.course_type")
		->join("tiku_course ON tiku_course.id=ceping.course_id")
		->where("ceping.teacher=".$_SESSION['user_id'])->limit($Page->firstRow.','.$Page->listRows)->select();
		//echo $joinModel->getLastSql();
		
		$this->assign('data',$data);
		$this->assign('current','jsceping');
		//SEO
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
	}
	public function jsJiexi(){
		$id = I('get.id');
		$Model = M('ceping');
		$ceping = $Model->field("ceping.course_id,ceping.title,ceping.limit_time,ceping.score,ceping.join_num")->where("ceping.id=$id AND ceping.teacher=".$_SESSION['user_id'])->find();
		if(!$ceping){
			redirect('/');
		}
		$extModel = M('ceping_extend');
		$data = $extModel->field("tiku.id,tiku_type.is_zgt,tiku.difficulty_id,tiku.content,tiku.options,tiku.type_id,tiku.answer,tiku.analysis,ceping_extend.ceping_id,ceping_extend.order_char,ceping_extend.x_score")
		->join("tiku on tiku.id=ceping_extend.tiku_id")
		->join("tiku_type on tiku.type_id=tiku_type.id")
		->where("ceping_extend.ceping_id=$id")->order("ceping_extend.order_char ASC")->select();

		$answerModel = M('ceping_answer');
		foreach($data as $key=>$val){
			$dadui = $answerModel->where("ceping_id=$id AND is_right=1 AND tiku_id=".$val['id'])->count();
			$average = round(($dadui/$ceping['join_num'])*$val['x_score'],1);
			$data[$key]['average'] = $average;
			$data[$key]['dadui'] = $dadui;
			$data[$key]['dacuo'] = $ceping['join_num']-$dadui;
			// if($val['type_id']==1){
				// $data[$key]['A'] = $this->getOptionTongji($id,$val['id'],'A',$ceping['join_num']);
				// $data[$key]['B'] = $this->getOptionTongji($id,$val['id'],'B',$ceping['join_num']);
				// $data[$key]['C'] = $this->getOptionTongji($id,$val['id'],'C',$ceping['join_num']);
				// $data[$key]['D'] = $this->getOptionTongji($id,$val['id'],'D',$ceping['join_num']);
			// }else if($val['is_zgt']==1){
// 				
			// }
		}
		//var_dump($data);exit;
		$course = $this->getCourseById($ceping['course_id']);
		$this->assign('course',$course);
		$this->assign('ceping',$ceping);
		$this->assign('data',$data);
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/dialog.js','js/xf.js'));
		$this->display();
	}
	public function zhFenxi(){
		$id = I('get.id');
		$Model = M('ceping');
		$jonModel = M('ceping_jon');
		$answerModel = M('ceping_answer');
		$ceping = $Model->field("*")->where("ceping.id=$id AND ceping.teacher=".$_SESSION['user_id'])->find();
		if(!$ceping){
			redirect('/');
		}
		//计算平均分
		$result = $jonModel->field("SUM(s_score) AS score_sum")->where("ceping_id=$id")->find();
		$average = ceil($result['score_sum']/$ceping['join_num']);
		$this->assign('average',$average);
		//计算错误率
		$rightNums = $answerModel->where("ceping_id=$id AND is_right=1")->count();
		$rightPercent = round($rightNums/($ceping['shiti_num']*$ceping['join_num']),2)*100;
		$errorPercent = 100-$rightPercent;
		$this->assign('error_percent',$errorPercent);
		//计算平均答题时间
		$result = $jonModel->field("AVG(end_time-start_time) AS avg_time")->where("ceping_id=$id")->find();//平均答卷时间
		$averageDati = ceil($result['avg_time']/$ceping['shiti_num']);
		$this->assign('average_dati',$averageDati);
		//获取ceping_jon 数据
		$paiming = $jonModel->field("ceping_jon.*,user.nick_name")->join("user on ceping_jon.student=user.id")->where("ceping_jon.ceping_id=$id")->order("ceping_jon.s_score DESC")->limit(20)->select();
		$this->assign('paiming',$paiming);
		
		$this->assign('ceping',$ceping);
		$this->assign('current','jsceping');
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/dialog.js','js/xf.js'));
		$this->display();
	}
	public function cjFenxi(){
		$id = I('get.id');
		$Model = M('ceping');
		$jonModel = M('ceping_jon');
		$answerModel = M('ceping_answer');
		$extModel = M('ceping_extend');
		$pointModel = M('tiku_point');
		$cepingJon = $jonModel->field("ceping_jon.*,user.id,user.nick_name,ceping.shiti_num")
		->join('ceping on ceping.id=ceping_jon.ceping_id')
		->join("user on ceping_jon.student=user.id")->where("ceping_jon.ceping_id=$id")->order("s_score DESC")->select();
		if(!$cepingJon){
			redirect('/');
		}
		foreach($cepingJon as $key=> $val){
			$pointArr = array();
			for($i=1;$i<=$val['shiti_num'];$i++){
				$tiku = $extModel->field("tiku_point.*,ceping_extend.*")->join("tiku_to_point on tiku_to_point.tiku_id=ceping_extend.tiku_id")
				->join("tiku_point on tiku_to_point.point_id=tiku_point.id")
				->where("ceping_extend.order_char=$i AND ceping_id=".$val['ceping_id'])->find();
				if($tiku['level']==3){
					$point = $pointModel->where("id=".$tiku['parent_id'])->find();
					if(!in_array($point['point_name'], $pointArr)){
						$pointArr = array_merge($pointArr,array($point['point_name']));
					}
					
				}
				$answer = $answerModel->where("ceping_id=".$val['ceping_id']." AND student=".$val['student']." AND tiku_id=".$tiku['tiku_id'])->find();
				if($answer['is_right'] != 1){
					$cepingJon[$key]['cuoti'] .= $tiku['order_char'].',';
					if($i==$val['shiti_num']){
						$cepingJon[$key]['zsd_count'] = count($pointArr);
						$cepingJon[$key]['zsd'] = implode(',',$pointArr);
					}
				}
			}
		}
		$this->assign('ceping_jon',$cepingJon);
		$this->assign('ceping_id',$id);
		$this->assign('current','jsceping');
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/dialog.js','js/xf.js'));
		$this->display();
	}
	public function stFenxi(){
		$id = I('get.id');
		$Model = M('ceping');
		$jonModel = M('ceping_jon');
		$answerModel = M('ceping_answer');
		$extModel = M('ceping_extend');
		$pointModel = M('tiku_point');
		$ceping = $Model->field("*")->where("ceping.id=$id AND ceping.teacher=".$_SESSION['user_id'])->find();
		if(!$ceping){
			redirect('/');
		}
		$jonData = $jonModel->field("user.id,user.nick_name")->join("user on ceping_jon.student=user.id")->where("ceping_jon.ceping_id=$id")->select();
		$pointArr = array();
		for($i=1;$i<=$ceping['shiti_num'];$i++){
			$tiku = $extModel->field("tiku_point.*,ceping_extend.*,tiku_difficulty.section")->join("tiku_to_point on tiku_to_point.tiku_id=ceping_extend.tiku_id")
			->join("tiku_point on tiku_to_point.point_id=tiku_point.id")
			->join("tiku on ceping_extend.tiku_id=tiku.id")
			->join("tiku_difficulty on tiku_difficulty.id=tiku.difficulty_id")
			->where("ceping_extend.order_char=$i AND ceping_id=".$id)->find();
			if($tiku['level']==3){
				$point = $pointModel->where("id=".$tiku['parent_id'])->find();
				$point_name = $point['point_name'];
				
			}else{
				$point_name = $tiku['point_name'];
			}
			$data[$i]['order_char'] = $i;
			$data[$i]['section'] = $tiku['section'];
			$data[$i]['point_name'] = $point_name;
			$rightAnswer = $answerModel->field("user.id,user.nick_name")->join("user on ceping_answer.student=user.id")->where("ceping_answer.ceping_id=".$id." AND ceping_answer.is_right=1 AND  ceping_answer.tiku_id=".$tiku['tiku_id'])->select();
			$rightCount = count($rightAnswer);
			$rightPercent = round($rightCount/$ceping['join_num'],2)*100;
			$errorPercent = 100-$rightPercent;
			$data[$i]['error_percent'] = $errorPercent;
			//var_dump($rightAnswer);exit;
			$errorArr = array();
			foreach($jonData as $key=>$val){
				if(!in_array($val,$rightAnswer)){
					$errorArr = array_merge($errorArr,array($val['nick_name']));
				}
			}
			$data[$i]['error_count'] = count($errorArr);
			$data[$i]['error_mingdan'] = implode(',',$errorArr);
		}
		
		$this->assign('fenxi',$data);
		$this->assign('ceping_id',$id);
		$this->assign('current','jsceping');
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/dialog.js','js/xf.js'));
		$this->display();
	}
	public function zsdFenxi(){
		$id = I('get.id');
		$Model = M('ceping');
		$jonModel = M('ceping_jon');
		$answerModel = M('ceping_answer');
		$extModel = M('ceping_extend');
		$pointModel = M('tiku_point');
		$ceping = $Model->field("*")->where("ceping.id=$id AND ceping.teacher=".$_SESSION['user_id'])->find();
		if(!$ceping){
			redirect('/');
		}
		$jonData = $jonModel->field("user.id,user.nick_name")->join("user on ceping_jon.student=user.id")->where("ceping_jon.ceping_id=$id")->select();
		$pointArr = array();
		for($i=1;$i<=$ceping['shiti_num'];$i++){
			$tiku = $extModel->field("tiku_point.id as point_id,tiku_point.parent_id,tiku_point.point_name,tiku_point.level,ceping_extend.*,tiku_difficulty.section")->join("tiku_to_point on tiku_to_point.tiku_id=ceping_extend.tiku_id")
			->join("tiku_point on tiku_to_point.point_id=tiku_point.id")
			->join("tiku on ceping_extend.tiku_id=tiku.id")
			->join("tiku_difficulty on tiku_difficulty.id=tiku.difficulty_id")
			->where("ceping_extend.order_char=$i AND ceping_id=".$id)->find();
			
			$rightAnswer = $answerModel->field("user.id,user.nick_name")->join("user on ceping_answer.student=user.id")->where("ceping_answer.ceping_id=".$id." AND ceping_answer.is_right=1 AND  ceping_answer.tiku_id=".$tiku['tiku_id'])->select();
			//$rightCount = count($rightAnswer);
			//$rightPercent = round($rightCount/$ceping['join_num'],2)*100;
			//$errorPercent = 100-$rightPercent;
			//$data[$i]['error_percent'] = $errorPercent;
			//var_dump($rightAnswer);exit;
			$errorArr = array();
			foreach($jonData as $key=>$val){
				if(!in_array($val,$rightAnswer)){
					//if(!in_array($val['nick_name'],$errorArr)){
						$errorArr = array_merge($errorArr,array($val['nick_name']));
					//}
					
				}
			}
			//var_dump($errorArr);exit;
			//$data[$i]['error_count'] = count($errorArr);
			//$data[$i]['error_mingdan'] = implode(',',$errorArr);
			
			
			$point = $pointModel->where("id=".$tiku['parent_id'])->find();
			if(!isset($data[$point['id']]['e_mingdan'])){
				$data[$point['id']]['e_mingdan'] = array();
			}
			$point_name = $point['point_name'];
			$data[$point['id']]['point_name'] = $point['point_name'];
			$data[$point['id']]['count'] += 1;
			$data[$point['id']]['shiti'] .= $i.',';
			$data[$point['id']]['x_score'] += $tiku['x_score'];
			$data[$point['id']]['e_mingdan'] = array_merge($data[$point['id']]['e_mingdan'],$errorArr);
			$data[$point['id']]['e_mingdan'] = array_unique($data[$point['id']]['e_mingdan']);
			$data[$point['id']]['right_count'] += count($rightAnswer);
			
		}
		foreach($data as $key=> $val){
			$data[$key]['bili'] = round($val['x_score']/$ceping['score'],2)*100;
			$data[$key]['e_count'] = count($val['e_mingdan']);
			$data[$key]['e_mingdan'] = implode(',',$val['e_mingdan']);
			$rightPercent = round($data[$key]['right_count']/($ceping['join_num']*$ceping['shiti_num']),2)*100;
			$data[$key]['error_percent'] = 100-$rightPercent;
		}
		$this->assign('fenxi',$data);
		$this->assign('ceping_id',$id);
		$this->assign('current','jsceping');
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/dialog.js','js/xf.js'));
		$this->display();
	}
	public function getTongji($ceping_id,$tiku_id){
		$ceping_id = I('get.ceping_id');
		$tiku_id = I('get.tiku_id');
		$extModel = M('ceping_extend');
		$tiku = $extModel->field("tiku_type.*,ceping_extend.x_score,ceping.join_num")
		->join("ceping on ceping.id=ceping_extend.ceping_id")
		->join("tiku on tiku.id=ceping_extend.tiku_id")
		->join("tiku_type on tiku.type_id=tiku_type.id")
		->where("ceping_extend.ceping_id=$ceping_id AND ceping_extend.tiku_id=$tiku_id")->find();
		$Model = M('ceping_answer');
		$data = array();
		//var_dump($tiku);
		if($tiku['id']==1){//单选题
			$index = array('A','B','C','D');
			foreach($index as $val){
				$total = $Model->where("ceping_id=$ceping_id AND tiku_id=$tiku_id AND s_answer='".$val."'")->count();
				$percent = round($total/$tiku['join_num'],3)*100;
				$data[$val]['total'] = $total;
				$data[$val]['percent'] = $percent;
			}
			$this->ajaxReturn(array('status'=>'ok','type'=>1,'data'=>$data));
		}elseif($tiku['x_score']>=6){
			if($tiku['x_score']<8){
				$top = 2;
			}else{
				$top = 3;
			}
			$step = floor($tiku['x_score']/3);
			$start = 0;
			for($i=0;$i<=$top;$i++){
				if($i==$top){
					$end = $tiku['x_score'];
				}else{
					$end = $start+$step;
				}
				
				$range = $start.'-'.$end;
				$data['range'] =  $range;
				$total = $Model->where("ceping_id=$ceping_id AND tiku_id=$tiku_id AND s_score>=$start AND s_score<$end")->count();
				if($start==0){//未作答也是0分
					$yes = $Model->where("ceping_id=$ceping_id AND tiku_id=$tiku_id ")->count();//作答人数
					$not = $tiku['join_num']-$yes;//未作答人数
					$total = $total+$not;
				}
				$data['total'] = $total;
				$percent = round($total/$tiku['join_num'],3)*100;
				$data['percent'] = $percent;
				$new[] = $data;
				$start += $step;
			}
			$this->ajaxReturn(array('status'=>'ok','type'=>2,'data'=>$new));
		}
		
		
	}
	public function getMingdan(){
		$index = I('get.index');
		$ceping_id = I('get.ceping_id');
		$tiku_id = I('get.tiku_id');
		$Model = M('ceping_answer');
		$mingdan = $Model->field("user.nick_name")->join("user on ceping_answer.student=user.id")
		->where("ceping_answer.ceping_id=$ceping_id AND ceping_answer.tiku_id=$tiku_id AND ceping_answer.s_answer='".$index."'")->select();
		$this->ajaxReturn(array('status'=>'ok','data'=>$mingdan));
	}
	public function getMingdanByRange(){
		$range = I('get.range');
		$arr = explode('-',$range);
		$start = $arr[0];
		$end = $arr[1];
		$ceping_id = I('get.ceping_id');
		$tiku_id = I('get.tiku_id');
		$Model = M('ceping_answer');
		$mingdan = $Model->field("user.nick_name,ceping_answer.s_score")->join("user on ceping_answer.student=user.id")
		->where("ceping_answer.ceping_id=$ceping_id AND ceping_answer.tiku_id=$tiku_id AND ceping_answer.s_score>=$start AND ceping_answer.s_score<$end")->select();
		if($start==0){
			$jonModel = M('ceping_jon');
			$not = array();
			$jonData = $jonModel->field("user.id,user.nick_name")->join("user on ceping_jon.student=user.id")->where("ceping_id=$ceping_id")->select();
			foreach($jonData as $val){
				$result = $Model->where("ceping_answer.ceping_id=$ceping_id AND ceping_answer.tiku_id=$tiku_id AND ceping_answer.student=".$val['id'])->find();
				if(!$result){
					$data['nick_name'] = $val['nick_name'];
					$data['s_score'] = 0;
					$not[] = $data;
				}
			}
			$mingdan = array_merge($mingdan,$not);
		}
		$this->ajaxReturn(array('status'=>'ok','data'=>$mingdan));
	}
	public function gaiJuan(){
		$id = I('get.id');
		$Model = M('ceping');
		$result = $Model->where("id=$id AND teacher=".$_SESSION['user_id'])->find();
		if(!$result){
			redirect('/');
		}
		$extModel = M('ceping_extend');
		$ceping_extend = $extModel->field("ceping_extend.*")->join("tiku on tiku.id=ceping_extend.tiku_id")
		->join("tiku_type on tiku.type_id=tiku_type.id")
		->where("ceping_extend.ceping_id=$id AND tiku_type.is_zgt=1")->order("ceping_extend.order_char ASC")->select();
		$this->assign('ceping_extend',$ceping_extend);
		//var_dump($ceping_extend);
		//获取首条未批改试题id
		$not = $extModel->field("ceping_extend.tiku_id")
		->join("tiku on tiku.id=ceping_extend.tiku_id")
		->join("tiku_type on tiku.type_id=tiku_type.id")
		->join("ceping_answer ON ceping_answer.`ceping_id`=ceping_extend.`ceping_id` AND ceping_answer.`tiku_id`=ceping_extend.`tiku_id`")
		->where("ceping_extend.ceping_id=$id AND tiku_type.is_zgt=1 AND ceping_answer.is_right=0")->order("ceping_extend.order_char ASC")->find();
		if(!$not){
			redirect('/studycenter/jsceping/');
		}
		$this->assign('tiku_id_not',$not['tiku_id']);
		$this->assign('ceping',$result);
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js'));
		$this->display();
	}
	public function ajaxGetStudentAnswer(){
		$ceping_id = I('get.ceping_id');
		$tiku_id = I('get.tiku_id');
		$answer_id = I('get.answer_id');
		$by = I('get.by');
		if(!empty($by)){
			if($by=='xia'){
				$where = " AND ceping_answer.id>$answer_id";
			}elseif($by=='shang'){
				$where = " AND ceping_answer.id<$answer_id";
			}
			
		}else{
			$where = " AND ceping_answer.is_right=0";
		}
		$Model = M('ceping_answer');
		$result = $Model->field("ceping_answer.*,ceping_extend.x_score,ceping_extend.order_char,tiku.answer")
		->join("tiku on ceping_answer.tiku_id=tiku.id")
		->join("ceping_extend ON ceping_answer.`ceping_id`=ceping_extend.`ceping_id` AND ceping_answer.`tiku_id`=ceping_extend.`tiku_id`")->where("ceping_answer.ceping_id=$ceping_id  AND ceping_answer.tiku_id=$tiku_id".$where)->find();
		//echo $Model->getLastSql();
		if($result){
			$result['total'] = $Model->where("ceping_id=$ceping_id AND tiku_id=$tiku_id")->count();
			$result['geted'] = $Model->where("ceping_id=$ceping_id AND tiku_id=$tiku_id AND is_right<>0")->count();
			$result['s_answer'] = preg_replace('/\[img:(.+)\]/','<img src="$1" />',$result['s_answer']);
			$result['answer'] = htmlspecialchars_decode($result['answer']);
			$this->ajaxReturn(array('status'=>'ok','data'=>$result));
		}else{
			$this->ajaxReturn(array('status'=>'error','msg'=>'服务器出错'));
		}
	}
	public function ajaxGetNextStudentAnswer(){
		$ceping_id = I('get.ceping_id');
		$tiku_id = I('get.tiku_id');
		$answer_id = I('get.answer_id');
		
	}
	public function ajaxPingFen(){
		$ceping_id = I('get.ceping_id');
		$answer_id = I('get.answer_id');
		$tiku_id = I('get.tiku_id');
		$s_score = I('get.s_score');
		$Model = M('ceping_answer');
		$jonModel = M('ceping_jon');
		$result = $Model->join("ceping on ceping.id=ceping_answer.ceping_id")
		->join("ceping_extend ON ceping_answer.`ceping_id`=ceping_extend.`ceping_id` AND ceping_answer.`tiku_id`=ceping_extend.`tiku_id`")
		->where("ceping.teacher=".$_SESSION['user_id']." AND ceping_answer.id=$answer_id AND ceping_answer.ceping_id=$ceping_id")->find();
		if($result){
			if($s_score==$result['x_score']){
				$is_right = 1;
			}else{
				$is_right = -1;
			}
			
			$Model->startTrans();
			$result_1 = $Model->where("id=$answer_id")->save(array('s_score'=>$s_score,'is_right'=>$is_right));
			$offset = $s_score-$result['s_score'];
			$result_2 = $jonModel->where("ceping_id=$ceping_id AND student=".$result['student'])->setInc('s_score',$offset);
			
			if($result_1 && $result_2){
				$Model->commit();
				$Model = M('ceping_answer');
				$result = $Model->field("ceping_answer.*")
				->where("ceping_answer.ceping_id=$ceping_id AND ceping_answer.is_right=0 AND ceping_answer.tiku_id=$tiku_id")->find();
				if(!$result){
					$extModel = M('ceping_extend');
					$result = $extModel->field("ceping_extend.*")
					->join("tiku on tiku.id=ceping_extend.tiku_id")
					->join("tiku_type on tiku.type_id=tiku_type.id")
					->join("ceping_answer ON ceping_answer.`ceping_id`=ceping_extend.`ceping_id` AND ceping_answer.`tiku_id`=ceping_extend.`tiku_id`")
					->where("ceping_extend.ceping_id=$ceping_id AND tiku_type.is_zgt=1 AND ceping_answer.is_right=0")->order("ceping_extend.order_char ASC")->find();
				}
				if($result){
					$this->ajaxReturn(array('status'=>'ok','tiku_id'=>$result['tiku_id']));
				}else{
					$this->ajaxReturn(array('status'=>'finished','msg'=>'试卷已改完'));
				}
				
			}else{
				$Model->rollback();
				$this->ajaxReturn(array('status'=>'error','msg'=>'服务器出错'));
			}
			
		}else{
			$this->ajaxReturn(array('status'=>'error','msg'=>'服务器出错'));
		}
	}
	public function myLianxi(){
		$Model = M('lianxi');
		$edtotal = $Model->where("user_id=".$_SESSION['user_id']." AND commit_time IS NOT NULL")->count();
		$this->assign('edtotal',$edtotal);
		$untotal = $Model->where("user_id=".$_SESSION['user_id']." AND commit_time IS NULL")->count();
		$this->assign('untotal',$untotal);
		$complete = I('get.com');
		$complete = empty($complete)?'yes':$complete;
		if($complete == 'yes'){
			$this->assign('com','yes');
			$where = " AND commit_time IS NOT NULL";
		}else{
			$this->assign('com','no');
			$where = " AND commit_time IS NULL";
		}
		$count = $Model->where("user_id=".$_SESSION['user_id'].$where)->count();
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev',' < 上一页');
		$Page->setConfig('next','下一页  >  ');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->_show($params);
		$this->assign('page_show',$page_show);
		$cepignModel = M('ceping');
		$data = $Model->field("lianxi.*,user.nick_name,tiku_course.course_name,tiku_course.course_type")
		->join("tiku_course ON tiku_course.id=lianxi.course_id")
		->join("user ON lianxi.user_id=user.id")
		->where("lianxi.user_id=".$_SESSION['user_id'].$where)->limit($Page->firstRow.','.$Page->listRows)->order("lianxi.id DESC")->select();
		//echo $Model->getLastSql();
		
		$this->assign('data',$data);
		$this->assign('current','lianxi');
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
	}
	public function myShijuan(){
		$Model = M('user_shijuan');
		
		$count = $Model->where("user_id=".$_SESSION['user_id'].$where)->count();
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev',' < 上一页');
		$Page->setConfig('next','下一页  >  ');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->_show($params);
		$this->assign('page_show',$page_show);
		$cepignModel = M('ceping');
		$data = $Model->field("user_shijuan.*,tiku_course.course_name,tiku_course.course_type")
		->join("tiku_course ON tiku_course.id=user_shijuan.course_id")
		->where("user_shijuan.user_id=".$_SESSION['user_id'].$where)->limit($Page->firstRow.','.$Page->listRows)->order("user_shijuan.id DESC")->select();
		//echo $Model->getLastSql();
		
		$this->assign('data',$data);
		$this->assign('current','shijuan');
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
	}
	public function myJiaoan(){
		$Model = M('user_jiaoan');
		
		$count = $Model->where("user_id=".$_SESSION['user_id'].$where)->count();
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev',' < 上一页');
		$Page->setConfig('next','下一页  >  ');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->_show($params);
		$this->assign('page_show',$page_show);
		$data = $Model->field("user_jiaoan.*,tiku_course.course_name,tiku_course.course_type")
		->join("tiku_course ON tiku_course.id=user_jiaoan.course_id")
		->where("user_jiaoan.user_id=".$_SESSION['user_id'].$where)->limit($Page->firstRow.','.$Page->listRows)->order("user_jiaoan.id DESC")->select();
		//echo $Model->getLastSql();
		
		$this->assign('data',$data);
		$this->assign('current','jiaoan');
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
	}
	public function cpJiexi(){
		$id = I('get.id');
		$cpModel = M('ceping');
		$ceping = $cpModel->field("ceping.course_id,ceping.title,ceping.limit_time,ceping.score")->join("ceping_jon on ceping.id=ceping_jon.ceping_id")->where("ceping.id=$id AND ceping_jon.student=".$_SESSION['user_id'])->find();
		if(!$ceping){
			redirect('/');
		}
		$extModel = M('ceping_extend');
		$data = $extModel->field("tiku.id,tiku.content,tiku.options,tiku.type_id,tiku.answer,tiku.analysis,ceping_extend.order_char")->join("tiku on tiku.id=ceping_extend.tiku_id")->where("ceping_id=$id")->order("ceping_extend.order_char ASC")->select();
		
		$answerModel = M('ceping_answer');
		foreach($data as $key=>$val){
			$answer = $answerModel->field("s_answer,is_right,s_score")->where("ceping_id=$id AND student=".$_SESSION['user_id']." AND tiku_id=".$val['id'])->find();
			if($answer){
				$answer['s_answer'] = preg_replace('/\[img:(\S+)\]/U','<img src="$1" />',$answer['s_answer']);
				$data[$key]['s_answer'] = $answer['s_answer'];
				$data[$key]['is_right'] = $answer['is_right'];
				$data[$key]['s_score'] = $answer['s_score'];
			}else{
				$data[$key]['is_right'] = -1;
				$data[$key]['s_score'] = 0;
			}
		}
		//var_dump($data);
		$course = $this->getCourseById($ceping['course_id']);
		$this->assign('course',$course);
		$this->assign('ceping',$ceping);
		$this->assign('data',$data);
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css'));
		$this->addJs(array('js/xf.js'));
		$this->display();
	}
	public function lxJiexi(){
		$id = I('get.id');
		$lxModel = M('lianxi');
		$lianxi = $lxModel->field("lianxi.course_id,lianxi.title")->where("lianxi.id=$id AND lianxi.user_id=".$_SESSION['user_id'])->find();
		if(!$lianxi){
			redirect('/');
		}
		$extModel = M('lianxi_extend');
		$data = $extModel->field("tiku.id,tiku.content,tiku.options,tiku.type_id,tiku.answer,tiku.analysis,lianxi_extend.order_char")->join("tiku on tiku.id=lianxi_extend.tiku_id")->where("lianxi_extend.lianxi_id=$id")->order("lianxi_extend.order_char ASC")->select();

		$answerModel = M('lianxi_answer');
		foreach($data as $key=>$val){
			$answer = $answerModel->field("s_answer,is_right")->where("lianxi_id=$id AND user_id=".$_SESSION['user_id']." AND tiku_id=".$val['id'])->find();
			if($answer){
				$answer['s_answer'] = preg_replace('/\[img:(\S+)\]/U','<img src="$1" />',$answer['s_answer']);
				$data[$key]['s_answer'] = $answer['s_answer'];
				$data[$key]['is_right'] = $answer['is_right'];
			}else{
				$data[$key]['is_right'] = -1;
			}
		}
		//var_dump($data);exit;
		$course = $this->getCourseById($lianxi['course_id']);
		$this->assign('course',$course);
		$this->assign('lianxi',$lianxi);
		$this->assign('data',$data);
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css'));
		$this->addJs(array('js/xf.js'));
		$this->display();
	}
	public function downloadJiaoAn(){
		$id = I('get.id');
		$Model = M('user_jiaoan');
		$result = $Model->where("id=$id AND user_id=".$_SESSION['user_id'])->find();
		if(!$result){
			return false;
		}
		$Zuke = A('Zuke');
		Vendor('PhpWord.src.PhpWord.Autoloader');
		\PhpOffice\PhpWord\Autoloader::register();
		Vendor('PhpOffice.PhpWord.Shared.Font');
		$PHPWord_Shared_Font = new \PhpOffice\PhpWord\Shared\Font();
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$sectionStyle = array(
		    'pageSizeW' => $PHPWord_Shared_Font->centimeterSizeToTwips(20.9),
		    'pageSizeH' => $PHPWord_Shared_Font->centimeterSizeToTwips(29.6),
		    'colsNum'	=> 1,
		    'orientation'	=> 'portrait'
		);
		$section = $phpWord->addSection($sectionStyle);
		$section->addText($result['title'], array( 'size'=>'15','bold'=>true),array('align' => 'center'));
		$shiti = json_decode($result['content'],true);
		$title = $result['title'];
		foreach($shiti as $val){
			$section->addText($val['order_char'].'、'.$val['resource_name'],array('size'=>13));
			
			foreach($val['childs'] as $key=>$v){
				$textrun = $section->createTextRun(array('widowControl'=>'true'));
				$order_char = $key+1;
				$textrun->addText($order_char.'.',array('size'=>13));
				$result = $Zuke->getCaseContent($v['id'], $v['type']);
				$content = htmlspecialchars_decode($result['content']);
				//echo $content;exit;
				$content = trim(strip_tags($content,'<br><img>'));
				$content = preg_replace('/(&nbsp;)*/','',$content);
				$text_arr = preg_split('/<img[\s|\S]+>/U',$content);
				preg_match_all('/src="[\s|\S]+"/U',$content,$matchs);
				//var_dump($text_arr);exit;
				//echo $content;exit;
				if($matchs){
					$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
					$i=0;
					$text_count = count($text_arr);
					$img_count = count($img_arr);
					while($i<$text_count){
						//echo $text_arr[$i];exit;
						//$text_arr[$i]='以制备某物质为目的：这种题型其实是利用某些原料来制备物质的过程,它可能包含分离、提纯等实验基本操作,解题的基本思路是先浏览流程及需解决的问题有哪些,其次要明白制备什么物质,这些物质有着什么性质,通过什么方法制备,该反应造成了什么后果,制备过程中产生了什么杂质,杂质如何去除,然后带着问题结合基础知识和题目信息解答。例2 [2014·天津卷] 合成氨是人类科学技术上的一项重大突破，其反应原理为N2(g)＋3H2(g)⇌2NH3(g) ΔH＝－92.4 kJ·mol－1。一种工业合成氨的简式流程图如下：';
						$tts = preg_split('/<br\s*\/{0,1}>/U',$text_arr[$i]);
						//var_dump($tts);exit;
						if($tts){
							foreach($tts as $vv){
								$textrun->addText($vv,array('size'=>13));
								//$section->addTextBreak();
								$textrun = $section->createTextRun(array('widowControl'=>'true'));
							}
						}else{
							$textrun->addText($text_arr[$i],array('size'=>13));
						}
						
						if($i==$img_count) break;
						$textrun->addImage($img_arr[$i]);
						$i++;
					}
					
				}else{
					$section->addText($content,array('size'=>13,'align'=>'both'));
				}
				if($v['type']==2){
					//输出答案
					$textrun = $section->createTextRun(array('widowControl'=>'true'));
					$textrun->addText('【答案】',array('size'=>13));
					$answer = htmlspecialchars_decode($result['answer']);
					//echo $content;exit;
					$answer = trim(strip_tags($answer,'<br><img>'));
					$answer = preg_replace('/(&nbsp;)*/','',$answer);
					$text_arr = preg_split('/<img[\s|\S]+>/U',$answer);
					preg_match_all('/src="[\s|\S]+"/U',$answer,$matchs);
					//var_dump($text_arr);exit;
					//echo $content;exit;
					if($matchs){
						$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
						$i=0;
						$text_count = count($text_arr);
						$img_count = count($img_arr);
						while($i<$text_count){
							//echo $text_arr[$i];exit;
							//$text_arr[$i]='以制备某物质为目的：这种题型其实是利用某些原料来制备物质的过程,它可能包含分离、提纯等实验基本操作,解题的基本思路是先浏览流程及需解决的问题有哪些,其次要明白制备什么物质,这些物质有着什么性质,通过什么方法制备,该反应造成了什么后果,制备过程中产生了什么杂质,杂质如何去除,然后带着问题结合基础知识和题目信息解答。例2 [2014·天津卷] 合成氨是人类科学技术上的一项重大突破，其反应原理为N2(g)＋3H2(g)⇌2NH3(g) ΔH＝－92.4 kJ·mol－1。一种工业合成氨的简式流程图如下：';
							$tts = preg_split('/<br\s*\/{0,1}>/U',$text_arr[$i]);
							//var_dump($tts);exit;
							if($tts){
								foreach($tts as $vv){
									$textrun->addText($vv,array('size'=>13));
									//$section->addTextBreak();
									$textrun = $section->createTextRun(array('widowControl'=>'true'));
								}
							}else{
								$textrun->addText($text_arr[$i],array('size'=>13));
							}
							
							if($i==$img_count) break;
							$textrun->addImage($img_arr[$i]);
							$i++;
						}
						
					}else{
						$section->addText($answer,array('size'=>13,'align'=>'both'));
					}
					//输出解析
					$textrun = $section->createTextRun(array('widowControl'=>'true'));
					$textrun->addText('【解析】',array('size'=>13));
					$analysis = htmlspecialchars_decode($result['analysis']);
					//echo $content;exit;
					$analysis = trim(strip_tags($analysis,'<br><img>'));
					$analysis = preg_replace('/(&nbsp;)*/','',$analysis);
					$text_arr = preg_split('/<img[\s|\S]+>/U',$analysis);
					preg_match_all('/src="[\s|\S]+"/U',$analysis,$matchs);
					//var_dump($text_arr);exit;
					//echo $content;exit;
					if($matchs){
						$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
						$i=0;
						$text_count = count($text_arr);
						$img_count = count($img_arr);
						while($i<$text_count){
							//echo $text_arr[$i];exit;
							//$text_arr[$i]='以制备某物质为目的：这种题型其实是利用某些原料来制备物质的过程,它可能包含分离、提纯等实验基本操作,解题的基本思路是先浏览流程及需解决的问题有哪些,其次要明白制备什么物质,这些物质有着什么性质,通过什么方法制备,该反应造成了什么后果,制备过程中产生了什么杂质,杂质如何去除,然后带着问题结合基础知识和题目信息解答。例2 [2014·天津卷] 合成氨是人类科学技术上的一项重大突破，其反应原理为N2(g)＋3H2(g)⇌2NH3(g) ΔH＝－92.4 kJ·mol－1。一种工业合成氨的简式流程图如下：';
							$tts = preg_split('/<br\s*\/{0,1}>/U',$text_arr[$i]);
							//var_dump($tts);exit;
							if($tts){
								foreach($tts as $vv){
									$textrun->addText($vv,array('size'=>13));
									//$section->addTextBreak();
									$textrun = $section->createTextRun(array('widowControl'=>'true'));
								}
							}else{
								$textrun->addText($text_arr[$i],array('size'=>13));
							}
							
							if($i==$img_count) break;
							$textrun->addImage($img_arr[$i]);
							$i++;
						}
						
					}else{
						$section->addText($analysis,array('size'=>13,'align'=>'both'));
					}
				}
			}
		}
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="'.$title.'.docx"');
        //header("Content-Type: application/docx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header("Cache-Control: public");
        header('Expires: 0');
        $objWriter->save("php://output");
	}
}