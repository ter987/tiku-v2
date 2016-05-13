<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class ZukeController extends GlobalController {
	var $parent_id;
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
	}
	
    public function index(){
    	$course_id = $_SESSION['course_id'];
		$round_id = $_SESSION['round_id'];
		$resource_id = I('resource_id');
		if(!$course_id){
			$myCourse = $this->getMyCourse();
			$course_id = $myCourse['id'];
			$_SESSION['course_id'] = $course_id;
			$this->assign('this_course_type',$myCourse['course_type']);
			$this->assign('this_course_id',$myCourse['course_id']);
			$this->assign('this_course_name',$myCourse['course_name']);
			$this->assign('this_pinyin',$myCourse['pinyin']);
		}else{
			$myCourse = $this->getCourseById($course_id);
			$course_id = $myCourse['id'];
		}

		//获取教材版本
    	$round = $this->getRoundByCourseId($course_id);
    	$this->assign('round',$round);
		if(empty($round_id)){
			$round_id = $round[0]['id'];
			$_SESSION['round_id'] = $round_id;
		}
		$this->assign('this_round_id',$round_id);
		//获取专题数据
		$topicTree = $this->getTopicTreeByRoundId($round_id);
		$this->assign('topic_tree',$topicTree);
		//获取资源类型
		$resource = $this->getResourceByRoundId($round_id);
		$this->assign('resource',$resource);
		
		$this->setMetaTitle('组课'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword(''.C('TITLE_SUFFIX'));
		$this->setMetaDescription(''.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->assign('this_module','zuke');
        $this->display();
	}
	public function ajaxGetCaseData(){
		$course_id = $_SESSION['course_id'];
		$round_id = $_SESSION['round_id'];
		$resource_id = I('resource_id');
		
		$where = "zuke_case.round_id=$round_id ";
		if(!empty($resource_id)){
			$where .= " && zuke_case.resource_id=$resource_id";
		}
		
		if(!empty($_SESSION['order_by'])){
			if($_SESSION['order_by']=='new'){
				$order_by = 'tiku.id desc';
			}elseif($_SESSION['order_by']=='hot'){
				$order_by = 'tiku.used_times desc';
			}
			$this->assign('order_by',$_SESSION['order_by']);
		}
		//echo $join;exit;
		$Model = M('zuke_case');

		//过滤使用过的题目
		if(!empty($_SESSION['my_used']) && !empty($_SESSION['user_id'])){
			$usedModel = M('user_used');
			$result = $usedModel->where("user_id=".$_SESSION['user_id'])->select();
			if($result){
				foreach($result as $val){
					$used_ids .= $val['id'].',';
				}
				$used_ids = trim($used_ids,',');
				$where .= " && tiku.id NOT IN(".$used_ids.')';
			}
			//echo $where;exit;
			$this->assign('my_used',1);
		}
		//只选收藏的题目
		if(!empty($_SESSION['my_collected']) && !empty($_SESSION['user_id'])){
			$join3= "user_collected ON user_collected.`tiku_id`=tiku.`id`";
			$where .= " && user_collected.user_id=".$_SESSION['user_id'];
			$this->assign('my_collected',1);
		}
		//获取数据
		$cache_name = md5('zuke_case_count_'.$where);
		$count = json_decode($this->redis->GET($cache_name),true);
		if(!$count){
			$result = $Model->field("COUNT(*) AS tp_count")->where($where)->find();
			//echo $Model->getLastSql();
			$count = $result['tp_count'];
			$this->redis->SET($cache_name,json_encode($count));
			$this->redis->EXPIRE($cache_name,C('REDIS_EXPIRE_TIME'));
		}
		$curPage = empty($_GET['p'])?1:$_GET['p'];
		$limit = 10;
		$start = ($curPage-1)*$limit;
		$pageCount = ceil($count/10);
		//S(array('type'=>'Memcache','host'=>C('MEMCACHED_HOST'),'port'=>C('MEMCACHED_POST'),'expire'=>C('MEMCACHED_EXPIRE')));
		$cache_name = md5($where."limit $start,$limit");
		$zuke_data = json_decode($this->redis->GET($cache_name),true);
		if($zuke_data=1){
			$zuke_data = $Model->field("zuke_case.*,resource.title,zuke_topic.title as topic_title")->join("resource on resource.id=zuke_case.resource_id")
			->join("zuke_topic on zuke_case.topic_id=zuke_topic.id")
			->where($where)->order($order_by)->limit($start.','.$limit)->select();
			$this->redis->SET($cache_name,json_encode($zuke_data));
			$this->redis->EXPIRE($cache_name,C('REDIS_EXPIRE_TIME'));
		}
		$data = array('total'=>$count,'page_count'=>$pageCount,'data'=>$zuke_data);
		$this->ajaxReturn(array('status'=>'ok','data'=>$data));
	}
	public function getRoundByCourseId($course_id){
		$Model = M('course_round');
		$round = $Model->where("course_id=$course_id")->select();
		return $round;
	}
	public function ajaxChangeCourse(){
		$course_id = I('get.id');
		$result = $this->getCourseById($course_id);
		if($result){
			$_SESSION['course_id'] = $result['id'];
			unset($_SESSION['round_id']);
			setcookie(session_name(),session_id(),time()+C('SESSION_EXPIRE_TIME'),'/',C('EXT_DOMAIN'));
			$this->ajaxReturn(array('status'=>'ok'));
		}else{
			$this->ajaxReturn(array('status'=>'error'));
		}
	}
	public function getTopicTreeByRoundId($round_id){
		$Model = M('zuke_topic');
		$topic = $Model->where("round_id=$round_id")->select();
		$topicTree = $this->getTree($topic,0);
		return $topicTree;
	}
	public function getResourceByRoundId($round_id){
		$Model = M('resource');
		$resource = $Model->field("resource.*")->join("round_to_resource on round_to_resource.resource_id=resource.id")->where("round_to_resource.round_id=$round_id")->select();
		return $resource;
	}
	public function getTree(&$data, $parent_id = 0) {
        $Model = M('zuke_topic');
        $childs = $this->findChild($data, $parent_id);
		
        if (empty($childs)) {
            return null;
        }
        foreach ($childs as $key => $val) {
        	$result = $Model->where("parent_id=".$val['id'])->find();
            if ($result) {
                $treeList = $this->getTree($data, $val['id']);
                if ($treeList !== null) {
                    $childs[$key]['childs'] = $treeList;
                }
            }
        }

        return $childs;
    }
	public function findChild(&$data, $parent_id = 0) {
        $rootList = array();
        foreach ($data as $key => $val) {
            if ($val['parent_id'] == $parent_id) {
                $rootList[]   = $val;
                unset($data[$key]);
            }
        }
        return $rootList;
    }
	public function ajaxChangeRound(){
		$id = I('get.id');
		$Model = M('course_round');
		$result = $Model->where("course_id=".$_SESSION['course_id']." AND id=$id")->find();
		if($result){
			$_SESSION['round_id'] = $result['id'];
			setcookie(session_name(),session_id(),time()+C('SESSION_EXPIRE_TIME'),'/',C('EXT_DOMAIN'));
			$this->ajaxReturn(array('status'=>'ok'));
		}else{
			$this->ajaxReturn(array('status'=>'error'));
		}
	}
	public function ajaxSelectOrder(){
		$val = I('get.val');
		$_SESSION['order_by'] = $val;
		$this->ajaxReturn(array('status'=>'ok'));
	}
	public function ajaxFilter(){
		$val = I('get.val');
		if(empty($_SESSION['user_id'])){
			$this->ajaxReturn(array('status'=>'notlogin'));
		}
		if(!empty($_SESSION[$val])){
			unset($_SESSION[$val]);
		}else{
			$_SESSION[$val] = 1;
		}
		$this->ajaxReturn(array('status'=>'ok'));
	}
	public function ajaxBaocuo(){
		$id = I('get.id');
		$msg = I('get.msg');
		if(empty($_SESSION['user_id'])){
			$this->ajaxReturn(array('status'=>'notlogin'));
		}
		$tikuModel = M('tiku');
		$result = $tikuModel->where("id=$id")->find();
		if(!$result){
			$this->ajaxReturn(array('status'=>'error','msg'=>'该试题不存在'));
		}
		$Model = M('tiku_error');
		$data['tiku_id'] = $id;
		$data['user_id'] = $_SESSION['user_id'];
		$data['error_msg'] = $msg;
		$data['update_time'] = time();
		if(!$Model->add($data)){
			$this->ajaxReturn(array('status'=>'error','msg'=>'提交失败，请重试！'));
		}else{
			$this->ajaxReturn(array('status'=>'ok'));
		}
	}
	
	public function ajaxDelCart(){
		unset($_SESSION['cart']);
		$this->ajaxReturn(array('status'=>'success'));
	}
	public function ajaxAddTiku(){
		$id = I('get.id');
		if($_SESSION['cart'][$id]){//如果已存在试题蓝，则移出
			unset($_SESSION['cart'][$id]);
			//$this->ajaxReturn(array('status'=>'success','data'=>$_SESSION['cart']));
		}else{
			$Model = M('tiku');
			$data = $Model->field("tiku.id,tiku_type.type_name,tiku.type_id")->join("tiku_type ON tiku_type.id=tiku.type_id")->where("tiku.id=$id")->find();
			if($data){
				$_SESSION['cart'][$data['id']] = array('id'=>$data['id'],'type_name'=>$data['type_name'],'type_id'=>$data['type_id']);
				
			}else{
				$this->ajaxReturn(array('status'=>'error'));
			}
		}
		
		foreach ($_SESSION['cart'] as $key => $val) {
			if(!in_array($val['type_name'],$arr)){
				$arr[] = $val['type_name'];
			}
			
		}
		
		foreach($arr as $k=>$v){
			$count = 0;
			foreach($_SESSION['cart'] as $key=>$val){
				
				if($v==$val['type_name']){
					$new_arr[$k]['type_name'] = $val['type_name'];
					$count ++;
					$new_arr[$k]['num'] = $count;
				}
				
			}
			//$new = $new_arr;
		}
		$this->ajaxReturn(array('status'=>'success','data'=>$_SESSION['cart'],'type_data'=>$new_arr));
		
	}

	/**
	 * 根据course_id获取所有知识点
	 */
	public function getPointsByCourseId($course_id){
		$data = S('tiku_points_'.$course_id);
		if(!$data){
			$Model = M('tiku_point');
			$child_data = $Model->where("course_id=$course_id")->select();
			if(!$child_data){
				return false;
			}
		$data = $this->getTree($child_data,0);
		S('tiku_points_'.$course_id,$data,array('type'=>'file','expire'=>C('FILE_CACHE_TIME')));
		}
		return $data;
		
	}
	

	/**
	 * 获取子节点ID
	 */
	public function getAllChildrenPointId($parent_point_id){
		$Model = M('tiku_point');
		$child_data = $Model->where("parent_id=$parent_point_id")->select();
		if($child_data){//如果存在子节点
			foreach($child_data as $val){
				$GLOBALS['str'] .= ','.$val['id'];
				$this->getAllChildrenPointId($val['id']);
			}
			
		}
		return $this->parent_id.$GLOBALS['str'];
	}
	/**
	 * 二维数组去重并排序
	 */
	protected function _array_unique($arr){
		$new = array();
		foreach($arr as $val){
			if(!in_array($val,$new)){
				$new[] = $val;
			}
		}
		sort($new);
		return $new;
	}
	/**
	 * 格式化参数
	 */
	public function formatParams(){
		
	}
	public function selectCourse(){
		$this->display();
	}
	
}