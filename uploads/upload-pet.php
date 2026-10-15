<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_FILES["petImage"])){

        $targetDir = "../uploads/pets/";

        if(!file_exists($targetDir)){
            mkdir($targetDir,0777,true);
        }

        $extension = pathinfo($_FILES["petImage"]["name"], PATHINFO_EXTENSION);

        $fileName = "pet_" . time() . "." . $extension;

        $targetFile = $targetDir . $fileName;

        if(move_uploaded_file($_FILES["petImage"]["tmp_name"],$targetFile)){

            echo json_encode([
                "success"=>true,
                "image"=>"uploads/pets/".$fileName
            ]);

        }else{

            echo json_encode([
                "success"=>false
            ]);

        }

    }

}