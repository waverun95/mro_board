<?php
require_once("common/variable.php");
require_once("common/function.php");

$hit_query = "update mro_board set HIT = HIT +1 where IDX = {$_GET['IDX']}";
mysqli_query($conn, $hit_query);

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
                <?php echo $img_check ? '<img src="upload/'.$file_id.'-'.$file_name.'" style="width: 400px;"><br>' : "" ?>
                <?= $content ?>
              </td>
            </tr>

            <?php if($file_name !=''){?>
            <tr>
              <td class="col"><label for="">첨부파일</label> </td>
              <td class="col2"><span><?= $file_name ?></span> <?php echo $file_name !='' ? '<a id="download_btn" href="'.$local_filepath.'"download="'.$file_name.'">다운로드</a>':''?></td>
              <!-- 다운로드 파일이 있을경우만 다운로드 버튼 활성화 -->
            </tr>
            <?php }?>

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
    });

    $(document).on('click', '#delete', function(e) {
      if (confirm("삭제하실건가여?")) {
        $('#readform').attr("action", "delete_process.php");
        form.append("<input type='hidden' name=IDX value=" + idx + ">");
        form.append("<input type='hidden' name=pre_file_name value='"+ file_name+"'>");
        form.append("<input type='hidden' name=pre_file_id value="+ file_id+">");
        form.submit();
      }
    });

    $(document).on('click', '#list', function(e) {
      $('#readform').attr('action', 'index.php');
      $('#readform').attr("method", "get");
      form.append("<input type='hidden' name=page value=" + page + ">");
      form.append("<input type='hidden' name=serch_title value=" + serch_title + ">");
      form.append("<input type='hidden' name=serch_writer value=" + serch_writer + ">");
      form.append("<input type='hidden' name=serch_fdate value=" + serch_fdate + ">");
      form.append("<input type='hidden' name=serch_ldate value=" + serch_ldate + ">");
      form.submit();
    })
  });
</script>

</html>