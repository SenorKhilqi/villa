<nav class="navbar">
    <div class="container">
        <div class="logo">
            <a href="index.php">
                <img src="logo/kelompok.jpg" alt="Logo Villa Situ Lengkong">
            </a>
        </div>
        <button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false">☰</button>
        <ul class="menu">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="villa_kami.php">Villa Kami</a></li>
            <li><a href="calender.php">Cek Tanggal</a></li>
            <li><a href="tentang_kami.php">Tentang Kami</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
            <li><a href="refund_request.php">Pembatalan</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>
