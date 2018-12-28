<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class YaimportControllerPanel extends JControllerLegacy{
    function display($cachable = false, $urlparams = false){
        checkAccessController("panel");
        addSubmenu("");
		$view=$this->getView("panel", 'html');
        $view->setLayout("home");
        
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeDisplayHomePanel', array(&$view));
		$view->displayHome(); 
    }
}
?>		