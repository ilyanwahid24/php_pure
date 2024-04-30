<?php
// Work out the path to the database, so SQLite/PDO can connect
$root = __DIR__;
$database = $root . '/data/data.sqlite';
$dsn = 'sqlite:' . $database;

// Connect to the database, run a query, handle errors
$pdo = new PDO($dsn);
$stmt = $pdo->query(
	'SELECT
		title, created_at, body
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
	<h1>Blog title</h1>
	<p>This paragraph summarises what the blog is about.</p>

	<!-- Full Of repetition, simplified using php -->
	<!-- <h2>Article 1 title</h2>
	<div>dd Mon YYYY</div>
	<p>A paragraph summarising article 1.</p>
	<p>
		<a href="#">Read more...</a>
	</p>

	<h2>Article 2 title</h2>
	<div>dd Mon YYYY</div>
	<p>A paragraph summarising article 2.</p>
	<p>
		<a href="#">Read more...</a>
	</p> -->

	<!-- The Code using php is below -->
	<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
		<h2>
			<?php echo htmlspecialchars($row['title'], ENT_HTML5, 'UTF-8') ?>
		</h2>
		<div>
			<?php echo $row['created_at'] ?>
		</div>
		<p>
			<?php echo htmlspecialchars($row['body'], ENT_HTML5, 'UTF-8') ?>
		</p>
		<p>
			<a href="#">Read more...</a>
		</p>
		<?php endwhile ?>
	

</body>
</html>