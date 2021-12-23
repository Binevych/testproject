<?php session_start();
include('func.php');
if(!empty($_POST['reg'])) {
	$err='';
	if(empty($_POST['user'])||empty($_POST['email'])||empty($_POST['mob'])||empty($_POST['pass'])||empty($_POST['rpass']))
		$err.='Эти поля обязательны для заполнения';
	if(empty($_POST['user'])) $err.='<script>$("#user").css("border","1px solid red");</script>';
	if(empty($_POST['email'])) $err.='<script>$("#email").css("border","1px solid red");</script>';
	if(empty($_POST['mob'])) $err.='<script>$("#mob").css("border","1px solid red");</script>';
	if(empty($_POST['pass'])) $err.='<script>$("#pass").css("border","1px solid red");</script>';
	if(empty($_POST['rpass'])) $err.='<script>$("#rpass").css("border","1px solid red");</script>';
	if(!empty($err)) exit('<div class="error">'.$err.'</div>');
	if($_POST['pass']!=$_POST['rpass']) $err.='Пароли не соответствуют<script>$("#pass").css("border","1px solid red");$("rpass").css("border","1px solid red");</script>';
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		$err.='E-mail введен некорректно<script>$("#email").css("border","1px solid red");</script>';
	if(!empty($err)) exit('<div class="error">'.$err.'</div>');
	
	$query1 = mysqli_query($link, "SELECT * FROM users where user='".$_POST['user']."' or email='".$_POST['email']."' or mob='".$_POST['mob']."'") or die('<div class="error">Error1 - '.mysqli_error($link).'</div>');
	if(mysqli_num_rows($query1)>0) {
		$q1=mysqli_fetch_array($query1);
		if($q1['user']==$_POST['user']) exit('<div class="error">Пользователь с таким именем уже существует</div><script>$("#user").css("border","1px solid red");</script>');
		if($q1['email']==$_POST['user']) exit('<div class="error">Пользователь с таким E-mail уже существует</div><script>$("#email").css("border","1px solid red");</script>');
		if($q1['mob']==$_POST['user']) exit('<div class="error">Этот номер телефона уже занят</div><script>$("#mob").css("border","1px solid red");</script>');
	}
	$res1 = mysqli_query($link, "INSERT INTO users (user,email,mob,pass) VALUES ('".$_POST['user']."','".$_POST['email']."','".$_POST['mob']."','".$_POST['pass']."')") or die('<div class="error">Error2 - '.mysqli_error($link).'</div>');
	exit('<div class="success">Регистрация прошла успешно</div><meta http-equiv="Refresh" Content="0; url=/">');
}
?>
<style>

</style>
<script>
function check_form() {
	$('#res').html('<center><img style="max-height:30px; width:auto;" src="images/wait.gif"></center>');
	$("#regform input").css("border","1px solid #aaa");
	$('#pass').val(sha256($('#pass').val()));
	$('#rpass').val(sha256($('#rpass').val()));
	var form_data = new FormData(document.getElementById('regform'));
	$.ajax({
        url: 'register',
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
	<h1>Регистрация</h1>
	<form action="login" method="POST" name="login" id="regform" onsubmit="return false;">
		<input type="text" name="user" id="user" placeholder="Имя пользователя" required>
		<input type="text" name="email" id="email" placeholder="E-mail" required>
		<input type="text" name="mob" id="mob" placeholder="Номер телефона (в формате +380001234567)" required>
		<input type="password" name="pass" id="pass" placeholder="Пароль" required>
		<input type="password" name="rpass" id="rpass" placeholder="Повторите пароль" required>
		<input type="hidden" name="reg" value="1">
		<input type="button" class="butt" name="send" value="Зарегистрироваться" onclick="check_form();"><br>
		<center><a href="/#login">Войти</a></center><br>
	</form>
	<div id="res"></div>
</div>