<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class TongbuController extends GlobalController {
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
    	$params = I('get.param');
		$result1 = preg_split("/[0-9]/", $params,0,PREG_SPLIT_NO_EMPTY);
		$result2 = preg_split("/[a-z]/", $params,0,PREG_SPLIT_NO_EMPTY );
		$new_params = array_combine($result1, $result2);
		
		$course_id = $_SESSION['course_id'];
		if(!$course_id){//错误跳转
			
		}
		$version_id = $new_params['v'];//版本
		$book_id = $new_params['b'];//教材
		$chapter_id = $new_params['c'];//章节
		//var_dump($feature_id);exit;

		//获取版本
		$version_data = $this->getVersions($course_id);
		$this->assign('version_data',$version_data);
		if(empty($version_id)){
			$version_id = $version_data[0]['id'];
		}
		//获取教材
		$book_data = $this->getBooks($version_id);
		$this->assign('book_data',$book_data);
		if(empty($book_id)){
			$book_id = $book_data[0]['id'];
		}
		//获取章节
		$chapter_data = $this->getChapters($book_id);
		$this->assign('chapter_data',$chapter_data);
		
		$where = "tiku_source.course_id=$course_id ";
		$chapterModel = M('chapter');
		if($chapter_id){
			$childs = $chapterModel->field('id')->where("parent_id=$chapter_id")->select();
			if($childs){
				$childs_count = count($childs);
				foreach($childs as $k=>$v){
					$chapter_ids .= $v['id']; 
					if($k<$childs_count-1) $chapter_ids .= ',';
				}
			}else{
				$chapter_ids = $chapter_id; 
			}
		}else{
			$chapter = $chapterModel->field("id")->where("book_id=$book_id AND parent_id<>0")->select();
			//echo $chapterModel->getlastsql();
			foreach($chapter as $key=>$val){
				$chapter_ids .= $val['id']; 
				$chapter_ids .= ',';
			}
			$chapter_ids = trim($chapter_ids,',');
		}
		$where .= " && tiku_to_chapter.chapter_id IN($chapter_ids)";
		//获取题库数据
		$Model = M('tiku');
		$count = json_decode($this->redis->GET(md5('tongbu_tiku_count_'.$where)),true);
		if($count=1){
			$result = $Model->field("COUNT(*) AS tp_count")
			->join("left join tiku_to_chapter on tiku.id=tiku_to_chapter.tiku_id")
			->join("tiku_source on tiku.source_id=tiku_source.id")->where($where)->find();
			//echo $Model->getLastSql();
			$count = $result['tp_count'];
			$this->redis->SET(md5('tongbu_tiku_count_'.$where),json_encode($count));
			$this->redis->EXPIRE(md5('tongbu_tiku_count_'.$where),C('REDIS_EXPIRE_TIME'));
		}
		if(!empty($_SESSION['order_by'])){
			if($_SESSION['order_by']=='new'){
				$order_by = 'tiku.id desc';
			}elseif($_SESSION['order_by']=='hot'){
				$order_by = 'tiku.used_times desc';
			}
			$this->assign('order_by',$_SESSION['order_by']);
		}
		//echo $count;
		//echo $Model->getLastSql();exit;
		$Page = new \Think\Page($count,10);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','末页');
		$page_show = $Page->_show($params);
		$this->assign('page_show',$page_show);
		$this->assign('totalPages',$Page->totalPages);
		$this->assign('nowPage',$Page->nowPage);
		$this->assign('prevPage',$Page->prevPage);
		$this->assign('nextPage',$Page->nextPage);
		$tiku_data = json_decode($this->redis->GET(md5('tongbu'.$where."limit $Page->firstRow,$Page->listRows")),true);
		if($tiku_data=1){
			$tiku_data = $Model->field("distinct tiku.`id`,tiku.`zan_times`,tiku.`used_times`,tiku_type.`type_name`,tiku.options,tiku.`content`,tiku.`clicks`,tiku_source.`source_name`,tiku.difficulty_id")
			->join("left join tiku_to_chapter on tiku.id=tiku_to_chapter.tiku_id")
			->join("tiku_source on tiku.source_id=tiku_source.id")
			->join("tiku_type on tiku.type_id=tiku_type.id")
			->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->redis->SET(md5('tongbu'.$where."limit $Page->firstRow,$Page->listRows"),json_encode($tiku_data));
			$this->redis->EXPIRE(md5($where."limit $Page->firstRow,$Page->listRows"),C('REDIS_EXPIRE_TIME'));
		}
		//var_dump($tiku_data);
		//echo $Model->getLastSql();
		$this->assign('tiku_data',$tiku_data);
		$this->assign('version_id',$version_id);
		$this->assign('book_id',$book_id);
		$this->assign('chapter_id',$chapter_id);
		//SEO
		$this->setMetaTitle('章节选题-题库列表页'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword('登录'.C('TITLE_SUFFIX'));
		$this->setMetaDescription('登录'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css'));
		$this->addJs(array('/js/menu.js','/js/xf.js'));
		$this->assign('jumpto','tiku');
		$this->assign('controller_name',strtolower(CONTROLLER_NAME));
        $this->display();
	}
	
	
	protected function _getTikuInfo($id_arr,&$o){
		$Model = M('tiku');
		foreach($id_arr as $key=>$val){
			$rs = $Model->field("id,content,options,answer,analysis")->where("id=$val")->find();
			$rs['order_char'] = $o;
			$tiku[] = $rs;
			$o++;
		}
		return $tiku;
	}

	
	/**
	 * 更新点击次数
	 */
	protected function updateClicks($id){
		$Model = M('tiku_source');
		$Model->where("id=$id")->setInc('clicks',1);
	}
	
}
?>