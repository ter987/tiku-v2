<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class BaogaoController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
		$course_data = parent::getCourse();
	}
    public function index(){
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
		
		$where = " AND ceping.course_id=$course_id";
		$joinModel = M('ceping_jon');
		$pointModel = M('tiku_point');
		$extModel = M('ceping_extend');
		$cepingData = $joinModel->field("ceping.*")
		->join('`ceping` ON `ceping`.id=`ceping_jon`.`ceping_id`')
		->where("ceping_jon.student=".$_SESSION['user_id']." AND ceping.type=1 ".$where)->order("ceping.id DESC")->limit(10)->select();
		foreach($cepingData as $key=>$val){
			$extend = $extModel->field("tiku_point.*")
			->join("tiku_to_point on ceping_extend.tiku_id=tiku_to_point.tiku_id")
			->join("tiku_point on tiku_to_point.point_id=tiku_point.id")
			->where("ceping_extend.ceping_id=".$val['id'])->select();
			$pointArr = array();
			foreach($extend as $v){
				$point = $pointModel->where("id=".$v['parent_id'])->find();
				if(!in_array($point['point_name'],$pointArr)){
					$pointArr = array_merge($pointArr,array($point['point_name']));
					$cepingData[$key]['point_count'] += 1;
				}
				
			}
		}
		$this->assign('ceping_data',$cepingData);
		$zsdTongji = $this->getZsdTongji($course_id);
		$this->assign('zsd_tongji',$zsdTongji);
		$average = $this->getAverageByCourse($course_id);
		$this->assign('average',$average);
    	//SEO
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword(''.C('TITLE_SUFFIX'));
		$this->setMetaDescription(''.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->assign('current','baogao');
        $this->display();
	}
	public function shijuan(){
		$ceping_id = I('get.id');
		$cepingModel = M('ceping');
		$ceping = $cepingModel->join("ceping_jon on ceping_jon.ceping_id=ceping.id")->where("ceping.id=$ceping_id AND ceping_jon.student=".$_SESSION['user_id'])->find();
		if(!$ceping){
			redirect('/');
		}
		$used_time = $ceping['end_time']-$ceping['start_time'];
		$ceping['average_dati'] = ceil($used_time/$ceping['shiti_num']);
		$this->assign('ceping',$ceping);
		$shitifenxi = $this->getShitiFenxi($ceping_id);
		$this->assign('shitifenxi',$shitifenxi);
		//SEO
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword(''.C('TITLE_SUFFIX'));
		$this->setMetaDescription(''.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->assign('current','baogao');
        $this->display();
	}
	public function getShitiFenxi($ceping_id){
		$extModel = M('ceping_extend');
		$answerModel = M('ceping_answer');
		$pointModel = M('tiku_point');
		$shiti = $extModel->field("tiku_point.*,ceping_extend.*,tiku_difficulty.section")
			->join("tiku_to_point on tiku_to_point.tiku_id=ceping_extend.tiku_id")
			->join("tiku_point on tiku_to_point.point_id=tiku_point.id")
			->join("tiku on ceping_extend.tiku_id=tiku.id")
			->join("tiku_difficulty on tiku_difficulty.id=tiku.difficulty_id")
			->where("ceping_extend.ceping_id=".$ceping_id)->order("ceping_extend.order_char ASC")->select();
		$data = array();
		$pointArr = array();
		foreach($shiti as $key=>$val){
			//var_dump($shiti);exit;
			$data[$val['order_char']]['order_char'] = $val['order_char'];
			$data[$val['order_char']]['section'] = $val['section'];
			$answer = $answerModel->where("ceping_id=$ceping_id AND tiku_id=".$val['tiku_id']." AND student=".$_SESSION['user_id'])->find();
			if($answer['is_right']==1){
				$data[$val['order_char']]['is_right'] = 1;
			}else{
				$data[$val['order_char']]['is_right'] = 0;
			}
			$point = $pointModel->where("id=".$val['parent_id'])->find();
			if(!in_array($point['point_name'],$pointArr)){
				$pointArr = array_merge($pointArr,array($point['point_name']));
			}
			$data[$val['order_char']]['point_name'] = $point['point_name'];
		}
		//var_dump($data);
		$zsd_count = count($pointArr);
		$this->assign('zsd_count',$zsd_count);
		return $data;
	}
	public function getZsdTongji($course_id){
		$jonModel = M('ceping_jon');
		$answerModel = M('ceping_answer');
		$extModel = M('ceping_extend');
		$pointModel = M('tiku_point');
		$cepingData = $jonModel->field("ceping.*")
		->join('`ceping` ON `ceping`.id=`ceping_jon`.`ceping_id`')
		->where("ceping_jon.student=".$_SESSION['user_id']." AND ceping.type=1 AND ceping.course_id=$course_id")->select();
		foreach($cepingData as $val){
			$extend = $extModel->field("tiku_point.*,ceping_answer.is_right")
			->join("ceping_answer ON ceping_answer.`ceping_id`=ceping_extend.`ceping_id` AND ceping_answer.`tiku_id`=ceping_extend.`tiku_id`")
			->join("tiku_to_point on ceping_extend.tiku_id=tiku_to_point.tiku_id")
			->join("tiku_point on tiku_to_point.point_id=tiku_point.id")
			->where("ceping_extend.ceping_id=".$val['id'])->select();
			//echo $extModel->getLastSql();exit;
			foreach($extend as $v){
				$point = $pointModel->where("id=".$v['parent_id'])->find();
				$data[$point['id']]['point_name'] = $point['point_name'];
				if($v['is_right']){
					$data[$point['id']]['right_count'] += 1;
				}
				
			}
			$total += $val['shiti_num'];
		}
		foreach($data as $key=>$val){
			$data[$key]['right_percent'] = round($val['right_count']/$total,2)*100;
		}
		return $data;
	}
	public function getAverageByCourse($course_id){
		$joinModel = M('ceping_jon');
		$cepingData = $joinModel
		->join("ceping on ceping.id=ceping_jon.ceping_id")
		->where("ceping.course_id=$course_id AND ceping_jon.student=".$_SESSION['user_id']." AND ceping.type=1 ")->select();
		//echo $joinModel->getLastSql();
		foreach($cepingData as $val){
			$s_score += $val['s_score'];
			$score += $val['score'];
			$per_time = $val['end_time']-$val['start_time'];
			$total_time += $per_time;
			$shiti_num += $val['shiti_num'];
		}
		$count = count($cepingData);
		$data['a_score'] = ceil($score/$count);
		$data['error_percent'] = 100-round($s_score/$score,2)*100;
		$data['a_dati'] = ceil($total_time/$shiti_num);
		return $data;
	}
	public function stuReport(){
		$this->display();
	}
}