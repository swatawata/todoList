<?php
session_start();

$passwordCount = array_fill(0, strlen($_SESSION['password']), '*');
$showPassword = implode('', $passwordCount);

$confirm = "<div>
                <p>こちらの内容で登録します</p>
                <p>メールアドレス：</p>
                <p>" . $_SESSION['email'] . "</p>
                <p>パスワード：</p>
                <p>" . $showPassword . "</p>
                <form  method=POST>
                <input type=submit name=return value=戻る>
                <input type=submit name=regist value=登録>
                </form>
            </div> ";

$email = $_SESSION['email'];
$hashedPassword = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

if (isset($_POST["return"])) {
    header("Location: ./newAccount.php");
    exit;
} elseif (isset($_POST["regist"])) {

    try {
        $db['user_name'] = "root";
        $db['password'] = "root";

        $dbh = new PDO("mysql:host=localhost; dbname=todoList; charset=utf8", $db['user_name'], $db['password']);

        $sql = "INSERT INTO users(email, password) VALUES ('$email', '$hashedPassword')";
        $res = $dbh->query($sql);

        $_SESSION = [];
        header("Location: ./end.php");
        exit;
    } catch(PDOException $error) {
        echo "使用できない文字列があります";
    }
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php echo $confirm; ?>
</body>

</html>