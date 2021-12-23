<?php session_start();
include('func.php');
if(!empty($_POST['log'])) {
	$err='';
	if(empty($_POST['user'])||empty($_POST['pass']))
		$err.='Эти поля обязательны для заполнения';
	if(empty($_POST['user'])) $err.='<script>$("#user").css("border","1px solid red");</script>';
	if(empty($_POST['pass'])) $err.='<script>$("#pass").css("border","1px solid red");</script>';
	if(!empty($err)) exit('<div class="error">'.$err.'</div>');
	
	$query1 = mysqli_query($link, "SELECT id,user,pass FROM users where user='".$_POST['user']."' or email='".$_POST['email']."' or mob='".$_POST['mob']."'") or die('<div class="error">Error1 - '.mysqli_error($link).'</div>');
	if(mysqli_num_rows($query1)>0) {
		$q1=mysqli_fetch_array($query1);
		if($q1['pass']!=$_POST['pass']) exit('<div class="error">Неверный пароль</div><script>$("#pass").css("border","1px solid red");</script>');
		$_SESSION['token'] = hash('sha256', $q1['id'].':'.$q1['user']);
		exit('<meta http-equiv="Refresh" Content="0; url=/">');
	}
	else  exit('<div class="error">Пользователя не существует</div><script>$("#user").css("border","1px solid red");</script>');
}
?>
<style>

</style>
<script>
function check_form() {
	$('#res').html('<center><img style="max-height:30px; width:auto;" src="images/wait.gif"></center>');
	$("#logform input").css("border","1px solid #aaa");
	$('#pass').val(sha256($('#pass').val()));
	var form_data = new FormData(document.getElementById('logform'));
	$.ajax({
        url: 'login',
        type: "POST",
		cache: false,
		contentType: false,
		processData: false,
        dataType: "text",
		data: form_data,
        success: function(response) {
        	$('#res').html(response);
			$('#pass').val('');
			$('#rpass').val('');
    	},
    	error: function(response) {
            $('#res').html('<div class="error">Сталася помилка "'+response.statusText+'"</div>');
			$('#pass').val('');
			$('#rpass').val('');
    	}
 	});
}
</script>
<div class="login_form">
	<h1>Вход</h1>
	<form action="login" method="POST" name="login" id="logform" onsubmit="return false;">
		<input type="text" name="user" id="user" placeholder="Имя пользователя, E-mail или номер телефона" required>
		<input type="password" name="pass" id="pass" placeholder="Пароль" required>
		<input type="hidden" name="log" value="1">
		<input type="button" class="butt" name="send" value="Войти" onclick="check_form();"><br>
		<center><a href="/#register">Зарегистрироваться</a></center><br>
	</form>
	<div id="res"></div>
</div>