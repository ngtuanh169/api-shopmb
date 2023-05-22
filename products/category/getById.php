<?php
    include("../../connect.php");
    $data = [];

    $id = $_GET['idCategory'];



    $sql = "SELECT * FROM category_product WHERE category_id = $id";
    $rl = mysqli_query($conn,$sql);

    $row = mysqli_fetch_assoc($rl);
        $id = $row['category_id'];
        $name = $row['category_name'];
        $img = $row['category_img'];
        $config = $row['config'];
        $status = $row['category_status'];
        $dateCreated = $row['category_created'];

        array_push($data, [
            'id' => $id, 
            'name' => $name, 
            'img' => $img, 
            'config' => $config, 
            'status' => $status, 
            'dateCreated' => $dateCreated
        ]);

    echo json_encode($data);
	
?>