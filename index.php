<?php

date_default_timezone_set( "Europe/Warsaw" );

include("renderView.static.php");

$template = "views/sample.html";

$data = array(
	"title" => "Welcome to weather page!",
	"headline" => "Weather page",
	"date" => date("Y-m-d H:i:s"),
	"temperatures" => array(
		array( "date" => "2015-08-01" , "city" => "Warsaw" , "temperature" => 21 ),
		array( "date" => "2015-08-01" , "city" => "Oslo" , "temperature" => 13 ),
		array( "date" => "2015-08-01" , "city" => "Berlin" , "temperature" => 19 ),
		array( "date" => "2015-08-01" , "city" => "Rome" , "temperature" => 23 ),
		array( "date" => "2015-08-01" , "city" => "London" , "temperature" => 20 )
	),
	"rains" => array(
		array( "type" => "Tropical"),
		array( "type" => "Heavy"),
		array( "type" => "Rain and snow mixed")
	)
);

echo \view\renderView::render( $template , $data );

?>
