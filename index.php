<?php
require_once 'lib/common.php';
// Work out the path to the database, so SQLite/PDO can connect
$root = __DIR__;
$database = $root . '/data/data.sqlite';
$dsn = 'sqlite:' . $database;

// Connect to the database, run a query, handle errors
$pdo = getPDO();
$stmt = $pdo->query(
	'SELECT
		id, title, created_at, body
	FROM
		post
	ORDER BY
		created_at DESC'
);
if ($stmt === false){
	throw new Exception('There was a problem running this query');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>A blog application</title>
</head>
<body>
	<?php require 'template/title.php' ?>
	<h1>Blog title</h1>
	<p>This paragraph summarises what the blog is about.</p>

	<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
		<h2>
			<?php echo htmlEscape($row['title']) ?>
		</h2>
		<div>
			<?php echo convertSqlDate($row['created_at']) ?>
			(<?php echo countCommentsForPost($row['id']) ?> comments)
		</div>
		<p>
			<?php echo htmlEscape($row['body']) ?>
		</p>
		<p>
			<a href="view-post.php?post_id=<?php echo $row['id'] ?>">Read more...</a>
		</p>
		<?php endwhile ?>
	

</body>
</html>