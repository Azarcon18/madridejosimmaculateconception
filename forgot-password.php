<?php
// forgot_password.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('config.php');

    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // Generate a random token

    // Check if email exists in the database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the database with the reset token
        $query = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send the reset link to the user's email
        $resetLink = "https://yourwebsite.com/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: " . $resetLink;
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "A password reset link has been sent to your email.";
        } else {
            echo "There was an error sending the email. Please try again later.";
        }
    } else {
        echo "No account found with that email address.";
    }
}
?>
