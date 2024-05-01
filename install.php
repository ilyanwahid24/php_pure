<?php
// Get the PDO DSN String
$root = realpath(__DIR__);
// fungsi realpath akan mengembalikan string yang berupa lokasi dari code yang dieksekusi (absolute path) dan disimpan di variable root
$database = $root . '/data/data.sqlite';
// berisi lokasi dari database
$dsn = 'sqlite:' . $database;
// mendefinisikan data source name, dan menggunakan driver sqlite

$error = '';
// deklarasi variable error

// A security measure, to avoid anyone resetting the database if it already exists
if (is_readable($database) && filesize($database) > 0){
    // Memeriksa apakah file database bisa dibaca atau ada, dan memeriksa ukuran file lebih dari 0
    $error = 'Please delete the database manually before installing it a fresh';
    // file error akan berisi string diatas apabila kondisi if true
}

// Create an empty file for the database
if (!$error){
    // Jika variable $error kosong atau tidak ada isinya
    $createdOk  = @touch($database);
    // Membuat file dengan lokasi dan nama yang sudah ditentukan pada variable $database
    if(!$createdOk){
        // Apabila tidak terdapat output dari hasil dari pembuatan file database, maka variable error (lanjut bawah)
        $error = sprintf(
            'Could not create the database, please allow the server to create new filesin \'%s\'',
            dirname($database)
        );
        // Variable error akan berisi string diatas
    }
}

// Grab the SQL commands we want to run on the database
if (!$error){
    // Apabila variable $error kosong maka
    $sql = file_get_contents($root . '/data/init.sql');
    // memuat isi dari file init.sql kedalam bentuk string dan disimpan dalam variable $sql

    if ($sql === false){
        // Apabila variable $sql false
        $error = 'Cannot find SQL file';
        // Variable $error akan berisi string diatas
    }
}

// Connect to new database and try to run the SQL commands
if (!$error){
    // Apabila variable error kosong maka
    $pdo = new PDO($dsn);
    // Membuat object dari class PDO (yang digunakan untuk mengkoneksikan PHP dengan database), dengan parameter dsn yang berisi driver yang digunakann dan lokasi database
    $result = $pdo->exec($sql);
    // Mengeksekusi isi variable $sql yang berisi query database dengan menggunakan PDO dengan driver sqlite, dan menyimpan output berupa row yang terdampak terdari operasi ke variable $result
    if ($result === false){
        // Apabila variable result false maka
        $error = 'Could not run SQL: ' . print_r($pdo->errorInfo(), true);
        // Variable $error akan berisi string dengan ditambahkan informasi error dari object $pdo
    }
}

// See how many rows we created, if any
$count = null;
// Deklarasi variable $count dengan value null
if (!$error){
    // Jika variable $error kosong
    $sql = "SELECT COUNT(*) AS c FROM post";
    // deklarasi variable $sql dengan isi string diatas
    $stmt = $pdo->query($sql);
    // mengeksekusi query yang ada di variable $sql dan menyimpan output yang berupa PDO statement kedalam variable $stmt
    if ($stmt){
        // Jika variable $stmt isi maka
        $count = $stmt->fetchColumn();
        // Mengeksekusi fungsi fetchColumn yang akan mengembalikan sebuah kolom tunggal dari baris selanjutnya sebagai hasil atau false jika tidak ada row selanjutnya. dan menyimpannya di variable $count
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Installer</title>
    <style type="text/css">
                    .box {
                border: 1px dotted silver;
                border-radius: 5px;
                padding: 4px;
            }
            .error {
                background-color: #ff6666;
            }
            .success {
                background-color: #88ff88;
            }
    </style>
</head>
<body>
    <?php if ($error): ?>
        <?php // Jika error maka ?>
            <div class="error box">
                <?php echo $error ?>
                <?php // Menampilkan error melalui isi dari variable $error ?>
            </div>
    <?php else: ?>
        <?php // Jika tidak error maka ?>
        <div class="success box">
            The database and demo data was created OK.
            <?php if ($count): ?>
                <?php // Jika variable count tidak false maka ?>
                <?php echo $count ?> new rows were created.
                <?php // menampilkan banyak row yang dibuat plus isi dari variable $count ?>
            <?php endif ?>
        </div>
    <?php endif ?>
</body>
</html>