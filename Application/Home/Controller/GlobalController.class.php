<?php
namespace Home\Controller;
use Think\Controller;
class GlobalController extends Controller{
	/**
	 * 初始化
	 *
	*/
	public $redis;
	function _initialize()
	{
		$this->connectRedis();	
		$this->checkLogin();
		$this->getCourse();
	}
	public function checkLogin(){
		if(empty($_SESSION['user_id'])){
			if(!empty($_COOKIE['user_name'])){
				$this->autoLogin($_COOKIE['user_name'], $_COOKIE['password']);
			}
			
		}
		if(isset($_SESSION['user_id'])){
			$this->assign('user_id',$_SESSION['user_id']);
			$this->assign('nick_name',$_SESSION['nick_name']);
			$this->assign('user_type',$_SESSION['user_type']);
			$this->getCollectIds();
			$login_controller_arr = array('/member/login','/member/register','/member/resetpass');
			if(in_array('/'.strtolower(CONTROLLER_NAME).'/'.strtolower(ACTION_NAME), $login_controller_arr)){
				redirect('/member/');
			}
		}else{
			$pattern = '/studycenter\/\w+|shijuan\/\w+|member\/info|smart\/\w+|zuke\/\w+|member\/passchange|baogao\/\w+/';
			$uri = strtolower(CONTROLLER_NAME).'/'.strtolower(ACTION_NAME);
			if(preg_match($pattern,$uri,$match)){
				redirect('/member/login');
			}
		}
	}
	protected function connectRedis(){
		$this->redis = new \Redis();    
		$this->redis->pconnect(C('REDIS_HOSTNAME'),C('REDIS_PORT'));
	}
	protected function getCollectIds(){
		$Model = M('user_collected');
		$data = $Model->field("tiku_id")->where("user_id=".$_SESSION['user_id'])->select();
		$this->assign('tikus_in_collect',json_encode($data));
	}
	private function autoLogin($user_name,$password){
			$Model = M('User');
			$result = $Model->where("email='$user_name' OR telphone='$user_name'")->find();
			if(!$result){
				return false;
			}
			if(md5(md5($password.$result['salt']))!=$result['password']){
				return false;
			}
			$_SESSION['nick_name'] = $result['nick_name'];
			$_SESSION['user_id'] = $result['id'];
			$_SESSION['user_type'] = $result['type'];
			return true;
	}
	public function getCourse(){
		$Course = M('Tiku_course');
		$gaozhong_data = $Course->where('status=1 AND course_type=1')->select();
		if($gaozhong_data) $this->assign('gaozhong',$gaozhong_data);
		
		$chuzhong_data = $Course->where('status=1 AND course_type=2')->select();
		if($chuzhong_data) $this->assign('chuzhong_data',$chuzhong_data);
	}
	public function getAllCourse(){
		$Course = M('tiku_course');
		$courseData = $Course->where("status=1")->select();
		return $courseData;
	}
	/**
	 * 获取$point_id下的所有最后一级知识点
	 */
	public function getLastLevelPoint($point_id){
		static $data;
		$Model = M('tiku_point');
		$point = $Model->where("parent_id=$point_id")->select();
		foreach($point as $key=>$val){
			$this->getLastLevelPoint($val['id']);
			if(!$Model->where("parent_id=".$val['id'])->find()){
				$data[] = $val;
			}else{
				continue;
			}
			
		}	
		return $data;
	}
	public function addToCuoti($tiku_id,$s_answer){
		$cuotiModel = M('user_cuoti');
		if(!$result = $cuotiModel->where("user_id=".$_SESSION['user_id']." AND tiku_id=$tiku_id")->find()){
			$data['user_id'] = $_SESSION['user_id'];
			$data['tiku_id'] = $tiku_id;
			$data['s_answer'] = $s_answer;
			$data['update_time'] = time();
			if(!$cuotiModel->add($data)){
				return false;
			}else{
				return true;
			}
		}else{
			$data['s_answer'] = $s_answer;
			$data['update_time'] = time();
			if(!$cuotiModel->where("id=".$result['id'])->save($data)){
				return false;
			}else{
				return true;
			}
		}
	}
	public function addToUsed($tiku_id){
		$Model = M('user_used');
		if(!$Model->where("user_id=".$_SESSION['user_id']." AND tiku_id=$tiku_id")->find()){
			$data['user_id'] = $_SESSION['user_id'];
			$data['tiku_id'] = $tiku_id;
			$data['update_time'] = time();
			if(!$Model->add($data)){
				return false;
			}
		}
		return true;
	}
	/**
	 * 
	 */
	protected function getVersions($course_id){
		$data = S('versions_'.$course_id);
		if(!$data){
			$Model = M('version');
			$data = $Model->where("course_id=$course_id")->order("id asc")->select();
			S('versions_'.$course_id,$data,array('type'=>'file','expire'=>FILE_CACHE_TIME));
		}
		return $data;
	}
	protected function getBooks($version_id){
		$data = S('books_'.$version_id);
		if(!$data){
			$Model = M('books');
			$data = $Model->where("version_id=$version_id")->order("id asc")->select();
			S('books_'.$course_id,$data,array('type'=>'file','expire'=>FILE_CACHE_TIME));
		}
		return $data;
	}
	public function getChapters($book_id){
		$data = S('chapter_'.$book_id);
		if(!$data){
			$Model = M('chapter');
			$child_data = $Model->where("book_id=$book_id")->select();
			if(!$child_data){
				return false;
			}
		$data = $this->_getTree($child_data,0);
		}
		//var_dump($data);
		return $data;
		
	}
	public function getCourseById($id){
		if(!$id){
			return false;
		}
		$Model = M('tiku_course');
		$result = $Model->where("id=$id")->find();
		if($result){
			$this->assign('this_course_type',$result['course_type']);
			$this->assign('this_course_id',$result['course_id']);
			$this->assign('this_course_name',$result['course_name']);
			$this->assign('this_pinyin',$result['pinyin']);
			return $result;
		}else{
			return false;
		}
	}
	public function getMyCourse(){
		$User = M('user');
		$Course = M('tiku_course');
		$userInfo = $User->field("my_course")->where("id=".$_SESSION['user_id'])->find();
		if($userInfo['my_course']){
			$courseData = $Course->where("id=".$userInfo['my_course'])->find();
		}else{
			$courseData = $Course->find();
		}
		return $courseData;
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
	public function getUserInfo(){
		$Model = M('user');
		$data = $Model->where("id=".$_SESSION['user_id'])->find();
		return $data;
	}
    public function getTree(&$data, $parent_id = 0) {
        $Model = M('tiku_point');
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
	public function getFirstAndSecondPoint($course_id){
		$data = json_decode($this->redis->GET(md5('tiku_points_first_second_'.$course_id)),true);
		if(!$data){
			$Model = M('tiku_point');
			$child_data = $Model->where("course_id=$course_id AND (level=1 OR level=2)")->select();
			if(!$child_data){
				return false;
			}
		$data = $this->getTree($child_data,0);
		$this->redis->SET(md5('tiku_points_first_second_'.$course_id),json_encode($data));
		$this->redis->EXPIRE(md5('tiku_points_first_second_'.$course_id),C('REDIS_EXPIRE_TIME'));
		}
		return $data;
		
	}
	public function getTopLevelPoint(){
		$Model = M('tiku_point');
		$top = $Model->field("id,point_name")->where("parent_id=0 AND course_id=".$_SESSION['course_id'])->select();
		foreach($top as $val){
			$data['childs'] = $Model->field("id,point_name")->where("parent_id=".$val['id']." AND course_id=".$_SESSION['course_id'])->select();
			$data['top_id'] = $val['id'];
			$data['top_name'] = $val['point_name'];
			$second[] = $data;
		}
		$this->assign('top_point',$top);
		$this->assign('second_point',$second);
	}
	public function getBookByVersionId($version_id){
		$Model = M('books');
		$chapterModel = M('chapter');
		$book = $Model->where("version_id=$version_id")->select();
		foreach($book as $val){
			$data['top_name'] = $val['book_name'];
			$data['top_id'] = $val['id'];
			$top_chapter = $chapterModel->field("id,chapter_name")->where("parent_id=0 AND book_id=".$val['id'])->select();
			foreach($top_chapter as $key =>$v){
				$data['top_chapter'][$key] = $v;
				$second_chapter = $chapterModel->field("id,chapter_name")->where("parent_id=".$v['id']." AND book_id=".$val['id'])->select();
				$data['top_chapter'][$key]['childs'] = $second_chapter;
				
			}
			$second[] = $data;
			
		}
		$this->assign('top_point',$book);
		$this->assign('second_point',$second);
	}
	/**
	 * 获取题型
	 * 单选题、多选题。。。
	 */
	public function getTikuType($course_id){
		if(!empty($_SESSION['ceping'])){
			$Model = M('tiku_type');
			$data = $Model->field("tiku_type.`type_name`,tiku_type.`id`")
			->where("tiku_type.id=1")->select();
			$this->assign('ceping','yes');
			return $data;
		}
		$data = S('tiku_type_'.$course_id);
		if(!$data){
			$Model = M('tiku_type');
			$data = $Model->field("tiku_type.`type_name`,tiku_type.`id`")->join("course_to_type on tiku_type.id=course_to_type.type_id")->where("course_to_type.course_id=$course_id")->select();

			S('tiku_type_'.$course_id,$data,array('type'=>'file','expire'=>FILE_CACHE_TIME));
		}
		return $data;
	}
	public function getSecondLevelPoint(){
		$top_point_id = I('get.id');
		$top_point_name = I('get.name');
		if(empty($_GET['id'])){
			$Model = M('tiku_point');
			$top_data = $Model->field("id,point_name")->where("parent_id=0 AND course_id=".$_SESSION['course_id'])->find();
			$top_point_id = $top_data['id'];
			$top_point_name = $top_data['point_name'];
		}
		
		$Model = M('tiku_point');
		$data = $Model->field("id,point_name")->where("parent_id=".$top_point_id." AND course_id=".$_SESSION['course_id'])->select();
		if(empty($_GET['id'])){
			return array('top_id'=>$top_data['id'],'top_name'=>$top_point_name,'childs'=>$data);
		}else{
			$this->ajaxReturn(array('status'=>'success','top_id'=>$top_data['id'],'top_name'=>$top_point_name,'childs'=>$data));
		}
		
	}
	public function getVersionByCourseId(){
		if(empty($_SESSION['course_id'])){
			$courseModel = M('tiku_course');
			$course = $courseModel->find();
			$_SESSION['course_id'] = $course['id'];
		}
		$Model = M('version');
		$data = $Model->where("course_id=".$_SESSION['course_id'])->select();
		return $data;
	}
	public function ajaxChangeCourse(){
		$course_id = I('get.id');
		$_SESSION['course_id'] = $course_id;
	}
	public function ajaxGetTikuTypeByCourseId(){
		$id = I('get.id');
		$Model = M('tiku_type');
		$data = $Model->join("course_to_type on tiku_type.id=course_to_type.type_id")->where("course_to_type.course_id=$id")->select();
		$this->ajaxReturn(array('status'=>'success','data'=>$data));
	}
	public function _getTree(&$data, $parent_id = 0) {
        $Model = M('chapter');
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
	/**
	 * 获取题库难度数据
	 */
	public function getTikuDifficulty(){
		$data = S('tiku_difficulty');
		$data = json_decode($this->redis->GET(md5('tiku_difficulty')),true);
		if(!$data){
			$Model = M('tiku_difficulty');
			$data = $Model->order('degreen desc')->select();
			$this->redis->SET(md5('tiku_difficulty'),json_encode($data));
			$this->redis->EXPIRE(md5('tiku_difficulty'),C('REDIS_EXPIRE_TIME'));
		}
		return $data;
	}
	public function _getTikuCart(){
		if($_SESSION['cart']){
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
			}
			return $new_arr;
		}else{
			return false;
		}
	}
	/**
     * 数据列表
     *
     * @param $conditions 条件
     * @param $orders 排序
     * @param $listRows 每页显示数量
     * @param $joind 是否表关联
     * @param $table 关联表
     * @param $join 
     * @param $fields 取字段
     */
    public function getList($conditions = '', $orders = '' , $listRows = '')
    {
        $condition = !empty($conditions) ? $conditions : '' ;
        $pageCount = $this->dao->where($condition)->count();
        $listRows = empty($listRows) ? 15 : $listRows;
        $orderd = empty($orders) ? 'id DESC' : $orders;
        $paged = new page($pageCount, $listRows);
        $dataContentList = $this->dao->Where($condition)->Order($orderd)->Limit($paged->firstRow.','.$paged->listRows)->select();
        $pageContentBar = $paged->show();
        $this->assign('dataContentList', $dataContentList);
        $this->assign('pageContentBar', $pageContentBar);
        $this->display();
    }

    /**
     * 数据列表,表关联
     *
     * @param $conditions 条件
     * @param $orders 排序
     * @param $listRows 每页显示数量
     * @param $joind 是否表关联
     * @param $table 关联表
     * @param $join 
     * @param $fields 取字段
     */
    public function getJoinList($conditions = '', $orders = '' , $listRows = '', $table = '', $join = '', $fields = '')
    {
        $condition = !empty($conditions) ? $conditions : '' ;
        $pageCount = $this->dao->Where($condition)->Table($table)->Join($join)->Field($fields)->count();
        $listRows = empty($listRows) ? 15 : $listRows;
        $orderd = empty($orders) ? 'id DESC' : $orders;
        $paged = new page($pageCount, $listRows);
        $dataContentList = $this->dao->Table($table)->join($join)->field($fields)->Where($condition)->Order($orderd)->Limit($paged->firstRow.','.$paged->listRows)->select();
        $pageContentBar = $paged->show();
        $this->assign('dataContentList', $dataContentList);
        $this->assign('pageContentBar', $pageContentBar);
        $this->display();
    }

    /**
     * 数据集
     *
     * @param $conditions 条件
	 *
     */
    public function getDetail($conditions = '', $viewCount = false)
    {
        empty($conditions) && self::_message('errorUri', '查询条件丢失', U('Index/index'));
        $contentDetail = $this->dao->Where($conditions)->find();
        empty($contentDetail) && self::_message('errorUri', '记录不存在', U('Index/index'));
		//更新查看次数
		$viewCount && $this->dao->setInc($viewCount, $conditions);
        $this->assign('contentDetail', $contentDetail);
        $this->display($contentDetail['template']);
    }

    /**
     * 数据集,表关联
     * 此处查询条件可能为数组
     * @param $conditions 条件
     * @param $joind 是否表关联
     * @param $table 关联表
     * @param $join 
     * @param $fields 取字段
     */
    public function getJoinDetail($conditions = '', $viewCount = false, $table = '', $join = '', $fields = '')
    {
        empty($conditions) && self::_message('errorUri', '查询条件丢失', U('Index/index'));
		
		$condition1 = is_array($conditions) ? $conditions[0] : $conditions;
		$condition2 = is_array($conditions) ? $conditions[1] : $conditions;

        $contentDetail = $this->dao->Table($table)->Join($join)->Field($fields)->Where($condition1)->find();
        empty($contentDetail) && self::_message('errorUri', '记录不存在', U('Index/index'));
		//更新查看次数
		$viewCount && $this->dao->setInc($viewCount, $condition2);
        $this->assign('contentDetail', $contentDetail);
        $this->display($contentDetail['template']);
    }
	
    /**
     * 验证码
     *
     */
    public function verify()
    {
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }

    /**
     * 输出信息
     *
     * @param $type
     * @param $content
     * @param $jumpUrl
     * @param $time
     * @param $ajax
     */
    protected function _message($type = 'success', $content = '更新成功', $jumpUrl, $time = 3, $ajax = false)
    {
        //$jumpUrl = empty($jumpUrl) ? __URL__ : $jumpUrl ;
		$this->assign('type',$type);
		$this->assign('head_title','跳转提示');
        switch ($type){
            case 'success':
                $this->assign('jumpUrl', $jumpUrl);
                $this->assign('waitSecond', $time);
                $this->success($content, $ajax);
                break;
            case 'error':
                $this->assign('jumpUrl', 'javascript:history.back(-1);');
                $this->assign('waitSecond', $time);
                $this->assign('message', $content);
                $this->error($content, $ajax);
                break;
            case 'errorUri':
                $this->assign('jumpUrl', $jumpUrl);
                $this->assign('waitSecond', $time);
                $this->assign('message', $content);
                $this->error($content, $ajax);
                break;
            default:
                die('error type');
                break;
        }
    }
	/**
	 * 获取省份
	 */
	public function getProvince(){
		if($data = cache('province_data')){
				return $data;
		}else{
			$provinceModel = M('Province');
			$result = $provinceModel->where('prov_status=1')->order("prov_id ASC")->select();
			cache('province_data',$result,C('DATA_CACHE_TIME'));
			return $result;
		}
	}
	/**
	 * 根据省份id获取城市列表
	 */
	public function getCity(){
		$prov_id = $this->_post('prov_id');
		$cityModel = M('City');
		$result = $cityModel->where('province='.$prov_id)->select();
		return $result;
	}
	/**
	 * 文件上传
	 */
	public function upload($path){
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = C('UPLOAD_FILE_MAX');
		$upload->allowExts = C('UPLOAD_FILE_EXT');
		$upload->savePath = $path;
		
		if($upload->upload()){//成功返回数组
			return $upload->getUploadFileInfo();
		}else{//失败返回字符串
			return $upload->getErrorMsg();
		}
	}
	/**
	 * 设置、获取数据缓存
	 */
	 public function dataCache($name,$value='',$time=0){
	 	if($data = cache($name)){
	 		return $data;
	 	}else{
	 		cache($name,$value,$time);
			return cache($name);
	 	}
	 }
	 public function verifyCode(){
	 	$Verify = new \Think\Verify();
		$Verify->useCurve =false;
		$Verify->entry();
	 }

	 public function ajaxCheckVerifyCode(){
	 	$verify_code = I('post.param');
		$Verify = new \Think\Verify();
		//$this->ajaxReturn(array('status'=>$verify_code,'info'=>"验证码有误！"));
		if(!$Verify->check($verify_code)){
			$this->ajaxReturn(array('status'=>'n','info'=>"验证码有误！"));
		}else{
			$this->ajaxReturn(array('status'=>'y'));
		}
	 }
	 public function ajaxSendPvCode(){
	 	$telphone = I("get.telphone");
		$Model = M('msg_send_record');
		$data['client_ip'] = get_client_ip();
		$data['send_time'] = time();
		$result = $Model->where("client_ip='".$data['client_ip']."' AND FROM_UNIXTIME(send_time,'%Y-%m-%d')=CURDATE()")->order("id desc")->find();
		if($result){
			if((time()-$result['send_time'])<C('MESSAGE_INTERVAL')){
				$this->ajaxReturn(array('status'=>'error','message'=>'请'.C('MESSAGE_INTERVAL').'秒后再重新获取！'));
			}
			$count = $Model->where("client_ip='".$data['client_ip']."' AND FROM_UNIXTIME(send_time,'%Y-%m-%d')=CURDATE()")->count();
			if($count>C('DAY_MAX_MESSAGE_SEND')){
				$this->ajaxReturn(array('status'=>'error','message'=>'请勿重新发送短信！'));
			}
		}
		import('Org\Util\Sendmessage');
		$obj = new \Org\Util\Sendmessage();
		$rand_code = mt_rand(1000,9999);
		$message = C('MESSAGE_HEADER').'您的验证码是'.$rand_code.'。'.C('MESSAGE_END');
		$result = $obj->send_sms($message,$telphone);
		$result = json_decode($result,true);
		if($result['msg']=='OK'){
			$_SESSION['phone_vcode'] = $rand_code;
			setcookie(session_name(),session_id(),time()+C('MESSAGE_EXPIRE'),'/',C('EXT_DOMAIN'));  
			
			
			$Model->add($data);
			$this->ajaxReturn(array('status'=>'success'));
		}else{
			$this->ajaxReturn(array('status'=>'error','message'=>'发送失败，请重试！'));
		}
	 }
	 public function ajaxCheckPvCode(){
	 	$code = I('post.param');
	 	if($code==$_SESSION['phone_vcode']){
			$this->ajaxReturn(array('status'=>'y','info'=>'通过验证'));
		}else{
			$this->ajaxReturn(array('status'=>'n','info'=>'验证码有误！'));
		}
	 }
	 public function setMetaTitle($title){
	 	$this->assign('meta_title',$title);
	 }
	 public function setMetaKeyword($keyword){
	 	$this->assign('meta_keyword',$keyword);
	 }
	 public function setMetaDescription($description){
	 	$this->assign('meta_description',$description);
	 }
	 public function addCss($cssArr){
	 	foreach($cssArr as $val){
	 		$css .= '<link href="'.C('CSS_PATH').$val.'" rel="stylesheet" type="text/css" />'."\n";
	 	}
		$this->assign('my_css',$css);
	 }
	 public function addJs($jsArr){
	 	foreach($jsArr as $val){
	 		$js .= '<script src="'.C('JS_PATH').$val.'" type="text/javascript"></script>'."\n";
	 	}
		$this->assign('my_js',$js);
	 }
}
?>