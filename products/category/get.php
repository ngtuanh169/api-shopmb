<?php
    include("../../connect.php");
    
    $output = [];
    $data = [];
    $where = '';

    $name = isset($_GET['name']) && $_GET['name'] != '' ? 'category_name like "%'.$_GET['name'].'%"' : '';
    $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? 'LIMIT '.$_GET['limit'] : '';
    $offset = isset($_GET['page']) && $_GET['page'] != '' ? 'OFFSET '.($_GET['page'] - 1) * $_GET['limit'] : '';
    $status = isset($_GET['status']) && $_GET['status'] != '' ? 'category_status = '.$_GET['status'] : '';

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

    $sql_total = "SELECT * FROM category_product $where ";
    $rl_total = mysqli_query($conn,$sql_total);
    $count = mysqli_num_rows($rl_total);



    $sql = "SELECT * FROM category_product $where ORDER BY category_id ASC $limit $offset ";
    $rl = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($rl) ) {
        $id = $row['category_id'];
        $name = $row['category_name'];
        $img = $row['category_img'];
        $status = $row['category_status'];
        $dateCreated = $row['category_created'];

        array_push($data, [
            'id' => $id, 
            'name' => $name, 
            'img' => $img, 
            'status' => $status, 
            'dateCreated' => $dateCreated
        ]);
    }

    array_push($output, [
        'max' => $count,
        'data'=> $data,
    ]);

    echo json_encode($output);
	
?>