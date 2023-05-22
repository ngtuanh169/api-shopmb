<?php
    include("../../connect.php");
    $data = [];

    $idCategory = $_GET['idCategory'];


    $sql = "SELECT * FROM brand_product  WHERE category_id = '$idCategory' ";
    $rl = mysqli_query($conn,$sql);
    // var_dump($sql);
    while ($row = mysqli_fetch_assoc($rl) ) {
        $id = $row['brand_id'];
        $name = $row['brand_name'];
        $logo = $row['brand_logo'];
        $status = $row['status'];
        $dateCreated = $row['brand_created'];
        

        array_push($data, [
            'id' => $id,
            'name' => $name, 
            'logo' => $logo,  
            'status' => $status,
            'dateCreated' => $dateCreated,
            
        ]);
    }

    echo json_encode($data);
// ?>