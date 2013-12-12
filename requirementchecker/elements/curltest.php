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

class ReqTester{
    
    const CURL_TEST = 1;
    const CURL_SUPPORT = 2;
    const JSON_SUPPORT = 3;
    const CHECKERPLUGIN_URI = '/plugins/system/requirementchecker/';
    
    /**
     * Returns an url to the assets
     * @return string The url to the assets
     */
    static function assetsURI(){
        if(version_compare(JVERSION, '1.6.0', '<')){
            return JURI::root(true).ReqTester::CHECKERPLUGIN_URI.'assets/';
        }else{
            return JURI::root(true).ReqTester::CHECKERPLUGIN_URI.'requirementchecker/assets/';
        }
    }
    
    /**
     * Returns an image based on the availability
     * @param boolean $available
     * @return string Image url
     */
    static function getImage($available=true){
        $imagesURI = self::assetsURI().'images/';
        if($available){
            return $imagesURI.'notice-info.png';
        }else{
            return $imagesURI.'notice-alert.png';
        }
    }
    
    /**
     * Returns HTML based on the test
     * @param string $elementID
     * @param integer $functiontest
     * @return string HTML
     */
    static function getHTML($elementID, $functiontest){
        $imageSrc = self::getImage();
        $notice = JText::_('PLG_SYSTEM_REQUIREMENTCHECKER_ELEMENT_OK');
        $supported = false;
        switch ($functiontest) {
            case self::CURL_TEST:
                $supported = ReqTester::isCurlWorking();
                break;
            case self::CURL_SUPPORT:
                $supported = ReqTester::isCurlSupported();
                break;
            case self::JSON_SUPPORT :
                $supported = ReqTester::isJsonSupported();
                break;
            default:
                break;
        }
        if( !$supported ){
            $imageSrc = self::getImage(FALSE);
            $notice = JText::_('PLG_SYSTEM_REQUIREMENTCHECKER_ELEMENT_NOT_OK');
        }
        $html = '<span id="'.$elementID.'_loader" class="readonly">'.
                '<img src="'.$imageSrc.'">'.
                $notice.
                '</span>';
        return $html;
    }
    
    /**
     * Checks if the server supports CURL
     * @return boolean
     */
    static function isCurlSupported(){
        if( ! function_exists( 'curl_init' ) ){
            return false;
        }
        return true;
    }
    
    /**
     * Checks if the server supports JSON
     * @return boolean
     */
    static function isJsonSupported(){
        if( ! function_exists( 'json_decode' ) ){
            return false;
        }
        return true;
    }
    
    /**
     * Checks if CURL is working
     * @return boolean
     */
    static function isCurlWorking(){
        $url = 'http://www.stilero.com';
        $post = '';
        $ch = curl_init(); 
         curl_setopt_array($ch, array(
            CURLOPT_USERAGENT       =>  'requirementchecker - www.stilero.com',
            CURLOPT_CONNECTTIMEOUT  =>  20,
            CURLOPT_TIMEOUT         =>  20,
            CURLOPT_RETURNTRANSFER  =>  true,
            CURLOPT_SSL_VERIFYPEER  =>  false,
            CURLOPT_FOLLOWLOCATION  =>  false,
            CURLOPT_PROXY           =>  false,
            CURLOPT_ENCODING        =>  false,
            CURLOPT_URL             =>  $url,
            CURLOPT_HEADER          =>  false,
            CURLINFO_HEADER_OUT     =>  true,
        ));
        curl_setopt($ch, CURLOPT_POST, $post);
        $response = curl_exec ($ch);
        $responses = curl_getinfo($ch); 
        curl_close ($ch);
        if($responses['http_code'] == 0){
            return false;
        }else if ($responses['http_code'] != '200') {
            return false;
        }
        return true;
    }
}
if(version_compare(JVERSION, '1.6.0', '<')){
    /**
    * @since J1.5
    */
    class JElementCurltest extends JElement{
        private $config;

        function fetchElement($name, $value, &$node, $control_name){
            JPlugin::loadLanguage('plg_system_requirementchecker', JPATH_ADMINISTRATOR);
            return ReqTester::getHTML($control_name.$name, ReqTester::CURL_TEST);
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
    class JFormFieldCurltest extends JFormField {
        protected $type = 'curltest';
        private $config;

        protected function getInput(){
            return ReqTester::getHTML($this->id, ReqTester::CURL_TEST);
        }
        
        protected function getLabel(){
            $toolTip = JText::_($this->element['description']);
            $text = JText::_($this->element['label']);
            $labelHTML = '<label id="'.$this->id.'-lbl" for="'.$this->id.'" class="hasTip" title="'.$text.'::'.$toolTip.'">'.$text.'</label>';
            return $labelHTML;
        }
        
    }//End Class
}