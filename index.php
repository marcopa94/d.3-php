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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $age = $_POST['age'];
   
    $errors = [];

    // Validazione dei dati
    if ((!$name)) {
        $errors['name'] = 'Nome non può essere vuoto';
    }

    if ((!$surname)) {
        $errors['surname'] = 'Cognome non può essere vuoto';
    }

    if (!is_numeric($age)) {
        $errors['age'] = 'Età deve essere un numero';
    }
// chiamat al database//
    if (($errors==[])) {
        $stmt = $pdo->prepare("INSERT INTO client (name, surname, age) VALUES (:name, :surname, :age)");
        $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'age' => $age,
        ]);
        echo "Dati inseriti con successo!";
        header('Location:/w-1/d.3%20php/index2.php');
    } else {
        echo '<pre>' . print_r($errors, true) . '</pre>';
    }
}
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
    <form style="width: 500px" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="name" />
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input type="text" class="form-control" name="surname" id="surname" />
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="text" class="form-control" name="age" id="age" />
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
></script>
</body>
</html>
