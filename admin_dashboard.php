<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Function to format payment method names correctly
function formatPaymentMethod($method) {
    $method = strtolower(trim($method));
    
    if ($method === 'dana') {
        return 'Dana';
    } elseif ($method === 'gopay' || $method === 'go pay') {
        return 'Gopay';
    } elseif ($method === 'ovo') {
        return 'OVO';
    } else {
        // Capitalize first letter for other payment methods
        return ucfirst($method);
    }
}

// Update payment status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $booking_id = intval($_POST['booking_id']);
        $new_status = 'completed';
        $conn->query("UPDATE bookings SET payment_status = '$new_status' WHERE id = $booking_id");
        header("Location: admin_dashboard.php");
        exit();
    } elseif (isset($_POST['delete_booking'])) {
        $booking_id = intval($_POST['booking_id']);
        $conn->query("DELETE FROM bookings WHERE id = $booking_id");
        header("Location: admin_dashboard.php");
        exit();
    }
}

$result = $conn->query("
    SELECT bookings.id AS booking_id, users.username, villas.name AS villa_name, villas.price, bookings.booking_date, bookings.payment_status, bookings.payment_method
    FROM bookings 
    JOIN users ON bookings.user_id = users.id 
    JOIN villas ON bookings.villa_id = villas.id 
    ORDER BY bookings.booking_date
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS for the buttons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff3867 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(255, 59, 103, 0.3);
        }
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 59, 103, 0.4);
            background: linear-gradient(135deg, #ff3867 0%, #ff6b6b 100%);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<div class="container mx-auto mt-6">
    <!-- Header with title and logout button aligned -->
    <div class="flex justify-between items-center mb-6 px-4">
        <div class="w-1/4"><!-- Empty space for alignment --></div>
        <h2 class="text-2xl font-bold text-gray-700 text-center w-2/4">Daftar Booking</h2>
        <div class="w-1/4 flex justify-end">
            <a href="logout.php" class="logout-btn flex items-center">
                <span class="mr-2">Logout</span>
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto shadow-lg rounded-lg">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-green-500 text-white">
                    <th class="py-3 px-4 text-left">Username</th>
                    <th class="py-3 px-4 text-left">Villa</th>
                    <th class="py-3 px-4 text-left">Tanggal Booking</th>
                    <th class="py-3 px-4 text-left">Harga</th>
                    <th class="py-3 px-4 text-left">Metode Pembayaran</th>
                    <th class="py-3 px-4 text-left">Status Pembayaran</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-3 px-4"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td class="py-3 px-4"><?php echo htmlspecialchars($row['villa_name']); ?></td>
                        <td class="py-3 px-4"><?php echo htmlspecialchars($row['booking_date']); ?></td>
                        <td class="py-3 px-4 text-left text-green-500 font-bold">Rp<?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                        <td class="py-3 px-4"><?php echo htmlspecialchars(formatPaymentMethod($row['payment_method'])); ?></td>
                        <td class="py-3 px-4 font-bold <?php echo strtolower($row['payment_status']) === 'pending' ? 'text-yellow-500' : (strtolower($row['payment_status']) === 'completed' ? 'text-green-500' : 'text-red-500'); ?>">
                            <?php echo htmlspecialchars($row['payment_status']); ?>
                        </td>
                        <td class="py-3 px-4 space-x-2">
                            <?php if (strtolower($row['payment_status']) === 'pending') { ?>
                                <form method="POST" action="" class="inline">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                    <button type="submit" name="update_status" class="btn btn-primary">
                                        Tandai Lunas
                                    </button>
                                </form>
                            <?php } else { ?>
                                <button type="button" class="btn btn-secondary">Lunas</button>
                            <?php } ?>
                            <form method="POST" action="" class="inline">
                                <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                <button type="submit" name="delete_booking" class="btn btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
