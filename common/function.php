<?php
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
    if ($val == "Y") echo "<img src='img/pic.png'>";
    else echo "";
}

function category_change($val)
{
    switch ($val) {
        case "1":
            echo "홈페이지";
            break;
        case "2":
            echo "네트워크";
            break;
        case "3":
            echo "서버";
            break;
    }
}
function customer_type_check($val, $array)
{
    if (in_array($val, $array)) {
        echo "checked";
    }
}
function post_type_check($val, $val2)
{
    if ($val == $val2) echo 'selected';
}
function category_check($val, $val2)
{
    if ($val == $val2) echo 'checked';
}
function gen_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
?>