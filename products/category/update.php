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
    $img_text = '';
	$time = time();

    $id = $_POST['id'] ;
    $name = $_POST['name'] ;
    $img = $_FILES['picture'];
    $status = $_POST['status'];

    if($img){
        $fileImg = $time.$img['name'];

        $img_text = 'category_img = '.json_encode($fileImg).',';
    }
    $sql = "SELECT * FROM category_product WHERE category_name = '$name' && category_id != '$id'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Tên danh mục đã tồn tại']);
    }else{
        $sql = "UPDATE category_product SET category_name='$name', $img_text category_status = '$status' WHERE category_id = '$id'";
        
        $rl = mysqli_query($conn,$sql);


        if($img){
            move_uploaded_file($img["tmp_name"], '../../assets/category/'.$fileImg);
        }
             
        array_push($data,['status'=> 'success', 'message'=> 'Sửa danh mục thành công!']);
        
    }

    echo json_encode($data);
	
?>