<?php
    include("../../connect.php");
    $output = [];
    $data = [];
    $where = '';
    $status = '';
    $orderBy = '';

    // $status = isset($_GET['status']) && $_GET['status'] != '' ? 'WHERE status = '.$_GET['status'] : '';
    $name = isset($_GET['name']) && $_GET['name'] != '' ? 'product_name like "%'.$_GET['name'].'%"' : '';
    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';
    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';


    if(isset($_GET['status']) && $_GET['status'] == '0'){
        $status = 'product_qty > 0 ';
    }

    elseif (isset($_GET['status']) && $_GET['status'] == '1') {
        $status = 'product_qty = 0 ';
    }

    else{
        $status = '';
    }



    if($name && $status){
        $where='WHERE '.$name.' AND '.$status;
    }

    elseif (!$name && $status) {
        $where='WHERE '.$status;
    }

    elseif ($name && !$status) {
        $where='WHERE '.$name;
    }

    else{
        $where='';
    }



    if(isset($_GET['sold']) && $_GET['sold'] == 'max' ){
        $orderBy = 'sold';
    }

    else{
        $orderBy = 'product_id';
    }



    $sql_total = "SELECT * FROM product $where ";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);

    $sql = "SELECT * FROM product $where ORDER BY $orderBy DESC $limit $offset ";
    $rl = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($rl) ) {
        $id = $row['product_id'];
        $name = $row['product_name'];
        $category_id = $row['category_id'];
        $brand_id = $row['brand_id'];
        $versions = $row['versions'];
        $colors = $row['colors'];
        $price = $row['product_price'];
        $qty = $row['product_qty'];
        $sale = $row['sale'];
        $sold = $row['sold'];
        $img = $row['product_img'];
        $imgs = $row['product_imgs'];
        $product_des = $row['product_des'];
        $product_created = $row['product_created'];
        $product_updated = $row['product_updated'];

        $sql_ct = "SELECT * FROM category_product WHERE category_id = '$category_id' ";
        $rl_ct = mysqli_query($conn,$sql_ct);
        $data_ct = mysqli_fetch_assoc($rl_ct);

        $sql_br = "SELECT * FROM brand_product WHERE brand_id = '$brand_id' ";
        $rl_br = mysqli_query($conn,$sql_br);
        $data_br = mysqli_fetch_assoc($rl_br);

        $sql_total = "SELECT * FROM rating WHERE pro_id = '$id' ";

        $rl_total = mysqli_query($conn,$sql_total);

        $totalRating = mysqli_num_rows($rl_total);

        $star = 0;

        while($row = mysqli_fetch_assoc($rl_total)){

            $star += $row['star'];
        }

        $tbcRating = $totalRating > 0 ? round(($star / $totalRating)) : 0 ;
        
        array_push($data, [
            'id' => $id, 
            'name' => $name, 
            'category_id' => $category_id, 
            'brand_id' => $brand_id, 
            'category' => $data_ct['category_name'], 
            'brand' => $data_br['brand_logo'], 
            'brandName' => $data_br['brand_name'], 
            'versions' => $versions, 
            'colors' => $colors, 
            'price' => $price, 
            'qty' => $qty,
            'sale' => $sale, 
            'sold' => $sold, 
            'img' => $img, 
            'imgs' => $imgs, 
            'product_des' => $product_des, 
            'star' => $tbcRating, 
            'product_created' => $product_created, 
            'product_updated' => $product_updated,
        ]);
    }

    array_push($output, [
        'max' => $count,
        'data'=> $data,
    ]);

    echo json_encode($output);
	
?>