<?php
require_once("common/dbcon.php");
require_once("common/function.php");

$current_idx_query = "select max(IDX)+1 FROM mro_board"; 

$current_idx_result = mysqli_query($conn, $current_idx_query);

$current_idx = mysqli_fetch_array($current_idx_result);

mysqli_query($conn,"alter table mro_board AUTO_INCREMENT={$current_idx[0]}"); // 자동 증가값이 계속해서 변경되서 초기화후 다시 사용

$customer_type = $_POST['CUSTOMER_TYPE'];
$customer_type2 = implode(",",$customer_type);

require("common/validation1.php");

// 트랜잭션 시작......................
mysqli_query($conn, "SET AUTOCOMMIT=0");
mysqli_query($conn, "START TRANSACTION");


$filtered_write = array(
  'POST_TYPE'=>mysqli_real_escape_string($conn, $_POST['POST_TYPE']),
  'WRITER'=>htmlspecialchars(mysqli_real_escape_string($conn, $_POST['WRITER'])),
  'CATEGORY'=>mysqli_real_escape_string($conn, $_POST['CATEGORY']),
  'CUSTOMER_TYPE'=>mysqli_real_escape_string($conn, $customer_type2),
  'TITLE'=>htmlspecialchars(mysqli_real_escape_string($conn, $_POST['TITLE'])),
  'CONTENT'=>htmlspecialchars(mysqli_real_escape_string($conn, $_POST['CONTENT'])),
  'FILE_CHECK'=>mysqli_real_escape_string($conn, $_POST['FILE_CHECK'])
);

$write_query = "insert into mro_board(POST_TYPE,WRITER,CATEGORY,CUSTOMER_TYPE,TITLE,CONTENT,FILE_CHECK)
values('{$filtered_write['POST_TYPE']}',
'{$filtered_write['WRITER']}',
'{$filtered_write['CATEGORY']}',
'{$filtered_write['CUSTOMER_TYPE']}',
'{$filtered_write['TITLE']}',
'{$filtered_write['CONTENT']}',
'{$filtered_write['FILE_CHECK']}')";


if($_FILES['file']['size'] > 0){

// 파일첨부............
  $file_id = gen_uuid(); //파일명 겹치지 않게

  $tmpfile =  $_FILES['file']['tmp_name'];
  $file_name = $_FILES['file']['name'];

  $save_filename = $_SERVER['DOCUMENT_ROOT'] . "/mro_board/upload/{$file_id}-".$file_name;
  
  $filtered_attach = array(
  'FILE_ID'=>mysqli_real_escape_string($conn, $file_id),
  'IDX'=>mysqli_real_escape_string($conn, $current_idx[0]),
  'FILE_NAME'=>mysqli_real_escape_string($conn, $file_name),
  'FILE_PATH'=>mysqli_real_escape_string($conn, $save_filename),
  'FILE_TYPE'=>mysqli_real_escape_string($conn, $_FILES['file']['type']),
  'FILE_SIZE'=>mysqli_real_escape_string($conn, $_FILES['file']['size'])
  );

  $attach_query = "insert into mro_attach(FILE_ID,IDX,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_SIZE)
  values('{$filtered_attach['FILE_ID']}',
  '{$filtered_attach['IDX']}',
  '{$filtered_attach['FILE_NAME']}',
  '{$filtered_attach['FILE_PATH']}',
  '{$filtered_attach['FILE_TYPE']}',
  '{$filtered_attach['FILE_SIZE']}')";

  //파일첨부..............
  $attach_result = mysqli_query($conn, $attach_query);
  // 글 등록............
  $write_result = mysqli_query($conn, $write_query);
  
  // Rollback transaction

  if($attach_result && $write_result){
  mysqli_query($conn, "COMMIT");
  mysqli_query($conn, "SET AUTOCOMMIT=1");
  move_uploaded_file($tmpfile, $save_filename);

  echo '<script>  
  alert("글이 등록되었습니다.");
  location.href = "/mro_board/index.php";
  </script>';

  }else {
  
  mysqli_query($conn, "ROLLBACK");
  echo '<script>  
  alert("글이 등록되지 않았습니다.");
  location.href = "/mro_board/index.php";
  </script>';
  }

}else {
  //파일첨부 없이..... 글 등록........
  $write_result = mysqli_query($conn, $write_query);

  if($write_result){
    mysqli_query($conn, "COMMIT");
    mysqli_query($conn, "SET AUTOCOMMIT=1");
    echo '<script> 
    alert("글이 등록되었습니다.");
    location.href = "/mro_board/index.php";
    </script>';

  }else {
  
    mysqli_query($conn, "ROLLBACK"); 
    echo '<script>  
    alert("글이 등록되지 않았습니다.");
    location.href = "/mro_board/index.php";
    </script>';
  }
}
