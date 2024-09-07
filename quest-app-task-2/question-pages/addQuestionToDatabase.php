<?php
include "connectdb.php";

$title = $_POST['T'];
$question = $_POST['Q'];
$optionA = $_POST['A'];
$optionB = $_POST['B'];
$optionC = $_POST['C'];
$optionD = $_POST['D'];
$correctOption = $_POST['correctAnswer'];
$difficulty = $_POST['difficulty'];


$sql = "INSERT INTO questions (title, question, option_a, option_b, option_c, option_d, correct_option, difficulty) 
        VALUES (:title, :question, :option_a, :option_b, :option_c, :option_d, :correct_option, :difficulty)";

$stmt = $db->prepare($sql);

$stmt->bindParam(':title', $title);
$stmt->bindParam(':question', $question);
$stmt->bindParam(':option_a', $optionA);
$stmt->bindParam(':option_b', $optionB);
$stmt->bindParam(':option_c', $optionC);
$stmt->bindParam(':option_d', $optionD);
$stmt->bindParam(':correct_option', $correctOption);
$stmt->bindParam(':difficulty', $difficulty);



try {

    $stmt->execute();
    ?>
    <script>alert("Soru başarıyla eklendi"); location.href = "../admin-panel.php";</script>
    <?php
    
} catch (PDOException $e) {
    echo 'Veritabanı hatası: ' . $e->getMessage();
}
