<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="">
            <label for="matric">Matric:</label>
            <input type="text" id="matric" name="matric" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>
        <div class="message">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>

        <?php
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            $matric = $_POST['matric'];
            $password = $_POST['password'];

            $conn = new mysqli("localhost", "root", "", "Lab_5b");
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

            $stmt = $conn->prepare("SELECT * FROM users WHERE matric = ?");
            $stmt->bind_param("s", $matric);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['matric'] = $matric;
                    $_SESSION['role'] = $row['role'];
                    header("Location: display_users.php");
                    exit();
                } else {
                    echo "<div class='error'>Invalid username or password. <a href='login.php'>Try login again</a>.</div>";
                }
            } else {
                echo "<div class='error'>Invalid username or password. Try <a href='login.php'>login</a> again.</div>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>