<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class SearchController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
		$course_data = parent::getCourse();
	}
    public function connect(){
    	error_reporting(E_ALL);
    	//import('Org/Util/Sphinxclient');
		$mode = SPH_MATCH_ALL;
		$ranker = SPH_RANK_PROXIMITY_BM25;
		$sphinx = new \Sphinxclient();
		$sphinx->SetServer ('localhost',9312);
		$sphinx->SetArrayResult (true);
		$sphinx->SetLimits(0,20,1000);
		$sphinx->SetMaxQueryTime(10);
		//$sphinx->SetWeights (array ( 100, 1 ));
		$sphinx->SetMatchMode ( $mode );
		$sphinx->SetRankingMode ( $ranker );
		
		$index = '*';
		$result = $sphinx->Query('2', $index);
		var_dump($result);
		echo $sphinx->GetLastError();
    }
}