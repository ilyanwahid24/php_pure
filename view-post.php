<?php
    require_once 'lib/common.php';
    // Work out the path to the database, so SQLite/PDO can connect
    $root = __DIR__;
    $database = $root . '/data/data.sqlite';
    $dsn = 'sqlite:' . $database;

    // Get the post ID
    if(isset($_GET['post_id'])){
        $postId = $_GET['post_id'];
    }
    else{
        // So we always have a post ID var defined
        $postId = 0;
    }

    // Connect to the database, run a query, handle errors
    $pdo = getPDO();
    // $pdo = new PDO($dsn);
    $stmt = $pdo->prepare(
        'SELECT
            title, created_at, body
        FROM
            post
        WHERE
            id = :id'
    );
    if($stmt === false){
        throw new Exception("There was a problem preparing this query");
    }
    $result = $stmt->execute(
        array('id' => $postId,)
    );
    if($result === false){
        throw new Exception("There was a problem running this query");
    }

    //Let's get a row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Swap carriage returnsfor paragraph breaks
    $bodyText = htmlEscape($row['body']);
    $paraText = str_replace("\n", "</p><p>", $bodyText);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        A blog application |
        <?php echo htmlEscape($row['title']) ?>
    </title>
</head>
<body>
    <?php require 'templates/title.php' ?>
    <h1>Blog title</h1>
    <p>this paragraph summarises what the blog is about.</p>
    <h2>
        <?php echo htmlEscape($row['title']) ?>
    </h2>
    <div>
        <?php echo convertSqlDate($row['created_at']) ?>
    </div>
    <p>
        <?php // This is already escaped, so doesn't need further escaping ?>
        <?php echo $paraText ?>
    </p>
    <h3><?php echo countCommentsForPost($postId) ?> comments</h3>

    <?php foreach(getCommentsForPost($postId) as $comment): ?>
        <?php // For now, we'll use a horizontal rule-off to split it up a bit ?>
        <hr/>
        <div class="comment">
            Comment form
            <?php echo htmlEscape($comment['name']) ?>
            on 
            <?php echo convertSqlDate($comment['created_at']) ?>
        </div>
    <?php endforeach ?>
</body>
</html>