<?php
namespace Home\Controller;
use Home\Controller\GlobalController;
class BaogaoController extends GlobalController {
	/**
	 * 初始化
	 */
	function _initialize()
	{
		parent::_initialize();
		$course_data = parent::getCourse();
	}
    public function index(){
    	
    	//SEO
		$this->setMetaTitle('首页'.C('TITLE_SUFFIX'));
		$this->setMetaKeyword(''.C('TITLE_SUFFIX'));
		$this->setMetaDescription(''.C('TITLE_SUFFIX'));
		$this->addCss(array('xf.css','home.css'));
		$this->addJs(array('js/banner.js','js/xf.js'));
        $this->display();
	}
	public function stuReport(){
		$this->display();
	}
}