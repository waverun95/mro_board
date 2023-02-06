<?php
require_once("common/dbcon.php");
require_once("common/function.php");

$current_idx_query ="select max(IDX)+1 FROM mro_board"; 

$current_idx_result = mysqli_query($conn, $current_idx_query);

$current_idx = mysqli_fetch_array($current_idx_result);

mysqli_query($conn,"alter table mro_board AUTO_INCREMENT={$current_idx[0]}"); // 자동 증가값이 계속해서 변경되서 초기화후 다시 사용

//function gen_uuid() {
//  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
//     mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
//     mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
//     mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
//   );
//}
// $post_type = $_POST['POST_TYPE'];
// $writer = $_POST['WRITER'];
// $category = $_POST['CATEGORY'];
// $title = $_POST['TITLE'];
// $content = $_POST['CONTENT'];
// $filepath = $_POST['FILEPATH'];
$customer_type = $_POST['CUSTOMER_TYPE'];
$customer_type2 = implode(",",$customer_type);
var_dump($customer_type2);
// $URL = './index.php';

require_once("validation.php");

$filtered_write = array(
  'POST_TYPE'=>mysqli_real_escape_string($conn,$_POST['POST_TYPE']),
  'WRITER'=>mysqli_real_escape_string($conn,$_POST['WRITER']),
  'CATEGORY'=>mysqli_real_escape_string($conn,$_POST['CATEGORY']),
  'CUSTOMER_TYPE'=>mysqli_real_escape_string($conn,$customer_type2),
  'TITLE'=>mysqli_real_escape_string($conn,$_POST['TITLE']),
  'CONTENT'=>mysqli_real_escape_string($conn,$_POST['CONTENT']),
  'FILE_CHECK'=>mysqli_real_escape_string($conn,$_POST['FILE_CHECK'])
);

$write_query = "insert into mro_board(POST_TYPE,WRITER,CATEGORY,CUSTOMER_TYPE,TITLE,CONTENT,FILE_CHECK)
values('{$filtered_write['POST_TYPE']}',
'{$filtered_write['WRITER']}',
'{$filtered_write['CATEGORY']}',
'{$filtered_write['CUSTOMER_TYPE']}',
'{$filtered_write['TITLE']}',
'{$filtered_write['CONTENT']}',
'{$filtered_write['FILE_CHECK']}')";


$write_result = $conn->query($write_query);


if($_FILES['file']['size'] > 0){
 
  $file_id = gen_uuid(); //파일명 겹치지 않게

  $tmpfile =  $_FILES['file']['tmp_name'];
  $file_name = $_FILES['file']['name'];
  //$filename = iconv("UTF-8", "EUC-KR",$_FILES['file']['name']);
  
  echo "ddd";
  print_r($file_name);
  $save_filename = $_SERVER['DOCUMENT_ROOT'] . "/mro_board/upload/{$file_id}-".$file_name;
  
  move_uploaded_file($tmpfile,$save_filename);

  $filtered_attach = array(
  'FILE_ID'=>mysqli_real_escape_string($conn,$file_id),
  'IDX'=>mysqli_real_escape_string($conn,$current_idx[0]),
  'FILE_NAME'=>mysqli_real_escape_string($conn,$file_name),
  'FILE_PATH'=>mysqli_real_escape_string($conn,$save_filename),
  'FILE_TYPE'=>mysqli_real_escape_string($conn,$_FILES['file']['type']),
  'FILE_SIZE'=>mysqli_real_escape_string($conn,$_FILES['file']['size'])
  );

  $attach_query = "insert into mro_attach(FILE_ID,IDX,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_SIZE)
  values('{$filtered_attach['FILE_ID']}',
  '{$filtered_attach['IDX']}',
  '{$filtered_attach['FILE_NAME']}',
  '{$filtered_attach['FILE_PATH']}',
  '{$filtered_attach['FILE_TYPE']}',
  '{$filtered_attach['FILE_SIZE']}')";

  print_r($attach_query);
  $attach_result = $conn->query($attach_query);
  
  var_dump($filtered_attach['IDX']);
  if ($attach_result) {
    echo '<script>
    alert("파일첨부 되었습니다.");
    </script>';
  }
  else{
    echo "FAIL";
  }
}
if ($write_result) {
  echo ' <script>
  alert("글이 등록되었습니다.");
  location.href = "/mro_board/index.php";
  </script>';
     //$URL = $_SERVER['HTTP_HOST']."/mro_board/index.php";
     //header("HTTP/1.1 307 Temporary move");
     //header("Location: http://$URL");
}
else{
  echo "FAIL";
}
  ?>
