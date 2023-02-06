<?php
require_once("dbcon.php");

$serch_title = isset($_GET['serch_title']) ? $_GET['serch_title'] : "";
$serch_writer = isset($_REQUEST['serch_writer']) ? $_REQUEST['serch_writer'] : "";
$serch_fdate = isset($_REQUEST['serch_fdate']) ? $_REQUEST['serch_fdate'] : "";
$serch_ldate = isset($_REQUEST['serch_ldate']) ? $_REQUEST['serch_ldate'] : "";
//$_REQUEST 검색만 했을 경우에는 get방식으로 페이지 유지하고 수정을 했을경우에 post로 들어와서 request로 사용


if (preg_match("/^([0-9]{2,4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])/", $serch_fdate)) {
    echo "ddddd";
    print_r(preg_match("/^([0-9]{2,4})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])/", $serch_fdate));
} else {
    echo "???????";
}

$total_query_common = "select count(*) as total from mro_board";



if ($serch_fdate != "" && $serch_ldate != "") { // 날짜가 있는경우

    $list_query = "select * from mro_board  
    WHERE TITLE LIKE '%$serch_title%' AND WRITER LIKE '%$serch_writer%' AND DATE(REGDATE) 
    BETWEEN '$serch_fdate' AND '$serch_ldate'";

    $total_query = $total_query_common . " WHERE TITLE LIKE '%$serch_title%' AND WRITER LIKE '%$serch_writer%' AND DATE(REGDATE) 
    BETWEEN '$serch_fdate' AND '$serch_ldate'";

    $serch_paging = "&serch_title=$serch_title&serch_writer=$serch_writer&serch_fdate=$serch_fdate&serch_ldate=$serch_ldate";
} else {
    $list_query = "select * from mro_board  
    WHERE TITLE LIKE '%$serch_title%' AND WRITER LIKE '%$serch_writer%'";
    $total_query = $total_query_common . " WHERE TITLE LIKE '%$serch_title%' AND WRITER LIKE '%$serch_writer%'";
    $serch_paging = "&serch_title=$serch_title&serch_writer=$serch_writer";
}

if ($serch_title == "" && $serch_writer == "" && $serch_fdate == "" && $serch_ldate == "") {
    $serch_paging = "";
}


//@param $page 현재 페이지 
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

//settype($page,"integer");
//$total_query = "select count(*) as total from mro_board";

$total_result = mysqli_query($conn, $total_query);

$total = mysqli_fetch_array($total_result);

settype($total['total'], 'integer');

var_dump($total['total']);

require_once ("paging.php");


//@param $list_num 한 페이지 당 데이터 개수
$list_num = 10;


//@param $page_num 한 블럭 당 페이지 수
$page_num = 3;

$paging = new paging($page, $list_num, $page_num, $total['total']);

echo "dasdsaddsa";
var_dump($paging ->total_page);

//@param $total_page 전체 페이지 수 = 전체 데이터 / 페이지당 데이터 개수, ceil : 올림값, floor : 내림값, round : 반올림
$total_page = ceil($total['total'] / $list_num);

//@param $total_page 전체 블럭 수 = 전체 페이지 수 / 블럭 당 페이지 수
$total_block = ceil($total_page / $page_num);


//@param $now_block 현재 블럭 번호 = 현재 페이지 번호 / 블럭 당 페이지 수 
$now_block = ceil($page / $page_num);

//@param $now_block 현재 블럭 번호 블럭 당 시작 페이지 번호 = (해당 글의 블럭번호 - 1) * 블럭당 페이지 수 + 1
$s_pageNum = ($now_block - 1) * $page_num + 1;
// 데이터가 0개인 경우
if ($s_pageNum <= 0) {
    $s_pageNum = 1;
};

//@param $e_pageNum 블럭 당 마지막 페이지 번호 = 현재 블럭 번호 * 블럭 당 페이지 수
$e_pageNum = $now_block * $page_num;
// 마지막 번호가 전체 페이지 수를 넘지 않도록
if ($e_pageNum > $total_page) {
    $e_pageNum = $total_page;
};

//@param $start 시작 번호 = (현재 페이지 번호 - 1) * 페이지 당 보여질 데이터 수 */
$start = ($page - 1) * $list_num;

//@param $list_query 쿼리 작성 - limit 몇번부터, 몇개 */
$list_query .= "order by IDX desc limit $start, $list_num";

//echo $list_query;
//paging : 쿼리 전송 
$result = mysqli_query($conn, $list_query);

function post_type_change($val)
{
    if ($val == "1") {
        echo "유지보수";
    } else {
        echo "문의사항";
    }
}
function file_check($val)
{
    if ($val == "Y") echo "<img src='../img/pic.png'>";
    else echo "";
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery.min.js"></script>
    <title>Document</title>
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
                    <h1>목록</h1>
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

<!--                    <div class="page_info">Total : --><?php //= $total['total'] ?><!-- &nbsp;&nbsp;&nbsp; Page : --><?php //= $page . "/" . $total_page ?><!--</div>-->
<!---->
<!--                    <table class="list_table">-->
<!--                        <tr>-->
<!--                            <td class="list_td">번호</td>-->
<!--                            <td class="list_td">구분</td>-->
<!--                            <td class="list_td">제목</td>-->
<!--                            <td class="list_td">첨부</td>-->
<!--                            <td class="list_td">작성일</td>-->
<!--                            <td class="list_td">작성자</td>-->
<!--                            <td class="list_td">조회수</td>-->
<!--                        </tr>-->
<!--                        --><?php
//                        $i = 0;
//                        if ($total['total'] == 0) {
//                        ?>
<!--                            <td colspan="7" style="text-align: center;">검색된 결과가 없습니다</td>-->
<!---->
<!--                        --><?php
//                            var_dump($serch_paging);
//                        }
//                        while ($row = mysqli_fetch_array($result)) {
//                        ?>
<!--                            <tr>-->
<!--                                <td class="list_td2">--><?php //= $total['total'] - (($page - 1) * $list_num) - $i ?><!--</td>-->
<!--                                <td class="list_td2">--><?php //= post_type_change($row['POST_TYPE']) ?><!--</td>-->
<!--                                <td class="list_td2"><a href="read.php?IDX=--><?php //= $row['IDX'] . '&page=' . $page . $serch_paging ?><!--">--><?php //= $row['TITLE'] ?><!--</a></td>-->
<!--                                <td class="list_td2">--><?php //= file_check($row['FILE_CHECK']) ?><!--</td>-->
<!--                                <td class="list_td2">--><?php //= date_format(date_create($row['REGDATE']), 'Y-m-d'); ?><!--</td>-->
<!--                                <td class="list_td2">--><?php //= $row['WRITER'] ?><!--</td>-->
<!--                                <td class="list_td2">--><?php //= $row['HIT'] ?><!--</td>-->
<!---->
<!--                            </tr>-->
<!--                        --><?php
//                            $i++;
//                        }?>
                    <!--                    <p class="pager">-->
                    <!---->
                    <!--                        --><?php
                    //                        if ($page <= 1) {
                    //                        } else {
                    //                            echo "<a class='paging' href='index.php?page=1{$serch_paging}'><<</a>";
                    //                        }
                    //                        if ($page <= 1) {
                    //                        } else {
                    //                            echo "<a class='paging' href='index.php?page=" . ($page - 1) . $serch_paging . "'><</a>";
                    //                        }
                    //                        for ($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++) {
                    //                            if ($page == $print_page) {
                    //                                echo "<b><a class='now_paging' href='index.php?page=" . $print_page . $serch_paging . "'>{$print_page}</a></b>";
                    //                            } else {
                    //                                echo "<a class='paging' href='index.php?page=" . $print_page . $serch_paging . "'>{$print_page}</a>";
                    //                            }
                    //                        }
                    //                        if ($page >= $total_page) {
                    //                        } else {
                    //                            echo "<a class='paging' href='index.php?page=" . ($page + 1) . $serch_paging . "'>></a>";
                    //                        }
                    //                        if ($page >= $total_page) {
                    //                        } else {
                    //                            echo "<a class='paging' href='index.php?page=" . ($total_page) . $serch_paging . "'>>></a>";
                    //                        }
                    //                        ?>
                    <!--                    </p>-->

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
                            var_dump($serch_paging);
                        }
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td class="list_td2"><?= $paging->total - (($paging->page - 1) * $paging->list_num) - $i ?></td>
                                <td class="list_td2"><?= post_type_change($row['POST_TYPE']) ?></td>
                                <td class="list_td2"><a href="../read.php?IDX=<?= $row['IDX'] . '&page=' . $paging->page . $serch_paging ?>"><?= $row['TITLE'] ?></a></td>
                                <td class="list_td2"><?= file_check($row['FILE_CHECK']) ?></td>
                                <td class="list_td2"><?= date_format(date_create($row['REGDATE']), 'Y-m-d'); ?></td>
                                <td class="list_td2"><?= $row['WRITER'] ?></td>
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
            var serch_form = $('#serch_form');
            console.log(serch_form);
            $('#write_btn').on('click', function(e) {
                location.href = "../write.php";
            });
            $('#reset_btn').on('click', function(e) {
                location.href = "../index.php";
            })


            $('#fdate').on('change', function(e) {
                console.log($('#fdate').val());
            })

            $('#ldate').on('change', function(e) {
                console.log($('#ldate').val());
            })
            $('#serch_btn').on('click', function(e) {

                if ($('#fdate').val() != "") {
                    if ($('#ldate').val() == "") {
                        alert("날짜를 다시 입력해주세요");
                        return false;
                    }
                }
                if ($('#ldate').val() != "") {
                    if ($('#fdate').val() == "") {
                        alert("날짜를 다시 입력해주세요");
                        return false;
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