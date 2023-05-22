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
    $picture = $_FILES['picture'];
    $status = $_POST['status'];

    $time = time();
    $fileImg = $time.$picture['name'];

    if(!$name){
        die();
    }

    $sql = "SELECT * FROM category_product WHERE category_name = '$name'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Tên danh mục đã tồn tại']);
    }else{
        $sql = "INSERT INTO category_product (category_name, category_img,  category_status) 
        VALUES ('$name', '$fileImg',  '$status') ";
        $rl = mysqli_query($conn,$sql);
        $id_insert = mysqli_insert_id($conn);
        //kiểm tra thêm vào database chưa
        if($id_insert > 0){
             move_uploaded_file($picture["tmp_name"], '../../assets/category/'.$fileImg);

            array_push($data,['status'=> 'success', 'message'=> 'Thêm danh mục thành công!']);
        }else{
            array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Thêm danh mục thất tại']);
        }  
    }

    echo json_encode($data);
	
?>