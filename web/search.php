<?php
$film = urlencode($_POST['film']);
$res = file_get_contents("http://www.vodkaster.com/api/search?type=film&q=".$film);

if(isset($_GET['callback'])){
  $myjscallback = $_GET['callback'];
  echo $myjscallback."(".$res.")";
}else{
  echo $res;  
}


