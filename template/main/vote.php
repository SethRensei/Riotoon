<?php

use Riotoon\Repository\VoteRepository;
use Riotoon\Service\DbConnection;

try {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        http_response_code(403);
        die();
    }

    $connection = DbConnection::GetConnection();
    $vote = new VoteRepository();
    $user = (int) $_SESSION['User'];
    $id = (int) $_POST['id'];
    if ($_POST['vote'] == 1)
        $success = $vote->like(clean($_POST['id']), $user);
    else
        $success = $vote->dislike(clean($_POST['id']), $user);

    $req = $connection->prepare("SELECT likes, dislikes FROM webtoon WHERE id = :id");
    $req->bindValue(':id', clean($id));
    $req->execute();
    header('Content-Type: application/json');
    $record = $req->fetch(PDO::FETCH_ASSOC);
    $record['success'] = $success;
    die(json_encode($record));
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);
}
