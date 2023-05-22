<?php
    include("../../connect.php");
    $data = [];

    $id = isset($_GET['id']) && $_GET['id'] != '' ? $_GET['id'] : '';


    $sql = "SELECT * FROM product WHERE product_id = '$id' ";
    $rl = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($rl);

        $id = $row['product_id'];
        $name = $row['product_name'];
        $category = $row['category_id'];
        $brand = $row['brand_id'];
        $versions = $row['versions'];
        $colors = $row['colors'];
        $img = $row['product_img'];
        $imgs = $row['product_imgs'];
        $price = $row['product_price'];
        $sale = $row['sale'];
        $qty = $row['product_qty'];
        $sold = $row['sold'];
        $des = $row['product_des'];
        $created = $row['product_created'];

        $sql_ct = "SELECT * FROM category_product WHERE category_id = '$category' ";
        $rl_ct = mysqli_query($conn,$sql_ct);
        $data_ct = mysqli_fetch_assoc($rl_ct);

        $sql_br = "SELECT * FROM brand_product WHERE brand_id = '$brand' ";
        $rl_br = mysqli_query($conn,$sql_br);
        $data_br = mysqli_fetch_assoc($rl_br);

        array_push($data, [
            'id' => $id, 
            'name' => $name, 
            'category_id' => $data_ct['category_id'], 
            'category' => $data_ct['category_name'], 
            'brand_id' => $data_br['brand_id'], 
            'brand' => $data_br['brand_name'], 
            'versions' =>$versions,
            'colors' =>$colors,
            'img' => $img, 
            'imgs' => $imgs, 
            'price' => $price, 
            'sale' => $sale, 
            'qty' => $qty,
            'sold' => $sold,
            'des' => $des,
            'created' => $created,
      ]);

    echo json_encode($data);
	
?>