<?php

/**
 * @author Piotr Kabacinski
 */

namespace view;

class RenderView {

	static $template;
	static $data = array();

	static function render($template = "" , $data = array()) {
		$template = file_get_contents($template);

		self::$template = $template;
		self::$data = $data;

		self::renderLoops();
		self::renderVars();
		self::clearTemplate();

		return self::$template;
	}

	static function renderLoops() {
		$template = self::$template;
		$data = self::$data;

		// Catch loops names
		preg_match_all("/{{loop:(.*)}}/", $template, $loops_array);

	    $catch = $loops_array[1];

	    for($i = 0; $i < count($catch); $i++) {

	        // Get specific loop content
			preg_match_all("/{{loop:{$catch[$i]}}}(.*){{\\/loop:{$catch[$i]}}}/s", $template, $content_array);

	        $content = $content_array[1][0];

	        // Get vars within loop
	        preg_match_all("/{{(\s*[\w\.]+\s*)}}/", $content , $vars);

	        $loop = $content;

	        // Check if content array for specific loop exists
	        if (array_key_exists($catch[$i] , $data)) {
	        	$sourceArray = $data[ $catch[$i] ];

		        $return = "";
		        $append = $loop;

		        for($r = 0; $r < count($sourceArray); $r++) {

		            $append = $loop;

		            for($v = 0; $v < count($sourceArray[$r]); $v++) {

		                $local = $vars[1][$v];
		                $value = $sourceArray[$r][$local];

		                $append = str_replace("{{{$local}}}" , $value , $append);

		                unset($value);
		                unset($local);
		            }

		            $return = $return . $append;
		        }

		        // Replace loop object with rendered content
		        $template = str_replace("{{loop:{$catch[$i]}}}" . $loop . "{{/loop:{$catch[$i]}}}" , $return , $template);
	        } else {
	        	// Remove loop object from final code
	        	$template = preg_replace("/{{loop:{$catch[$i]}}}" . $loop . "{{\\/loop:{$catch[$i]}}}/", "", $template);
	        }
	    }

	    self::$template = $template;
	}

	static function renderVars() {
		$template = self::$template;
		$data = self::$data;

		preg_match_all("/{{(\s*[\w\.]+\s*)}}/", $template, $output_array);

		$vars = $output_array[1];

	    for($i = 0; $i < count($vars); $i++) {
	    	if (array_key_exists($vars[$i], $data)) {
	      		$template = str_replace("{{{$vars[$i]}}}" , $data[$vars[$i]] , $template);
	      	}
	    }

	    self::$template = $template;
	}

	static function clearTemplate() {
		$template = self::$template;
		$template = preg_replace('/{{(\s*[\w\.]+\s*)}}/' , '', $template);
		self::$template = $template;
	}
}


?>
