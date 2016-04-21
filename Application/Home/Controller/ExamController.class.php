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
			
		}
		$cepingModel = M('ceping');
		$data = $cepingModel->field("ceping.*,tiku_course.course_name,tiku_course.course_type")
		->join('`ceping_jon` ON `ceping`.id=`ceping_jon`.`ceping_id`')
		->join("tiku_course ON tiku_course.id=ceping.course_id")
		->where("ceping_jon.student=".$_SESSION['user_id']." AND ceping.type=1 AND ceping.id=$id ")->find();
		//echo $joinModel->getLastSql();
		if(!$data){//404
			
		}
		$shiti = json_decode($data['shiti'],true);
		
		$Tiku = A('Shijuan');
		$o = 1;
		foreach($shiti as $key=> $val){
			$shiti[$key]['childs'] = $Tiku->_getTikuInfo($val['childs'],$o);
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
}