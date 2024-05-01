<?php
// Work out the path to the database, so SQLite/PDO can connect
$root = __DIR__;
// Menyimpan data lokasi script (absolute path) kedalam variable $root
$database = $root . '/data/data.sqlite';
// Menyimpan lokasi dan nama database kedalam variable $database
$dsn = 'sqlite:' . $database;
// Mendefinisikan driver dan lokasi database kedalam variable $dsn

// Connect to the database, run a query, handle errors
$pdo = new PDO($dsn);
// Menginisialisasi object $pdo dari class PDO dengan parameter berupa value dari variable $dsn
$stmt = $pdo->query(
	'SELECT
		title, created_at, body
	FROM
		post
	ORDER BY
		created_at DESC'
);
// Mengeksekusi query dari string diatas untuk melihat data dari table post menggunakan pdo dan fungsi query, dan outputnya disimpan di variable $stmt
if ($stmt === false){
	// Apabila variable $stmt false maka
	throw new Exception('There was a problem running this query');
	// digunakan untuk melemparkan exception apabila $stmt false
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
	<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
		<?php // ketika variable row ada isinya dan berisi array yang berupa data dari penggunaan pdostatement dan fetch data, dari fungsi diatas maka ?>
		<h2>
			<?php echo htmlspecialchars($row['title'], ENT_HTML5, 'UTF-8') ?>
			<?php //melakukan echo dengan output berupa string dari array $row yaitu key title, yang menggunakan fungsi htmlspecialchars yang digunakan untuk output text secara aman (apabila didalam text terdapat karakter khusus yang biasanya ada di syntax html atau javascript) ?>
		</h2>
		<div>
			<?php echo $row['created_at'] ?>
			<?php // echo dari array $row dari key created_at ?>
		</div>
		<p>
			<?php echo htmlspecialchars($row['body'], ENT_HTML5, 'UTF-8') ?>
			<?php //melakukan echo dengan output berupa string dari array $row yaitu key body, yang menggunakan fungsi htmlspecialchars yang digunakan untuk output text secara aman (apabila didalam text terdapat karakter khusus yang biasanya ada di syntax html atau javascript) ?>
		</p>
		<p>
			<a href="#">Read more...</a>
		</p>
		<?php endwhile ?>
		<?php // End while ?>
	

</body>
</html>