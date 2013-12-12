<?php
/**
 * Description of requirementChecker
 *
 * @version  1.1
 * @author Daniel Eliasson - joomla at stilero.com
 * @copyright  (C) 2012-jul-22 Stilero Webdesign http://www.stilero.com
 * @category Plugins
 * @license	GPLv2
 * 
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 
if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}

//Check the version of Joomla and import the correct plugin file
if(version_compare(JVERSION, '3.0.0', '<')){
    jimport('joomla.plugin.plugin');
}else{
    jimport('joomla.event.plugin');
}

class plgSystemRequirementchecker extends JPlugin {
    var $config;

    function plgSystemRequirementchecker ( &$subject, $config ) {
        define('CHECKERPLUGIN_URI', JURI::root(true).'/plugins/system/requirementchecker/');
        parent::__construct( $subject, $config );
        $language = JFactory::getLanguage();
        $language->load('plg_system_requirementchecker', JPATH_ADMINISTRATOR, 'en-GB', true);
        $language->load('plg_system_requirementchecker', JPATH_ADMINISTRATOR, null, true);

    }
    

} //End Class