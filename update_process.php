<?php
require_once("common/common_variable.php");
require_once("common/function.php");

if ($serch_title !="" || $serch_writer != "" || $serch_fdate != "" || $serch_ldate != ""){
  $URL = "/mro_board/index.php?page=$page&serch_title=$serch_title&serch_writer=$serch_writer&serch_fdate=$serch_fdate&serch_ldate=$serch_ldate"; 
  echo $URL;
}else {
  $URL = "/mro_board/index.php?page=$page";
  echo $URL;
}


$serch_paging = "localhost/mro_board/index.php?serch_title={$_POST['serch_title']}serch_writer=&serch_fdate=&serch_ldate=";
echo $serch_paging;

var_dump($_POST['pre_file_id'].$_POST['pre_file_name']);
// $post_type = $_POST['POST_TYPE'];
// $writer = $_POST['WRITER'];
// $category = $_POST['CATEGORY'];
// $title = $_POST['TITLE'];
// $content = $_POST['CONTENT'];
// $filepath = $_POST['FILEPATH'];
$customer_type = $_POST['CUSTOMER_TYPE'];
$customer_type2 = implode(",",$customer_type);
// $URL = './index.php';

require_once("common/validation.php");

//function gen_uuid() {//파일이름 중복방지를 위해 생성
//  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
//     mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
//     mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
//     mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
//   );
//}

if(isset($_POST['IDX'])){
 $idx = mysqli_real_escape_string($conn, $_POST['IDX']); 
}
$filtered = array(
  'POST_TYPE'=>mysqli_real_escape_string($conn,$_POST['POST_TYPE']),
  'WRITER'=>mysqli_real_escape_string($conn,$_POST['WRITER']),
  'CATEGORY'=>mysqli_real_escape_string($conn,$_POST['CATEGORY']),
  'CUSTOMER_TYPE'=>mysqli_real_escape_string($conn,$customer_type2),
  'TITLE'=>mysqli_real_escape_string($conn,$_POST['TITLE']),
  'CONTENT'=>mysqli_real_escape_string($conn,$_POST['CONTENT']),
  'FILE_CHECK'=>mysqli_real_escape_string($conn,$_POST['FILE_CHECK'])

);

$query = "update mro_board
set 
POST_TYPE = '{$filtered['POST_TYPE']}',
WRITER = '{$filtered['WRITER']}',
CATEGORY = '{$filtered['CATEGORY']}',
CUSTOMER_TYPE = '{$filtered['CUSTOMER_TYPE']}',
TITLE = '{$filtered['TITLE']}',
CONTENT = '{$filtered['CONTENT']}',
FILE_CHECK = '{$filtered['FILE_CHECK']}'
where IDX = {$idx}";


//echo($query);

$result = $conn->query($query);

//echo($result);
//echo "%%%%%%%%";
//var_dump($_FILES);
//echo "%%%%%%%%";

if($_FILES['file']['size'] > 0) {
 
  $file_id = gen_uuid(); 

  $tmpfile =  $_FILES['file']['tmp_name'];
  $file_name = $_FILES['file']['name'];
  //$filename = iconv("UTF-8", "EUC-KR",$_FILES['file']['name']);//한글 깨짐 방지
  //echo "ddd";
  //print_r($filename);
  
  $save_filename = $_SERVER['DOCUMENT_ROOT'] . "/mro_board/upload/{$file_id}-".$file_name;
  
  
  $filtered_attach = array(
    'FILE_ID'=>mysqli_real_escape_string($conn,$file_id),
    'IDX'=>mysqli_real_escape_string($conn,$idx),
    'FILE_NAME'=>mysqli_real_escape_string($conn,$file_name),
    'FILE_PATH'=>mysqli_real_escape_string($conn,$save_filename),
    'FILE_TYPE'=>mysqli_real_escape_string($conn,$_FILES['file']['type']),
    'FILE_SIZE'=>mysqli_real_escape_string($conn,$_FILES['file']['size'])
  );

  if($_POST['pre_file_name'] != "") {// 이전 파일이 있고 파일을 새로 등록할경우 update
 
  $attach_query = "update mro_attach
  set
  FILE_ID = '{$filtered_attach['FILE_ID']}',
  IDX = '{$filtered_attach['IDX']}',
  FILE_NAME = '{$filtered_attach['FILE_NAME']}',
  FILE_PATH = '{$filtered_attach['FILE_PATH']}',
  FILE_TYPE = '{$filtered_attach['FILE_TYPE']}',
  FILE_SIZE = '{$filtered_attach['FILE_SIZE']}'
  where IDX = {$idx}";
  
  move_uploaded_file($tmpfile,$save_filename);

  unlink("{$_SERVER['DOCUMENT_ROOT']}/mro_board/upload/{$_POST['pre_file_id']}-{$_POST['pre_file_name']}");
  //print_r($attach_query);

  $attach_result = $conn->query($attach_query);
  }
  else if($_POST['pre_file_name'] == "") { //이전 파일이 없고 파일이 새로 들어온 경우는 create
    $attach_query = "insert into mro_attach(FILE_ID,IDX,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_SIZE)
  values('{$filtered_attach['FILE_ID']}',
  '{$filtered_attach['IDX']}',
  '{$filtered_attach['FILE_NAME']}',
  '{$filtered_attach['FILE_PATH']}',
  '{$filtered_attach['FILE_TYPE']}',
  '{$filtered_attach['FILE_SIZE']}')";

  move_uploaded_file($tmpfile,$save_filename);

  //print_r($attach_query);

  $attach_result = $conn->query($attach_query);
  }


  if ($attach_result) {
    ?>                  
    <script>
      alert("파일첨부 되었습니다.");
      
      </script>
    <?php
      
  }
  else{
    echo "atta3chFAIL";
  }
} else {
  if($_POST['FILE_CHECK'] == 'N' && $_POST['pre_file_name'] != "") {// 이전 파일이름과 파일체크가 N으로 바뀐경우
          //db 파일먼저 삭제
      $delete_query = "delete from mro_attach where IDX = {$idx}";

      $delete_result = $conn->query($delete_query);

      //print_r($delete_query);

      $update_query = "update mro_board set FILE_CHECK = 'N' where IDX = {$idx}";
      
      $update_result = $conn->query($update_query);

      unlink("{$_SERVER['DOCUMENT_ROOT']}/mro_board/upload/{$_POST['pre_file_id']}-{$_POST['pre_file_name']}");
  }
}

if ($result) {
  ?>                  
  <script>
    alert("글이 수정되었습니다.");
    location.href = "<?=$URL?>";
    </script>
  <?php
  //$URL = $_SERVER['HTTP_HOST']."/mro_board/index.php";
  //header("HTTP/1.1 307 Temporary move");
  //header("Location: http://$URL");
}
else{
  echo "upd3ateFAIL";
}
  ?>
