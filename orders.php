<?php
//Storing all the default variables
$API_KEY = '';
$API_NAME = 'admin';
//$AUTH_CLIENT_TOKEN = ';
$STORE_URL = 'https://ecademy.playfullearning.net/api/v2';
$CANVAS_URL = 'https://playfullearning.instructure.com/api/v1';
$CANVAS_TOKEN = '';
$CANVAS_ACCOUNT = '1';
//$PRODUCT_ID = 'product-id-here';
//
$time = time();
//$json = fopen('php://input' , 'rb'); 
$data = json_decode(file_get_contents("php://input"));
$results = print_r($data, true);
//$handle = fopen('event-'.$time.'.json', 'w');
//fp = fwrite($handle, $results);
file_put_contents('logs/event-'.$time.'.json', $results);
$producer = $data->producer;
$url = $data->destination->url;
$id = $data->data->id;
$type = $data->data->type;
$scope = $data->scope;

echo "Your Store is: ".$producer."<p></p>";
echo "Your destination url is ".$url."<br>";

echo "Your Id is: ".$id."<p></p>";
echo "Your type is ".$type."<br>";
echo "Your Scope is ".$scope;


if ($scope === 'store/customer/created'){
  $bc_url = $STORE_URL."/customers/".$id.".json";
	$ch = curl_init($bc_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
	curl_setopt($ch, CURLOPT_USERPWD, "$API_NAME:$API_KEY");
	$response = curl_exec($ch);
	curl_close($ch);

	

	$json = json_decode($response);
	$first_name = $json->first_name;
	$last_name = $json->last_name;
	$email = $json->email;

	file_put_contents('customer-'.$email.'-'.$time.'.txt',$response);
	error_log('new customer created-'.$email);

	$params = array (
		'user[name]' => $first_name." ".$last_name,
		'user[short_name]' => $first_name." ".$last_name,
		'pseudonym[unique_id]' => $email,
		'pseudonym[password]' => 'playful',
		'pseudonym[sis_user_id]' => $id,
		//'pseudonym[send_confirmation]' => '1'
		);

	print_r($params);

	$canvas_api_url = $CANVAS_URL."/accounts/".$CANVAS_ACCOUNT."/users/?access_token=".$CANVAS_TOKEN; 
	//$canvas_api_url = 'http://requestb.in/ysek20ys';	
	$ch = curl_init($canvas_api_url);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params
	);
	$canvas_response = json_decode(curl_exec($ch));
	curl_close($ch);
	$data = print_r($canvas_response, true);
	file_put_contents('logs/canvas-createuser-'.$time.'.txt',$canvas_response);

	//header('HTTP/1.0 200 OK');

}
?>
