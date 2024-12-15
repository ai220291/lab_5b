<?php
session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "Lab_5b");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $matric = $_POST['matric'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $accessLevel = ($role === 'Lecturer') ? 'Admin' : 'User';

        $stmt = $conn->prepare("UPDATE users SET matric = ?, name = ?, role = ?, accessLevel = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $matric, $name, $role, $accessLevel, $id);
        $stmt->execute();

        echo "<p style='color: green;'>User updated successfully!</p><a href='display_users.php'>Back to list</a>";
        $stmt->close();
    } else {
        $result = $conn->query("SELECT * FROM users WHERE id = $id");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            die("User not found.");
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 0; }
        .container { width: 50%; margin: 50px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #007bff; }
        form { display: flex; flex-direction: column; gap: 15px; }
        input, select, button { padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 5px; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update User</h2>
        <form method="POST" action="">
            <label for="matric">Matric:</label>
            <input type="text" id="matric" name="matric" value="<?php echo $row['matric']; ?>" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Lecturer" <?php if ($row['role'] == 'Lecturer') echo 'selected'; ?>>Lecturer</option>
                <option value="Student" <?php if ($row['role'] == 'Student') echo 'selected'; ?>>Student</option>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>