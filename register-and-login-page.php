<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	session_start(); // Start the session
	
	// Include database connection file
	require("db-conn.php");
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check the form type
        if (isset($_POST['form_type']) && $_POST['form_type'] === 'register') {
            $reg_matric = $_POST['reg_matric'];
			$reg_name = $_POST['reg_name'];
			$reg_password = $_POST['reg_password'];
			$reg_hashpassw = password_hash($reg_password, PASSWORD_DEFAULT);
			$reg_role = $_POST['reg_role'];
			
			// Prepare the insert statement
			$stmt = $pdo->prepare('INSERT INTO users VALUES (:reg_matric, :reg_name, :reg_hashpassw, :reg_role)');
			$stmt->execute([
				'reg_matric' => $reg_matric,
				'reg_name' => $reg_name,
				'reg_hashpassw' => $reg_hashpassw,
				'reg_role' => $reg_role
			]);
        } elseif (isset($_POST['form_type']) && $_POST['form_type'] === 'login') {
            // Handle login form
            $log_matric = $_POST['log_matric'];
            $log_password = $_POST['log_password'];

            // Verify the login credentials
            $stmt = $pdo->prepare('SELECT password FROM users WHERE matric = :log_matric');
            $stmt->execute(['log_matric' => $log_matric]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($log_password, $user['password'])) {
                // Set session variable to indicate the user is logged in
				$_SESSION['logged_in'] = true;
				$_SESSION['user_matric'] = $log_matric; // Optional: Store user's matric for further use

				// Redirect to homepage or protected page
				header("Location: view-data.php");
				$pdo = null;
				exit;
            } else {
                $login_error = "Invalid matric or password";
            }
        } else {
            echo "Unknown form submission.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Login & Registration Form</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container">
		<input type="checkbox" id="check">
		
		<!-- login form -->
		<div class="login form">
			<header>Login</header>
			<form action="" method="POST">
				<input type="hidden" name="form_type" value="login">
				<input type="text" placeholder="Enter your matric" name="log_matric" required>
				<input type="password" placeholder="Enter your password" name="log_password" required>
				<?php if (!empty($login_error)): ?>
					<div class="error"><?php echo $login_error; ?></div>
				<?php endif; ?>
				<input type="submit" class="button" value="Login">
			</form>
			<div class="signup">
				<span class="signup">Don't have an account? <label for="check">Signup</label>
				</span>
			</div>
		</div>
		
		<!-- registration form -->
		<div class="registration form">
			<header>Signup</header>
			<form action="" method="POST">
				<input type="hidden" name="form_type" value="register">
				<input type="text" placeholder="Matric" name="reg_matric" required>
				<input type="text" placeholder="Name" name="reg_name" required>
				<input type="password" placeholder="Password" name="reg_password" required>
				<label>Role:</label>
				<select name="reg_role" required>
					<option value="" disabled selected>Please select</option>
					<option value="student">Student</option>
					<option value="lecturer">Lecturer</option>
					<option value="admin">Admin</option>
				</select>
				<input type="submit" class="button" value="Signup">
			</form>
			<div class="signup">
				<span class="signup">Already have an account? <label for="check">Login</label>
				</span>
			</div>
		</div>
		
	</div>
</body>
</html>

<?php
// Close the database connection
$pdo = null;
?>