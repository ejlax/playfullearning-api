<?php
//Storing all the default variables
$API_KEY = '';
$API_NAME = 'admin';
//$AUTH_CLIENT_TOKEN = '';
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

//$producer = $data->producer;
//$url = $data->destination->url;
$order_id = $data->data->id;
//$customer_id = $data->customer_id;
$type = $data->data->type;
$scope = $data->scope;
file_put_contents('logs/hooks/'.$order_id.'-'.$time.'.txt', $results);
error_log('new order recieved - '.$id);
//echo "Your Store is: ".$producer."<p></p>";
//echo "Your destination url is ".$url."<br>";


//echo "Your Id is: ".$order_id."<p></p>";
//echo "Your type is ".$type."<br>";
//echo "Your Scope is ".$scope;

/*if ($scope === 'store/order/created'){
  $bc_url = $STORE_URL."/orders/".$order_id.".json";
	$ch = curl_init($bc_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
	curl_setopt($ch, CURLOPT_USERPWD, "$API_NAME:$API_KEY");
	$response = curl_exec($ch);
	curl_close($ch);
file_put_contents('order-'.$time.'.txt',$response);
	echo "order<br>";
	print_r($response);

	$json = json_decode($response);
	//$course_id = $json->sku;
	$customer_id = $json->customer_id;

	//get products
		$bc_url = $STORE_URL."/orders/".$order_id."/products";
	$ch = curl_init($bc_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
	curl_setopt($ch, CURLOPT_USERPWD, "$API_NAME:$API_KEY");
	$response = new SimpleXMLElement(curl_exec($ch));
	curl_close($ch);
file_put_contents('products-'.$time.'.txt',$response);
	echo "product<br>";
	print_r($response);

	//$json = json_decode($response);
	$course_id = $response->product->sku;
	//$customer_id = $json->customer_id;

	echo "Your sku Id is ".$course_id."<br>";
	//$last_name = $json->last_name;
	//$email = $json->email;
	//Get the Canvas Id Of customer

	print_r($params);

	$canvas_api_url = $CANVAS_URL."/users/sis_user_id:".$customer_id."/profile/?access_token=".$CANVAS_TOKEN; 
	//$canvas_api_url = 'http://requestb.in/ysek20ys';		
	$ch = curl_init($canvas_api_url);
	//curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$canvas_response = json_decode(curl_exec($ch));
	curl_close($ch);
	$canvas_customer_id = $canvas_response->id;
	file_put_contents('users-canvas-'.$time.'.txt',$canvas_response);

	print_r($canvas_response);

$canvas_api_url = $CANVAS_URL."/courses/sis_course_id:".$course_id."/?access_token=".$CANVAS_TOKEN; 
	//$canvas_api_url = 'http://requestb.in/ysek20ys';		
	$ch = curl_init($canvas_api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$canvas_response = json_decode(curl_exec($ch));
	curl_close($ch);

	$canvas_course_id = $canvas_response->id;

	print_r($canvas_response);
	file_put_contents('canvas-course'.$time.'.txt',$canvas_response);


	$params = array (
		'enrollment[user_id]' => $canvas_customer_id,
		'enrollment[type]' => 'StudentEnrollment',
		'enrollment[enrollment_state]' => 'active'
		);

	print_r($params);

	$canvas_api_url = $CANVAS_URL."/courses/".$canvas_course_id."/enrollments/?access_token=".$CANVAS_TOKEN; 
	//$canvas_api_url = 'http://requestb.in/ysek20ys';		
	$ch = curl_init($canvas_api_url);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params
	);
	$canvas_response = json_decode(curl_exec($ch));
	curl_close($ch);

	file_put_contents('orders-canvas-'.$time.'.txt',$canvas_response);

	print_r($canvas_response);

	//header('HTTP/1.0 200 OK');

}*/

if ($scope === 'store/order/statusUpdated'){
	$bc_url = $STORE_URL."/orders/".$order_id.".json";
	$ch = curl_init($bc_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
	curl_setopt($ch, CURLOPT_USERPWD, "$API_NAME:$API_KEY");
	$response = curl_exec($ch);
	curl_close($ch);
file_put_contents('logs/orders/orderComplete-'.$order_id.'-'.$time.'.txt',$response);

	echo "order<br>";
	print_r($response);

	$json = json_decode($response);
	//$course_id = $json->sku;
	$customer_id = $json->customer_id;
	$orderStatus = $json->status;
	error_log($id.'- status updated - '.$orderStatus);
	sleep(30);
	if ($orderStatus === 'Completed'){
	//get products
	$bc_url = $STORE_URL."/orders/".$order_id."/products";
	error_log("new completed order - ".$bc_url);
	$ch = curl_init($bc_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
	curl_setopt($ch, CURLOPT_USERPWD, "$API_NAME:$API_KEY");
	$response = new SimpleXMLElement(curl_exec($ch));
	curl_close($ch);
	$results = print_r($response, true);
	file_put_contents('logs/products-'.$time.'.txt',$results);
	echo "product<br>";
	//print_r($response);

	//$json = json_decode($response);
	foreach ( $response as $product ) {


	
	$course_id = (String) $product->sku;
	//$customer_id = $json->customer_id;

	echo "Your sku Id is ".$course_id."<br>";
	//$last_name = $json->last_name;
	//$email = $json->email;
	//Get the Canvas Id Of customer

	//print_r($params);

	$canvas_api_url = $CANVAS_URL."/users/sis_user_id:".$customer_id."/profile/?access_token=".$CANVAS_TOKEN; 
	//$canvas_api_url = 'http://requestb.in/ysek20ys';		
	$ch = curl_init($canvas_api_url);
	//curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$canvas_response = json_decode(curl_exec($ch));
	$data = print_r($canvas_response, true);
	curl_close($ch);
	$canvas_customer_id = $canvas_response->id;
	file_put_contents('users-canvas-'.$time.'.txt',$data);

	print_r($canvas_response);



$canvas_api_url = $CANVAS_URL."/courses/sis_course_id:".$course_id."/?access_token=".$CANVAS_TOKEN; 
	//$canvas_api_url = 'http://requestb.in/ysek20ys';		
	$ch = curl_init($canvas_api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$canvas_response = json_decode(curl_exec($ch));
	curl_close($ch);
	$data = print_r($canvas_response, true);
	$canvas_course_id = $canvas_response->id;

	print_r($canvas_response);
	file_put_contents('canvas-course'.$time.'.txt',$data);


	$params = array (
		'enrollment[user_id]' => $canvas_customer_id,
		'enrollment[type]' => 'StudentEnrollment',
		'enrollment[enrollment_state]' => 'active',
		//'enrollment[notify]' => 'true'
		);

	print_r($params);

	$canvas_api_url = $CANVAS_URL."/courses/".$canvas_course_id."/enrollments/?access_token=".$CANVAS_TOKEN; 
	//$canvas_api_url = 'http://requestb.in/ysek20ys';		
	$ch = curl_init($canvas_api_url);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params
	);
	$canvas_response = json_decode(curl_exec($ch));
	curl_close($ch);
	$data = print_r($canvas_response, true);

	file_put_contents('orders-canvas-'.$time.'.txt',$data);

	print_r($canvas_response);

	//header('HTTP/1.0 200 OK');
}
}
}
$to = 'ericjamesadams@gmail.com';
//$from = 'subscriptions';
$subject = 'You Have a New Customer Enrollment';
$body = 'Thank you for registering with us.\r\nHope you enjoy your time with us.\r\n'.$customer_id;
$headers = 'From: canvas@playfullearning.net';

if(mail( $to, $subject, $body, $headers)){
	error_log("Email was sent to ".$to);
}else{
	error_log("Email failed.");
}
?>
