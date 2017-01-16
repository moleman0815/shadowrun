<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// misc helpers


 function impInclude($file, $action='include', $string='empty'){
        $file2include = MY_BASEROOT_PATH . $file;
        if(file_exists($file2include)){ 
           if($action == 'include'){
              include($file2include);
           } else if ($action == 'echo' && $string !='empty'){
              echo $string;
           }
        } else{
          error_log("impInclude file not exists: $file2include");
        }
    }


function mysqldatetime_to_timestamp($datetime = "")
{
  // function is only applicable for valid MySQL DATETIME (19 characters) and DATE (10 characters)
  $l = strlen($datetime);
    if(!($l == 10 || $l == 19))
      return 0;

    //
    $date = $datetime;
    $hours = 0;
    $minutes = 0;
    $seconds = 0;

    // DATETIME only
    if($l == 19)
    {
      list($date, $time) = explode(" ", $datetime);
      list($hours, $minutes, $seconds) = explode(":", $time);
    }

    list($year, $month, $day) = explode("-", $date);

    return mktime($hours, $minutes, $seconds, $month, $day, $year);
}

function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
        return $uuid;
    }
}


function notEmptyArray($array)
{
    if(isset($array) && is_array($array) && count($array) > 0)
        return true;
    else
        return false;
}

/**
 * required method accepts an array of keys and returns false if any of those keys are not present in the data array
 *
 * @param array $required
 * @param array $data
 * @return bool
 */
function RequiredValues($required, $data)
{
    foreach($required as $field) if(!isset($data[$field])) return false;
    return true;
}

/**
 * default method accepts an associated array of key => default value pairs then returns a new array containing the default values should they have not been declared in the $options array.
 *
 * @param array $defaults
 * @param array $options
 * @return array
 */
function DefaultValues($defaults, $options)
{
    return array_merge($defaults, $options);
}

/**
 * parseKeys method accepts a list of array keys and an array then returns a new array containing only matching keys
 *
 * @param array $values
 * @param array $data
 * @return array
 */
function ParseKeys($values, $data)
{
    if(!is_array($values)) return false;
    foreach($values as $index => $value)
    {
        $values[$index] = strtolower($value);
    }

    $newArray = array();
    foreach($data as $key => $value)
    {
        if(in_array(strtolower($key), $values)) $newArray[$key] = $value;
    }

    return $newArray;
}

/**
 * generateSlug creates a delimited slug
 *
 * @param string $original
 * @return string
 */
function GenerateSlug($original, $delimiter = '-')
{
    $slug = strtolower($original);
    $slug = trim($slug);

    $slug = preg_replace("/\s/", $delimiter, $slug);
    $slug = preg_replace('/\W/', $delimiter, $slug);
    $slug = str_replace('_', $delimiter, $slug);
    $slug = preg_replace("/($delimiter)+/", $delimiter, $slug);

    return $slug;
}

/**
* MysqlDate formats any date string to a mysql datetime format
*
* @param string $dateString
* @param bool $timestamp Pass true if datestring is a unix timestamp
* @return string
*/
function MysqlDate($dateString = false, $timestamp = false)
{
    if($dateString && $timestamp) return date("Y-m-d H:i:s", $dateString);
    else if($dateString) return date("Y-m-d H:i:s", strtotime($dateString));
    return date("Y-m-d H:i:s");
}

/**
* DropDownOptions function generates an associative array from the passed array of objects.
* Using the key and value names specified in the 2nd and 3rd parameters.
*
* This method could use some polish.
*
* @param array $array
* @param string $key
* @param string $value
* @param string $emptyOptionText
*/
function DropDownOptions($array, $key, $value = false, $emptyOptionText = "-- Choose One --", $excludeArray = false, $excludeKey = false)
{
    $retval = array();

    // check if empty option text is set, then use it
    if(!empty($emptyOptionText)) $retval[''] = $emptyOptionText;

    // check to see if we're using an exclude array
    if($excludeArray && $excludeKey)
    {
        $excludeArray = DropDownOptions($excludeArray, $excludeKey, '', '');
        print_r($excludeArray);
        die();
    }

    if(is_array($array)) foreach($array as $element)
    {
        if(!empty($value))
            $retval[$element->$key] = $element->$value;
        else $retval[] = $element->$key;
    }

    return $retval;
}

/**
 * CurlRequest method queries a URI and returns the response
 * CurlRequest(array('uri' => $uri));
 *
 * Option: Values
 * --------------
 * uri                uri to query
 *
 * @param array $options
 * @return string
 */
function CurlRequest($options = array())
{
    $CI =& get_instance();

    // required values
    if(!RequiredValues(array('uri'), $options)) return false;

    $ch = curl_init($options['uri']);

    curl_setopt_array($ch, array(
    CURLOPT_HEADER=>FALSE,
    CURLOPT_RETURNTRANSFER=>TRUE,
    CURLOPT_USERAGENT => 'BS'
    ));

    $response = curl_exec($ch);
    $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $response;
}

function GetUniqueFilename($path, $filename)
{
    $testPath = $path . $filename;
    list($name, $ext) = explode('.', $filename);

    $i=0;
    while(file_exists($testPath))
    {
        $testPath = $path . $name . '_' . $i . '.' . $ext;
        $i++;
    }

    return $name . '_' . $i . '.' . $ext;
}

function linkify($text)
{
    $ret = ' ' . $text;
    $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" >\\2</a>'", $ret);
    $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" >\\2</a>'", $ret);
    $ret = preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
    $ret = substr($ret, 1);
    return($ret);
}


    function getFullMonthFromShort($month) {
        $mymonth = array(
                        'jan'  =>  'Januar', 
                        'feb'  =>  'Februar', 
                        'mar'  =>  'März', 
                        'apr'  =>  'April', 
                        'mai'  =>  'Mai', 
                        'jun'  =>  'Juni', 
                        'jul'  =>  'Juli', 
                        'aug'  =>  'August', 
                        'sep'  =>  'September', 
                        'okt'  =>  'Oktober', 
                        'nov'  =>  'November', 
                        'dez'  =>  'Dezember',
                    );  
        return $mymonth[$month];
    }

    function getFullMonthFromNumber($month) {
        $mymonth = array(
                        '01'  =>  'Januar', 
                        '02'  =>  'Februar', 
                        '03'  =>  'März', 
                        '04'  =>  'April', 
                        '05'  =>  'Mai', 
                        '06'  =>  'Juni', 
                        '07'  =>  'Juli', 
                        '08'  =>  'August', 
                        '09'  =>  'September', 
                        '10'  =>  'Oktober', 
                        '11'  =>  'November', 
                        '12'  =>  'Dezember',
                    );  
        return $mymonth[$month];
    }    
    function _debug($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }    

    function _debugDie($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();
    } 

    function ciInclude ($file) {
        if ($file) {
            $file = (substr($file, 0,1) == '/') ? substr($file, 1) : $file;
            $file = preg_replace('/la\//', '', $file);
            if (file_exists($file)) {
                include($file);
            } else {
                echo "<!--Die zu inkludierende Datei: ".$file." existiert auf dem Server nicht.-->";
            }
        } else {
            echo "<!--Die zu inkludierende Datei: ".$file." existiert auf dem Server nicht.-->";
        }
    }
    
    function ciEmpty ($data) {    	
    	if (is_array($data)) {
    		if (array_key_exists('0', $data)) {
    			return false;
    		} else {
    			return true;
    		}
    	} else {    		
    		if (empty($data)) {
    			return true;
    		} else {
    			return false;
    		}
    
    	}
    }
    
    function emojiReplace ($text) {
    	$text = preg_replace(':)', '<img src="/secure/snn/assets/img/icons/emoji/smilie.png">', $text);
    	return $text;
    }
?>
