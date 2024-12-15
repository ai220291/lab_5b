<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
        input, select, button {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="">
            <label for="matric">Matric:</label>
            <input type="text" id="matric" name="matric" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="Lecturer">Lecturer</option>
                <option value="Student">Student</option>
            </select>

            <button type="submit" name="register">Register</button>
        </form>
        <div class="message">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
            $matric = $_POST['matric'];
            $name = $_POST['name'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = $_POST['role'];

            $conn = new mysqli("localhost", "root", "", "Lab_5b");
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

            $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role, accessLevel) VALUES (?, ?, ?, ?, ?)");
            $accessLevel = ($role === 'Lecturer') ? 'Admin' : 'User';
            $stmt->bind_param("sssss", $matric, $name, $password, $role, $accessLevel);

            if ($stmt->execute()) {
                echo "<p class='message' style='color: green;'>Registration successful!</p>";
            } else {
                echo "<p class='message' style='color: red;'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>