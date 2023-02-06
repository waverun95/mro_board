<?php
require_once("common/variable.php");
require_once("common/function.php");
//$db = new dbClass();
echo $_SERVER['REQUEST_URI'];
echo 'zzz';
echo $_SERVER['PHP_SELF'];
//$query = "select * from mro_board where IDX = {$_GET['IDX']}";
$hit_query = "update mro_board set HIT = HIT +1 where IDX = {$_GET['IDX']}";
$db -> executeQuery($hit_query);

//$hit_result = mysqli_query($conn, $hit_query);
//$URL = $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];

//$page = $_GET['page'];

//$result = mysqli_query($conn, $query);
//$row = $db -> executeResult($query);
//$row = mysqli_fetch_array($result);
//집에서 다시 테스트 result값이 안들어감


//$idx = $row['IDX'];
//$post_type = $row['POST_TYPE'];
//$writer = $row['WRITER'];
//$category = $row['CATEGORY'];
//$customer_type = $row['CUSTOMER_TYPE'];
//$title = $row['TITLE'];
//$content = $row['CONTENT'];
//$file_check = $row['FILE_CHECK'];
//
//$serch_title = isset($_GET['serch_title']) ? $_GET['serch_title'] : "";
//$serch_writer = isset($_GET['serch_writer']) ? $_GET['serch_writer'] : "";
//$serch_fdate = isset($_GET['serch_fdate']) ? $_GET['serch_fdate'] : "";
//$serch_ldate = isset($_GET['serch_ldate']) ? $_GET['serch_ldate'] : "";

//echo 'file_check <br>'.$file_check; 

//$file_name = "";
//$file_path = "";
//$file_id = "";
//if ($file_check == 'Y') {
//  //$file_query = "select * from mro_board,mro_attach where mro_board.IDX = mro_attach.IDX AND mro_board.IDX = {$idx};";
//  $file_query = "select * from mro_attach where IDX = {$idx}";
//  //$file_result = mysqli_query($conn, $file_query);
//  //$file_row = mysqli_fetch_array($file_result);
//
//  $file_row = $db ->executeResult($file_query);
//  $file_id = $file_row['FILE_ID'];
//  $file_name = $file_row['FILE_NAME'];
//  echo $file_name.'>>>>>>>>>>';
//  $local_filepath = "http://localhost/mro_board/upload/{$file_id}-" . $file_name;
//  //$file_path = $file_row['FILE_PATH']; 절대 경로는 403에러가 나서 톰캣 설정
//
//}

?>
<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/read.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="//code.jquery.com/jquery.min.js"></script>
  <title>조회</title>
</head>

<body>

  <div id="wrap">
    <!-- wrap -->
    <div id="header">
      <div id="header-top">
        <div class="container"></div>
      </div>
      <div id="header-nav">
        <div class="container">
          <h1>조회</h1>
          <hr>
        </div>
      </div>
    </div>
    <!-- header -->
    <div id="contents">
      <div id="content1">
        <div class="container">

          <table>
            <tr>
              <td class="col"><label for="">구분</label></td>
              <td class="col2">
                <?php post_type_change($post_type) ?>
              </td>

            </tr>
            <tr>
              <td class="col"><label for="">작성자</label></td>
              <td class="col2"><?= $writer ?></td>
            </tr>
            <tr>
              <td class="col"><label for="">분류</label></td>
              <td class="col2">
                <?php category_change($category) ?>
              </td>
            </tr>
            <tr>
              <td class="col"><label for="">고객유형</label></td>
              <td class="col2">
                <?= $customer_type ?>
              </td>
            </tr>
            <tr>
              <td class="col"><label for="">제목</label></td>
              <td class="col2"><?= $title ?></td>
            </tr>
            <tr>
              <td class="col"><label for="">내용</label></td>
              <td class="col2 content">
                <?= $content ?>
              </td>
            </tr>
            <tr>
              <td class="col"><label for="">첨부파일</label> </td>
              <!-- <td class="col2"><span><?= $file_name ?></span> <a id="download_btn" href="<?= $local_filepath ?>" download="<?= $file_name ?>">다운로드</a></td> -->
              <td class="col2"><span><?= $file_name ?></span> <?php echo $file_name !='' ? '<a id="download_btn" href="'.$local_filepath.'"download="'.$file_name.'">다운로드</a>':''?></td>
              <!-- 다운로드 파일이 있을경우만 다운로드 버튼 활성화 -->
            </tr>
          </table>
          <div id="f_btn">
            <button type="button" id="update" class="fr_btn">수정</button>
            <button type="button" id="delete" class="fr_btn">삭제</button>
            <button type="button" id="list" class="fr_btn">목록</button>
          </div>
          <form id="readform" action="write.php" method="post">
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

    var idx = "<?php echo $idx; ?>";
    var form = $("#readform");
    var page = <?php echo $page ?>;
    var file_id = "<?php echo $file_id?>";
    var file_name = "<?php echo $file_name?>";


    var serch_title = "<?= $serch_title ?>";
    var serch_writer = "<?= $serch_writer ?>";
    var serch_fdate = "<?= $serch_fdate ?>";
    var serch_ldate = "<?= $serch_ldate ?>";
    $(document).on('click', '#update', function(e) {
      $('#readform').attr("action", "write.php");
      $('#readform').attr("method", "get");
      form.append("<input type='hidden' name=IDX value=" + idx + ">");
      form.append("<input type='hidden' name=page value=" + page + ">");
      form.append("<input type='hidden' name=serch_title value=" + serch_title + ">");
      form.append("<input type='hidden' name=serch_writer value=" + serch_writer + ">");
      form.append("<input type='hidden' name=serch_fdate value=" + serch_fdate + ">");
      form.append("<input type='hidden' name=serch_ldate value=" + serch_ldate + ">");
      form.submit();
    })

    $(document).on('click', '#delete', function(e) {
      if (confirm("삭제하실건가여?")) {
        $('#readform').attr("action", "delete_process.php");
        form.append("<input type='hidden' name=IDX value=" + idx + ">");
        form.append("<input type='hidden' name=pre_file_name value='"+ file_name+"'>");
        form.append("<input type='hidden' name=pre_file_id value="+ file_id+">");
        form.submit();
      }
    })

    $(document).on('click', '#list', function(e) {
      $('#readform').attr('action', 'index.php');
      $('#readform').attr("method", "get");
      form.append("<input type='hidden' name=page value=" + page + ">");
      form.submit();
    })
  })
</script>

</html>