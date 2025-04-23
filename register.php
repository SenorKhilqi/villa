<?php
include 'config.php';

$notification = ""; // Variabel untuk menyimpan pesan notifikasi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user';

    // Memeriksa apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $notification = "Konfirmasi password tidak sesuai dengan password yang pertama. Pastikan kedua password sama.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            $notification = "Registrasi berhasil!";
        } else {
            $notification = "Gagal registrasi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            color: #606060;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #8d7b4f;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .login-link {
            margin-top: 15px;
            display: block;
            color: #B0A695;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin: 10px 0;
        }

        .toggle-password {
            cursor: pointer;
            position: absolute;
            margin-left: -30px;
            margin-top: 18px;
        }
    </style>
    <script>
        // Menampilkan notifikasi saat halaman dimuat
        window.onload = function() {
            const notification = "<?php echo $notification; ?>";
            if (notification) {
                alert(notification);
            }
        };

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>
    <form method="POST" action="register.php">
        <div style="position: relative;">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i id="togglePassword1" class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password', 'togglePassword1')"></i>
        </div>
        <div style="position: relative;">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password" required>
            <i id="togglePassword2" class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('confirm_password', 'togglePassword2')"></i>
        </div>
        <button type="submit">Register</button>
    </form>
    <a href="login.php" class="login-link">Login</a>
</div>

<!-- Font Awesome untuk ikon mata -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

</body>
</html>