<?php
include "connectdb.php"; 

$sql = "SELECT * FROM scoreboard ORDER BY score DESC"; 
$stmt = $db->prepare($sql);

try {
    $stmt->execute();
    $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Veritabanı hatası: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skor Tablosu</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <h1>Skor Tablosu</h1>
        <table id="scoreboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>Skor</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($scores)): ?>
                    <tr>
                        <td colspan="4">Veri bulunamadı</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($scores as $score): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($score['id']); ?></td>
                            <td><?php echo htmlspecialchars($score['username']); ?></td>
                            <td><?php echo htmlspecialchars($score['score']); ?></td>
                            <td><?php echo htmlspecialchars($score['date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
