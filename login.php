<?php
session_start();
$db = new SQLite3('kon_db.sqlite');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        echo "<script>
            localStorage.setItem('activeUser', JSON.stringify({
                id: '{$user['id']}',
                name: '{$user['username']}'
            }));
            window.location.href = 'index.html';
        </script>";
        exit();
    } else {
        $error = "Kullanıcı adı veya şifre hatalı!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-ON! Chatbot - Giriş</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffedf0;
            margin: 0;
            padding: 20px;
            color: #594a4e;
        }

        .container {
            max-width: 400px;
            margin: 40px auto;
            padding: 25px;
            background-color: #fff6f7;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(241, 187, 191, 0.2);
        }

        h2 {
            color: #594a4e;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #594a4e;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #f1bbbf;
            border-radius: 8px;
            font-size: 15px;
            color: #594a4e;
            background-color: #ffffff;
            box-sizing: border-box;
        }

        input:focus {
            border-color: #ffadb3;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #f1bbbf;
            color: #594a4e;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #ffadb3;
        }

        .error {
            color: #ff4444;
            background-color: #ffe6e6;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #594a4e;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        /* Dark theme styles */
        body.dark-theme {
            background-color: #2d2e32;
            color: #e2e8f0;
        }

        .dark-theme .container {
            background-color: #373a41;
        }

        .dark-theme input {
            background-color: #2d2e32;
            border-color: #4a4d57;
            color: #e2e8f0;
        }

        .dark-theme button {
            background-color: #4a4d57;
            color: #e2e8f0;
        }

        .dark-theme button:hover {
            background-color: #5a5d67;
        }

        .dark-theme .links a {
            color: #e2e8f0;
        }

        .theme-switch {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f1bbbf;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(241, 187, 191, 0.3);
            transition: all 0.3s ease;
        }

        .dark-theme .theme-switch {
            background-color: #4a4d57;
        }

        .theme-switch i {
            color: #594a4e;
        }

        .dark-theme .theme-switch i {
            color: #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Giriş Yap</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Kullanıcı Adı:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Şifre:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Giriş Yap</button>
        </form>
        
        <div class="links">
            <p>Hesabınız yok mu? <a href="register.php">Kayıt olun</a></p>
            <p><a href="index.html">Ana Sayfaya Dön</a></p>
        </div>
    </div>

    <div class="theme-switch" onclick="toggleTheme()">
        <i class="fas fa-moon"></i>
    </div>

    <script>
        function toggleTheme() {
            const body = document.body;
            const themeSwitch = document.querySelector('.theme-switch');
            const icon = themeSwitch.querySelector('i');
            
            if (body.classList.contains('dark-theme')) {
                body.classList.remove('dark-theme');
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                body.classList.add('dark-theme');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
            
            localStorage.setItem('theme', body.classList.contains('dark-theme') ? 'dark' : 'light');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                document.querySelector('.theme-switch i').classList.remove('fa-moon');
                document.querySelector('.theme-switch i').classList.add('fa-sun');
            }
        });
    </script>
</body>
</html> 