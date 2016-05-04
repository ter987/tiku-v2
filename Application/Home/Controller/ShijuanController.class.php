<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class ShijuanController extends GlobalController {
	var $parent_id;
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
		$Tiku = A('Tiku');
		$tiku_cart = $Tiku->_getTikuCart();
		$this->assign('tiku_cart',$tiku_cart);
		$this->assign('tikus_in_cart',json_encode($_SESSION['cart']));
	}
	
    public function index(){
    	if(empty($_SESSION['cart'])){
    		redirect('/');
    	}
		if(!$this->getCourseById($_SESSION['course_id'])){
			redirect('/');
		}
		unset($_SESSION['shijuan']);
		//var_dump($_SESSION['cart']);exit;
		//if(empty($_SESSION['shijuan']['title'])){
	    	$shijuan_type = !empty($_SESSION['shijuan']['shijuan_banshi'])?$_SESSION['shijuan']['shijuan_banshi']:1;//默认的试卷类型：随堂练习
	    	$shijuantypeModel = M('shijuan_banshi');
			$shijuantype_data = $shijuantypeModel->where("id=$shijuan_type")->find();
	    	$courseModel = M('tiku_course');
	    	$course_data = $courseModel->where("id=".$_SESSION['course_id'])->find();
			$grade = $course_data['course_type']==1?'高中':'初中';
	    	$_SESSION['shijuan']['title'] = $grade.$course_data['course_name'].$shijuantype_data['name'].'-'.date('Ymd');
			$_SESSION['shijuan']['pdftitle'] = $grade.$course_data['course_name'].$shijuantype_data['name'];
			
			foreach ($_SESSION['cart'] as $key => $val) {
				if(!in_array($val['type_name'],$arr)){
					$arr[] = $val['type_name'];
				}
			
			}
			//var_dump($_SESSION['cart']);
			foreach($arr as $k=>$v){
				$count = 0;
				foreach($_SESSION['cart'] as $key=>$val){
					if(empty($new_arr[$k]['childs'])) $new_arr[$k]['childs']=array();
					if($v==$val['type_name']){
						$new_arr[$k]['type_name'] = $val['type_name'];
						$new_arr[$k]['type_id'] = $val['type_id'];
						$new_arr[$k]['childs'] = array_merge($new_arr[$k]['childs'],array($val['id']));
					}
					
				}
			}
			
			//var_dump($new_arr);
			//区分第一卷和第二卷
			foreach($new_arr as $k=>$v){
				if($v['type_name']=='单选题' || $v['type_name']=='多选题'){
					$data[1][] = $new_arr[$k];
				}else{
					$data[2][] = $new_arr[$k];
				}
			}
			//var_dump($data);
			if(empty($data[1])) unset($_SESSION['shijuan'][1]);
			if(empty($data[2])) unset($_SESSION['shijuan'][2]);
	
			foreach($data as $key=>$val){
				//if(empty($_SESSION['shijuan'][$key]['t_title'])){
					$oc = array(1=>'一',2=>'二');
					$_SESSION['shijuan'][$key]['t_title'] = '';//第N卷标题
					if($key==1){
						$_SESSION['shijuan'][$key]['t_title'] = '第I卷（选择题）';//第1卷标题
					}else{
						$_SESSION['shijuan'][$key]['t_title'] = '第II卷（非选择题）';//第2卷标题
					}
					$_SESSION['shijuan'][$key]['note'] = '';//第N卷注释
					
					$count = 0;
					$shiti_count_per_juan = 0;
					foreach($val as $k=>$v){
						$count ++;
						$shiti_count = count($v['childs']);
						$_SESSION['shijuan'][$key]['shiti'][$count]['t_title'] = $v['type_name'].'(共'.$shiti_count.'小题)';
						$_SESSION['shijuan'][$key]['shiti'][$count]['childs'] = $v['childs'];
						$_SESSION['shijuan'][$key]['shiti'][$count]['type_id'] = $v['type_id'];
						$_SESSION['shijuan'][$key]['shiti'][$count]['type_name'] = $v['type_name'];
						$_SESSION['shijuan'][$key]['shiti'][$count]['count'] = $shiti_count;
						$shiti_count_per_juan += $shiti_count;
					}
					$_SESSION['shijuan'][$key]['note'] = '本试卷第'.$oc[$key].'部分共有'.$shiti_count_per_juan.'道试题。';//第N卷注释
				//}
			}
		//}
		$oa = array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七');
		$last = 0;
		$o = 1;
		//var_dump($_SESSION['shijuan'][1]);
		if($_SESSION['shijuan'][1]){
			$first_juan['t_title'] = $_SESSION['shijuan'][1]['t_title'];
			$first_juan['note'] = $_SESSION['shijuan'][1]['note'];
			foreach($_SESSION['shijuan'][1]['shiti'] as $k=>$v){
				$first_juan['shiti'][$k]['t_title'] = $v['t_title'];
				//var_dump($v['childs']);exit;
				$first_juan['shiti'][$k]['childs'] = $this->_getTikuInfo($v['childs'],$o,1,$k);
				$first_juan['shiti'][$k]['order_char']  = $oa[$k];
				$last = $k;
				$_SESSION['shijuan'][1]['shiti'][$k]['order_char'] = $oa[$k];
			}
		}
		if($_SESSION['shijuan'][2]){
			$second_juan['t_title'] = $_SESSION['shijuan'][2]['t_title'];
			$second_juan['note'] = $_SESSION['shijuan'][2]['note'];
			foreach($_SESSION['shijuan'][2]['shiti'] as $k=>$v){
				$second_juan['shiti'][$k]['t_title'] = $v['t_title'];
				$second_juan['shiti'][$k]['childs'] = $this->_getTikuInfo($v['childs'],$o,2,$k);
				$second_juan['shiti'][$k]['order_char'] = $oa[$k+$last];
				$_SESSION['shijuan'][2]['shiti'][$k]['order_char'] = $oa[$k+$last];
			}
		}
		// $_SESSION['shijuan']['attention'] = "
// 注意事项：</br>
// 1.答题前请填写好自己的班级、姓名、考号等信息</br>
// 2.请将答案正确填写在答题卡上
		// ";
		//var_dump($_SESSION['shijuan']);
		$shijuan['title'] = $_SESSION['shijuan']['title'];
		$this->assign('first_juan',$first_juan);
		$this->assign('shijuan_title',$_SESSION['shijuan']['title']);
		$this->assign('shijuan_subtitle',$_SESSION['shijuan']['subtitle']);
		$this->assign('shijuan_attention',$_SESSION['shijuan']['attention']);
		$this->assign('second_juan',$second_juan);
		$this->assign('shijuan',$shijuan);
		$this->assign('score',$_SESSION['shijuan']['score']);
		//SEO
		$this->setMetaTitle('组卷'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword('登录'.C('TITLE_SUFFIX'));
		$this->setMetaDescription('登录'.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','style_content.css','preview.css'));
		$this->addJs(array('js/menu.js','js/xf.js','js/jquery-ui-1.11.2.custom/jquery-ui.js','js/dialog.js'));
        $this->display();
	}
	public function ajaxSendStudent(){
		$ids = trim(I('get.ids'),',');
		if(empty($ids)){
			$this->ajaxReturn(array('status'=>'error','msg'=>'请选择学生'));
		}
		//先保存试卷
		if(!$this->saveShijuan()){
			$this->ajaxReturn(array('status'=>'error'));
		}
		
		$userModel = M('user');
		$result = $userModel->where("id IN($ids) AND type=1")->select();
		$cepingModel = M('ceping');
		$cepingModel->startTrans();
		$cpData['title'] = $_SESSION['shijuan']['title'];
		$cpData['teacher'] = $_SESSION['user_id'];
		$cpData['content'] = json_encode($_SESSION['shijuan']);
		$cpData['course_id'] = $_SESSION['course_id'];
		$cpData['limit_time'] = $_SESSION['shijuan']['limittime'];
		$cpData['type'] = 1;
		$cpData['score'] = $_SESSION['shijuan']['score'];
		$cpData['join_num'] = count($result);
		$cpData['unjoined_num'] = count($result);
		$cpData['create_time'] = time();
		$data = array();
		if(!empty($_SESSION['shijuan'][1])){
			foreach($_SESSION['shijuan'][1]['shiti'] as $val){
				$data = array_merge($data,array($val));
			}
		}
		if(!empty($_SESSION['shijuan'][2])){
			foreach($_SESSION['shijuan'][2]['shiti'] as $val){
				$data = array_merge($data,array($val));
			}
		}
		$cpData['shiti'] = json_encode($data);
		//var_dump($data);exit;
		$ce_id = $cepingModel->add($cpData);
		
		$extendModel = M('ceping_extend');
		$order_char = 1;
		$_result = true;
		if(!empty($_SESSION['shijuan'][1])){
			foreach($_SESSION['shijuan'][1]['shiti'] as $val){
				foreach($val['childs'] as $v){
					$extendData['tiku_id'] = $v;
					$extendData['ceping_id'] = $ce_id;
					$extendData['order_char'] = $order_char;
					if(!$extendModel->add($extendData)){
						$_result = false;
					}
					$order_char++;
				}
			}
		}
		if(!empty($_SESSION['shijuan'][2])){
			foreach($_SESSION['shijuan'][2]['shiti'] as $val){
				foreach($val['childs'] as $v){
					$extendData['tiku_id'] = $v;
					$extendData['ceping_id'] = $ce_id;
					$extendData['order_char'] = $order_char;
					if(!$extendModel->add($extendData)){
						$_result = false;
					}
					$order_char++;
				}
			}
		}
		$joinModel = M('ceping_jon');
		foreach($result as $val){
			$joinData['student'] = $val['id'];
			$joinData['ceping_id'] = $ce_id;
			if(!$joinModel->add($joinData)){
				$_result = false;
			}
		}
		if($ce_id && $_result){
			$cepingModel->commit();
			$this->ajaxReturn(array('status'=>'success'));
		}else{
			$cepingModel->rollback();
			$this->ajaxReturn(array('status'=>'error'));
		}
	}
	public function ajaxGetXiaotiScore(){
		$juan_no = I('get.juan_no');
		$shiti_no = I('get.shiti_no');
		if(empty($juan_no) || empty($shiti_no)){
			$this->ajaxReturn(array('status'=>'error'));
		}else{
			$xiaoti_score = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['xiaoti_score'];
			$title = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['t_title'];
			if(empty($title)){
				$this->ajaxReturn(array('status'=>'error'));
			}else{
				$this->ajaxReturn(array('status'=>'success','data'=>array('title'=>$title,'xiaoti_score'=>$xiaoti_score)));
			}
			
		}
	}
	public function ajaxChangeOneTeam(){
		$juan_no = I('get.juan_no');
		$shiti_no = I('get.shiti_no');
		$childs = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'];
		$Model = M('tiku');
		foreach($childs as $key=>$val){
			$geted = true;
			while($geted){
				$result = $Model->field("tiku.type_id,tiku_to_point.point_id")
				->join("tiku_to_point on tiku.id=tiku_to_point.tiku_id")
				->where("tiku.id=".$val['id'])->find();
				$data = $Model->field("tiku.id")->join("tiku_to_point on tiku.id=tiku_to_point.tiku_id")->where("tiku.type_id=".$result['type_id']." AND tiku_to_point.point_id=".$result['point_id'])->select();
				if($data){
					shuffle($data);
					$data = array_slice($data,0,1);
					if(in_array($new,$data[0]['id'])){
						continue;
					}else{
						$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key]['id'] = $data[0]['id'];
						$geted = false;
					}
					
				}
			}
		}
		foreach($_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'] as $kk=> $v){
			$new = $Model->where("id=".$v['id'])->find();
			$html .= '<div data-score="" id="quesbox1579176" class="quesbox"><div class="quesopmenu" shiti_id="'.$new['id'].'" juan_no="'.$juan_no.'" shiti_no="'.$shiti_no.'" key="'.$kk.'">
	<a class="ico_zjgn1" onclick="autoXuanti($(this))">自动选题</a>
	<a class="ico_zjgn2">手动选题</a>
	<a class="ico_zjgn3" onclick="moveUp($(this))">上移</a>
	<a class="ico_zjgn4" onclick="moveDown($(this))">下移</a>
	<a class="ico_zjgn5" onclick="jiexi($(this))">解析</a>
	<a class="ico_zjgn6" onclick="deleteMe($(this))">删除</a>
	<a class="ico_zjgn7" onclick="toBaocuo($(this))">纠错</a>
</div>

<!-- 纠错 -->
<div class="xf_jiecuobox">
	<textarea name="" id="" cols="30" rows="10" placeholder="描述下纠错问题吧，我们会及时改正"></textarea>
	<div><a href="JavaScript:;" onclick="baocuo($(this),'.$new['id'].');">确定</a><a href="JavaScript:;" onclick="quxiao($(this));">取消</a></div>
</div>
<!-- 纠错end -->
<div class="quesdiv" id="quesdiv1579176">
	<table>
	<tbody>
	<tr>
		<td valign="top">
			<span class="quesindex"><b>'.$v['order_char'].'</b>.</span><span class="tips"></span>
		</td>
		<td>
			<p style="font-size:10.5pt; line-height:150%; margin:0pt; orphans:0; text-align:justify; widows:0">
				'.htmlspecialchars_decode($new['content']);
		if($new['type_id'] == 1 || $new['type_id']==6){
				$options = json_decode($new['options']);
				$options_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E');
				$k = 0;
				foreach($options as $k=>$val){
	            	$html .= '<p>
	            		
	            			<span style="float:left;line-height:18px;font-size:14px;padding-right:30px;" class="em2">';
	            	$html .= $options_index[$k].'.'.$val;		
	            	$html .=		'</span>
	            		
	            	</p>';
            	}
         }   	
		$html .=	'</p>
		</td>
	</tr>
	</tbody>
	</table>
	<br>
	<div class="xf_jiexibox">
		<p>答案：'.htmlspecialchars_decode($new['answer']).'</p>
		<p>试题解析：</p>
		<p>'.htmlspecialchars_decode($new['analysis']).'</p>
	</div>
</div></div>';
		}
		$this->ajaxReturn(array('status'=>'ok','data'=>$html));
	}
	//替换数组的键和值
	public function ajaxChangeOne(){
		$juan_no = I('get.juan_no');
		$shiti_no = I('get.shiti_no');
		$shiti_id = I('get.shiti_id');
		$key = I('get.key');
		//$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$shiti_id] = array('id'=>);
		$order_char = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key]['order_char'];
		unset($_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key]);
		$Model = M('tiku');
		$result = $Model->field("tiku.type_id,tiku_to_point.point_id")
		->join("tiku_to_point on tiku.id=tiku_to_point.tiku_id")
		->where("tiku.id=$shiti_id")->find();
		$data = $Model->field("tiku.id")->join("tiku_to_point on tiku.id=tiku_to_point.tiku_id")->where("tiku.type_id=".$result['type_id']." AND tiku_to_point.point_id=".$result['point_id'])->select();
		shuffle($data);
		$data = array_slice($data,0,1);
		$new = $Model->field("id,content,options,answer,analysis")->where("id=".$data[0]['id'])->find();
		//$id = new['id'];
		$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key] = array('order_char'=>$order_char,'id'=>$new['id']);
		ksort($_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs']);

		$html = '<div class="quesopmenu" shiti_id="'.$new['id'].'" juan_no="'.$juan_no.'" shiti_no="'.$shiti_no.'" key="'.$key.'">
	<a class="ico_zjgn1" onclick="autoXuanti($(this))">自动选题</a>
	<a class="ico_zjgn2">手动选题</a>
	<a class="ico_zjgn3" onclick="moveUp($(this))">上移</a>
	<a class="ico_zjgn4" onclick="moveDown($(this))">下移</a>
	<a class="ico_zjgn5" onclick="jiexi($(this))">解析</a>
	<a class="ico_zjgn6" onclick="deleteMe($(this))">删除</a>
	<a class="ico_zjgn7" onclick="toBaocuo($(this))">纠错</a>
</div>

<!-- 纠错 -->
<div class="xf_jiecuobox">
	<textarea name="" id="" cols="30" rows="10" placeholder="描述下纠错问题吧，我们会及时改正"></textarea>
	<div><a href="JavaScript:;" onclick="baocuo($(this),'.$new['id'].');">确定</a><a href="JavaScript:;" onclick="quxiao($(this));">取消</a></div>
</div>
<!-- 纠错end -->
<div class="quesdiv" id="quesdiv1579176">
	<table>
	<tbody>
	<tr>
		<td valign="top">
			<span class="quesindex"><b>'.$order_char.'</b>.</span><span class="tips"></span>
		</td>
		<td>
			<p style="font-size:10.5pt; line-height:150%; margin:0pt; orphans:0; text-align:justify; widows:0">
				'.htmlspecialchars_decode($new['content']);
		if($result['type_id'] == 1 || $result['type_id']==6){
				$options = json_decode($new['options']);
				$options_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E');
				$k = 0;
				foreach($options as $k=>$val){
	            	$html .= '<p>
	            		
	            			<span style="float:left;line-height:18px;font-size:14px;padding-right:30px;" class="em2">';
	            	$html .= $options_index[$k].'.'.$val;		
	            	$html .=		'</span>
	            		
	            	</p>';
            	}
         }   	
		$html .=	'</p>
		</td>
	</tr>
	</tbody>
	</table>
	<br>
	<div class="xf_jiexibox">
		<p>答案：'.htmlspecialchars_decode($new['answer']).'</p>
		<p>试题解析：</p>
		<p>'.htmlspecialchars_decode($new['analysis']).'</p>
	</div>
</div>';
		$this->ajaxReturn(array('status'=>'ok','data'=>$html));
	}
	public function ajaxDelete(){
		$key = I('get.key');
		$juan_no = I('get.juan_no');
		$shiti_no = I('get.shiti_no');
		$shiti_id = I('get.shiti_id');
		unset($_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key]);
		foreach($_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'] as $k=>$val){
			if($k>$key){
				$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$k]['order_char'] -= 1;
			}
		}
		for($i=$shiti_no+1;$i<15;$i++){
			if(!empty($_SESSION['shijuan'][$juan_no]['shiti'][$i])){
				foreach($_SESSION['shijuan'][$juan_no]['shiti'][$i]['childs'] as $k=>$val){
					$_SESSION['shijuan'][$juan_no]['shiti'][$i]['childs'][$k]['order_char'] -= 1;
				}
			}
		}
		sort($_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs']);
		if($juan_no==1 && !empty($_SESSION['shijuan'][2])){
			for($i=1;$i<15;$i++){
				if(!empty($_SESSION['shijuan'][2]['shiti'][$i])){
					foreach($_SESSION['shijuan'][2]['shiti'][$i]['childs'] as $k=>$val){
						$_SESSION['shijuan'][2]['shiti'][$i]['childs'][$k]['order_char'] -= 1;
					}
				}
			}
		}
		$this->ajaxReturn(array('status'=>'ok'));
	}
	public function ajaxMoveDown(){
		$key = I('get.key');
		$juan_no = I('get.juan_no');
		$shiti_no = I('get.shiti_no');
		$shiti_id = I('get.shiti_id');
		//$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$shiti_id] = array('id'=>);
		$childs = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'];
		if($key+1!=count($childs)){
			$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key+1]['order_char'] -= 1;
			$down = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key+1];
			$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key]['order_char'] += 1;
			$me = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key];
			$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key+1] = $me;
			$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key] = $down;
			$this->ajaxReturn(array('status'=>'ok'));
		}
	}
	public function ajaxMoveUp(){
		$key = I('get.key');
		$juan_no = I('get.juan_no');
		$shiti_no = I('get.shiti_no');
		$shiti_id = I('get.shiti_id');
		//$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$shiti_id] = array('id'=>);
		$childs = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'];
		if($key!=0){
			$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key-1]['order_char'] += 1;
			$up = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key-1];
			$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key]['order_char'] -= 1;
			$me = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key];
			$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key-1] = $me;
			$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'][$key] = $up;
			$this->ajaxReturn(array('status'=>'ok'));
		}
	}
	public function ajaxEditXiaotiScore(){
		$juan_no = I('get.juan_no');
		$shiti_no = I('get.shiti_no');
		$xiaoti_score = I('get.xiaoti_score');
		$old = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['t_title'];
		$count = $_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['count'];
		$total = $count*$xiaoti_score;
		$new = preg_replace('/(\(\S+\))/','(共'.$count.'小题，每小题'.$xiaoti_score.'分，共'.$total.'分)',$old);
		$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['t_title'] = $new;
		$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['scole'] = $total;
		$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['xiaoti_score'] = $xiaoti_score;
		
		if(!empty($_SESSION['shijuan'][1])){
			foreach($_SESSION['shijuan'][1]['shiti'] as $v){
				$first_score += $v['scole'];
			}
		}
		if(!empty($_SESSION['shijuan'][2])){
			foreach($_SESSION['shijuan'][2]['shiti'] as $v){
				$second_score += $v['scole'];
			}
		}
		$_SESSION['shijuan']['score'] = $first_score+$second_score;
		if(preg_match('/满分：\d+分/',$_SESSION['shijuan']['subtitle'])){
			$_SESSION['shijuan']['subtitle'] = preg_replace('/满分：\d+分/','满分：'.$_SESSION['shijuan']['score'].'分',$_SESSION['shijuan']['subtitle']);
		}else{
			$_SESSION['shijuan']['subtitle'] .= ' 满分：'.$_SESSION['shijuan']['score'].'分';
		}
		$this->ajaxReturn(array('status'=>'success','title'=>$new,'juan_no'=>$juan_no,'shiti_no'=>$shiti_no,'subtitle'=>$_SESSION['shijuan']['subtitle']));
	}
	public function ajaxEditShijuanTitle(){
		$title = I('get.title');
		$subtitle = I('get.subtitle');
		$limittime = I('get.limittime');
		$_SESSION['shijuan']['title'] = $title;
		$_SESSION['shijuan']['subtitle'] = $subtitle.' 时间：'.$limittime.'分钟';
		if(!empty($limittime)){
			$_SESSION['shijuan']['limittime'] = $limittime;
			if(preg_match('/时间：\d+分钟/',$_SESSION['shijuan']['subtitle'])){
				$_SESSION['shijuan']['subtitle'] = preg_replace('/时间：\d+分钟/','时间：'.$_SESSION['shijuan']['limittime'].'分',$_SESSION['shijuan']['subtitle']);
			}else{
				$_SESSION['shijuan']['subtitle'] .= ' 时间：'.$_SESSION['shijuan']['limittime'].'分钟';
			}
		}else{
			unset($_SESSION['shijuan']['limittime']);
			$_SESSION['shijuan']['subtitle'] = preg_replace('/时间：\d+分钟/',‘’,$_SESSION['shijuan']['subtitle']);
		}
		$this->ajaxReturn(array('status'=>'success','data'=>array('title'=>$_SESSION['shijuan']['title'],'subtitle'=>$_SESSION['shijuan']['subtitle'])));
	}
	public function ajaxFirstjuanTitle(){
		$title = I('get.title');
		$note = I('get.note');
		$_SESSION['shijuan'][1]['t_title'] = $title;
		$_SESSION['shijuan'][1]['note'] = $note;
		$this->ajaxReturn(array('status'=>'success','title'=>$title,'note'=>$note));
	}
	public function test(){
		Vendor('PhpWord.src.PhpWord.Autoloader');
		\PhpOffice\PhpWord\Autoloader::register();
		// Creating the new document...
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$section = $phpWord->addSection();
		
		

		//$section->getStyle()->setPageNumberingStart(1);
		$footer = $section->addFooter();
		 $textbox = $section->addTextBox(
		    array(
		        'width' => \PhpOffice\PhpWord\Shared\Drawing::centimetersToPixels(2.5),
			    'height' => \PhpOffice\PhpWord\Shared\Drawing::centimetersToPixels(21),
			    'positioning' => 'relative',
			    'posHorizontalRel' => 'page',
			    'posVerticalRel' => 'page',
			    'vPos' => 'center',
			    'hPos' => 'left',
			    'layoutFlow' => 'vertical',
			    'layoutFlowAlt' => 'bottom-to-top',
			    'borderColor'=> 'white'
		    )
		);
		//$phpWord->addParagraphStyle('fStyle', $fontStyle);
		//$phpWord->addFontStyle('fStyle', $fontStyle);
		//$textrun = $section->addTextRun();
		$textrun = $textbox->addTextRun();
		$textrun->addText('   ');
		$textrun->addText('学校',array('size'=>'15'),array('align' => 'center'));
		$textrun->addText('   ');
		$textrun->addText('                                ',array('underline'=>'single'));
		$textrun->addText('   ');
		$textrun->addText('班级',array('size'=>'15'),array('align' => 'center'));
		$textrun->addText('   ');
		$textrun->addText('                                ',array('underline'=>'single'));
		$textrun->addText('   ');
		$textrun->addText('姓名',array('size'=>'15'),array('align' => 'center'));
		$textrun->addText('   ');
		$textrun->addText('                                ',array('underline'=>'single'));
		$textrun->addText('   ');
		$textrun->addText('学号',array('size'=>'15'),array('align' => 'center'));
		$textrun->addText('   ');
		$textrun->addText('                                ',array('underline'=>'single'));
		$textbox->addText('                                                                                                                                                                                                                                      ',array('underline'=>'dotted'));
		$textbox->addText('              密                    封                    线                    内                    不                    要                    答                    题                                                        ',array('size'=>'15','underline'=>'single'));
		//$textbox->addPreserveText('ceshi ');
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('helloWorld.doc');exit;
		exit;
		//$objWriter->save(Yii::app()->params['exportToDir'].$filename.".docx");
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename=docment.docx');
        //header("Content-Type: application/docx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header("Cache-Control: public");
        header('Expires: 0');
        $objWriter->save("php://output");
		exit;
		//echo $_SERVER['DOCUMENT_ROOT'];
		exec("wkhtmltopdf\bin\wkhtmltopdf.exe D:/xampp/htdocs/tiku/Public/attache/html/20160212.html  D:/xampp/htdocs/tiku/Public/attache/pdf/高中数学20160212.pdf",$return);
		var_dump($return);
		exit;
		Vendor('tcpdf.tcpdf');
		$pdf = new \tcpdf\TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
		// set font
		$pdf->SetFont('stsongstdlight', '', 8);
		
		// define some html content for testing
		$txt = '<span style="vertical-align: middle;">已知全集，，则集合<img  width="43" height="28" align="middle"    src="/Public/tikupics/20151126/19/24/5656ebefadc581448537071.gif">你说地方</span>';
		// add a page
		$pdf->AddPage();
		
		
		// write html text
		$pdf->writeHTML($txt, true, false, true, false, '');
		
		//Close and output PDF document
		$pdf->Output('example064.pdf', 'I');
	}
	public function addFont(){
		Vendor('tcpdf.include.tcpdf_fonts');
		$result = \tcpdf\TCPDF_FONTS::addTTFfont($_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/tcpdf/fonts/DroidSansFallback.ttf', 'DroidSansFallback', '', 32);
		var_dump($result);
	}
	public function createToPdf(){
		Vendor('tcpdf.tcpdf');
		$pdf = new \tcpdf\TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		//Vendor('tcpdf.include.tcpdf_fonts');
		/*
		 * ArialNarrowSpecialG1Bold
		 */
		$result = \tcpdf\TCPDF_FONTS::addTTFfont($_SERVER['DOCUMENT_ROOT'].'/ThinkPHP/Library/Vendor/tcpdf/fonts/cid0cs.ttf', 'cid0cs', '', 32);
		$pdf->SetFont('cid0cs', '', 8);
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->AddPage();
		$txt = '<h1 style="text-align:center;">'.$_SESSION['shijuan']['pdftitle'].'</h1>';
		$txt .= '<font style="font-size:13;text-align:center;">满分：'.$_SESSION['shijuan']['score'].'</font>';
		$txt .= '<p style="font-size:13;text-align:center;">班级：<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>姓名：<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>考号：<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>';
		
		$oa = array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七');
		$last = 0;
		$o = 1;
		$answer_part = array();
		if($_SESSION['shijuan'][1]){
			$option_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E');
			$txt .= '<strong style="font-size:13;text-align:center;">'.$_SESSION['shijuan'][1]['t_title'].'</strong>';
			$txt .= '<p style="font-size:13;">'.$_SESSION['shijuan'][1]['note'].'</p>';
			foreach($_SESSION['shijuan'][1]['shiti'] as $k=>$v){
				$childs = $this->_getTikuInfo($v['childs'],$o);
				$answer_part = array_merge($answer_part,$childs);
				//var_dump($answer_part);exit;
				$last = $k;
				$txt .= '<strong style="font-size:13;">'.$oa[$k].'、'.$v['t_title'].'</strong>';
				foreach($childs as $key=>$val){
					$txt .= '<p style="font-size:13;">'.$val['order_char'].'.'.htmlspecialchars_decode($val['content']).'</p>';
					if($val['options']){
						$options = json_decode($val['options']);
						$option_len = 0;
						foreach($options as $opt){
							preg_match_all('/src="([\s|\S]+)"/U',$opt,$matchs);
							$opt = preg_replace('/<img[\s|\S]+>/U','',$opt);
							$option_len += strlen($opt)*10;
							foreach($matchs[1] as $m1){
								$info = getimagesize('http://'.$_SERVER['HTTP_HOST'].$m1);
								if($info){
									$option_len += $info[0];
								}
							}
						}
						$txt .= '<p style="font-size:13;"><table>';
						if($option_len>400 && $option_len<800){
							$options = array_chunk($options,2);
							foreach($options as $son){
								$txt .= '<tr>';
								foreach($son as $d=>$c){
									$txt .= '<td width="300px;" >'.$option_index[$d].'.'.$c.'</td>';
								}
								$txt .= '</tr>';
							}
						}else if($option_len>800){
							foreach($options as $d=>$c){
								$txt .= '<tr><td width="800px;">'.$option_index[$d].'.'.$c.'</td></tr>';
							}
						}else{
							$txt .= '<tr>';
							foreach($options as $d=>$c){
								$txt .= '<td width="100px;">'.$option_index[$d].'.'.$c.'</td>';
							}
							$txt .= '</tr>';
						}
						
						$txt .= '</table></p>';
					//break;
					
					}
					//解析跟在试题后面
					if($_SESSION['answer_order']==1){
						$txt .= '<p style="font-size:11;">试题解析：';
						$txt .= htmlspecialchars_decode($val['analysis']);
						$txt .= '</p>';
						
						$txt .= '<p style="font-size:11;">答案：';
						$txt .= htmlspecialchars_decode($val['answer']);
						$txt .= '</p>';
						
					}
				}
			}
		}
		$txt .= '<br />';
		if($_SESSION['shijuan'][2]){
			$txt .= '<strong style="font-size:13;text-align:center;">'.$_SESSION['shijuan'][2]['t_title'].'</strong>';
			$txt .= '<p style="font-size:13;">'.$_SESSION['shijuan'][2]['note'].'</p>';
			foreach($_SESSION['shijuan'][2]['shiti'] as $k=>$v){
				$childs = $this->_getTikuInfo($v['childs'],$o);
				$answer_part = array_merge($answer_part,$childs);
				$last = $k;
				$txt .= '<strong style="font-size:13;">'.$oa[$k].'、'.$v['t_title'].'</strong>';
				//break;
				foreach($childs as $key=>$val){
					$txt .= '<p style="font-size:13;">'.$val['order_char'].'.'.htmlspecialchars_decode($val['content']).'</p>';
					//break;
				}
				if(strpos($v['t_title'],'解答题')!==false){
					$txt .= '<br /><br /><br /><br /><br />';
				}
				//解析跟在试题后面
				if($_SESSION['answer_order']==1){
					$txt .= '<p style="font-size:11;">试题解析：';
					$txt .= htmlspecialchars_decode($val['analysis']);
					$txt .= '</p>';
					
					$txt .= '<p style="font-size:11;">答案：';
					$txt .= htmlspecialchars_decode($val['answer']);
					$txt .= '</p>';
				}
			}
			
		}
		$pdf->writeHTML($txt, true, false, true, false, '');
		$pdf->AddPage();
		$txt = '';
		//解析跟试卷分离
		if($_SESSION['answer_order']==2){
			$txt .= '<h1 style="text-align:center;">答案部分</h1>';
			$txt .= '<br /><br />';
			foreach($answer_part as $val){
				$txt .= '<p style="font-size:13;">'.$val['order_char'].'.试题解析：'.htmlspecialchars_decode($val['analysis']).'</p>';
				$txt .= '<p style="font-size:13;">答案：'.htmlspecialchars_decode($val['answer']).'</p>';
			}
		}
		$pdf->writeHTML($txt, true, false, true, false, '');
		
		//Close and output PDF document
		$pdf->Output('example064.pdf', 'I');
	}
	/**
	 * 生成word文件
	 */
	public function createToWord(){
		// if(empty($_SESSION['shijuan'])){
    		// redirect('/');
    	// }
    	//var_dump($_SESSION['shijuan']);exit;
		Vendor('PhpWord.src.PhpWord.Autoloader');
		\PhpOffice\PhpWord\Autoloader::register();
		Vendor('PhpOffice.PhpWord.Shared.Font');
		$PHPWord_Shared_Font = new \PhpOffice\PhpWord\Shared\Font();
		// Creating the new document...
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		// Every element you want to append to the word document is placed in a section. So you need a section:
		$model_array = array(
			'A4'=>array('width'=>'20.9','height'=>'29.6','colsnum'=>'1','orientation'=>'portrait'),
			'A3'=>array('width'=>'29.6','height'=>'41.91','colsnum'=>'2','orientation'=>'landscape'),
			'B5'=>array('width'=>'18.1','height'=>'25.6','colsnum'=>'1','orientation'=>'portrait'),
			'B4'=>array('width'=>'24.9','height'=>'35.2','colsnum'=>'2','orientation'=>'landscape'),
		);
		// $sectionStyle = array(
		    // 'pageSizeW' => $PHPWord_Shared_Font->centimeterSizeToTwips($model_array[$_SESSION['shijuan_model']]['width']),
		    // 'pageSizeH' => $PHPWord_Shared_Font->centimeterSizeToTwips($model_array[$_SESSION['shijuan_model']]['height']),
		    // 'colsNum'	=> $model_array[$_SESSION['shijuan_model']]['colsnum'],
		    // 'orientation'	=> $model_array[$_SESSION['shijuan_model']]['orientation']
		// );
		$section = $phpWord->addSection();
		//$section->getStyle()->setPageNumberingStart(1);
		//$footer = $section->addFooter();
		//$footer->addTextboxes();
		//$footer->addPreserveText('第{PAGE}页(共{NUMPAGES}页).');
		//$header = $section->addHeader();
		//$header->addText('头部');
		// You can directly style your text by giving the addText function an array:
		$section->addText($_SESSION['shijuan']['title'], array( 'size'=>'15','bold'=>true),array('align' => 'center'));
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		//$objWriter->save(Yii::app()->params['exportToDir'].$filename.".docx");
        
		$section->addText('满分：'.$_SESSION['shijuan']['score'], array( 'size'=>'13'),array('align' => 'center'));
		$section->addText('班级：_________  姓名：_________  考号：_________', array( 'size'=>'13'),array('align' => 'center'));
		$section->addTextBreak();//换行
		
		$oa = array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七');
		$last = 0;
		$o = 1;
		$answer_part = array();
		if($_SESSION['shijuan'][1]){
			$option_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E');
			$section->addText($_SESSION['shijuan'][1]['t_title'],array('size'=>13,'bold'=>true),array('align' => 'center'));
			$section->addText($_SESSION['shijuan'][1]['note'],array('size'=>13));
			foreach($_SESSION['shijuan'][1]['shiti'] as $k=>$v){
				$childs = $this->getTikuInfo($v['childs']);
				$answer_part = array_merge($answer_part,$childs);
				//var_dump($answer_part);exit;
				$last = $k;
				$section->addText($oa[$k].'、'.$v['t_title'],array('size'=>13,'bold'=>true));
				foreach($childs as $key=>$val){
					$textrun = $section->createTextRun(array('widowControl'=>'true'));
					$question = trim(strip_tags(htmlspecialchars_decode($val['content']),'<img>'));
					$question = preg_replace('/(&nbsp;)*/','',$question);
					$text_arr = preg_split('/<img[\s|\S]+>/U',$question);
					preg_match_all('/src="[\s|\S]+"/U',$question,$matchs);
					//var_dump($matchs);exit;
					if($matchs){
						$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
						$i=0;
						$text_count = count($text_arr);
						$img_count = count($img_arr);
						$textrun->addText($val['order_char'].'.',array('size'=>13));
						while($i<$text_count){
							//echo $text_arr[$i];exit;
							$textrun->addText($text_arr[$i],array('size'=>13));
							if($i==$img_count) break;
							$textrun->addImage($img_arr[$i]);
							$i++;
						}
						
					}else{
						$section->addText($val['order_char'].'.'.$question,array('size'=>13,'align'=>'both'));
					}
					if($val['options']){
						$options = json_decode($val['options']);
						$option_len = 0;
						foreach($options as $opt){
							preg_match_all('/src="([\s|\S]+)"/U',$opt,$matchs);
							$opt = preg_replace('/<img[\s|\S]+>/U','',$opt);
							$option_len += strlen($opt)*10;
							foreach($matchs[1] as $m1){
								$info = getimagesize('http://'.$_SERVER['HTTP_HOST'].$m1);
								if($info){
									$option_len += $info[0];
								}
							}
						}
						$table = $section->addTable('myTable');
						if($option_len>400 && $option_len<800){
							$options = array_chunk($options,2);
							foreach($options as $son){
								$table->addRow();
								foreach($son as $d=>$c){
									$cell = $table->addCell(5000,array('valign'=>'center'));
									$textrun2 = $cell->addTextRun();
									$option = trim($c);
									$option = preg_replace('/(&nbsp;)*/','',$option);
									$text_arr = preg_split('/<img[\s|\S]+>/U',$option);
									preg_match_all('/src="[\s|\S]+"/U',$option,$matchs);
									if($matchs){
										$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
										$i=0;
										$text_count = count($text_arr);
										$img_count = count($img_arr);
										$textrun2->addText($option_index[$d].'.',array('size'=>13));
										while($i<$text_count){
											//echo $text_arr[$i];exit;
											$textrun2->addText($text_arr[$i],array('size'=>13));
											//if($i==$img_count) {$i++;break;}
											$textrun2->addImage($img_arr[$i]);
											$i++;
										}
									}else{
										$textrun2->addText($option_index[$d].'.'.$option,array('size'=>13));
									}
								}
							}
						}else if($option_len>800){
							foreach($options as $d=>$c){
								$table->addRow();
								$cell = $table->addCell(10000,array('valign'=>'center'));
								$textrun2 = $cell->addTextRun();
								$option = trim($c);
								$option = preg_replace('/(&nbsp;)*/','',$option);
								$text_arr = preg_split('/<img[\s|\S]+>/U',$option);
								preg_match_all('/src="[\s|\S]+"/U',$option,$matchs);
								if($matchs){
									$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
									$i=0;
									$text_count = count($text_arr);
									$img_count = count($img_arr);
									$textrun2->addText($option_index[$d].'.',array('size'=>13));
									while($i<$text_count){
										$textrun2->addText($text_arr[$i],array('size'=>13));
										$textrun2->addImage($img_arr[$i]);
										$i++;
									}
								}else{
									$textrun2->addText($option_index[$d].'.'.$option,array('size'=>13));
								}
							}
						}else{
							$table->addRow();
							foreach($options as $d=>$c){
								$cell = $table->addCell(2500,array('valign'=>'center'));
								$textrun2 = $cell->addTextRun();
								$option = trim($c);
								$option = preg_replace('/(&nbsp;)*/','',$option);
								$text_arr = preg_split('/<img[\s|\S]+>/U',$option);
								preg_match_all('/src="[\s|\S]+"/U',$option,$matchs);
								if($matchs){
									$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
									$i=0;
									$text_count = count($text_arr);
									$img_count = count($img_arr);
									$textrun2->addText($option_index[$d].'.',array('size'=>13));
									while($i<$text_count){
										$textrun2->addText($text_arr[$i],array('size'=>13));
										$textrun2->addImage($img_arr[$i]);
										$i++;
									}
								}else{
									$textrun2->addText($option_index[$d].'.'.$option,array('size'=>13));
								}
							}
						}
						
						
					//break;
					
					}
					//解析跟在试题后面
					if($_SESSION['answer_order']==1){
						$textrun = $section->createTextRun(array('widowControl'=>'true'));
						$textrun->addText('试题解析：',array('size'=>13));
						$analysis = trim(strip_tags(htmlspecialchars_decode($val['analysis']),'<img>'));
						$analysis = preg_replace('/(&nbsp;)*/','',$analysis);
						$text_arr = preg_split('/<img[\s|\S]+>/U',$analysis);
						preg_match_all('/src="[\s|\S]+"/U',$analysis,$matchs);
						//var_dump($matchs);exit;
						if($matchs){
							$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
							$i=0;
							$text_count = count($text_arr);
							$img_count = count($img_arr);
							//$textrun->addText($val['order_char'].'.',array('size'=>13));
							while($i<$text_count){
								//echo $text_arr[$i];exit;
								$textrun->addText($text_arr[$i],array('size'=>13));
								if($i==$img_count) break;
								$textrun->addImage($img_arr[$i]);
								$i++;
							}
							
						}else{
							$section->addText($analysis,array('size'=>13));
						}
						
						$textrun = $section->createTextRun(array('widowControl'=>'true'));
						$textrun->addText('答案：',array('size'=>13));
						$answer = trim(strip_tags(htmlspecialchars_decode($val['answer']),'<img>'));
						$answer = preg_replace('/(&nbsp;)*/','',$answer);
						$text_arr = preg_split('/<img[\s|\S]+>/U',$answer);
						preg_match_all('/src="[\s|\S]+"/U',$answer,$matchs);
						//var_dump($matchs);exit;
						if($matchs){
							$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
							$i=0;
							$text_count = count($text_arr);
							$img_count = count($img_arr);
							//$textrun->addText($val['order_char'].'.',array('size'=>13));
							while($i<$text_count){
								//echo $text_arr[$i];exit;
								$textrun->addText($text_arr[$i],array('size'=>13));
								if($i==$img_count) break;
								$textrun->addImage($img_arr[$i]);
								$i++;
							}
							
						}else{
							$section->addText($answer,array('size'=>13));
						}
					}
				}
			}
		}
		
		$section->addTextBreak();
		if($_SESSION['shijuan'][2]){
			$section->addText($_SESSION['shijuan'][2]['t_title'],array('size'=>13,'bold'=>true),array('align' => 'center'));
			$section->addText($_SESSION['shijuan'][2]['note'],array('size'=>13));
			foreach($_SESSION['shijuan'][2]['shiti'] as $k=>$v){
				$childs = $this->getTikuInfo($v['childs']);
				$answer_part = array_merge($answer_part,$childs);
				$last = $k;
				$section->addText($oa[$k].'、'.$v['t_title'],array('size'=>13,'bold'=>true));
				//break;
				foreach($childs as $key=>$val){
					$question = trim(strip_tags(htmlspecialchars_decode($val['content']),'<img><p><br />'));
					$question = preg_replace('/(&nbsp;)*/','',$question);
					$question_arr = preg_split('/<p[\s|\S]*>|<br \/>/U',$question);
					//var_dump($question_arr);exit;
					foreach($question_arr as $kk=>$vv){
						//echo $vv;
						$vv =strip_tags($vv,'<img>');
						$order_char = '';
						if($kk==0) $order_char = $val['order_char'].'.';
						$textrun_name = 'textrun_'.$kk;
						$$textrun_name = $section->createTextRun();
						$text_arr = preg_split('/<img[\s|\S]+>/U',$vv);
						$text_arr = preg_replace('/\n/','',$text_arr);
						preg_match_all('/src="[\s|\S]+"/U',$vv,$matchs);
						//var_dump($matchs);exit;
						if(!empty($matchs[0])){
							$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
							$i=0;
							$text_count = count($text_arr);
							$img_count = count($img_arr);
							$$textrun_name->addText($order_char,array('size'=>13));
							while($i<$text_count){
								//echo $text_arr[$i];exit;
								$$textrun_name->addText($text_arr[$i],array('size'=>13));
								if($i==$img_count) break;
								//echo $img_arr[$i];exit;
								$$textrun_name->addImage($img_arr[$i]);
								$i++;
							}
							
						}else{
							//echo $vv;
							$section->addText($order_char.$vv,array('size'=>13));
						}
						//break;
					}
					if(strpos($v['t_title'],'解答题')!==false){
						$section->addTextBreak(10);
					}
					//解析跟在试题后面
					if($_SESSION['answer_order']==1){
						$textrun = $section->createTextRun(array('widowControl'=>'true'));
						$textrun->addText('试题解析：',array('size'=>13));
						$analysis = trim(strip_tags(htmlspecialchars_decode($val['analysis']),'<img>'));
						$analysis = preg_replace('/(&nbsp;)*/','',$analysis);
						$text_arr = preg_split('/<img[\s|\S]+>/U',$analysis);
						preg_match_all('/src="[\s|\S]+"/U',$analysis,$matchs);
						//var_dump($matchs);exit;
						if($matchs){
							$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
							$i=0;
							$text_count = count($text_arr);
							$img_count = count($img_arr);
							//$textrun->addText($val['order_char'].'.',array('size'=>13));
							while($i<$text_count){
								//echo $text_arr[$i];exit;
								$textrun->addText($text_arr[$i],array('size'=>13));
								if($i==$img_count) break;
								$textrun->addImage($img_arr[$i]);
								$i++;
							}
							
						}else{
							$section->addText($analysis,array('size'=>13));
						}
						
						$textrun = $section->createTextRun(array('widowControl'=>'true'));
						$textrun->addText('答案：',array('size'=>13));
						$answer = trim(strip_tags(htmlspecialchars_decode($val['answer']),'<img>'));
						$answer = preg_replace('/(&nbsp;)*/','',$answer);
						$text_arr = preg_split('/<img[\s|\S]+>/U',$answer);
						preg_match_all('/src="[\s|\S]+"/U',$answer,$matchs);
						//var_dump($matchs);exit;
						if($matchs){
							$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
							$i=0;
							$text_count = count($text_arr);
							$img_count = count($img_arr);
							//$textrun->addText($val['order_char'].'.',array('size'=>13));
							while($i<$text_count){
								//echo $text_arr[$i];exit;
								$textrun->addText($text_arr[$i],array('size'=>13));
								if($i==$img_count) break;
								$textrun->addImage($img_arr[$i]);
								$i++;
							}
							
						}else{
							$section->addText($answer,array('size'=>13));
						}
					}
				}
			}
		}
		//解析跟试卷分离
		if($_SESSION['answer_order']==2){
			$section = $phpWord->addSection($sectionStyle);
			$section->addText('答案部分',array( 'size'=>'15','bold'=>true),array('align' => 'center'));
			$section->addTextBreak(2);
			//var_dump($answer_part);exit;
			foreach($answer_part as $val){
				$textrun = $section->createTextRun(array('widowControl'=>'true'));
				$textrun->addText($val['order_char'].'.试题解析：',array('size'=>13));
				$analysis = trim(strip_tags(htmlspecialchars_decode($val['analysis']),'<img>'));
				$analysis = preg_replace('/(&nbsp;)*/','',$analysis);
				$text_arr = preg_split('/<img[\s|\S]+>/U',$analysis);
				preg_match_all('/src="[\s|\S]+"/U',$analysis,$matchs);
				//var_dump($matchs);exit;
				if($matchs){
					$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
					$i=0;
					$text_count = count($text_arr);
					$img_count = count($img_arr);
					//$textrun->addText($val['order_char'].'.',array('size'=>13));
					while($i<$text_count){
						//echo $text_arr[$i];exit;
						$textrun->addText($text_arr[$i],array('size'=>13));
						if($i==$img_count) break;
						$textrun->addImage($img_arr[$i]);
						$i++;
					}
					
				}else{
					$section->addText($analysis,array('size'=>13));
				}
				
				$textrun = $section->createTextRun(array('widowControl'=>'true'));
				$textrun->addText('答案：',array('size'=>13));
				$answer = trim(strip_tags(htmlspecialchars_decode($val['answer']),'<img>'));
				$answer = preg_replace('/(&nbsp;)*/','',$answer);
				$text_arr = preg_split('/<img[\s|\S]+>/U',$answer);
				preg_match_all('/src="[\s|\S]+"/U',$answer,$matchs);
				//var_dump($matchs);exit;
				if($matchs){
					$img_arr = preg_replace('/(src="\/)|"/U','',$matchs[0]);
					$i=0;
					$text_count = count($text_arr);
					$img_count = count($img_arr);
					//$textrun->addText($val['order_char'].'.',array('size'=>13));
					while($i<$text_count){
						//echo $text_arr[$i];exit;
						$textrun->addText($text_arr[$i],array('size'=>13));
						if($i==$img_count) break;
						$textrun->addImage($img_arr[$i]);
						$i++;
					}
					
				}else{
					$section->addText($answer,array('size'=>13));
				}
			}
		}
		//exit;
		// At least write the document to webspace:
		//$PHPWord_IOFactory = new \Vendor\PHPWord\PHPWord_IOFactory();
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		//$objWriter->save('helloWorld.doc');exit;
		
		//$objWriter->save(Yii::app()->params['exportToDir'].$filename.".docx");
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="'.$_SESSION['shijuan']['title'].'.docx"');
        //header("Content-Type: application/docx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header("Cache-Control: public");
        header('Expires: 0');
        $objWriter->save("php://output");
	}
	public function saveShijuan(){
		$Model = M('user_shijuan');
		if(isset($_SESSION['shijuan']['id'])&&$Model->where("id=".$_SESSION['shijuan']['id'].' AND user_id='.$_SESSION['user_id'])->find()){//更新数据库
			$data['id'] = $_SESSION['shijuan']['id'];
			$data['update_time'] = time();
			$data['content'] = json_encode($_SESSION['shijuan']);
			$data['cart'] = json_encode($_SESSION['cart']);
			if($Model->data($data)->save()){
				return true;
			}else{
				return false;
			}
		}else{//添加到数据库
			$data['title'] = $_SESSION['shijuan']['title'];
			$data['user_id'] = $_SESSION['user_id'];
			$data['create_time'] = $data['update_time'] = time();
			$data['content'] = json_encode($_SESSION['shijuan']);
			$data['cart'] = json_encode($_SESSION['cart']);
			$data['course_id'] = $_SESSION['course_id'];
			if($id = $Model->add($data)){
				$_SESSION['shijuan']['id'] = $id;
				return true;
			}else{
				return false;
			}
		}
	}
	public function ajaxSave(){
		$Model = M('user_shijuan');
		if(isset($_SESSION['shijuan']['id'])&&$Model->where("id=".$_SESSION['shijuan']['id'].' AND user_id='.$_SESSION['user_id'])->find()){//更新数据库
			$data['id'] = $_SESSION['shijuan']['id'];
			$data['update_time'] = time();
			$data['content'] = json_encode($_SESSION['shijuan']);
			$data['cart'] = json_encode($_SESSION['cart']);
			if($Model->data($data)->save()){
				$this->ajaxReturn(array('status'=>'success','action'=>'update'));
			}else{
				$this->ajaxReturn(array('status'=>'error','action'=>'update'));
			}
		}else{//添加到数据库
			$data['title'] = $_SESSION['shijuan']['title'];
			$data['user_id'] = $_SESSION['user_id'];
			$data['create_time'] = $data['update_time'] = time();
			$data['content'] = json_encode($_SESSION['shijuan']);
			$data['cart'] = json_encode($_SESSION['cart']);
			$data['course_id'] = $_SESSION['course_id'];
			if($id = $Model->add($data)){
				$_SESSION['shijuan']['id'] = $id;
				$this->ajaxReturn(array('status'=>'success','action'=>'add'));
			}else{
				$this->ajaxReturn(array('status'=>'error','action'=>'add'));
			}
		}
		
	}
	public function ajacCheckIsSaved(){
		$Model = M('user_shijuan');
		if(isset($_SESSION['shijuan']['id'])&&$Model->where("id=".$_SESSION['shijuan']['id'].' AND user_id='.$_SESSION['user_id'])->find()){//更新数据库
			$this->ajaxReturn(array('status'=>'yes'));
		}else{
			$this->ajaxReturn(array('status'=>'no'));
		}
	}
	public function ajaxGoTiku(){
		if(!empty($_SESSION['course_id'])){
			$data = $this->getCourseById($_SESSION['course_id']);
			
			$this->ajaxReturn(array('status'=>'ok','pinyin'=>$data['pinyin']));
		}else{
			$this->ajaxReturn(array('status'=>'error'));
		}
	}
	public function ajaxDownload(){
		if(isset($_SESSION['shijuan']['id'])){
			$_SESSION['doctype'] = !empty($_GET['doctype'])?$_GET['doctype']:'word2007';
			$_SESSION['shijuan_model'] = !empty($_GET['shijuan_model'])?$_GET['shijuan_model']:'A4';
			$_SESSION['answer_order'] = !empty($_GET['answer_order'])?$_GET['answer_order']:'2';
			$this->ajaxReturn(array('status'=>'success'));
		}else{
			$this->ajaxSave();
		}
	}
	public function _getTikuInfo($id_arr,&$o,$juan_no,$shiti_no){
		$Model = M('tiku');
		foreach($id_arr as $key=>$val){
			$rs = $Model->field("id,content,options,answer,analysis")->where("id=".$val)->find();
			$rs['order_char'] = $o;
			$tiku[] = $rs;
			$child[] = array('order_char'=>$o,'id'=>$val);
			$o++;
		}
		$_SESSION['shijuan'][$juan_no]['shiti'][$shiti_no]['childs'] = $child;
		return $tiku;
	}
	public function getTikuInfo($id_arr){
		$Model = M('tiku');
		foreach($id_arr as $key=>$val){
			$rs = $Model->field("id,content,options,answer,analysis")->where("id=".$val['id'])->find();
			$rs['order_char'] = $val['order_char'];
			$tiku[] = $rs;
		}
		return $tiku;
	}
	public function deleteShijuan(){
		unset($_SESSION['shijuan']);
	}
	
}