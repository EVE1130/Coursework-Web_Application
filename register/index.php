<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../login/login_style.css">
    <link href="https://fonts.cdnfonts.com/css/venus-rising" rel="stylesheet">
    
    <!--This is for the icon-->
    <script src="https://kit.fontawesome.com/3e15daf571.js" crossorigin="anonymous"></script>
    
    <title>REGISTER | EVANGELION</title>

    <style>
        section{
            background: url('../picturecol/laser1.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-blend-mode: overlay;
        }
    </style>
</head>
<body>

<?php include('../include/evan_header.php');?>
<section>
<div class="register-container">
    <h1>Register</h1>
    <?php
    
    //Connect to database
    require_once '../db_connection.php';

    // Initialize error and success variables
    $error= [];
    $error_message ="";
    $success_message = "";

    // Process user registration
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate email
        $name = $_POST["name"];
        $email = $_POST["email"];
        $contact = $_POST["contact"];
        $gender = $_POST["gender"];

        $contact = str_replace([' ', '-', '(', ')'], '', $contact); // Strip common formatting characters

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $error['email'] = "Invalid email address. Please enter a valid email.";
        }else{
            // Check if the email already exists
            $checkQuery = "SELECT * FROM Member WHERE email = '$email' ";
            $checkResult = $conn->query($checkQuery);

            if ($checkResult->num_rows > 0) {
                $error['email'] = 'Email already exists. Please use a different email.';
            } 
        }

        if (!ctype_digit($contact)) {
            $error['contact'] = 'Contact number must be numeric.';
        } else{
            // Check if the contact already exists
            $checkQuery = "SELECT * FROM Member WHERE contact = '$contact' ";
            $checkResult = $conn->query($checkQuery);

            if ($checkResult->num_rows > 0) {
                $error['contact'] = 'Contact number already exist. Please use a different contact number.';
            } 
        }

        if (strlen($_POST["password"]) < 8) {
            $error['password'] = "Password must be at least 8 characters long.";
        } else{
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        }
        
        
        if  (empty($error)) {
            // Prepare SQL statement to insert user data into the database
            $sql = "INSERT INTO Member (mem_name, email, contact, mem_pass, gender) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $contact, $password, $gender);

            // Execute the prepared statement
            if ($stmt->execute()) {
                $success_message = "User account created successfully!";
            } else {
                $error_message = "Error creating account: " . $conn->error;
            }

            // Close statement
            $stmt->close();
        }

    }

    // Close database connection
    $conn->close();
    ?>
    <div class="message success-message"><?php echo $success_message; ?></div>
    <div class="message error-message"><?php echo $error_message; ?></div>
    
    <form class="register-form" method="POST" autocomplete="off">
        <label for="re_name">Name:</label>
        <input type="text" id="re_name" name="name" required><br>
        
        <label for="re_email">Email:</label>
        <input type="email" id="re_email" name="email" required><br>
        <div class="message error-message"><?= $error['email'] ?? '' ?></div>

        <label for="re_contact">Contact:</label>
        <input type="text" id="re_contact" name="contact"  required><br>
        <div class="message error-message"><?= $error['contact'] ?? '' ?></div>

        <label for="re_pass">Password:</label>
        <input type="password" id="re_pass" name="password"  required><br>
        <div class="message error-message"><?= $error['password'] ?? '' ?></div>

        <label for="re_gender">Gender:</label>
        <select name="gender" id="re_gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br><br>

        <input type="submit" value="Create Account">
        </form>

    <div class="login-link">
        <a href="../login/">Already have an account? Login here.</a>
    </div>
</div>
</section>
<?php include('../include/evan_footer.php');?>

</body>
</html>
