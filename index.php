<?php
require_once("common/common_variable.php");
require_once("common/function.php");

$list_query = "select * from mro_board WHERE TITLE LIKE '%$serch_title%' AND WRITER LIKE '%$serch_writer%'".$date_query; //리스트 조회하는 쿼리

$total_query = "select count(*) as total from mro_board WHERE TITLE LIKE '%$serch_title%' AND WRITER LIKE '%$serch_writer%'".$date_query; //검색조건에 맞는 토탈 값 쿼리

$serch_paging = "&serch_title=$serch_title&serch_writer=$serch_writer&serch_fdate=$serch_fdate&serch_ldate=$serch_ldate"; //페이징 유지


//검색 쿼리 없을 경우
if ($serch_title == "" && $serch_writer == "" && $serch_fdate == "" && $serch_ldate == "") {
    $serch_paging = "";
}


$total = mysqli_fetch_array(mysqli_query($conn, $total_query));

settype($total['total'], 'integer');

require_once ("common/paging.php");

// $list_num 한 페이지 당 데이터 개수
$list_num = 10;

// $page_num 한 블럭 당 페이지 수
$page_num = 3;

$paging = new paging($page, $list_num, $page_num, $total['total']);

// $list_query 쿼리 작성 - limit 몇번부터, 몇개 
$list_query .= "order by IDX desc limit $paging->start, $paging->list_num";

$result = mysqli_query($conn, $list_query);

?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery.min.js"></script>
    <title>목록</title>
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
                    <a href="/mro_board/index.php"><h1>목록</h1></a>
                    <hr>
                </div>
            </div>
        </div>
        <!-- header -->
        <div id="contents">
            <div id="content1">
                <div class="container">

                    <table class="serch_table">
                        <form action="" method="get" id="serch_form">
                            <tr>
                                <td>제목</td>
                                <td><input type="text" name="serch_title" id="serch_title" value="<?php if (isset($_REQUEST['serch_title'])) echo $_REQUEST['serch_title'] ?>"></td>
                                <td>작성자</td>
                                <td><input type="text" name="serch_writer" id="serch_writer" value="<?php if (isset($_REQUEST['serch_writer'])) echo $_REQUEST['serch_writer'] ?>"></td>
                                <td>작성일</td>
                                <td><input type="date" name="serch_fdate" id="fdate" value="<?php if (isset($_REQUEST['serch_fdate'])) echo $_REQUEST['serch_fdate'] ?>">~</td>
                                <td><input type="date" name="serch_ldate" id="ldate" value="<?php if (isset($_REQUEST['serch_ldate'])) echo $_REQUEST['serch_ldate'] ?>"></td>
                                <td><button type="submit" id="serch_btn">검색</button></td>
                            </tr>
                        </form>
                    </table>


                <div class="page_info">Total : <?= $paging->total ?> &nbsp;&nbsp;&nbsp; Page : <?= $paging->page . "/" . $paging->total_page ?></div>

                    <table class="list_table">
                        <tr>
                            <td class="list_td">번호</td>
                            <td class="list_td">구분</td>
                            <td class="list_td">제목</td>
                            <td class="list_td">첨부</td>
                            <td class="list_td">작성일</td>
                            <td class="list_td">작성자</td>
                            <td class="list_td">조회수</td>
                        </tr>
                        <?php
                        $i = 0;
                        if ($paging->total == 0) {
                        ?>
                            <td colspan="7" style="text-align: center;">검색된 결과가 없습니다</td>

                        <?php                          
                        }
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td class="list_td2"><?= $paging->total - (($paging->page - 1) * $paging->list_num) - $i ?></td>
                                <td class="list_td2"><?= post_type_change($row['POST_TYPE']) ?></td>
                                <td class="list_td2"><a href="read.php?IDX=<?= $row['IDX'] . '&page=' . $paging->page . $serch_paging ?>"><?= strlen($row['TITLE']) > 30 ? mb_substr($row['TITLE'], 0, 30) . "..." : $row['TITLE'] ?></a></td>
                                <td class="list_td2"><?= file_check($row['FILE_CHECK']) ?></td>
                                <td class="list_td2"><?= date_format(date_create($row['REGDATE']), 'Y-m-d'); ?></td>
                                <td class="list_td2"><?= strlen($row['WRITER']) > 12 ? mb_substr($row['WRITER'], 0, 6) . "..." : $row['WRITER'] ?></td>
                                <td class="list_td2"><?= $row['HIT'] ?></td>

                            </tr>
                        <?php
                            $i++;
                        }
                        ?></table>
                        <p class="pager">
                            <?php $paging ->paging_ui($paging->page, $serch_paging, $paging->total_page, $paging->s_pageNum, $paging->e_pageNum); ?>
                        </p>
                    <button type="button" id="write_btn">등록</button>
                    <?php
                    if ($serch_title != "" || $serch_writer != "" || ($serch_fdate != "" && $serch_ldate != "")) {
                        echo "<button type='button' id='reset_btn'>검색 초기화</button>";
                    }
                    ?>

                </div>
            </div>

        </div>
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

    <script>
        $(document).on('ready', function(e) {

            var date = new Date();
            var year = date.getFullYear();
            var month = ("0" + (1 + date.getMonth())).slice(-2);
            var day = ("0" + date.getDate()).slice(-2);
            var today = year + "-" + month + "-" + day;

            var serch_form = $('#serch_form');
            $('#write_btn').on('click', function(e) {
                location.href = "http://localhost/mro_board/write.php";
            });
            $('#reset_btn').on('click', function(e) {
                location.href = "http://localhost/mro_board/index.php";
            })

            $('#serch_btn').on('click', function(e) {

                if ($('#fdate').val() != "") {
                    if ($('#ldate').val() == "") {
                        $('#ldate').val(today);
                    }
                }
                if ($('#ldate').val() != "") {
                    if ($('#fdate').val() == "") {
                        today = '1970-01-01';
                        $('#fdate').val(today);
                    }
                }

                if ($('#fdate').val() != "" && $('#ldate').val() != "") {
                    if (new Date($('#fdate').val()) > new Date($('#ldate').val())) {
                        alert("날짜를 올바르게 입력해주세요");
                        return false;
                    }
                }

                if ($('#serch_title').val() == "" && $('#serch_writer').val() == "" && $('#fdate').val() == "" && $('#ldate').val() == ""){
                    alert("검색 조건을 입력해주세요");
                    return false;
                }
            });
        })
    </script>
</body>

</html>