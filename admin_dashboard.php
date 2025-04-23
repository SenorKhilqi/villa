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
    <title>Admin Dashboard - Villa Situ Lengkong</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #B0A695;
            --primary-dark: #8A7F6C;
            --primary-light: #EBE3D5;
            --accent: #776B5D;
            --text-dark: #333333;
            --text-light: #777777;
            --white: #FFFFFF;
            --danger: #FF6B6B;
            --success: #6BCB77;
            --warning: #FFD166;
            --gray-light: #f8f9fa;
            --border-color: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--gray-light);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header styles */
        .admin-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-badge {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.25);
        }

        /* Main content styles */
        .admin-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--text-dark);
            position: relative;
            padding-bottom: 10px;
        }

        .dashboard-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px;
        }

        /* Stats cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-right: 15px;
        }

        .stat-icon i {
            font-size: 24px;
            color: var(--primary-dark);
        }

        .stat-info h3 {
            font-size: 22px;
            margin: 0;
            font-weight: 600;
        }

        .stat-info p {
            color: var(--text-light);
            margin: 0;
            font-size: 14px;
        }

        /* Table styles */
        .bookings-table-container {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .search-box {
            position: relative;
        }

        .search-input {
            padding: 8px 15px 8px 40px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            width: 250px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(176, 166, 149, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--primary-light);
        }

        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background-color: rgba(235, 227, 213, 0.1);
        }

        td {
            padding: 15px 20px;
            font-size: 14px;
            color: var(--text-dark);
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: rgba(255, 209, 102, 0.15);
            color: var(--warning);
        }

        .status-completed {
            background-color: rgba(107, 203, 119, 0.15);
            color: var(--success);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-secondary {
            background-color: var(--gray-light);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        /* Footer */
        .admin-footer {
            margin-top: 40px;
            padding: 20px;
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
            border-top: 1px solid var(--border-color);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .header-actions {
                justify-content: center;
            }

            .dashboard-title {
                font-size: 24px;
                margin-bottom: 20px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .search-input {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .admin-content {
                padding: 20px 15px;
            }

            td, th {
                padding: 12px 10px;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
            }

            .stat-icon i {
                font-size: 20px;
            }

            .stat-info h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="brand">
            <div class="brand-logo">Villa Situ Lengkong</div>
        </div>
        <div class="header-actions">
            <div class="admin-badge">Administrator</div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-content">
        <h1 class="dashboard-title">Dashboard</h1>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3><?php 
                    $bookings_count = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc();
                    echo $bookings_count['count'];
                    ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?php 
                    $completed_count = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE payment_status = 'completed'")->fetch_assoc();
                    echo $completed_count['count'];
                    ?></h3>
                    <p>Completed</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3><?php 
                    $pending_count = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE payment_status = 'pending'")->fetch_assoc();
                    echo $pending_count['count'];
                    ?></h3>
                    <p>Pending</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php 
                    $users_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'")->fetch_assoc();
                    echo $users_count['count'];
                    ?></h3>
                    <p>Users</p>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="bookings-table-container">
            <div class="table-header">
                <h2 class="table-title">Booking Management</h2>
                <div class="table-actions">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Search bookings...">
                    </div>
                </div>
            </div>
            
            <div class="table-wrapper">
                <table id="bookingsTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Villa</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['villa_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                            <td>Rp<?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(formatPaymentMethod($row['payment_method'])); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($row['payment_status']) === 'pending' ? 'status-pending' : 'status-completed'; ?>">
                                    <?php echo htmlspecialchars($row['payment_status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if (strtolower($row['payment_status']) === 'pending') { ?>
                                        <form method="POST" action="" class="inline">
                                            <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                            <button type="submit" name="update_status" class="btn btn-primary">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-secondary" disabled>
                                            <i class="fas fa-check-circle"></i> Completed
                                        </button>
                                    <?php } ?>
                                    <form method="POST" action="" class="inline">
                                        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                        <button type="submit" name="delete_booking" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        &copy; 2024 Villa Situ Lengkong. All rights reserved.
    </footer>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('bookingsTable');
            tr = table.getElementsByTagName('tr');

            for (i = 1; i < tr.length; i++) { // Start from 1 to skip header row
                let visible = false;
                for (let j = 0; j < 6; j++) { // Check the first 6 columns (exclude actions)
                    td = tr[i].getElementsByTagName('td')[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            visible = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = visible ? '' : 'none';
            }
        });
    </script>
</body>
</html>
