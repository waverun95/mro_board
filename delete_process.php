<?php
require_once("common/dbcon.php");

$filtered_id = mysqli_real_escape_string($conn, $_POST['IDX']);

$delete_query = "delete from mro_board where IDX = {$filtered_id}";

//$result = $conn->query($delete_query);
$result = mysqli_query($conn, $delete_query);
if($_POST['pre_file_id'] != ''|| $_POST['pre_file_name'] != '') {
    unlink("{$_SERVER['DOCUMENT_ROOT']}/mro_board/upload/{$_POST['pre_file_id']}-{$_POST['pre_file_name']}");
}

if ($result) {
  echo '<script>
        alert("글이 삭제되었습니다.");
        location.href = "/mro_board/index.php";
        </script>';
}
else{
  echo "FAIL";
}
  ?>
