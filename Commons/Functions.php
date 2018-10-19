<?php

function redirect($url){

	if(headers_sent($file, $line)){
		trigger_error("HTTP headers already sent at ($file):($line)", E_USER_ERROR);
	}else{
		
		header("location: {$url}");
	}
}


/*
*renders a view and passes in values
*/
function render($view, $values = []){
	
	//set_include_path(get_include_path().":"."/storage/ssd5/188/1658188/public_html/");
	if(file_exists($view)){

		//extract values
		extract($values);



		//render view (between header and footer)
		require("Views/Partials/header.php");
		require("Views/{$view}");
		require("Views/Partials/footer.php");
		exit;
	}else {echo $view . "does not exist"; }
}

function get_params($req_uri){
	$id = explode('/', $req_uri);

	return $id;
}

function getIp(){
	//print("<pre>".print_r($_SERVER, true) . "</pre>");

	if(!empty($_SERVER['HTTP_CLIENT_IP'])){

		return $_SERVER['HTTP_CLIENT_IP'];

	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){

		return $_SERVER['HTTP_X_FORWARDED_FOR'];

	}else{

		return $_SERVER['REMOTE_ADDR'];

	}
}
function text_chooser($var){

	switch($var){
		case 'pets1':
		$result = "Pets are allowed";
		return $result;
		break;

		case 'pets0':
		$result = "Pets are not allowed";
		return $result;
		break;

		case 'water1':
		$result = "Water is available";
		return $result;
		break;

		case 'water0':
		$result = "Water is not available";
		return $result;
		break;

		case 'electricity1':
		$result = "Electricity is available";
		return $result;
		break;

		case 'electricity0':
		$result = "Electricity is not available";
		return $result;
		break;

		case 'furnished1':
		$result = "The house is fully furnished";
		return $result;
		break;

		case 'furnished0':
		$result = "The house is not furnished";
		return $result;
		break;

		case 'furnished2':
		$result = "The house is partly furnished";
		return $result;
		break;

		case 'pool1':
		$result = "There is a pool";
		return $result;
		break;

	}

			
}