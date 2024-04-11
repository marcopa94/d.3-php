<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <h1> ciao</h1>

</head>
<body>
    <?php
    $host = "localhost";
    $db = "client";
    $user = "root";
    $pass = "";
    $dsn = "mysql:host=$host;dbname=$db";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    // comando che connette al database
    $pdo = new PDO($dsn, $user, $pass, $options);
    
$stmt = $pdo->query('SELECT * FROM client');

echo '<ul>';
foreach ($stmt as $row)
{
    echo "<li>$row[name]</li>";
}
echo '</ul>';
    ?>
</body>
</html>