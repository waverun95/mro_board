<?php
require_once("common_variable.php");

$post_type = ""; //구분(문의사항,유지보수)
$writer = ""; //작성자
$category = ""; // 분류(홈페이지,네트워크,서버)
$customer_type = []; // 고객유형
$customer_type_arr = []; // 문자열 고객유형 배열형태 변경
$title = ""; //제목
$content = ""; //내용
$file_check = "N"; //파일 유무
$delete_btn = "";
$file_name = ""; //파일 이름
$file_id = ""; //중복방지 파일아이디
//write
$form_action = "write_process.php";
$board_title = "등록";
$file_path = "";


if($idx !=""){
    $board_title = "수정";
    $form_action = "update_process.php";
    $query = "select * from mro_board where IDX = {$idx}";    
    //$row = $db->executeResult($query);
    $row =  mysqli_fetch_array(mysqli_query($conn, $query));
    $post_type = $row['POST_TYPE'];
    $writer = $row['WRITER'];
    $category = $row['CATEGORY'];
    $title = $row['TITLE'];
    $content = $row['CONTENT'];
    $file_check = $row['FILE_CHECK'];
    $customer_type = $row['CUSTOMER_TYPE'];
    $customer_type_arr = explode(",", $customer_type);

    if($file_check == 'Y'){
        $delete_btn = '<button type = "button" id="delete_btn">지우기</button>';
        $file_query = "select * from mro_attach where IDX = {$idx}";
        //$file_row = $db->executeResult($file_query);
        $file_row = mysqli_fetch_array(mysqli_query($conn, $file_query));
        $file_name = $file_row['FILE_NAME'];
        $file_id = $file_row['FILE_ID'];
        $local_filepath = "http://localhost/mro_board/upload/{$file_id}-" . $file_name;
        //$file_path = $file_row['FILE_PATH']; 절대 경로는 403에러가 나서 톰캣 설정
    }
}


?>