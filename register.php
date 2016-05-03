<!DOCTYPE html>
<html lang="pl">
 <head>
  <meta charset="UTF-8">
  <title>System rejestracji i logowania PHP & MySQL - Dominik Ryńko</title>
  <meta name="Author" content="Dominik Ryńko - http://www.rynko.pl/">
  <meta name="Description" content="System rejestracji i logowania na stronę WWW. Napisany w PHP i w oparciu o bazę danych MySQL" >
  <link rel="stylesheet" href="style.css">
 </head>
<body>
 <header>
  <h1><a href="index.php" title="Dominik Ryńko - system rejestracji">System rejestracji i logowania</a></h1>
 </header>
 
 <nav id="menu">
  <ul>
   <li><a href="form.php" title="Formualarz rejestracji">Formularz rejestracji</a></li>
   <li><a href="login.php" title="Formualarz logowania">Formularz logowania</a></li>
   <li><a href="database.php" title="Zrzut bazy danych">Kod bazy danych</a></li>
   <li><a href="userpanel.php" title="Plik dla zalogowanych użytkowników">Panel użytkownika</a></li>
   <li><a href="http://rynko.pl/system-rejestracji-i-logowania/" title="Powrót na bloga "><strong>Powrót na stronę artykułu</strong></a></li>
  </ul>
 </nav>

 <section id="main">
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {
	   	 
	    $login    = $_POST['login'];
	    $password = $_POST['password'];
	    $email    = $_POST['email'];
	 
	    if(empty($login) || empty($password) || empty($email)) {
		    return '<p>Wypełnij wszystkie dane.</p>';
		}
		elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    return '<p>Nie poprawny adres E-mail.</p>';
		}
		else {
            if (file_exists('config.php')) {
                include_once('config.php');
            } else {
                return 'config.php file not found.';
            }

            $mysqli = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);

            if($mysqli -> connect_error) {
                return '<p>Problem z połączeniem się z bazą danych:' . $mysqli->connect_error . '[' . $mysqli->connect_errno . ']</p>';
            } else {
                $login     = trim(strip_tags($mysqli -> real_escape_string($login)));
                $password  = hash('sha256', trim(strip_tags($mysqli -> real_escape_string($password))));
                $email     = trim(strip_tags($mysqli -> real_escape_string($email)));
                $ip        = $_SERVER['REMOTE_ADDR'];

                $stmt = $mysqli -> prepare("INSERT INTO `user`(`id_user`, `login`,`password`,`email`,`added`,`ip`) VALUES('', ? , ? , ? , now(), ?)");
                $stmt -> bind_param('ssss', $login, $password, $email, $ip);
                $stmt -> execute();

                if($stmt -> affected_rows == 1) {
                    echo '<p>Zostałeś pomyślnie zarejestrowany</p>';
                } else {
                    echo '<p>Błąd podczas rejestracji</p>';
                }
            }
        }
    }
	?>
 </section>
</body>
</html>

