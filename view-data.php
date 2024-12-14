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

// Include database connection file
include("db-conn.php");

// Fetch users Data
$data_query = "SELECT * FROM users";
$data_result = $pdo->query($data_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="style2.css">
    <title>Data Table</title>
    <style>
        table {
            table-layout: auto;
            border-collapse: collapse;
			margin-left: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        td {
            padding: 8px;
            text-align: left;
			white-space: nowrap; /* Prevent text from wrapping */
        }
		th {
			padding: 8px;
			text-align: center;
			white-space: nowrap; 
		}
    </style>
</head>
<body>
    <h2>Student and Lecturer Information</h2>
	<button onclick="window.location.href='logout.php'" id="logoutButton">Logout</button>
    <table>
        <thead>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Level</th>
				<th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($data = $data_result->fetch(PDO::FETCH_ASSOC)) { 
                $matric = $data['matric'];
				$name = $data['name'];
				$role = $data['role'];
            ?>
            <tr>
                <td><?php echo htmlspecialchars($matric); ?></td>
                <td><?php echo htmlspecialchars($name); ?></td>
                <td><?php echo htmlspecialchars($role); ?></td>
				<td>
                    <a href="update.php?matric=<?php echo urlencode($matric); ?>">Update</a> |
                    <a href="delete.php?matric=<?php echo urlencode($matric); ?>" 
                       onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
