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
    $arr = [];
	$a = 'a';
	$time = time();

    $name = $_POST['name'] ;
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $img = $_FILES['img'];
    $imgs = $_FILES['imgs'];
    $ver = $_POST['ver'];
    $colors = $_POST['colors'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $sale = isset($_POST['sale']) ? $_POST['sale'] : '0';
    $des = isset($_POST['des']) ? $_POST['des'] : '';

    if(!$name){
        die();
    }
    
    // $imgsLength = $_POST['imgsLength'];

    for ($i=0; $i < count($imgs["tmp_name"]) ; $i++) { 
    	array_push($arr, [
        	$i+1 => $time.$imgs["name"][$i],
        ]);
    }
    $imgsArr = json_encode($arr);

    $fileImg = $time.$img['name'];

    $sql = "SELECT * FROM product WHERE product_name = '$name'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Tên sản phẩm đã tồn tại']);
    }else{
        $sql = "INSERT INTO product (category_id, brand_id, product_name, versions, colors, product_price,  sale, product_qty, product_img, product_imgs,product_des) 
        VALUES ('$category', '$brand', '$name',  '$ver',  '$colors',  '$price',  '$sale',  '$qty',  '$fileImg',  '$imgsArr',  '$des') ";
        $rl = mysqli_query($conn,$sql);
        $id_insert = mysqli_insert_id($conn);
        //kiểm tra thêm vào database chưa
        if($id_insert > 0){
             move_uploaded_file($img["tmp_name"], '../../assets/products/'.$fileImg);

             for ($id=0; $id < count($imgs["tmp_name"]) ; $id++) { 
             	move_uploaded_file($imgs["tmp_name"][$id], '../../assets/products/'.$arr[$id][$id+1]);
             }

            array_push($data,['status'=> 'success', 'message'=> 'Thêm sản phẩm thành công!']);
        }else{
            array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Thêm sản phẩm thất tại']);
        }  
    }
    echo json_encode($data);
	
?>