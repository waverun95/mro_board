<?php
require_once("common/common_variable.php");
require_once("common/function.php");

$attach_result = false;
$write_result = false;
$update_result = false;
$delete_result = false; 
$write_result_one = false;

if ($serch_title != "" || $serch_writer != "" || $serch_fdate != "" || $serch_ldate != "") {
  $URL = "/mro_board/index.php?page=$page&serch_title=$serch_title&serch_writer=$serch_writer&serch_fdate=$serch_fdate&serch_ldate=$serch_ldate";
} else {
  $URL = "/mro_board/index.php?page=$page";
}
$serch_paging = "localhost/mro_board/index.php?serch_title={$_POST['serch_title']}serch_writer=&serch_fdate=&serch_ldate=";

$customer_type = $_POST['CUSTOMER_TYPE'];
$customer_type2 = implode(",", $customer_type);

require("common/validation.php");


// 트랜잭션 시작......................
mysqli_query($conn, "SET AUTOCOMMIT=0");
mysqli_query($conn, "START TRANSACTION");


if (isset($_POST['IDX'])) { // 필터 적용한 내용들
  $idx = mysqli_real_escape_string($conn, $_POST['IDX']);
}
$filtered = array(
  'POST_TYPE' => mysqli_real_escape_string($conn, $_POST['POST_TYPE']),
  'WRITER' => htmlspecialchars(mysqli_real_escape_string($conn, $_POST['WRITER'])),
  'CATEGORY' => mysqli_real_escape_string($conn, $_POST['CATEGORY']),
  'CUSTOMER_TYPE' => mysqli_real_escape_string($conn, $customer_type2),
  'TITLE' => htmlspecialchars(mysqli_real_escape_string($conn, $_POST['TITLE'])),
  'CONTENT' => htmlspecialchars(mysqli_real_escape_string($conn, $_POST['CONTENT'])),
  'FILE_CHECK' => mysqli_real_escape_string($conn, $_POST['FILE_CHECK'])

);
$write_query = "update mro_board
                set 
                POST_TYPE = '{$filtered['POST_TYPE']}',
                WRITER = '{$filtered['WRITER']}',
                CATEGORY = '{$filtered['CATEGORY']}',
                CUSTOMER_TYPE = '{$filtered['CUSTOMER_TYPE']}',
                TITLE = '{$filtered['TITLE']}',
                CONTENT = '{$filtered['CONTENT']}',
                FILE_CHECK = '{$filtered['FILE_CHECK']}'
                where IDX = {$idx}";

if ($_FILES['file']['size'] > 0) { //파일이 새로 들어온경우

  $file_id = gen_uuid();
  $tmpfile =  $_FILES['file']['tmp_name'];
  $file_name = $_FILES['file']['name'];
  $save_filename = $_SERVER['DOCUMENT_ROOT'] . "/mro_board/upload/{$file_id}-" . $file_name;

  $filtered_attach = array( // 필터 적용한 첨부파일 내용들
    'FILE_ID' => mysqli_real_escape_string($conn, $file_id),
    'IDX' => mysqli_real_escape_string($conn, $idx),
    'FILE_NAME' => mysqli_real_escape_string($conn, $file_name),
    'FILE_PATH' => mysqli_real_escape_string($conn, $save_filename),
    'FILE_TYPE' => mysqli_real_escape_string($conn, $_FILES['file']['type']),
    'FILE_SIZE' => mysqli_real_escape_string($conn, $_FILES['file']['size'])
  );

  if ($_POST['pre_file_name'] != "") { // 이전 파일이 있고 파일을 새로 등록할경우 update   
    $attach_query = "update mro_attach
                      set
                          FILE_ID = '{$filtered_attach['FILE_ID']}',
                          IDX = '{$filtered_attach['IDX']}',
                          FILE_NAME = '{$filtered_attach['FILE_NAME']}',
                          FILE_PATH = '{$filtered_attach['FILE_PATH']}',
                          FILE_TYPE = '{$filtered_attach['FILE_TYPE']}',
                          FILE_SIZE = '{$filtered_attach['FILE_SIZE']}'
                      where IDX = {$idx}";
    $attach_result = mysqli_query($conn, $attach_query);
    $write_result = mysqli_query($conn, $write_query);

  } else if ($_POST['pre_file_name'] == "") { //이전 파일이 없고 파일이 새로 들어온 경우는 create
      $attach_query = "insert into mro_attach(FILE_ID,IDX,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_SIZE)
                        values('{$filtered_attach['FILE_ID']}',
                        '{$filtered_attach['IDX']}',
                        '{$filtered_attach['FILE_NAME']}',
                        '{$filtered_attach['FILE_PATH']}',
                        '{$filtered_attach['FILE_TYPE']}',
                        '{$filtered_attach['FILE_SIZE']}')";

      $attach_result = mysqli_query($conn, $attach_query);
      $write_result = mysqli_query($conn, $write_query);  
  }
  //결과 처리.....
    if ($attach_result && $write_result) { 
      mysqli_query($conn, "COMMIT");
      mysqli_query($conn, "SET AUTOCOMMIT=1");
      move_uploaded_file($tmpfile, $save_filename);

      if(file_exists("{$_SERVER['DOCUMENT_ROOT']}/mro_board/upload/{$_POST['pre_file_id']}-{$_POST['pre_file_name']}")){
        unlink("{$_SERVER['DOCUMENT_ROOT']}/mro_board/upload/{$_POST['pre_file_id']}-{$_POST['pre_file_name']}");
      }
  } else {
      mysqli_query($conn, "ROLLBACK");
      }
  
} else {// 파일이 안들어왔을경우.....

  if ($_POST['FILE_CHECK'] == 'N' && $_POST['pre_file_name'] != "") { // 이전 파일이름이 있고 파일체크가 N으로 바뀐경우...
    //db 파일먼저 삭제
    $delete_query = "delete from mro_attach where IDX = {$idx}";

    $update_query = "update mro_board set FILE_CHECK = 'N' where IDX = {$idx}";

    $write_result = mysqli_query($conn, $write_query);
    $delete_result = mysqli_query($conn, $delete_query);
    $update_result = mysqli_query($conn, $update_query);

  } else if ($_POST['FILE_CHECK'] == 'N' && $_POST['pre_file_name'] == "" || $_POST['FILE_CHECK'] == 'Y') { // 글만 수정한 경우....
    $write_result_one = mysqli_query($conn, $write_query);

  }
  //결과 처리...
  if (($update_result && $write_result && $delete_result) || $write_result_one) {      
      mysqli_query($conn, "COMMIT");
      mysqli_query($conn, "SET AUTOCOMMIT=1");

      if($update_result && $write_result && $delete_result) {
        unlink("{$_SERVER['DOCUMENT_ROOT']}/mro_board/upload/{$_POST['pre_file_id']}-{$_POST['pre_file_name']}");
      }
    } else {
          mysqli_query($conn, "ROLLBACK");
        }
}

if(($attach_result && $write_result) || ($update_result && $write_result && $delete_result) || $write_result_one) {
  echo ' <script>
              alert("글이 수정되었습니다.");
              location.href = "' . $URL . '";
              </script>';
}else {
  echo '<script>
              alert("글이 수정되지 않았습니다.");
              location.href = "/mro_board/index.php";
              </script>';
}