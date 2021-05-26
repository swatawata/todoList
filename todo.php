<?php
session_start();

$db['user_name'] = "root";
$db['password'] = "root";

$dbh = new PDO("mysql:host=localhost; dbname=todoList; charset=utf8", $db['user_name'], $db['password']);

$logout = '<a href="./?logout">logout</a><br />';
$appendTask = "
        <div>
            <form action=./todo.php?append method=POST>
            <input type=text name=task>
            <input type=submit name=append value=追加>
            </form>
        </div>
";

if (isset($_POST['append'])) {
    $_SESSION['task'] = $_POST['task'];
    $timeStanp = date("Y-m-d");
    $sql = "INSERT INTO tasks(id, user_id, status, contents, deadline, created_at, update_at) VALUES (0, $_SESSION[userId], 0, '$_SESSION[task]', '$timeStanp', '$timeStanp', '$timeStanp')";
    $res = $dbh->query($sql);
}


$setLogout = isset($_GET['logout']);
if ($setLogout) $_SESSION['loginStatus'] = false;

$save = false;
if (isset($_POST['checkbox'])) {
    $checkboxies = $_POST['checkbox'];
    $sql = "select * from tasks where user_id=$_SESSION[userId]";
    $res = $dbh->query($sql);
    foreach ($res as $key => $task) {
        if ($checkboxies[$key] == 0) {
            $sql = "update tasks set status = 0 where user_id = $_SESSION[userId] && contents = '$task[3]'";
            $res = $dbh->query($sql);
        } elseif ($checkboxies[$key] == 1) {
            $sql = "update tasks set status = 1 where user_id = $_SESSION[userId] && contents = '$task[3]'";
            $res = $dbh->query($sql);
        }
    }
    $save = true;
}
$saved = "";
if ($save) $saved = "<p>セーブされました</p>";
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $sql = "SELECT * FROM `tasks` WHERE user_id=$_SESSION[userId]";
    $res = $dbh->query($sql);
    foreach ($res as $key => $value) {
        if ($key != $deleteId) continue;
        $sql = "DELETE FROM tasks WHERE user_id=$_SESSION[userId] && contents='$value[contents]'";
        $res = $dbh->query($sql);
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>todo view</title>
</head>

<body>
    <?php
    //login
    if ($_SESSION['loginStatus'] == true) {
        echo $logout;
        $sql = "select * from tasks where user_id = $_SESSION[userId]";
        $res = $dbh->query($sql);
        echo "<h2>タスク一覧</h2>";

        $noTasks = "";
        $taskList = [];
        foreach ($res as $key => $task) {
            $check = "";
            $tasks[] = $task['contents'];
            if ($task['status'] == 1) $check = "checked=checked";
            $taskList[] = "<input type=hidden name=checkbox[$key] value=0><input type=checkbox name=checkbox[$key] value=1 $check>$task[contents] $task[deadline] $task[created_at] $task[update_at] <a href=./todo.php?delete=$key>完了</a><br />";
        }
        if (count($taskList) == 0) $noTasks = "<p>現在タスクはありません</p>";
    }
    ?>
    <?php
    echo $noTasks;
    echo "<a href=./todo.php?new-task>+</a>";
    if (isset($_GET['new-task'])) echo $appendTask;
    ?>
    <div>
        <form action="./todo.php" method="POST">
            <?php echo implode("\n", $taskList); ?>
            <input type="submit" name="update" value="更新">
        </form>
    </div>
    <?php
    echo $saved;
    ?>
</body>

</html>