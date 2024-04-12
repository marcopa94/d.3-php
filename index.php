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

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $age = $_POST['age'];
   
    $errors = [];

    if (empty($name)) {
        $errors['name'] = 'Nome non può essere vuoto';
    }

    if (empty($surname)) {
        $errors['surname'] = 'Cognome non può essere vuoto';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO client (name, surname, age) VALUES (:name, :surname, :age)");
            $stmt->execute([
                'name' => $name,
                'surname' => $surname,
                'age' => $age,
            ]);
            echo "Dati inseriti con successo!";
            header('Location: /w-1/d.3%20php/index2.php');
            exit();
        } catch (PDOException $e) {
            die("Errore durante l'inserimento dei dati: " . $e->getMessage());
        }
    } else {
        echo '<pre>' . print_r($errors, true) . '</pre>';
    }
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM client WHERE user_id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>Modifica Cliente</title>
    <style>
        #box {
            display: flex;
            justify-content: center;
            margin-top: 3em;
            border-radius: 10px;
            padding: 10px;
        }

        body {
            text-align: center;
            background-color: aliceblue;
        }

        form {
            background-color: aliceblue;
        }
    </style>
</head>
<body>
<div id="box">
    <form style="width: 500px" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo $user['name'] ?? ''; ?>" />
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Cognome</label>
            <input type="text" class="form-control" name="surname" id="surname" value="<?php echo $user['surname'] ?? ''; ?>" />
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Età</label>
            <input type="text" class="form-control" name="age" id="age" value="<?php echo $user['age'] ?? ''; ?>" />
        </div>

        <button type="submit" class="btn btn-primary">Inserisci</button>
    </form>
</div>

<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
></script>
</body>
</html>
