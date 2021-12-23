<?php session_start();
require_once('func.php');

$page='login';
$auth=0;

if(!empty($_SESSION['token'])) {
	$query1 = mysqli_query($link, "SELECT id,user FROM users") or die('<div class="error">Error1 - '.mysqli_error($link).'</div>');
	while($q1=mysqli_fetch_array($query1)) {
		if($_SESSION['token'] == hash('sha256', $q1['id'].':'.$q1['user'])) {
			$auth=$q1['id'];
			break;
		}
	}
	if($auth!=0) {
		$page='contacts';
	}
	else {
		session_destroy();
		exit('<meta http-equiv="Refresh" Content="0; url=/">');
	}
}

?>
<!DOCTYPE html>
<head>
<title>Test Project</title>
<meta http-equiv="Content-Type" content="text/html; charset:utf-8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
function gotopage() {
	var link='';
	if(!location.hash) link='#<?=$page;?>';
	else link=location.hash;
	window.location.replace(link);
	link=link.slice(1);
	$('#content').load(link, function(data,status,xhr){
		$('#preloader').fadeOut(500);
		if(status == "error") {
			var msg = "Произошла ошибка: ";
			$("#content").html('<div class="error">'+msg+ xhr.status+' '+xhr.statusText+'</div>');
			window.location.replace('/');
		}
	});
}
window.onload = function() {
	gotopage();
}
window.addEventListener('hashchange', function(e) {
	gotopage();
});
</script>
<style>

</style>
</head>
<body>
<div id="preloader"><img src="images/wait.gif"></div>
<div id="content"></div>
</body>
</html>