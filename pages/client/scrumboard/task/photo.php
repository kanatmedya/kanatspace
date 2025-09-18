<?php          
    $photoPathsJson = $row['photoPath'];
    $photoPaths = json_decode($photoPathsJson);
    
    if (!empty($photoPaths)) {
        $photoPaths = json_decode($photoPathsJson);
        foreach ($photoPaths as $photoPath) {

        }
    }else{
        $photoPath = "";
        $resized_width = "";
        $resized_height = "";
    }
    
    if ($photoPath != ""){
        echo'<img src="../'.$photoPath.'" alt="images" class="h-32 w-full rounded-md object-cover" />';
    }
?>