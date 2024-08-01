<?php
    session_start(); 

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $error="";
        // Retrieve the email and password from the form
        if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !isset($_POST['password'])) {
            $error = 'Please enter valid email and password.';
        }else{
            $email = $_POST['email'];
    
            //Connect to database
            require_once '../db_connection.php';
            // Prepare the SQL query with prepared statement
            $query = "SELECT * FROM Member WHERE email=? LIMIT 1";
            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                die('mysqli_prepare failed: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if there was a match
            if (mysqli_num_rows($result) === 1) {
                $user = mysqli_fetch_assoc($result);
                $password = $_POST['password'];

                // Verify password
                if (password_verify($password, $user['mem_pass'])) {
                    // Login successful, store the email in the session
                    $_SESSION['email'] = $email;
                    $_SESSION['mem_id'] = $user['mem_id'];
                    $_SESSION['mem_name'] = $user['mem_name'];
                    
                    // Redirect to account.php
                    header('Location: ../wardrobe/');
                    exit();
                } else {
                    $error='Password verification failed';
                }
            } else {
                $error='No user found with this email';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="login_style.css">
    <link href="https://fonts.cdnfonts.com/css/venus-rising" rel="stylesheet">
    
    <!--This is for the icon-->
    <script src="https://kit.fontawesome.com/3e15daf571.js" crossorigin="anonymous"></script>
    
    <title>LOGIN | EVANGELION</title>
</head>
<body>

<?php include '../include/evan_header.php'; ?>
<section>
    <div class="register-container">
        <h1>Login</h1>

        <?php if (isset($error)): ?>
            <div class="message error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="register-form" method="post" action="" >
            <label for="log_email">Email:</label>
            <input type="email" id="log_email" name="email" required value="" autocomplete="off"><br>
            <label for="log_password">Password:</label>
            <input type="password" id="log_password" name="password" required value="" autocomplete="off"><br>
            <input type="submit" value="Login">
        </form>
        <div class="login-link">
            <a href="../register/">Not yet have an account? Create here</a>
        </div>
    </div>
</section>

<?php include '../include/evan_footer.php'; ?>

</body>
</html>
