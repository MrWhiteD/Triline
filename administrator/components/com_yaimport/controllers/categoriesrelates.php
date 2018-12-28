<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class YaimportControllerCategoriesRelates extends JControllerLegacy{
	
	function saveRelated(){
		$mainframe = JFactory::getApplication();    
        $db = JFactory::getDBO();
		
		$product_id = JRequest::getInt('id');
		$yandex_id = JRequest::getInt('yandex_id');
		
        $relates = $this->getModel("categoriesrelates"); 
		$relates->save($product_id, $yandex_id);

		$response = array("success" => true);

		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		JResponse::setHeader('Content-Disposition','attachment;filename="result.json"');

		exit(json_encode($response));
		
	}
}