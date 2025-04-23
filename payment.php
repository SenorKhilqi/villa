<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil booking terbaru yang menunggu pembayaran
$stmt = $conn->prepare("SELECT b.id, v.name, v.price, b.booking_date, b.payment_status, b.payment_method 
                        FROM bookings b
                        JOIN villas v ON b.villa_id = v.id
                        WHERE b.user_id = ? AND b.payment_status = 'pending'
                        ORDER BY b.booking_date DESC
                        LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "Tidak ada booking yang menunggu pembayaran.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update status pembayaran menjadi 'completed'
    $booking_id = $booking['id'];
    $stmt = $conn->prepare("UPDATE bookings SET payment_status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        echo "Pembayaran berhasil!";
    } else {
        echo "Pembayaran gagal!";
    }
}
?>

<h2>Konfirmasi Pembayaran</h2>
<p>Villa: <?php echo $booking['name']; ?></p>
<p>Tanggal: <?php echo $booking['booking_date']; ?></p>
<p>Harga: Rp<?php echo number_format($booking['price'], 0, ',', '.'); ?></p>
<p>Metode Pembayaran: <?php echo $booking['payment_method']; ?></p>
<p>Status Pembayaran: <?php echo $booking['payment_status']; ?></p>

<form method="POST" action="payment.php">
    <button type="submit">Konfirmasi Pembayaran</button>
</form>
<a href="logout.php">Logout</a>
