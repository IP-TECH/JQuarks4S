<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

// define Constants
define('JQUARKS4S_VERSION', '1.1.2');

// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require Helpers
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'toolbar.php');

// Require specific controller if requested
if( $controller = JRequest::getCmd('controller') )
{
    
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    
	if(file_exists($path)) {
		require_once $path;
	}
    else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'JQuarks4sController'.$controller;
$controller	= new $classname();

// Perform the Request task
$controller->execute( JRequest::getCmd('task') );
$controller->redirect();
