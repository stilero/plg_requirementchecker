<?php
/**
* Description of requirementChecker
*
* @version  1.0
* @author Daniel Eliasson - joomla at stilero.com
* @copyright  (C) 2012-jul-22 Stilero Webdesign http://www.stilero.com
* @category Custom Form field
* @license    GPLv2
*
*/
 
// no direct access
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}
if(version_compare(JVERSION, '1.6.0', '<')){
    /**
    * @since J1.5
    */
    class JElementCurlsupport extends JElement{
        private $config;

        function fetchElement($name, $value, &$node, $control_name){
            JPlugin::loadLanguage('plg_system_requirementchecker', JPATH_ADMINISTRATOR);
            return ReqTester::getHTML($control_name.$name, ReqTester::CURL_SUPPORT);
        }
        function fetchTooltip ( $label, $description, &$xmlElement, $control_name='', $name=''){
            $output = '<label id="'.$control_name.$name.'-lbl" for="'.$control_name.$name.'"';
            if ($description) {
                    $output .= ' class="hasTip" title="'.JText::_($label).'::'.JText::_($description).'">';
            } else {
                    $output .= '>';
            }
            $output .= JText::_( $label ).'</label>';
            return $output;        
        }
    }//End Class J1.5
}else{
    /**
    * @since J1.6
    */
    class JFormFieldCurlsupport extends JFormField {
        protected $type = 'curlsupport';
        private $config;

        protected function getInput(){
            return ReqTester::getHTML($this->id, ReqTester::CURL_SUPPORT);
        }
        
        protected function getLabel(){
            $toolTip = JText::_($this->element['description']);
            $text = JText::_($this->element['label']);
            $labelHTML = '<label id="'.$this->id.'-lbl" for="'.$this->id.'" class="hasTip" title="'.$text.'::'.$toolTip.'">'.$text.'</label>';
            return $labelHTML;
        }
        
    }//End Class
}