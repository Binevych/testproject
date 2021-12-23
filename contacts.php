<?php session_start();
require_once('func.php');

$page='login';
$auth=0;

if(!empty($_SESSION['token'])) {
	$query1 = mysqli_query($link, "SELECT id,user FROM users") or die('<div class="error">Error1 - '.mysqli_error($link).'</div>');
	while($q1=mysqli_fetch_array($query1)) {
		if($_SESSION['token'] ==  hash('sha256', $q1['id'].':'.$q1['user'])) {
			$auth=$q1['id'];
			break;
		}
	}
	if($auth==0) {
		exit('<meta http-equiv="Refresh" Content="0; url=login">');
	}
}
else exit('<meta http-equiv="Refresh" Content="0; url=login">');

if(!empty($_GET['add'])) {
	$query1 = mysqli_query($link, "SELECT id FROM u_c where userid='".$auth."' and contactid='".$_GET['add']."'") or die('<div class="error">Error1 - '.mysqli_error($link).'</div>');
	if(mysqli_num_rows($query1)<1) {
		$res1 = mysqli_query($link, "INSERT INTO u_c (userid,contactid) VALUES ('".$auth."','".$_GET['add']."')") or die('<div class="error">Error4 - '.mysqli_error($link).'</div>');
		exit('<div class="success">Контакт успешно добавлен в избранные</div>');
	}
}
if(!empty($_GET['del'])) {
	$query1 = mysqli_query($link, "SELECT id FROM u_c where userid='".$auth."' and contactid='".$_GET['del']."'") or die('<div class="error">Error1 - '.mysqli_error($link).'</div>');
	if(mysqli_num_rows($query1)>0) {
		$res2 = mysqli_query($link, "DELETE FROM u_c WHERE userid = '".$auth."' and contactid='".$_GET['del']."'") or die('<div class="error">Error5 - '.mysqli_error($link).'</div>');
		$res3 = mysqli_query($link, "ALTER TABLE u_c AUTO_INCREMENT = 1") or die('<div class="error">Error6 - '.mysqli_error($link).'</div>');
		exit('<div class="success">Контакт успешно удален из избранных</div>');
	}
}
?>
<style>

</style>
<script>
$('.contactbut').click(function() {
	$.get('contacts?'+$(this).attr('act')+'='+$(this).attr('id'), function(data) {
		var dialog=$('<div class="dialog">'+data+'</div>');
		$("body").append(dialog);
		setTimeout("$('.dialog').remove();location.reload();",1500);
	}).fail(function(data) {
		var dialog=$('<div class="dialog"><div class="error">Сталася помилка "'+data.statusText+'"</div></div>');
		$("body").append(dialog);
		setTimeout("$('.dialog').remove();location.reload();location.reload();",1500);
	});
});
$(function(){
	$('a[href="'+location.hash+'"]').attr('style','background:#5d5d8c');
});
</script>
<div class="contacts">
	<a class="hbut" href="#contacts">Контакты</a><a class="hbut" href="#contacts?favourites=1">Избранные</a>
	<table>
	<tr><td>Имя</td><td>E-mail</td><td>Моб. номер</td><td></td></tr>
	<?php
		if(empty($_GET['favourites'])) {
			$query2 = mysqli_query($link, "SELECT * FROM contacts") or die('<div class="error">Error2 - '.mysqli_error($link).'</div>');
			while($q2=mysqli_fetch_array($query2)) {
				$query3 = mysqli_query($link, "SELECT id FROM u_c where userid='".$auth."' and contactid='".$q2['id']."'") or die('<div class="error">Error3 - '.mysqli_error($link).'</div>');
				echo '<tr><td>'.$q2['name'].'</td><td>'.$q2['email'].'</td><td>'.$q2['mob'].'</td>';
				if(mysqli_num_rows($query3)>0) echo '<td><span class="contactbut" id="'.$q2['id'].'" act="del">Удалить из избранного</span></td></tr>';
				else echo '<td><span class="contactbut" id="'.$q2['id'].'" act="add">Добавить в избранное</span></td></tr>';
			}
		}
		else {
			$query2 = mysqli_query($link, "SELECT contactid FROM u_c where userid='".$auth."'") or die('<div class="error">Error2 - '.mysqli_error($link).'</div>');
			while($q2=mysqli_fetch_array($query2)) {
				$query3 = mysqli_query($link, "SELECT * FROM contacts where id='".$q2['contactid']."'") or die('<div class="error">Error3 - '.mysqli_error($link).'</div>');
				$q3=mysqli_fetch_assoc($query3);
				echo '<tr><td>'.$q3['name'].'</td><td>'.$q3['email'].'</td><td>'.$q3['mob'].'</td><td><span class="contactbut" id="'.$q3['id'].'" act="del">Удалить из избранного</span></td></tr>';
			}
		}
		
	?>
	</table>
</div>