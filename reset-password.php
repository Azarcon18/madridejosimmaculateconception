<?php
require_once('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists in the database
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch();

    if ($user) {
        // Generate reset token
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update user record with token and expiry
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        $update->execute([$token, $expiry, $email]);

        // Construct reset link
        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";

        // Send reset email
        $subject = "Password Reset Request";
        $message = "Hello,\n\nClick the link below to reset your password:\n$resetLink\n\nIf you did not request this, please ignore this email.";
        $headers = "From: no-reply@yourdomain.com";
        mail($email, $subject, $message, $headers);

        $_SESSION['success'] = "Password reset link has been sent to your email.";
    } else {
        $_SESSION['error'] = "No account found with this email address.";
    }

    // Redirect back to login page
    header("Location: ../index.php");
    exit;
}
?>
