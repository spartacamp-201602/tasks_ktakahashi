<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDb();

$sql = 'select * from tasks';
$stmt = $dbh->prepare($sql);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $title = $_POST['title'];

    // バリデーション処理
    $errors = array(); //エラーの情報を格納する配列
    if ($title == "")
    {
        $errors['title'] = 'タスク名を入力してください。';
    }

    // エラーがないかどうかの確認
    if (count($errors) == 0)
    {
        $sql = 'insert into tasks (title, created_at, updated_at) ';
        $sql.= 'values (:title, now(), now())';

        $stmt = $dbh->prepare($sql);
        $stmt->bindparam(':title', $title);
        $stmt->execute();

        // 自分自身
        header('Location: index.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>タスク管理</title>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                <a class="navbar-brand" href="#">タスク管理アプリ</a>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">

                <div class="col-xs-12">
                    <form action="" method="post" class="form-inline">
                        <p>
                            <input class="form-control" type="text" name="title"></input>
                            <input class="btn btn-default btn-sm" type="submit" value="追加"></input>
                        </p>
                        <p>
                            <span style="color: red;">
                                <?php echo h($errors['title']) ?>
                            </span>
                        </p>
                    </form>
                </div>
                <div class="col-xs-6">
                    <h2>未完了タスク</h2>
                    <ul>
                        <?php foreach ($tasks as $task) :?>

                        <?php if ($task['status'] == 'notyet'): ?>

                        <li>
                            <a href="done.php?id=<?php echo $task['id'] ?>">[完了]</a>
                            <?php echo $task['title'] ?>
                            <a href="edit.php?id=<?php echo $task['id'] ?>">[編集]</a>
                            <a href="delete.php?id=<?php echo $task['id'] ?>">[削除]</a>
                        </li>

                        <?php endif; ?>

                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-xs-6">

                    <h2>完了したタスク</h2>
                    <ul>
                        <?php foreach ($tasks as $task) : ?>

                            <?php if ($task['status'] == 'done'): ?>
                            <li><?php echo h($task['title']) ?></li>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>