<?php
session_start();
require_once '../../db/db_connect.php';

$user_id = $_SESSION['user_id'];
$test_id = $_POST['test_id'];
$answers = $_POST['answers'] ?? [];

$score = 0;

foreach ($answers as $question_id => $answer_id) {
    $stmt = $conn->prepare("
        SELECT is_correct FROM test_answers WHERE id = ? AND question_id = ?
    ");
    $stmt->bind_param("ii", $answer_id, $question_id);
    $stmt->execute();
    $stmt->bind_result($is_correct);
    if ($stmt->fetch() && $is_correct) {
        $score++;
    }
    $stmt->close();
}

// Сохраняем результат
$stmt = $conn->prepare("INSERT INTO test_results (user_id, test_id, score) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $user_id, $test_id, $score);
$stmt->execute();

echo "Вы набрали $score баллов.";
echo "<br><a href='/project_gazpromtraining/user/profile.php'>Вернуться в профиль</a>";
