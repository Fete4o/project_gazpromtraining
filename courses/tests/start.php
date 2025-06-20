<?php
session_start();
require_once '../../db/db_connect.php';

$course_id = $_POST['course_id'];
$user_id = $_SESSION['user_id'];

// Получаем тест по курсу
$stmt = $conn->prepare("SELECT id FROM tests WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$test = $result->fetch_assoc();

$test_id = $test['id'];

// Вопросы и ответы
$stmt = $conn->prepare("
    SELECT q.id AS question_id, q.question_text, a.id AS answer_id, a.answer_text
    FROM test_questions q
    JOIN test_answers a ON a.question_id = q.id
    WHERE q.test_id = ?
");
$stmt->bind_param("i", $test_id);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $qid = $row['question_id'];
    if (!isset($questions[$qid])) {
        $questions[$qid] = [
            'text' => $row['question_text'],
            'answers' => []
        ];
    }
    $questions[$qid]['answers'][] = [
        'id' => $row['answer_id'],
        'text' => $row['answer_text']
    ];
}
?>

<form method="post" action="submit.php">
    <input type="hidden" name="test_id" value="<?= $test_id ?>">
    <?php foreach ($questions as $qid => $q): ?>
        <fieldset>
            <legend><?= $q['text'] ?></legend>
            <?php foreach ($q['answers'] as $ans): ?>
                <label>
                    <input type="radio" name="answers[<?= $qid ?>]" value="<?= $ans['id'] ?>" required>
                    <?= $ans['text'] ?>
                </label><br>
            <?php endforeach; ?>
        </fieldset>
    <?php endforeach; ?>
    <button type="submit">Отправить</button>
</form>
