<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    $conn = mysqli_connect("localhost","root","qwe789","homework");
    
    $query = "select * from mro_board where idx = 2";
    
    $result = $conn ->query($query);
    $row = mysqli_fetch_array($result);
    
    $customer_type = explode(",",$row['CUSTOMER_TYPE']); 
    $cate = $row['CATEGORY'];
    function check( $val , $array )
{  
if (in_array( $val , $array ))  
{
echo "checked" ;  
}
}
if($row['POST_TYPE'] == '2') {
  echo 'true';
}else {
  echo 'false';
}
function check2($val, $val2){
  if($val == $val2) echo 'checked';
}
function check3($val, $val2){
  if($val == $val2) echo 'selected';
}

    
  ?>
   <div>
      <label for="">구분(분류dd)</label>
      <select name="POST_TYPE">
        <option value="" >선택해주세요</option>
        <option value="1" <?php check3("1",$cate)?>>유지보수</option>
        <option value="2" <?php check3("2",$cate)?>>문의사항</option>
      </select>
    </div>

     <div>
      <label for="">분류</label>
      <input type="radio" name="CATEGORY" value="1" <?php check2("1",$cate) ?>>홈페이지
      <input type="radio" name="CATEGORY" value="2" <?php check2("2",$cate) ?>>네트워크
      <input type="radio" name="CATEGORY" value="3" <?php check2("3",$cate) ?>>서버
    </div>

  <label>
<input type = "checkbox" name = "key_name[]"
value = "keyman1" <?php check( "ghtmxld" ,
$customer_type ); ?> > 키 관리자1
</label>
<label>
<input type = "checkbox" name = "key_name[]"
value = "keyman2" <?php check( "66" ,
$customer_type ); ?> > 키 관리자333333
</label>
<label>
<input type = "checkbox" name = "key_name[]"
value = "keyman2" <?php check( "유지보수" ,
$customer_type ); ?> > 키 관리자3
</label>
<label>
<input type = "checkbox" name = "key_name[]"
value = "keyman2" <?php check( "서버임대" ,
$customer_type ); ?> > 키 관리자3
</label><label>
<input type = "checkbox" name = "key_name[]"
value = "keyman2" <?php check( "기타" ,
$customer_type ); ?> > 키 관리자3
</label>
<label>
<input type = "checkbox" name = "key_name[]"
value = "keyman2" <?php check( "0" ,
$customer_type ); ?> > 키 관리자3
</label>
</body>
</html>