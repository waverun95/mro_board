<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    $conn = mysqli_connect("localhost", "root", "qwe789", "homework");
    
   
    
    if($_FILES['file']['size'] > 0) {
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $save_filename = $_SERVER['DOCUMENT_ROOT'] . "/mro_board/upload/" . $_FILES['file']['name'];
        
        $file_upload    = move_uploaded_file($file_tmp_name, $save_filename);
        array_push($_FILES,["file_tmp_name" => $_FILES['file']['tmp_name'],
        "save_filename" => $_SERVER['DOCUMENT_ROOT'] . "/mro_board/upload/" . $_FILES['file']['name']]);

        $filtered = array(
            'FILE_ID'=>mysqli_real_escape_string($conn,$_POST['FILE_ID']),
            'IDX'=>mysqli_real_escape_string($conn,$_POST['IDX']),
            'FILE_NAME'=>mysqli_real_escape_string($conn,$_FILES['file']['name']),
            'FILE_PATH'=>mysqli_real_escape_string($conn,$save_filename),
            'FILE_TYPE'=>mysqli_real_escape_string($conn,$_FILES['file']['type']),
            'FILE_SIZE'=>mysqli_real_escape_string($conn,$_FILES['file']['size'])
            
          
          );
          
          $query = "insert into mro_attach(FILE_ID,IDX,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_SIZE)
          values('{$filtered['FILE_ID']}',
          '{$filtered['IDX']}',
          '{$filtered['FILE_NAME']}',
          '{$filtered['FILE_PATH']}',
          '{$filtered['FILE_TYPE']}',
          '{$filtered['FILE_SIZE']}')";
          
          echo($query);
          
          $result = $conn->query($query);
          
          echo($result);

        print_r(json_encode($_FILES)); 
    } else {
        echo "failed";
    }
?>