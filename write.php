<?php
require_once("common/variable.php");
require_once("common/function.php");

//$form_action = "write_process.php";
//
//
//$board_title = "등록";
//$post_type = ""; //구분(문의사항,유지보수)
//$writer = ""; //작성자
//$category = ""; // 분류(홈페이지,네트워크,서버)
//$customer_type = []; // 고객유형
//$customer_type_arr = []; // 고객유형
//$title = ""; //제목
//$content = ""; //내용
//$file_check = "N"; //파일 유무
//$page = ""; //현재 페이지
//$idx = ""; //업데이트에 필요
//$delete_btn = "";
//$file_name = ""; //파일 이름
//$file_id = ""; //중복방지 파일아이디
//
//$serch_title = isset($_GET['serch_title']) ? $_GET['serch_title'] : "";
//$serch_writer = isset($_GET['serch_writer']) ? $_GET['serch_writer'] : "";
//$serch_fdate = isset($_GET['serch_fdate']) ? $_GET['serch_fdate'] : "";
//$serch_ldate = isset($_GET['serch_ldate']) ? $_GET['serch_ldate'] : "";
//
//
//if (isset($_GET['IDX'])) {
//  $board_title = "수정";
//  $form_action = "update_process.php";
//  $page = $_GET['page'];
//
//  $query = "select * from mro_board where IDX = {$_GET['IDX']}";
//
//  $result = mysqli_query($conn, $query);
//  $row = mysqli_fetch_array($result);
//  $idx = $row['IDX'];
//  $post_type = $row['POST_TYPE'];
//  $writer = $row['WRITER'];
//  $category = $row['CATEGORY'];
//  $title = $row['TITLE'];
//  $content = $row['CONTENT'];
//  $file_check = $row['FILE_CHECK'];
//  $customer_type = $row['CUSTOMER_TYPE'];
//  $customer_type_arr = explode(",", $customer_type);
//
//  if ($file_check == 'Y') {
//    $delete_btn = '<button type = "button" id="delete_btn">지우기</button>';
//    $file_query = "select FILE_NAME,FILE_ID from mro_attach where IDX = {$_GET['IDX']}";
//    print_r($file_query);
//    $file_result = mysqli_query($conn, $file_query);
//    $file_row = mysqli_fetch_array($file_result);
//    var_dump($file_row);
//    $file_name = $file_row['FILE_NAME'];
//    echo $file_name.'>>>>>>>>';
//    $file_id = $file_row['FILE_ID'];
//  }
//}

?>

<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- 제이쿼리 추가 -->
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/write.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="//code.jquery.com/jquery.min.js"></script>
  </script>

  <title>Document</title>
</head>
<style> 

  
</style>

<body>

  <div id="wrap">
    <!-- wrap -->
    <div id="header">
      <div id="header-top">
        <div class="container"></div>
      </div>
      <div id="header-nav">
        <div class="container">
          <h1><?= $board_title ?></h1>
          <hr>
        </div>
      </div>
    </div>
    <!-- header -->
    <div id="contents">
      <div id="content1">
        <div class="container">

          <form action="<?= $form_action ?>" method="post" id="write_form" enctype="multipart/form-data">
            <table>
              <tr>
                <td class="col"><label for="">구분(분류)</label></td>
                <td class="col2">
                  <select name="POST_TYPE">
                    <option value="">선택해주세요</option>
                    <option value="1" <?php post_type_check("1", $post_type) ?>>유지보수</option>
                    <option value="2" <?php post_type_check("2", $post_type) ?>>문의사항</option>
                  </select> (유지보수,문의사항)
                </td>

              </tr>
              <tr>
                <td class="col"><label for="">작성자</label></td>
                <td class="col2"><input type="text" name="WRITER" value="<?= $writer ?>"></td>
              </tr>
              <tr>
                <td class="col"><label for="">분류</label></td>
                <td class="col2">
                  <input type="radio" name="CATEGORY" value="1" <?php category_check("1", $category) ?>>홈페이지
                  <input type="radio" name="CATEGORY" value="2" <?php category_check("2", $category) ?>>네트워크
                  <input type="radio" name="CATEGORY" value="3" <?php category_check("3", $category) ?>>서버
                </td>
              </tr>
              <tr>
                <td class="col"><label for="">고객유형</label></td>
                <td class="col2">
                  <input type="checkbox" name="CUSTOMER_TYPE[]" value="호스팅" <?php customer_type_check("호스팅", $customer_type_arr) ?>>호스팅
                  <input type="checkbox" name="CUSTOMER_TYPE[]" value="유지보수" <?php customer_type_check("유지보수", $customer_type_arr) ?>>유지보수
                  <input type="checkbox" name="CUSTOMER_TYPE[]" value="서버 임대" <?php customer_type_check("서버 임대", $customer_type_arr) ?>>서버 임대
                  <input type="checkbox" name="CUSTOMER_TYPE[]" value="기타" id="etc" <?php customer_type_check("기타", $customer_type_arr) ?>>기타
                </td>
              </tr>
              <tr>
                <td class="col"><label for="">제목</label></td>
                <td class="col2"><input type="text" name="TITLE" value="<?= $title ?>"></td>
              </tr>
              <tr>
                <td class="col"><label for="">내용</label></td>
                <td class="col2">
                  <textarea name="CONTENT" cols="50" rows="10"><?= $content ?></textarea>
                </td>
              </tr>
              <tr>
                <td class="col"><label for="">첨부파일</label></td>
                <td class="col2"> <input type="text" readonly id="show_file"> <input type="file" name="file" id="upload_file"><label id="show_btn" for="upload_file">찾아보기</label><span id="show_file2"><?= $file_name ?></span><?= $delete_btn ?></td>
              </tr>
              <input type="hidden" name="IDX" value="<?= $idx ?>">
              <div><input type="hidden" name="FILE_CHECK" value="<?= $file_check ?>"></div>
              <div><input type="hidden" name="pre_file_name" value="<?= $file_name ?>"></div>
              <div><input type="hidden" name="pre_file_id" value="<?= $file_id ?>"></div>
            </table>
            <div id="f_btn">
              <input type="submit" value="저장" id="submit">
              <button type="button" id="back_btn">취소</button>
            </div>
          </form>
          <!-- contents -->
          <div id="footer">
            <div id="footer-nav">
              <div class="container"></div>
            </div>
            <div id="footer-info">
              <div class="container"></div>
            </div>
          </div>
          <!-- footer -->
        </div>




</body>

<script>
  $(document).on('ready', function(e) {

    var btn = $("#back_btn");
    var page = "<?= $page ?>";
    var serch_title = "<?= $serch_title ?>";
    var serch_writer = "<?= $serch_writer ?>";
    var serch_fdate = "<?= $serch_fdate ?>";
    var serch_ldate = "<?= $serch_ldate ?>";

    console.log(btn);
    if (page != "") {
      $('#write_form').append("<input type='hidden' name='page' value=" + page + ">");
      $('#write_form').append("<input type='hidden' name='serch_title' value=" + serch_title + ">");
      $('#write_form').append("<input type='hidden' name='serch_writer' value=" + serch_writer + ">");
      $('#write_form').append("<input type='hidden' name='serch_fdate' value=" + serch_fdate + ">");
      $('#write_form').append("<input type='hidden' name='serch_ldate' value=" + serch_ldate + ">");
    }

    $(btn).on('click', function(e) {
      history.back(1);
    });

    $("input[type='submit']").on('click', function(e) {
      if ($('select[name="POST_TYPE"]').val() == '') {
        alert('구분을 선택해주세요');
        $('select[name="POST_TYPE"]').focus();
        return false;
      }
      if ($('input[name="WRITER"]').val() == '') {
        alert('작성자를 입력해주세요');
        $('input[name="WRITER"]').focus();
        return false;
      }
      if ($('input[name="CATEGORY"]:checked').val() == undefined) {
        alert('분류를 선택해주세요');
        $('input[name="CATEGORY"]:checked').focus();
        return false;
      }
      if ($('input[name="CUSTOMER_TYPE[]"]:checked').val() == undefined) {
        $('#etc').prop('checked', true);
      }
      if ($('input[name="TITLE"]').val() == '') {
        alert("제목을 입력해주세요");
        $('input[name="TITLE"]').focus();
        return false;
      }
      if ($('textarea[name="CONTENT"]').val() == '') {
        alert("내용을 입력해주세요");
        $('textarea[name="CONTENT"]').focus();
        return false;
      }

      if ($("input[type='file']").val() != '') {

        $("input[name='FILE_CHECK']").val('Y');
        //var formData = new FormData();

        //formData.append("file", jQuery("#upload_file")[0].files[0]);


        // var inputFile = $("input[name='FILEPATH']");

        // var files = inputFile[0].files;

        // for (var i = 0; i < files.length; i++) {

        //   if (!checkExtension(files[i].name, files[i].size)) {
        //     return false;
        //   }
        //   formData.append("uploadFile", files[i]);

        // }
        // $.ajax({
        //   url: 'ajax_file_upload_test.php',
        //   type:'POST',
        //   dataType: 'json',
        //   enctype: 'multipart/form-data',
        //   processData: false,
        //   contentType: false,
        //   data: formData,
        //   async : false,
        //   success: function(res){
        //     console.log(res);
        //     console.log("성공");
        //     console.log(`file : ${res['file']}`);
        //     console.log(`파일이름 : ${res['file']['name']}`);
        //     console.log(`파일 사이즈 : ${res['file']['size']}`);
        //     console.log(`파일형태 : ${res['file']['type']}`);
        //     console.log(`파일임시 : ${res['0']['file_tmp_name']}`);
        //     console.log(`파일임시 : ${res['0']['save_filename']}`);
        //     $('#test').html(res['file']['name']);

        //   },
        //   error: function(request,status,error){
        //     console.log("실패");
        //     alert(error);
        //     console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        //   }
        // });

        // $.ajax({
        //   url: 'unlink.php',
        //   type: 'POST',
        //   dataType: 'json',
        //   enctype: 'multipart/form-data',
        //   processData: false,
        //   contentType: false,
        //   data: formData,
        //   async: false,
        //   success: function(request,status,error) {
        //     console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);

        //   },
        //   error: function(request,status,error){
        //     console.log("실패");
        //     alert(error);
        //     console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        //   }
        // }); //$.ajax
      }
    });
    $('#delete_btn').on('click', function(e) {
      if (confirm("정말로 삭제하실건가요?")) {
        $('input[name="FILE_CHECK"]').val('N');
        $('#show_file').val("");
        $('#show_file2').text("");
      }
    });
    //var regex = new RegExp("(.*?)\.(exe|sh|zip|alz)$");
    //var maxSize = 5242880; //5MB

    function checkExtension(fileName, fileSize) {

      return true;
    }
    $("input[type='file']").change(function(e) {
      $('#show_file').val($('input[name="file"]').val());
      $('#show_file2').text($('input[name="file"]').val());

    });
  });
</script>

</html>