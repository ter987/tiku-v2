<?php
namespace Api\Controller;
use Think\Controller;
class ResetMysqlController extends Controller {
	
	/**
	 * tiku表新增冗余数据course_id
	 */
	public function AddCourseId(){
		$Model = M('tiku');
		for($i=1120399;$i<=1120399;$i++){
			$tiku = $Model->join("tiku_source on tiku.source_id=tiku_source.id")->where("tiku.id=$i")->find();
			if($tiku){
				$Model->where("id=$i")->save(array('course_id'=>$tiku['course_id']));
				usleep(3000);
			}
		}
		echo 'OK!';
	}
}
	