<?php

namespace view;

class renderView {

	static $template;
	static $data = array();

	static function render( $template = "" , $data = array() ) {

		$template = file_get_contents( $template );

		self::$template = $template;
		self::$data = $data;		

		// init render functions
		self::renderLoops();
		self::renderVars();

		echo self::$template;

	}

	static function renderLoops() {

		$template = self::$template;
		$data = self::$data;

		// replace white spaces in template for better rendering
		$template = str_replace( array("\n" ,"\r") ,  array("[[n]]" , "[[r]]") , $template);

		// catch loops names
	    preg_match_all("/{{loop:(.*)}}/U", $template, $loops_array);

	    $catch = $loops_array[1];
	    
	    for( $i = 0; $i < count( $catch ); $i++ ) {
	        
	        // get specific loop content
	        preg_match_all("/{{loop:".$catch[$i]."}}(.*){{\\/loop:".$catch[$i]."}}/U", $template, $content_array);

	        $content = $content_array[1][0];

	        // get vars within loop
	        preg_match_all("/{{(.*)}}/U", $content , $vars);
	        
	        $loop = $content;

	        // check if content array for specific loop exists
	        if ( array_key_exists( $catch[$i] , $data) ) {

	        	$sourceArray = $data[ $catch[$i] ];

		        $return = "";
		        $append = $loop;
		        
		        for( $r = 0; $r < count( $sourceArray ); $r++ ) {
		            
		            $append = $loop;
		            
		            for( $v = 0; $v < count( $sourceArray[$r] ); $v++ ) {
		                
		                $local = $vars[1][$v];
		                $value = $sourceArray[$r][$local];

		                $append = str_replace( "{{".$local."}}" , $value , $append );
		                
		                unset( $value );
		                unset( $local );
		 
		            }
		            
		            $return = $return . $append;

		        }
		        
		        // replace loop object with rendered content
		        $template = str_replace( "{{loop:".$catch[$i]."}}" . $loop . "{{/loop:".$catch[$i]."}}" , $return , $template );

		    
	        } else {

	        	// remove loop object from final code
	        	$template = preg_replace("/{{loop:".$catch[$i]."}}" . $loop . "{{\\/loop:".$catch[$i]."}}/U", "", $template);

	        }
	        
	    }

	    $template = str_replace( array("[[n]]" , "[[r]]") , array("\n" ,"\r")  , $template);
	    
	    self::$template = $template;

	    return;

	}

	static function renderVars() {

		$template = self::$template;
		$data = self::$data;

		preg_match_all("/{{(.*)}}/U", $template , $output_array);

		$vars = $output_array[1];

	    for( $i = 0; $i < count( $vars ); $i++) {

	    	if ( array_key_exists( $vars[$i] , $data) ) {

	      		$template = str_replace( "{{".$vars[$i]."}}" , $data[ $vars[$i] ] , $template );

	      	}

	    }

	    // remove empty variables from template
	    $template = preg_replace('/{{(.*)}}/U' , '', $template);

	    self::$template = $template;

	    return;

	}

}

?>