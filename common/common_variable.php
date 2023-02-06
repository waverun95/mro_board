<?php
require_once ("dbcon.php");

$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : 1;

$serch_title = isset($_REQUEST['serch_title']) ? $_REQUEST['serch_title'] : "";
$serch_writer = isset($_REQUEST['serch_writer']) ? $_REQUEST['serch_writer'] : "";
$serch_fdate = isset($_REQUEST['serch_fdate']) ? $_REQUEST['serch_fdate'] : "";
$serch_ldate = isset($_REQUEST['serch_ldate']) ? $_REQUEST['serch_ldate'] : "";

$idx = isset($_REQUEST['IDX'])? $_REQUEST['IDX']: "";

?>