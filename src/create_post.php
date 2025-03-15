<?php
// create_post.php

require 'vendor/autoload.php';

use MongoDB\Client;

// Cria a conexão com o MongoDB apontando para o replica set.
$client = new Client("mongodb://mongo1:27017,mongo2:27017,mongo3:27017,mongo4:27017/?replicaSet=rs0");
$collection = $client->posts_app->posts;

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados enviados pelo formulário
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $author = trim($_POST['author'] ?? '');

    // Validação simples: verifica se todos os campos foram preenchidos
    if ($title && $body && $author) {
        // Insere o post no MongoDB, adicionando a data de criação
        $result = $collection->insertOne([
            'title' => $title,
            'body' => $body,
            'author' => $author,
            'created_at' => new \MongoDB\BSON\UTCDateTime()
        ]);

        if ($result->getInsertedCount() == 1) {
            $message = "Post criado com sucesso!";
        } else {
            $message = "Erro ao criar o post.";
        }
    } else {
        $message = "Por favor, preencha todos os campos.";
    }
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Criar Post</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <nav>
        <a href="index.php">Home</a> |
        <a href="list_posts.php">Listar Posts</a>
    </nav>
    <h1>Criar um Novo Post</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="create_post.php">
        <label for="title">Título:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="body">Corpo:</label><br>
        <textarea id="body" name="body" rows="4" cols="50" required></textarea><br><br>

        <label for="author">Autor:</label><br>
        <input type="text" id="author" name="author" required><br><br>

        <input type="submit" value="Criar Post">
    </form>
</body>

</html>