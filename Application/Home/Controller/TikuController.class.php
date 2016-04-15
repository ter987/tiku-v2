<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class TikuController extends GlobalController {
	var $parent_id;
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
		$tiku_cart = $this->_getTikuCart();
		$this->assign('tiku_cart',$tiku_cart);
		$this->assign('tikus_in_cart',json_encode($_SESSION['cart']));
	}
	
    public function index(){
    	$course_id = $_SESSION['course_id'];
		$course_pinyin = I('get.course');
		if(empty($course_id) || empty($course_pinyin)){//错误跳转
			
		}else{
			$this->assign('this_course_type',$_SESSION['course_type']);
			$this->assign('this_course_id',$_SESSION['course_id']);
			$this->assign('this_course_name',$_SESSION['course_name']);
			$this->assign('this_pinyin',$_SESSION['pinyin']);
		}
		//$course_id =3;
    	$params = I('get.param');
		$result1 = preg_split("/[0-9|-]/", $params,0,PREG_SPLIT_NO_EMPTY);
		$result2 = preg_split("/[a-z]/", $params,0,PREG_SPLIT_NO_EMPTY );
		$new_params = array_combine($result1, $result2);
		
		$feature_id = $new_params['f'];//试卷类型ID
		$difficulty_ids = $new_params['d'];
		$type_ids = $new_params['t'];//题型id
		$point_id = $new_params['p'];//知识点ID
		$wenli_id = $new_params['w'];//知识点ID
		$year = $new_params['y'];//年份
		$province_id = $new_params['a'];//省份
		$this->parent_id = $point_id;
		//echo $type_ids;
		if(!empty($type_ids))$type_id_arr = explode('-',$type_ids);
		if(!empty($difficulty_ids))$difficulty_id_arr = explode('-',$difficulty_ids);
		$this->assign('type_ids',$type_id_arr);
		$this->assign('difficulty_ids',$difficulty_id_arr);
		$this->assign('feature_id',$feature_id);//试卷类型
		$this->assign('wenli_id',$wenli_id);
		$this->assign('year',$year);
		if($point_id){
			$this->assign('point_id',$point_id);
		}else{
			$this->assign('point_id','null');
		}
		$this->assign('province_id',$province_id);
		//var_dump($_SESSION['course_id']);exit;
		
    	//获取题库类型
    	
		$this->assign('course_id',$course_id);
    	$tiku_type = $this->getTikuType($course_id);
		$this->assign('tiku_type',$tiku_type);
		//获取题库难度系数
		$tiku_difficulty = $this->getTikuDifficulty();
		$this->assign('tiku_difficulty',$tiku_difficulty);
		//获取科目类型，高中或初中
		$course_type = $this->getCourseType($course_id);
		//获取试卷类型
		$source_type = $this->getSourceType($course_type);
		$this->assign('source_type',$source_type);
		//获取知识点
		$points = $this->getPointsByCourseId($course_id);
		$this->assign('points',$points);
		
		$where = "tiku_source.course_id=$course_id ";
		//$where['tiku_source.course_id'] = ':course_id';
		$bind[':course_id'] = array($course_id,\PDO::PARAM_INT);
		$join ="tiku_source  on tiku_source.id=tiku.source_id";
		if(!empty($type_id_arr)){
			$type_in = implode(',',$type_id_arr);
			$where .= " && tiku.type_id IN(".$type_in.')';
			//$where['tiku.type_id'] = ':type_id';
			//$bind[':type_id'] = array($type_id,\PDO::PARAM_INT);
		}
		
		if(!empty($difficulty_id_arr)){
			$difficulty_in = implode(',',$difficulty_id_arr);
			$where .= " && tiku.difficulty_id IN(".$difficulty_in.')';
			//$where['tiku.difficulty_id'] = ':difficulty_id';
			//$bind[':difficulty_id'] = array($difficulty_id,\PDO::PARAM_INT);
		}
		if($feature_id){
			$where .= " && tiku_source.source_type_id=$feature_id";
			//$where['tiku_source.source_type_id'] = ':source_type_id';
			$bind[':source_type_id'] = array($feature_id,\PDO::PARAM_INT);
		}
		if($year){
			$where .= " && tiku_source.year=$year";
		}
		if($province_id){
			$where .= " && tiku_source.province_id=$province_id";
			//$where['tiku_source.province_id'] = ':province_id';
			$bind[':province_id'] = array($province_id,\PDO::PARAM_INT);
		}
		if($wenli_id){
			$where .= " && tiku_source.wen_li=$wenli_id";
			//$where['tiku_source.wen_li'] = ':wenli_id';
			$bind[':wenli_id'] = array($wenli_id,\PDO::PARAM_INT);
		}
		if($point_id){
			$_points = $this->getAllChildrenPointId($point_id);
			$join2= "tiku_to_point ON tiku_to_point.`tiku_id`=tiku.`id`";
			$where .= " && tiku_to_point.point_id IN ($_points)";
			//$where['tiku_source.wen_li'] = ':wenli_id';
			$bind[':wenli_id'] = array($wenli_id,\PDO::PARAM_INT);
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
		$Model = M('tiku');
		//获取年份数据 地区数据
		$year_data = json_decode($this->redis->GET(md5('tiku_year_'.$where)),true);
		if(!$year_data){
			$year_data = $Model->field("tiku_source.year")->join($join)->join($join2)->where($where." AND tiku_source.year <>''")->select();
			$year_data = $this->_array_unique($year_data);
			$this->redis->SET(md5('tiku_year_'.$where),json_encode($year_data));
			$this->redis->EXPIRE(md5('tiku_year_'.$where),C('REDIS_EXPIRE_TIME'));
		}
		
		$this->assign('year_data',$year_data);
		
		$province_data = json_decode($this->redis->GET(md5('province_data_'.$where)),true);
		if(!$province_data){
			$province_data = $Model->field("province.id,province.province_name")->join($join)->join($join2)->join("province on tiku_source.province_id=province.id")->where($where)->select();
			//var_dump($province_data);exit;
			$province_data = $this->_array_unique($province_data);
			$this->redis->SET(md5('province_data_'.$where),json_encode($province_data));
			$this->redis->EXPIRE(md5('province_data_'.$where),C('REDIS_EXPIRE_TIME'));
		}
		
		$this->assign('province_data',$province_data);

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
		//获取题库数据
		$count = S('tiku_count_'.$where);
		$count = json_decode($this->redis->GET(md5('tiku_count_'.$where)),true);
		if(!$count){
			$result = $Model->field("COUNT(*) AS tp_count")->join($join)->join($join2)->join($join3)->join($join4)->where($where)->find();
			//echo $Model->getLastSql();
			$count = $result['tp_count'];
			$this->redis->SET(md5('tiku_count_'.$where),json_encode($count));
			$this->redis->EXPIRE(md5('tiku_count_'.$where),C('REDIS_EXPIRE_TIME'));
		}
		
		//echo $count;
		//echo $Model->getLastSql();exit;
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev',' < 上一页');
		$Page->setConfig('next','下一页  >  ');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->_show($params);
		$this->assign('page_show',$page_show);
		$this->assign('totalPages',$Page->totalPages);
		$this->assign('nowPage',$Page->nowPage);
		$this->assign('prevPage',$Page->prevPage);
		$this->assign('nextPage',$Page->nextPage);
		//S(array('type'=>'Memcache','host'=>C('MEMCACHED_HOST'),'port'=>C('MEMCACHED_POST'),'expire'=>C('MEMCACHED_EXPIRE')));
		$tiku_data = json_decode($this->redis->GET(md5($where."limit $Page->firstRow,$Page->listRows")),true);
		if(!$tiku_data){
			$tiku_data = $Model->field("tiku.`id`,tiku.`zan_times`,tiku.`used_times`,tiku_type.`type_name`,tiku.options,tiku.`content`,tiku.`analysis`,tiku.`clicks`,tiku_source.`source_name`,tiku.difficulty_id")
			->join($join)
			->join($join2)
			->join("tiku_type on tiku.type_id=tiku_type.id")
			->join($join3)
			->join($join4)
			->where($where)->order($order_by)->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->redis->SET(md5($where."limit $Page->firstRow,$Page->listRows"),json_encode($tiku_data));
			$this->redis->EXPIRE(md5($where."limit $Page->firstRow,$Page->listRows"),C('REDIS_EXPIRE_TIME'));
		}
		//var_dump($tiku_data);
		//echo $Model->getLastSql();
		$this->assign('tiku_data',$tiku_data);
		//SEO
		$this->setMetaTitle('题库列表页'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword('登录'.C('TITLE_SUFFIX'));
		$this->setMetaDescription('登录'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css'));
		$this->addJs(array('/js/menu.js','/js/xf.js'));
		$this->assign('jumpto','tiku');
		$this->assign('controller_name',strtolower(CONTROLLER_NAME));
        $this->display();
	}
	/**
	 * 试题详情页
	 */
	public function detail(){
		$id = I('get.id');
		if(!id){//错误提示页面
			
		}
		$Modle = M('tiku');
		$data = $Modle->field("tiku.id,tiku.analysis,tiku.options,tiku.answer,tiku.content,tiku_source.course_id,tiku_course.id,tiku_course.course_name,tiku_source.source_name,tiku_difficulty.section")
		->join("tiku_source ON tiku_source.id=tiku.source_id")
		->join("left join tiku_difficulty ON tiku_difficulty.id=tiku.difficulty_id")
		->join("tiku_course ON tiku_course.id=tiku_source.course_id")
		->where("tiku.id=$id")->find();
		//echo $Modle->getLastSql();
		if(!$data){//错误提示页面
			
		}
		//获取推荐试题
		$recommend = $this->_getRecommendTiku($data['course_id']);
		
		$this->assign('recommend',$recommend);
		$this->assign('tiku_data',$data);
		$this->display();
	}
	public function ajaxSelectCourse(){
		$course_id = I('get.id');
		$pinyin = I('get.pinyin');
		$jumpto = I('jumpto');
		$Model = M('tiku_course');
		$result = $Model->where("pinyin='".$pinyin."'")->find();
		if($result){
			if($_SESSION['course_id'] != $result['id'])unset($_SESSION['cart']);
			$_SESSION['course_id'] = $result['id'];
			$_SESSION['course_type'] = $result['course_type'];
			$_SESSION['course_name'] = $result['course_name'];
			$_SESSION['pinyin'] = $result['pinyin'];
			setcookie(session_name(),session_id(),time()+C('SESSION_EXPIRE_TIME'),'/');
			if($jumpto == 'tiku'){
				$this->ajaxReturn(array('status'=>'ok','jumpto'=>'/tiku/'.$pinyin.'/'));
			}else if($jumpto == 'ceping'){
				$this->ajaxReturn(array('status'=>'ok','jumpto'=>'/ceping/'));
			}
			
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
			$data = $Model->field("tiku.id,tiku_type.type_name")->join("tiku_type ON tiku_type.id=tiku.type_id")->where("tiku.id=$id")->find();
			if($data){
				$_SESSION['cart'][$data['id']] = array('id'=>$data['id'],'type_name'=>$data['type_name']);
				
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
	 * 获取推荐试题
	 */
	protected function _getRecommendTiku($course_id){
		$Model = M('tiku');
		$result = $Model->field("tiku.id,tiku.content")->where("tiku_source.course_id=$course_id")->join("tiku_source ON tiku_source.id=tiku.source_id")->find();
		//echo $Model->getLastSql();
		return $result;
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
	 * 获取试卷类型
	 * 历年高考真题、名校模拟题。。。
	 */
	public function getSourceType($course_type){
		$data = S('source_type');
		if(!$data){
			$Model = M('source_type');
			$data = $Model->where("course_type=$course_type")->select();
			S('source_type',$data,array('type'=>'file','expire'=>C('FILE_CACHE_TIME')));
		}
		return $data;
	}
	public function getCourseType($course_id){
		$course_type = S('course_type');
		if(!$data){
			$Model = M('tiku_course');
			$data = $Model->field('course_type')->where("id=$course_id")->find();
			$course_type = $data['course_type'];
			S('course_type',$course_type,array('type'=>'file','expire'=>C('FILE_CACHE_TIME')));
		}
		return $course_type;
	}
	/**
	 * 判断是否名校模拟题
	 */
	public function isMingXiao($feature_id){
		if($feature_id==2){
			$data = array(array('type_name'=>'高考模拟','id'=>2),array('type_name'=>'月考试卷','id'=>3),array('type_name'=>'期中试卷','id'=>4),array('type_name'=>'期末试卷','id'=>5));
			
			return $data;
		}else{
			return false;
		}
	}
	/**
	 * 判断选择的课程是否数学并且选择的题目特点是否
	 * 历年高考真题或名校模拟题或原创
	 * 只有高中数学题目有分文理科
	 */
	public function isShuxue($course_id,$feature_id){
		$Model = M('tiku_course');
		$data_1 = $Model->where("id=$course_id")->find();
		
		$Model = M('tiku_feature');
		$data_2 = $Model->where("id=$feature_id")->find();
		
		if($data_1['course_name']=='数学' && $data_2['is_wenli']==1){
			return true;
		}else{
			return false;
		}
		
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