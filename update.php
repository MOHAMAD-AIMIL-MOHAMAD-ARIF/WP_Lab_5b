<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page if not logged in
    header("Location: register-and-login-page.php");
    exit;
}

// Include database connection
include("db-conn.php");

// Check if the matric is provided in the URL
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Fetch the existing record
    $stmt = $pdo->prepare("SELECT * FROM users WHERE matric = :matric");
    $stmt->execute(['matric' => $matric]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the record doesn't exist, redirect back
    if (!$user) {
        header("Location: view-data.php");
        exit;
    }

    // Handle form submission for update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $role = $_POST['role'];

        // Update the record
        $update_stmt = $pdo->prepare("UPDATE users SET name = :name, role = :role WHERE matric = :matric");
        $update_stmt->execute([
            'name' => $name,
            'role' => $role,
            'matric' => $matric
        ]);

        // Redirect after update
        header("Location: view-data.php");
        exit;
    }
} else {
    header("Location: view-data.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Record</title>
</head>
<body>
    <h2>Update Record for Matric: <?php echo htmlspecialchars($matric); ?></h2>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>
        <label for="role">Role:</label>
        <select name="role" id="role" required>
            <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
            <option value="lecturer" <?php echo $user['role'] == 'lecturer' ? 'selected' : ''; ?>>Lecturer</option>
            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select><br><br>
        <button type="submit">Update</button>
        <a href="view-data.php">Cancel</a>
    </form>
</body>
</html>
