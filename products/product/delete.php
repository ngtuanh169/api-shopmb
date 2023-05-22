<?php 
	include("../../connect.php");

    include("../../auth/jwt.php");

    $headers = getallheaders();

    $token = $headers['access_token'];

    $verify=verifyAccessToken($token);

    if ($verify['err']) {
        die();
    }

    $arr = explode('.', $token);

    $base64Payload = $arr[1];

    $jsonPayload = base64_decode($base64Payload);

    $payload = json_decode($jsonPayload, true);

    $isAdmin = $payload['admin'];

    if(!$isAdmin){

        die();
    } 

	$message = [];
    $arr = [];

	$id = $_GET['id'];
	$_sql = "SELECT * FROM product WHERE product_id = '$id'";
	$_rl = mysqli_query($conn,$_sql);
	$data = mysqli_fetch_assoc($_rl);

	// var_dump($data['product_imgs']);
	$imgs = json_decode($data['product_imgs'], true);

	

	$sql = "DELETE FROM product WHERE product_id = '$id'";
	$rl = mysqli_query($conn,$sql);
	$insert_id = mysqli_insert_id($conn);
	if($insert_id > 0){
	   array_push($data,['status'=> 'error', 'message'=> 'Xóa sản phẩm thất bại!']);
	}else{
	    unlink('../../assets/products/'.$data['product_img']);

	    for ($i=0; $i < count($imgs) ; $i++) { 
    	unlink('../../assets/products/'.$imgs[$i][$i+1]);
    	}

	    array_push($message,['status'=> 'success', 'message'=> 'Xóa sản phẩm thàng công!']);
	}
	echo json_encode($message);