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


    $data = [];

    $name = $_POST['name'] ;
    $categoryId = $_POST['category'];
    $logo = $_FILES['logo'];
    $status = $_POST['status'];

    $time = time();
    $fileLogo = $time.$logo['name'];

    if(!$name){
        die();
    }

    $sql = "SELECT * FROM brand_product WHERE brand_name = '$name' AND category_id = '$categoryId'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['success'=> false, 'message'=> 'Lỗi! Nhãn hàng đã tồn tại']);
    }else{
        $sql = "INSERT INTO brand_product (category_id, brand_name, brand_logo, status) 
        VALUES ('$categoryId', '$name', '$fileLogo','$status') ";
        $rl = mysqli_query($conn,$sql);
        $id_insert = mysqli_insert_id($conn);
        //kiểm tra thêm vào database chưa
        if($id_insert > 0){
             move_uploaded_file($logo["tmp_name"], '../../assets/category/'.$fileLogo);

            array_push($data,['status'=> 'success', 'message'=> 'Thêm nhãn hàng thành công!']);
        }else{
            array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Thêm nhãn hàng thất tại']);
        }  
    }

    echo json_encode($data);
	
?>