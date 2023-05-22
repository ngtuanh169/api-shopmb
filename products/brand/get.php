<?php
    include("../../connect.php");
    $output = [];
    $data = [];
    $where = '';


    $name = isset($_GET['name']) && $_GET['name'] != '' ? 'brand_name like "%'.$_GET['name'].'%"' : '';
    $status = isset($_GET['status']) && $_GET['status'] != '' ? 'status = '.$_GET['status'] : '';
    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';
    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';

    if($name && $status){
        $where = 'WHERE '.$name.'AND'.$status;
    }
    elseif (!$name && $status) {
        $where = 'WHERE '.$status;
    }
    elseif ($name && !$status) {
        $where = 'WHERE '.$name;
    }
    else{
        $where = '';
    }

    $sql_total = "SELECT * FROM brand_product  $where ";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);

    $sql = "SELECT * FROM brand_product  $where ORDER BY brand_id DESC $limit $offset ";
    $rl = mysqli_query($conn,$sql);

    while ($row = mysqli_fetch_assoc($rl) ) {
        $id = $row['brand_id'];
        $category_id = $row['category_id'];
        $name = $row['brand_name'];
        $logo = $row['brand_logo'];
        $status = $row['status'];
        $dateCreated = $row['brand_created'];
        

        $_sql = "SELECT * FROM category_product WHERE category_id = '$category_id'";
        $_rl = mysqli_query($conn,$_sql);
        $_row = mysqli_fetch_assoc($_rl);
        $categoryName = $_row['category_name'];
        $categoryId = $_row['category_id'];

        array_push($data, [
            'id' => $id,
            'categoryId' => $categoryId, 
            'categoryName' => $categoryName, 
            'name' => $name, 
            'logo' => $logo,  
            'status' => $status,
            'dateCreated' => $dateCreated,
            
        ]);
    }
    array_push($output, [
        'max' => $count,
        'data' => $data
    ]);

    echo json_encode($output);
// ?>