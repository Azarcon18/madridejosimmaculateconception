<?php require_once('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaSecret = '6LfCPpMqAAAAAE4pB5LZP4P_TUqHsKnnt3J465OP'; // Replace with your secret key
    $recaptchaResponse = $_POST['g-recaptcha-response']; // User's response token

    // Verify reCAPTCHA with Google
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $response = json_decode($verify);

    // Check if reCAPTCHA validation is successful
    if (!$response->success || $response->score < 0.5) { // Adjust the score threshold as needed
        die('reCAPTCHA verification failed. Please try again.');
    }

    // Proceed with login/signup logic here
}
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
    </div>
<?php endif; ?>

<script src="https://www.google.com/recaptcha/api.js?render=6LfCPpMqAAAAANJD3dBADWW_bQgoZa5_SXfnrlvK"></script>

<!DOCTYPE html>
<html lang="en">
<?php require_once('inc/header.php'); ?>

<body class="light-mode">
    <?php if ($_settings->chk_flashdata('success')): ?>
        <script>
            alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
        </script>
    <?php endif; ?>
    <?php require_once('inc/topBarNav.php'); ?>
    <style>
        #uni_modal .modal-content>.modal-footer,
        #uni_modal .modal-content>.modal-header {
            display: none;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> <!-- Correct reCAPTCHA script -->
    <div class="container-fluid mb-5 mt-2">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4">
                <h3 class="text-center">Login</h3>
                <hr>
                <form id="login-form" action="classes/registereduser_login.php" method="post">
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" class="form-control form" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control form" id="password" name="password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePasswordVisibility('password', this)">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    <div class="row mb-4 mt-3">
                        <button type="submit" class="btn btn-primary float-end" name="login_btn">Login</button>
                    </div>
                    <div class="row mb-4">
                        <button type="button" class="btn btn-secondary float-end" data-toggle="modal"
                            data-target="#signupModal">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Create Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="signup-form">
                        <div class="form-group">
                            <label for="name" class="control-label">Full Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="user_name" class="control-label">Username</label>
                            <input type="text" class="form-control" name="user_name" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control form" id="signup-password" name="password"
                                    required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePasswordVisibility('signup-password', this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small><a href="#" onclick="suggestStrongPassword()">Suggest Strong Password</a></small>
                        </div>
                        <div class="form-group">
                            <label for="phone_no" class="control-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone_no" required>
                        </div>
                        <div class="form-group">
                            <label for="address" class="control-label">Address</label>
                            <textarea class="form-control" name="address" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label">Marital Status</label>
                            <select class="form-control" name="status" required>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                            </select>
                        </div>
                        <div class="g-recaptcha mb-3" data-sitekey="6LfCPpMqAAAAANJD3dBADWW_bQgoZa5_SXfnrlvK"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <a href="#" class="btn btn-link float-end" data-toggle="modal" data-target="#forgotPasswordModal">Forgot
            Password?</a>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog"
        aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="forgot-password-form" action="classes/reset_password.php" method="POST">
                        <div class="form-group">
                            <label for="reset-email" class="control-label">Enter your email address</label>
                            <input type="email" class="form-control" name="email" id="reset-email" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, toggleButton) {
            const passwordField = document.getElementById(fieldId);
            const icon = toggleButton.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function isStrongPassword(password) {
            // Define what constitutes a strong password
            const strongPasswordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            return strongPasswordPattern.test(password);
        }

        function suggestStrongPassword() {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@$!%*?&";
        let password = "";

        // Ensure the password contains at least one character from each category
        const categories = [
            "abcdefghijklmnopqrstuvwxyz", // Lowercase
            "ABCDEFGHIJKLMNOPQRSTUVWXYZ", // Uppercase
            "0123456789",                 // Numbers
            "@$!%*?&"                     // Special characters
        ];

        // Add one character from each category to ensure diversity
        categories.forEach(category => {
            password += category.charAt(Math.floor(Math.random() * category.length));
        });

        // Fill the rest of the password length with random characters from the charset
        for (let i = password.length; i < length; ++i) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }

        // Shuffle the password to ensure randomness
        password = password.split('').sort(() => 0.5 - Math.random()).join('');

        document.getElementById('signup-password').value = password;
        }

        document.getElementById('signup-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const passwordField = document.getElementById('signup-password');
            const password = passwordField.value;

            // Check if the password is strong
            if (!isStrongPassword(password)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Strong Password Required',
                    text: 'Please use a stronger password.',
                    position: 'top-end',
                    toast: true,
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            // Get reCAPTCHA response
            var recaptchaResponse = grecaptcha.getResponse();
            if (recaptchaResponse.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'reCAPTCHA Error',
                    text: 'Please complete the reCAPTCHA verification.',
                    position: 'top-end',
                    toast: true,
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            var formData = new FormData(this);
            formData.append('action', 'register');
            formData.append('g-recaptcha-response', recaptchaResponse);

            // Show loading alert
            Swal.fire({
                title: 'Processing...',
                html: 'Please wait while we create your account',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
                position: 'top-end',
                toast: true,
                showConfirmButton: false
            });

            fetch('classes/register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Close loading alert
                Swal.close();
                if (data.success) {
                    // Redirect to verify_gmail.php with email as a query parameter
                    const email = formData.get('email');
                    window.location.href = `verify_gmail.php?email=${encodeURIComponent(email)}`;
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: data.message,
                        position: 'top-end',
                        toast: true,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            })
            .catch(error => {
                // Close loading alert
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.',
                    position: 'top-end',
                    toast: true,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        });

        grecaptcha.ready(function () {
            grecaptcha.execute('6LfCPpMqAAAAANJD3dBADWW_bQgoZa5_SXfnrlvK', { action: 'submit' }).then(function (token) {
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <?php require_once('inc/footer.php'); ?>