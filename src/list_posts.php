<?php
// list_posts.php

// Se estiver utilizando Composer, descomente a linha abaixo
require 'vendor/autoload.php';

use MongoDB\Client;

// Conexão com o MongoDB usando o replica set
try {
    $client = new Client("mongodb://mongo1:27017,mongo2:27018,mongo3:27019,mongo4:27020/?replicaSet=rs0");

    $collection = $client->posts_app->posts;

    // Busca todos os posts, ordenados pela data de criação em ordem decrescente (mais recentes primeiro)
    $posts = $collection->find([], [
        'sort' => ['created_at' => -1]
    ]);

} catch (Exception $e) {
    echo "Erro ao conectar ao MongoDB: " . $e->getMessage();
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Listar Posts</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <nav>
        <a href="index.php">Home</a> |
        <a href="create_post.php">Criar Post</a>
    </nav>
    <h1>Posts</h1>
    <?php foreach ($posts as $post): ?>
        <div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
            <p><strong>Autor:</strong> <?php echo htmlspecialchars($post['author']); ?></p>
            <?php if (isset($post['created_at'])): ?>
                <small><?php echo $post['created_at']->toDateTime()->format('d/m/Y H:i'); ?></small>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</body>

</html>