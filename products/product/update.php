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
    $img_text = '';
    $imgs_text = '';
	$time = time();

    $id = $_POST['id'] ;
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
    
    // $imgsLength = $_POST['imgsLength'];
    if($img){
        $fileImg = $time.$img['name'];

        $img_text = 'product_img = '.json_encode($fileImg).',';
        // var_dump($img_text);
    }

    if($imgs){

        for ($i=0; $i < count($imgs["tmp_name"]) ; $i++) { 

            array_push($arr, [
                $i+1 => $time.$imgs["name"][$i],
            ]);

        }

        $imgsArr = json_encode($arr);

        $imgs_text = "product_imgs = '$imgsArr',";
        // var_dump($imgs_text);
    }

    $sql = "SELECT * FROM product WHERE product_name = '$name' && product_id != '$id'";
    $rl = mysqli_query($conn,$sql);
    $check = mysqli_num_rows($rl);
    if($check > 0){
        array_push($data,['status'=> 'error', 'message'=> 'Lỗi! Tên sản phẩm đã tồn tại']);
    }else{
        $sql = "UPDATE product SET category_id='$category', brand_id='$brand', product_name='$name', versions='$ver', colors='$colors', product_price='$price', sale='$sale', product_qty='$qty', $img_text $imgs_text product_des='$des' WHERE product_id = '$id'";
        
        $rl = mysqli_query($conn,$sql);

        if($_FILES['img']){
            move_uploaded_file($img["tmp_name"], '../../assets/products/'.$fileImg);
        }

        if($_FILES['imgs']){
            for ($id=0; $id < count($imgs["tmp_name"]) ; $id++) { 
            move_uploaded_file($imgs["tmp_name"][$id], '../../assets/products/'.$arr[$id][$id+1]);
            }
        }
             
        array_push($data,['status'=> 'success', 'message'=> 'Sửa sản phẩm thành công!']);
        
    }

    echo json_encode($data);
	
?>