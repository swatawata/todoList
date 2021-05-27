    <?php
    session_start();

    $email = '';
    $firstPassword = '';
    $secondPassword = '';
    $catchError = '';
    $sessionEmail = '';


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $firstPassword = $_POST['firstPassword'];
        $secondPassword = $_POST['secondPassword'];

        if (empty($email) || empty($firstPassword) || empty($secondPassword)) $catchError = '入力に誤りがあります';

        if ($firstPassword == '') {
            $catchError = 'パスワードを入力してください';
        } elseif ($firstPassword == $secondPassword) {
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $firstPassword;
        } elseif ($firstPassword != $secondPassword) {
            $catchError = 'パスワードが一致しません';
        }

        $db['user_name'] = "root";
        $db['password'] = "root";

        $dbh = new PDO("mysql:host=localhost; dbname=todoList; charset=utf8", $db['user_name'], $db['password']);

        $sql = "select * from users where email='$_SESSION[email]'";
        $res = $dbh->query($sql);
        if ($res->rowCount() != 0) $catchError = "使用できない文字が含まれています";
        else {
            header("Location: ./confirm.php");
            exit;
        }
    }

    $createAccount = '
        <div>
            <form action="./newAccount.php" method="POST">
                <label for="email-label">メールアドレス:</label><br />
                <input type=“text” name="email" type="email" required value="' . $_SESSION["email"] . '"><br />
                <label for="password-label">パスワード:</label><br />
                <input type="password" name="firstPassword"><br />
                <label for="password-label">パスワード確認:</label><br />
                <input type="password" name="secondPassword"><br />
                <input type="submit" name="send" value="確認">
            </form>
        </div>
    ';


    ?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <?php
        echo $createAccount;
        echo $catchError;
        ?>
    </body>

    </html>