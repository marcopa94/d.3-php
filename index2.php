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
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

// Verifica se il form è stato inviato e se user_id è stato impostato
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    if (!is_numeric($user_id)) {
        echo "L'ID utente deve essere un numero.";
    } else {
        // Prepara e esegui la query di eliminazione
        $stmt = $pdo->prepare("DELETE FROM client WHERE user_id = ?");
        $stmt->execute([$user_id]);
        echo "Record eliminato con successo!";
    }
}

$stmt = $pdo->query('SELECT * FROM client');

echo '<ul>';
foreach ($stmt as $row) {
    echo "<li>$row[name] $row[user_id]</li>";
}
echo '</ul>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    />
    <title>Document</title>
    <style>
        #box {
            display: flex;
            justify-content: center;
            margin-top: 100px;
            border: 1px solid grey;
            border-radius: 10px;
            padding: 10px;
        }

        body {
            background-color: bisque;
        }

        form {
            background-color: bisque;
        }
    </style>
</head>
<body>
<div id="box">
    <div id="box">
        <form style="width: 500px" method="POST">
            <div class="mb-3">
                <label for="delete" class="form-label">ID da eliminare</label>
                <input type="text" class="form-control" name="user_id" id="user_id" />
            </div>

            <button type="submit" class="btn btn-danger">Elimina</button>
        </form>
    </div>
</div>

<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
></script>
</body>
</html>
