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
    $img = $_FILES['logo'];
    $categoryId = $_POST['category'];
    $status = $_POST['status'];

    // var_dump($id);
    // var_dump($name);
    // var_dump($img);
    // var_dump($status);

    if($img){
        $fileImg = $time.$img['name'];

        $img_text = 'brand_logo = '.json_encode($fileImg).',';
    }
    $sql = "SELECT * FROM brand_product WHERE brand_name = '$name'
        && category_id = '$categoryId' && brand_id != '$id'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Tên nhãn hàng đã tồn tại']);
    }else{
        $sql = "UPDATE brand_product SET brand_name='$name', $img_text category_id = '$categoryId', status = '$status' WHERE brand_id = '$id'";
        
        $rl = mysqli_query($conn,$sql);


        if($img){
            move_uploaded_file($img["tmp_name"], '../../assets/category/'.$fileImg);
        }
             
        array_push($data,['status'=> 'success', 'message'=> 'Sửa nhãn hàng thành công!']);
        
    }

    echo json_encode($data);
	
?>