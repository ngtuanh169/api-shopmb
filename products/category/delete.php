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

	$_sql = "SELECT * FROM category_product WHERE category_id = '$id'";
	$_rl = mysqli_query($conn,$_sql);
	$data = mysqli_fetch_assoc($_rl);

	if(!$data){

		array_push($message,['status'=> 'error', 'message'=> 'Danh mục không tồn tại!']);

		echo json_encode($message);

		die();
	}

	$sql = "DELETE FROM category_product WHERE category_id = '$id'";
	$rl = mysqli_query($conn,$sql);
	$insert_id = mysqli_insert_id($conn);
	if($insert_id > 0){
		array_push($data,['status'=> 'error', 'message'=> 'Xóa danh mục thất bại!']);
	}else{
		unlink('../../assets/category/'.$data['category_img']);

		array_push($message,['status'=> 'success', 'message'=> 'Xóa danh mục thàng công!']);
	}

	echo json_encode($message);
?>