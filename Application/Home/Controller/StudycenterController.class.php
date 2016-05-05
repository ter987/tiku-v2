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
		$this->assign('current','collect');
		//SEO
		$this->setMetaTitle('学习中心'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','exam_info.css','study_centre.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->display();
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
}