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
    	unset($_SESSION['zuke']);
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
		//获取组课试题蓝
		$zukeCart = $this->getZukeCart($resource);
		$this->assign('zuke_cart',$zukeCart);
		//获取题型数据
		$tixing = $this->getTixingByRoundId($round_id);
		$this->assign('tixing',$tixing);
		
		$this->setMetaTitle('组课'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword(''.C('TITLE_SUFFIX'));
		$this->setMetaDescription(''.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css'));
		$this->addJs(array('js/menu.js','js/xf.js'));
		$this->assign('this_module','zuke');
        $this->display();
	}
	public function center(){
		$myCourse = $this->getCourseById($_SESSION['course_id']);
		if(empty($_SESSION['zuke_cart'])){
			redirect('/');
		}
		asort($_SESSION['zuke_cart']);
		//var_dump($_SESSION['zuke_cart']);
		$index = array('一','二','三','四','五','六','七');
		$i = -1;
		foreach($_SESSION['zuke_cart'] as $key=>$val){
			if(!isset($data[$val['resource_id']]['order_char'])){
				$i++;
			}
			$data[$val['resource_id']]['order_char'] = $index[$i];
			$new[$val['resource_id']]['order_char'] = $index[$i];
			$data[$val['resource_id']]['resource_name'] = $val['resource_name'];
			$new[$val['resource_id']]['resource_name'] = $val['resource_name'];
			$type = $val['resource_id']==10?2:1;
			$content = $this->getCaseContent($val['id'], $type);
			$data[$val['resource_id']]['childs'][] = array('id'=>$val['id'],'type'=>$type,'content'=>$content);
			$new[$val['resource_id']]['childs'][] = array('id'=>$val['id'],'type'=>$type);
		}
		$_SESSION['zuke']['shiti'] = $new;
		//var_dump($_SESSION['zuke']['shiti']);
		$nianduan = $myCourse['course_type']==1?'高中':'初中';
		$_SESSION['zuke']['title'] = $nianduan.$myCourse['course_name'].'教案-'.date("Ymd");
		$this->assign('zuke_title',$_SESSION['zuke']['title']);
		$this->assign('zuke_data',$data);
		
		$this->setMetaTitle('组课'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword(''.C('TITLE_SUFFIX'));
		$this->setMetaDescription(''.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','style_content.css','preview.css','exam_info.css'));
		$this->addJs(array('js/dialog.js','js/xf.js','js/jquery-ui-1.11.2.custom/jquery-ui.js'));
		$this->assign('this_module','zuke');
		$this->display();
	}
	public function getZukeCart($resource){
		$data = array();
		foreach($resource as $val){
			if(empty($data[$val['id']])){
				$data[$val['id']]['title'] = $val['title'];
				$data[$val['id']]['total'] = 0;
			}
			foreach($_SESSION['zuke_cart'] as $v){
				if($v['resource_id']==$val['id']){
					$data[$val['id']]['total'] += 1;
				}
			}
		}
		return $data;
	}
	public function getCaseContent($id,$type){
		if($type==1){
			$Model = M('zuke_case');
			$result = $Model->field('content')->where("id=$id")->find();
			return $result['content'];
		}elseif($type==2){
			$Model = M('zuke_shiti');
			$result = $Model->field('content')->where("id=$id")->find();
			return $result['content'];
		}
		return false;
	}
	public function ajaxGetAnalysisById(){
		$id = I('get.id');
		$Model = M('zuke_shiti');
		$result = $Model->field("answer,analysis")->where("id=$id")->find();
		if($result){
			$this->ajaxReturn(array('status'=>'ok','data'=>$result));
		}else{
			$this->ajaxReturn(array('status'=>'error'));
		}
	}
	public function ajaxClearCart(){
		unset($_SESSION['zuke_cart']);
		$this->ajaxReturn(array('status'=>'ok'));
	}
	public function ajaxGetResourceName(){
		$shiti_no = I('get.shiti_no');
		$data['resource_name'] = $_SESSION['zuke']['shiti'][$shiti_no]['resource_name'];
		$data['resource_note'] = $_SESSION['zuke']['shiti'][$shiti_no]['resource_note'];
		$this->ajaxReturn(array('status'=>'ok','data'=>$data));
	}
	public function ajaxEditResource(){
		$shiti_no = I('get.shiti_no');
		$resource_name = I('get.resource_name');
		$resource_note = I('get.resource_note');
		$_SESSION['zuke']['shiti'][$shiti_no]['resource_name'] = $resource_name;
		$_SESSION['zuke']['shiti'][$shiti_no]['resource_note'] = $resource_note;
		$data['resource_name'] = $_SESSION['zuke']['shiti'][$shiti_no]['resource_name'];
		$data['resource_note'] = $_SESSION['zuke']['shiti'][$shiti_no]['resource_note'];
		$this->ajaxReturn(array('status'=>'ok','data'=>$data));
	}
	public function ajaxMoveDown(){
		$key = I('get.key');
		$shiti_no = I('get.shiti_no');
		//$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$shiti_id] = array('id'=>);
		$childs = $_SESSION['zuke']['shiti'][$shiti_no]['childs'];
		if($key+1!=count($childs)){
			$down = $_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key+1];
			$me = $_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key];
			$_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key+1] = $me;
			$_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key] = $down;
			$this->ajaxReturn(array('status'=>'ok'));
		}
	}
	public function ajaxEditTitle(){
		$title = I('get.title');
		$_SESSION['zuke']['title'] = $title;
		$this->ajaxReturn(array('status'=>'ok','data'=>$_SESSION['zuke']['title']));
	}
	public function ajaxMoveUp(){
		$key = I('get.key');
		$shiti_no = I('get.shiti_no');
		//$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$shiti_id] = array('id'=>);
		$childs = $_SESSION['zuke']['shiti'][$shiti_no]['childs'];
		if($key!=0){
			$up = $_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key-1];
			$me = $_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key];
			$_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key-1] = $me;
			$_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key] = $up;
			$this->ajaxReturn(array('status'=>'ok'));
		}
	}
	public function ajaxGetCaseData(){
		$course_id = $_SESSION['course_id'];
		$round_id = $_SESSION['round_id'];
		$resource_id = I('get.resource_id');
		$topic_id = I('get.topic_id');
		
		$where = "zuke_case.round_id=$round_id ";
		if(!empty($resource_id)){
			$where .= " && zuke_case.resource_id=$resource_id";
		}
		if(!empty($topic_id)){
			$childs = $this->getChildTopicById($topic_id);
			foreach($childs as $val){
				$ids .= $val['id'].',';
			}
			$ids = trim($ids,',');
			$where .= " && zuke_case.topic_id IN($ids)";
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
		$data = array('total'=>$count,'page_count'=>$pageCount,'cur_page'=>$curPage,'data'=>$zuke_data);
		$this->ajaxReturn(array('status'=>'ok','data'=>$data));
	}
	public function ajaxDelete(){
		$key = I('get.key');
		$shiti_no = I('get.shiti_no');
		unset($_SESSION['zuke']['shiti'][$shiti_no]['childs'][$key]);
		foreach($_SESSION['zuke']['shiti'][$shiti_no]['childs'] as $k=>$val){
			$data[] = $val;
		}
		$_SESSION['zuke']['shiti'][$shiti_no]['childs'] = $data;
		$this->ajaxReturn(array('status'=>'ok'));
	}
	public function ajaxGetShitiData(){
		$course_id = $_SESSION['course_id'];
		$round_id = $_SESSION['round_id'];
		$topic_id = I('get.topic_id');
		$type_id = I('get.type_id');
		$tixing_id = I('get.tixing_id');
		$difficulty_id = I('get.difficulty_id');
		$year_id = I('get.year_id');
		
		$where = "zuke_shiti.round_id=$round_id ";
		if(!empty($type_id)){
			$where .= " && zuke_shiti.type=$type_id";
		}
		if(!empty($difficulty_id)){
			$where .= " && zuke_shiti.difficulty=$difficulty_id";
		}
		if(!empty($year_id)){
			$where .= " && zuke_shiti.year=$year_id";
		}
		if(!empty($tixing_id)){
			$where .= " && zuke_shiti.tixing_id=$tixing_id";
		}
		if(!empty($topic_id)){
			$childs = $this->getChildTopicById($topic_id);
			foreach($childs as $val){
				$ids .= $val['id'].',';
			}
			$ids = trim($ids,',');
			$where .= " && zuke_shiti.topic_id IN($ids)";
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
		$Model = M('zuke_shiti');

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
			$zuke_data = $Model->field("zuke_shiti.*,zuke_topic.title as topic_title,zuke_tixing.title as tixing_title")
			->join("zuke_topic on zuke_shiti.topic_id=zuke_topic.id")
			->join("zuke_tixing on zuke_shiti.tixing_id=zuke_tixing.id")
			->where($where)->order($order_by)->limit($start.','.$limit)->select();
			$this->redis->SET($cache_name,json_encode($zuke_data));
			$this->redis->EXPIRE($cache_name,C('REDIS_EXPIRE_TIME'));
		}
		$data = array('total'=>$count,'page_count'=>$pageCount,'cur_page'=>$curPage,'data'=>$zuke_data);
		$this->ajaxReturn(array('status'=>'ok','data'=>$data));
	}
	public function ajaxSave(){
		$Model = M('user_jiaoan');
		if(isset($_SESSION['zuke']['id'])&&$Model->where("id=".$_SESSION['zuke']['id'].' AND user_id='.$_SESSION['user_id'])->find()){//更新数据库
			$data['id'] = $_SESSION['zuke']['id'];
			$data['update_time'] = time();
			$data['content'] = json_encode($_SESSION['zuke']['shiti']);
			$data['title'] = $_SESSION['zuke']['title'];
			if($Model->data($data)->save()){
				$this->ajaxReturn(array('status'=>'ok','action'=>'update'));
			}else{
				$this->ajaxReturn(array('status'=>'error','action'=>'update'));
			}
		}else{//添加到数据库
			$data['title'] = $_SESSION['zuke']['title'];
			$data['user_id'] = $_SESSION['user_id'];
			$data['create_time'] = $data['update_time'] = time();
			$data['content'] = json_encode($_SESSION['zuke']['shiti']);
			$data['course_id'] = $_SESSION['course_id'];
			if($id = $Model->add($data)){
				$_SESSION['zuke']['id'] = $id;
				$this->ajaxReturn(array('status'=>'ok','action'=>'add'));
			}else{
				$this->ajaxReturn(array('status'=>'error','action'=>'add'));
			}
		}
		
	}
	public function getRoundByCourseId($course_id){
		$Model = M('course_round');
		$round = $Model->where("course_id=$course_id")->select();
		return $round;
	}

	public function getChildTopicById($topic_id){
		static $children = array();
		$Model = M('zuke_topic');
		$topic = $Model->where("id=$topic_id")->find();
		if($topic['has_child']){
			$child = $Model->where("parent_id=$topic_id")->select();
			foreach($child as $val){
				$this->getChildTopicById($val['id']);
			}
		}else{
			//var_dump($topic);exit;
			$children[] =  $topic;
		}
		return $children;
	}
	public function ajaxChangeCourse(){
		$course_id = I('get.id');
		$result = $this->getCourseById($course_id);
		if($result){
			$_SESSION['course_id'] = $result['id'];
			unset($_SESSION['round_id']);
			unset($_SESSION['zuke_cart']);
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
	public function getTixingByRoundId($round_id){
		$Model = M('zuke_tixing');
		$data = $Model->field("zuke_tixing.*")->join("round_to_tixing on round_to_tixing.tixing_id=zuke_tixing.id")->where("round_to_tixing.round_id=$round_id")->select();
		return $data;
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
    public function ajaxAddCart(){
    	$id = I('get.id');
		$type = I('get.type');
		$key = $type.'-'.$id;
		if($_SESSION['zuke_cart'][$key]){//如果已存在试题蓝，则移出
			unset($_SESSION['zuke_cart'][$key]);
			//$this->ajaxReturn(array('status'=>'success','data'=>$_SESSION['cart']));
		}else{
			if($type==1){
				$Model = M('zuke_case');
				$data = $Model->field("zuke_case.id,resource.title,resource.id as resource_id")->join("resource ON resource.id=zuke_case.resource_id")->where("zuke_case.id=$id")->find();
				if($data){
					$_SESSION['zuke_cart'][$key] = array('resource_id'=>$data['resource_id'],'id'=>$data['id'],'resource_name'=>$data['title']);
					
				}else{
					$this->ajaxReturn(array('status'=>'error'));
				}
			}else if($type==2){
				$Model = M('zuke_shiti');
				$data = $Model->field("zuke_shiti.id")->where("zuke_shiti.id=$id")->find();
				if($data){
					$_SESSION['zuke_cart'][$key] = array('resource_id'=>10,'id'=>$data['id'],'resource_name'=>'典型例题');
					
				}else{
					$this->ajaxReturn(array('status'=>'error'));
				}
			}
		}
		$arr = array();
		foreach ($_SESSION['zuke_cart'] as $key => $val) {
				$arr[$val['resource_id']]['resource_name'] = $val['resource_name'];
				$arr[$val['resource_id']]['my_total'] += 1;
			
		}
		//var_dump($_SESSION['zuke_cart']);exit;
		$resource = $this->getResourceByRoundId($_SESSION['round_id']);
		$resource_arr = array();
		foreach($resource as $val){
			$resource_arr[$val['id']]['resource_name'] = $val['title'];
			$resource_arr[$val['id']]['my_total'] = empty($arr[$val['id']])?0:$arr[$val['id']]['my_total'];
		}

		$this->ajaxReturn(array('status'=>'ok','data'=>$_SESSION['zuke_cart'],'resource_data'=>$resource_arr));
    }
	public function ajaxGoCenter(){
		if(count($_SESSION['zuke_cart'])){
			$this->ajaxReturn(array('status'=>'ok'));
		}else{
			$this->ajaxReturn(array('status'=>'error'));
		}
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