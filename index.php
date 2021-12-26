<?PHP header("Content-Type: text/html; charset=utf-8");
	if (!isset($_SESSION)) {
	  session_start();
	} 

	$server = 'localhost'; // Имя или адрес сервера
	$user = 'root'; // Имя пользователя БД
	$password = ''; // Пароль пользователя БД
	$role = '';//админ/студент/преподаватель
	$db = 'authorization_system'; // Название БД

	$db = mysqli_connect($server, $user, $password, $db); // Подключение

	// Проверка на подключение
	if (!$db) {
		// Если проверку не прошло, то выводится надпись ошибки и заканчивается работа скрипта
		echo "Не удается подключиться к серверу базы данных!"; 
		exit;
	} else {
	// Проверка нажата ли кнопка отправки формы
		if (isset($_REQUEST['doAuth'])) {

			// Последующий код проверяет вообще есть формы
			// Проверяет логин
			if (!$_REQUEST['login']) {
				$error = 'Введите логин';
			}
			// Проверяет пароль
			if (!$_REQUEST['password']) {
				$error = 'Введите пароль';
			}

			// Проверяет ошибки
			if (!$error) {
				$login = $_REQUEST['login'];
				$pass = $_REQUEST['password'];

				// берёт из БД пароль, роль и id пользователя 
				if ($result = mysqli_query($db, "SELECT `password`, `role`, `id` FROM `users` WHERE `login`='" . $login . "'")) {
					while( $row = mysqli_fetch_assoc($result) ){ 
						// Проверяет есть ли id
						if ($row['id']) {
							if ($pass == $row['password']) {
								if($row['role'] == 1){
//									echo "Вы вошли, вы админ, для вас ещё нет экрана";
									$_SESSION['id'] = $row['id'];
									header('Location: ../scripts/admin_main.php');
								}
								
								if($row['role'] == 2){
									$_SESSION['id'] = $row['id'];
									header('Location: ../scripts/tutor_main.php');
								}
								
								if($row['role'] == 3){
									$_SESSION['id'] = $row['id'];
//									echo $_SESSION['id'];
									header('Location: ../scripts/student_main.php');
									
								}
								exit;
							} else {
								 echo "<script>alert('Вы ввели неверный пароль')</script>";
							}
						} else {
							echo "<script>alert('Вы ввели неверный логин')</script>";
						}
					} 
				}
			} else {
				 // Выводит ошибки, если есть пустые поля формы
				 echo $error;
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/auth_styles.css" type="text/css" />
	<title>Авторизация</title>
	
</head>
<body>
	<table>
		<tr>
        <td>
            <img src="/images/mai_label.JPG" width="40" height="40">
        </td>
        <td>
            <p>Московский Авиационный Институт</p>
        </td>
    </tr>
	</table>
	
	<div class="rectangle1">
		<div class="rectangle2">
			<div class="form_auth_block">
			  <div class="form_auth_block_content">
				<p class="form_auth_block_head_text">Добро пожаловать!</p>
<!--				  class="form_auth_style"-->
				<form  action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post">
				  <input class="form_text" type="text" name="login" placeholder="Введите логин" required >
					<br>
				  	<input class="form_text" type="password" name="password" placeholder="Введите пароль" required >
						
					<button class="form_auth_button" type="submit" name="doAuth">Войти</button>
				</form>
				
				<button class= "get_pass_btn" onclick="window.location.href ='https://mai.ru/getpass/';">Получить доступ</button>
			  </div>
			</div>
			</div>
		</div>
</body>
</html>