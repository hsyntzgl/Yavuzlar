<?php


$current_page = basename($_SERVER['PHP_SELF']);

echo '<div class="nav">
<a href="/index.php">
    <img src="/logos/navbar-logo.png" alt="logo">
</a>
<ul>';

if (isset($_SESSION['id'])) {

    switch($_SESSION['role']){
        case 'admin':
            if ($current_page != 'mainpage.php') {
                echo '<li><a href="/admin-panel/mainpage.php">Admin Panel</a></li>';
            }
            break;
        case 'customer':
            if ($current_page != 'basket.php') {
            echo '<li><a href="/basket.php">Sepet</a></li>';
            }
            break;
        case 'company':
            if ($current_page != 'mainpage.php') {
                echo '<li><a href="/companies-panel/mainpage.php">Firma Panel</a></li>';
            }
            break;
    }

    if ($current_page != 'profile.php') {
        echo '<li><a href="/customer-panel/profile.php">Profil</a></li>';
    }
    
    echo '<li><a href="/logout.php">Çıkış Yap</a></li>';

} else {
    echo '<li><a href="/login.php">Giriş Yap</a></li>
          <li><a href="/register.php">Kayıt Ol</a></li>';
}

echo '</ul></div>';