<?php
// ------------------------------------comando che connette al database-----------------------------------------------//
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

// ---------------------------------------Eliminazione record--------------------------------------------------------//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    if (!is_numeric($user_id)) {
        echo "L'ID utente deve essere un numero.";
    } else {
        // Eliminazione record
        $stmt = $pdo->prepare("DELETE FROM client WHERE user_id = ?");
        $stmt->execute([$user_id]);
        echo "Record eliminato con successo!";
    }
}

//------------------------------------------Ricerca per nome---------------------------------------------------------//
$results = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];

    // Prepara la query di ricerca per il nome
    $stmt = $pdo->prepare("SELECT * FROM client WHERE name LIKE ? OR surname LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);

    $results = $stmt->fetchAll();
}

//---------------------------------Visualizzazione di tutti i record-------------------------------------------------//
$stmt = $pdo->query('SELECT * FROM client');

$all_clients = $stmt->fetchAll();
//---------------------------------------------- Query per i risultati paginati--------------------------------------------//
$limit =2; 
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 


$stmt = $pdo->prepare('SELECT * FROM client LIMIT :limit OFFSET :offset');
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$paginated_results = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ricerca e Eliminazione Record</title>
    <style>
        body {
            background-color: aliceblue;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mt-5 mb-4">Gestione Clienti</h1>

    <!---------------------------------------------- Form per eliminare un record ------------------------------------------>
    <div id="box">
        <form style="width: 500px;" method="POST">
            <div class="mb-3">
                <label for="delete" class="form-label">ID da eliminare</label>
                <input type="text" class="form-control" name="user_id" id="user_id" />
            </div>
            <button type="submit" class="btn btn-danger">Elimina</button>
        </form>
    </div>

    <!-------------------------------------------------- Form per la ricerca ----------------------------------------------------->
    <div id="box">
        <form style="width: 500px;" method="GET">
            <div class="mb-3">
                <label for="search" class="form-label">Ricerca per nome</label>
                <input type="text" class="form-control" name="search" id="search" />
            </div>
            <button type="submit" class="btn btn-primary">Cerca</button>
        </form>
    </div>

    <!---------------------------------------------- Visualizzazione dei risultati della ricerca -------------------------->
    <?php if (isset($results) && count($results) > 0): ?>
        <div class="row">
            <?php foreach ($results as $result): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $result['name'] ?></h5>
                            <p class="card-text">ID: <?= $result['user_id'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!----------------------------------------------------- Pulsanti di navigazione ----------------------------------------->
        <div class="mt-3">
            <?php if ($page > 1): ?>
                <a href="?search=<?= $search ?>&page=<?= $page - 1 ?>" class="btn btn-primary">Previous</a>
            <?php endif; ?>
            <?php if (count($results) == $limit): ?>
                <a href="?search=<?= $search ?>&page=<?= $page + 1 ?>" class="btn btn-primary">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h2 class="mt-5">Tutti i record nel database:</h2>
    <div class="row">
        <?php foreach ($paginated_results as $client): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $client['name'] , " " ,$client['surname'] ?></h5>
                        <p class="card-text">ID: <?= $client['user_id'] ?></p>
                        <a href="" class="btn-btn-primary"> edit</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!------------------------------------------------------- Pulsanti di navigazione --------------------------------------------->
    <div class="mt-3">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="btn btn-primary">Previous</a>
        <?php endif; ?>
        <?php if (count($all_clients) > ($offset + $limit)): ?>
            <a href="?page=<?= $page + 1 ?>" class="btn btn-primary">Next</a>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
