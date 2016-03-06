<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDb();

$sql = 'select title from tasks where id = :id';
$id = $_GET['id'];

$stmt = $dbh->prepare($sql);
$stmt->bindparam(':id', $id);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $title = $_POST['title'];

    $errors = array();
    if ($title == "")
    {
        $errors['title'] = 'タスク名を入力してください。';
        $title = '';
    }
    if ($title == $task['title'])
    {
        $errors['title'] = 'タスク名が変更されていません。';
    }

    if (count($errors) == 0)
    {
        $sql = 'update tasks set title = :title , updated_at=now() ';
        $sql.= 'where id = :id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindparam(':title', $title);
        $stmt->bindparam(':id', $id);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }

}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <h1>タスクの編集</h1>
        <form action="" method="post">
            <p>
                <input type="text" name="title"
                    <?php if (count($errors) == 0): ?>
                    value="<?php echo $task['title'] ?>">
                    <?php endif; ?>
                </input>
                <input type="submit" value="編集">
            </p>
            <p>
                <span style="color: red;">
                    <?php echo h($errors['title']); ?>
                </span>
            </p>
        </form>
    </body>
</html>
