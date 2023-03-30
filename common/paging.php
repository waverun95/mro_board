<?php

class paging
{
    var $page = null;
    var $list_num = null;
    var $page_num = null;
    var $total_page = null;
    var $total_block = null;
    var $now_block = null;
    var $next_block = null;
    var $prev_block = null;
    var $s_pageNum = null;
    var $e_pageNum = null;
    var $start = null;
    var $total = null;

    function __construct($page,$list_num,$page_num,$total)
    {
        //$list_num 한 페이지 당 데이터 개수
        $this ->list_num = $list_num;
        //$page_num 한 블럭 당 페이지 수
        $this ->page_num = $page_num;
        $this ->page = $page;
        $this->total = $total;

        //$total_page 전체 페이지 수 = 전체 데이터 / 페이지당 데이터 개수, ceil : 올림값, floor : 내림값, round : 반올림
        $this->total_page = ceil($total / $this-> list_num);
        //$now_block 현재 블럭 번호 = 현재 페이지 번호 / 블럭 당 페이지 수
        $this->total_block = ceil($this->total_page / $this->page_num);
        $this->now_block = ceil($this->page / $this->page_num);

        $this->next_block = (((floor(($this->page -1) / $this->page_num)) + 1) * $this->page_num) + 1 ;

//        if(this->total_page < $this->next_block) {
//            $this->next_block = $this->total_page;
//        }
        $this->prev_block = ((floor((($this->page -1)/ $this->page_num)) - 1) * $this->page_num) + 1 ;
        if($this-> prev_block < 0){$this->prev_block = 1;}
        $this->s_pageNum = ( $this->now_block - 1) * $this->page_num + 1;

        //$now_block 현재 블럭 번호 블럭 당 시작 페이지 번호 = (해당 글의 블럭번호 - 1) * 블럭당 페이지 수 + 1
        if ($this->s_pageNum <= 0) {
            $this->s_pageNum = 1;
        }
        //$e_pageNum 블럭 당 마지막 페이지 번호 = 현재 블럭 번호 * 블럭 당 페이지 수
        $this->e_pageNum = $this->now_block * $this->page_num;


        if ($this->e_pageNum > $this->total_page) {
            $this->e_pageNum = $this->total_page;
        }
        //$start 시작 번호 = (현재 페이지 번호 - 1) * 페이지 당 보여질 데이터 수 */
        $this->start = ($this->page - 1) * $this->list_num;
    }
    function paging_ui($page, $serch_paging, $total_page, $s_pageNum, $e_pageNum)
    {
        if ($page <= 1) {
        } else {
            echo "<a class='paging' href='index.php?page=1{$serch_paging}'><<</a>";
        }
        if ($page <= 1) {
        } else {
           // echo "<a class='paging' href='index.php?page=" . ($page - 1) . $serch_paging . "'><</a>";
            echo "<a class='paging' href='index.php?page=" . $this->prev_block . $serch_paging . "'><</a>";
        }
        for ($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++) {
            if ($page == $print_page) {
                echo "<b><a class='now_paging' href='index.php?page=" . $print_page . $serch_paging . "'>{$print_page}</a></b>";
            } else {
                echo "<a class='paging' href='index.php?page=" . $print_page . $serch_paging . "'>{$print_page}</a>";
            }
        }
        if ($page >= $total_page) {
        } else {
            //echo "<a class='paging' href='index.php?page=" . ($page + 1) . $serch_paging . "'>></a>";
            echo "<a class='paging' href='index.php?page=" . $this-> next_block . $serch_paging . "'>></a>";
        }
        if ($page >= $total_page) {
        } else {
            echo "<a class='paging' href='index.php?page=" . ($total_page) . $serch_paging . "'>>></a>";
        }
    }
}
?>