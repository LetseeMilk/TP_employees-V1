<?php
include 'inc/function.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des départements</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <header class="mb-4 text-center">
            <h1 class="fw-bold">Liste des départements</h1>
        </header>
        
        <main>
            <?php afficherDepartements($dataBase); ?>
        </main>
        
        <footer class="mt-4 text-center">
            <div class="d-flex justify-content-center gap-3 flex-wrap">
            </div>
        </footer>
    </div>

        <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>