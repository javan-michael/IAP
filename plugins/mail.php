<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic input handling
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if ($username === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid input. Please provide a valid username and email.";
        exit;
    }

    // Database connection (replace placeholders or load from env/config)
    $servername = 'localhost';
    $dbusername = 'root';
    $password   = 'Amerucas.1';
    $dbname     = 'task_app';

    $conn = new mysqli($servername, $dbusername, $password, $dbname);
    if ($conn->connect_error) {
        http_response_code(500);
        echo "Database connection failed.";
        exit;
    }

    // Store user in database
    $stmt = $conn->prepare('INSERT INTO users (username, email) VALUES (?, ?)');
    $stmt->bind_param('ss', $username, $email);

    if ($stmt->execute()) {
        // Send confirmation email
        require_once __DIR__ . '/vendor/autoload.php';

        $mail = new PHPMailer(true);
        try {
            // SMTP configuration (replace placeholders)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'michael.obunga@strathmore.edu';
            $mail->Password   = 'ziul rgre mmea rsen';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or PHPMailer::ENCRYPTION_SMTPS
            $mail->Port       = 465;

            // Message setup
            $mail->setFrom('michael.obunga@strathmore.edu', 'Michael Javan Obunga');
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->Subject = 'Welcome to Task App';
            $safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            $mail->Body    = "<p>Hi {$safeUsername},</p><p>Thanks for registering!</p>";
            $mail->AltBody = "Hi {$username},\n\nThanks for registering!";

            $mail->send();
            echo "User registered successfully! A confirmation email has been sent to " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . ". <a href='users.php'>View all users</a>";
        } catch (Exception $e) {

            echo "User registered successfully, but we couldn't send the email right now. Please try again later. <a href='users.php'>View all users</a>";
        }
    } else {
        http_response_code(500);
        echo "Error registering user.";
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: index.html');
    exit();
}