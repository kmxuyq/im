<?php 
if(!empty($_GET["return_url"])){
	header("Location:".base64_decode($_GET["return_url"])."&code={$_GET["code"]}");
}
?>