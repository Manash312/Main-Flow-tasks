<?php
session_start();
include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    echo "Rows found: " . $stmt->num_rows;

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashed);
        $stmt->fetch();

        if (password_verify($password, $hashed)) {
            $_SESSION['username'] = $username;
            header("Location: welcome.php");
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login Page</h2>
<form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
</form>
<p style="color:red;"><?php echo $message; ?></p>
</body>
</html>
