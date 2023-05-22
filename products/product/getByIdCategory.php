<?php
    include("../../connect.php");
    $output = [];
    $data = [];

    $id = $_GET['id'];
    $where = '';
    $price = '';
    $_sort = '';

    
    $_brand = isset($_GET['brand']) && $_GET['brand'] != '' ? $_GET['brand'] : '';
    $min = isset($_GET['min']) && $_GET['min'] != '' ? $_GET['min']*1000000 : '';
    $max = isset($_GET['max']) && $_GET['max'] != '' ? $_GET['max']*1000000 : '';

    $status = isset($_GET['status']) && $_GET['status'] != '' ? 'AND sale > 0' : '';

    $sort = isset($_GET['sort']) && $_GET['sort'] != '' ? $_GET['sort'] : '';

    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';
    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';

   

    if($_brand != '' ){
        $where.=" AND brand_id = $_brand ";
    }

    if($min != '' ){
        $price.=" AND product_price >= $min ";
    }
    if($max != '' ){
        $price.=" AND product_price <= $max";
    }

    if($sort == '' ){
        $_sort ="product_id DESC";
    }
    if($sort == 'hot' ){
        $_sort ="sold DESC";
    }
    if($sort == 'max' ){
        $_sort ="product_price DESC";
    }
    if($sort == 'min' ){
        $_sort ="product_price ASC";
    }



    $sql_category = "SELECT * FROM category_product WHERE category_id = '$id' ";
    $rl_category = mysqli_query($conn,$sql_category);
    $data_category = mysqli_fetch_assoc($rl_category);

    $sql_total = "SELECT * FROM product WHERE category_id = '$id' $where $price $status ";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);


    $sql = "SELECT * FROM product WHERE category_id = '$id' $where $price $status ORDER BY $_sort $limit $offset ";
    $rl = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($rl) ) {
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
        $sale = $row['sale'];

        $sql_ct = "SELECT * FROM category_product WHERE category_id = '$category' ";
        $rl_ct = mysqli_query($conn,$sql_ct);
        $data_ct = mysqli_fetch_assoc($rl_ct);

        $sql_br = "SELECT * FROM brand_product WHERE brand_id = '$brand' ";
        $rl_br = mysqli_query($conn,$sql_br);
        $data_br = mysqli_fetch_assoc($rl_br);

        $sql_rt = "SELECT * FROM rating WHERE pro_id = '$id' ";
        $rl_rt = mysqli_query($conn,$sql_rt);
        $totalRating = mysqli_num_rows($rl_rt);
        $star = 0;

        while($row_rt = mysqli_fetch_assoc($rl_rt)){

            $star += $row_rt['star'];
        }

        $tbcRating = $totalRating > 0 ? round(($star / $totalRating)) : 0 ;

        array_push($data, [
            'id' => $id, 
            'name' => $name, 
            'category' => $data_ct['category_name'], 
            'brand' => $data_br['brand_logo'], 
            'versions' => $versions,
            'colors' => $colors,
            'img' => $img, 
            'imgs' => $imgs,
            'price' => $price, 
            'sale' => $sale, 
            'qty' => $qty,
            'sold' => $sold,
            'sale' => $sale,
            'star' => $tbcRating,

        ]);
    }

    array_push($output, [
        'max' => $count,
        'data'=> $data,
        'category_product'=> $data_category['category_name'],
    ]);

    echo json_encode($output);
	
?>