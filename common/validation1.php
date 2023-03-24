<?php 
if($_POST['CONTENT'] == ""){
    echo '<script>  
    alert("내용이 없습니다.")
    history.back(1);     
    </script>';
    return;  
  }
  if($_POST['TITLE'] == ""){
    echo '<script>  
    alert("제목이 없습니다.")
    history.back(1);     
    </script>';
    return;    
  }
  if($_POST['WRITER'] == ""){
    echo '<script>  
    alert("작성자가 없습니다.")
    history.back(1);     
    </script>';
    return;   
  }
  
  
  if(!($_POST['POST_TYPE'] == '1' || $_POST['POST_TYPE'] == '2')){

      echo '<script>  
      alert("구분이 잘못되었습니다.")
      history.back(1);     
      </script>';
      return;   
  }
  
  if(!($_POST['CATEGORY'] == '1' || $_POST['CATEGORY'] == '2'|| $_POST['CATEGORY'] == '3')){
      echo '<script>  
      alert("분류가 잘못되었습니다.")
      history.back(1);     
      </script>';
      return;  
  }
  
  if (preg_match("/^(호스팅|서버 임대|유지보수|기타)(\,)?(호스팅|서버 임대|유지보수|기타)?(\,)?(호스팅|서버 임대|유지보수|기타)?(\,)?(호스팅|서버 임대|유지보수|기타)?(\,)?/", $customer_type2,$matches)) {  
  } else {//고객유형에 다른 값 들어왔을 때
      echo '<script>  
      alert("고객유형값이 다릅니다.")
      history.back(1);     
      </script>';  
    return;
  }
  
?>