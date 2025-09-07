<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Admin - Forgot Password</title>
    <link href="../assets/img/favicon.ico" rel="icon" />
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="../assets/css/main.css" rel="stylesheet" />
</head>

<body class="starter-page-page">
    <?php include "../alert.php"; ?>
    <main class="main">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="mb-3">Reset admin password</h4>
                            <p class="text-muted">Enter your registered mobile number to receive an OTP via SMS. After receiving the code, proceed to verification.</p>

                            <form method="POST" action="forgot_password_code.php" class="mb-2">
                                <input type="hidden" name="action" value="request_otp" />
                                <div class="mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" class="form-control" name="mobile"
                                        value="<?php echo htmlspecialchars($_GET['mobile'] ?? '', ENT_QUOTES); ?>"
                                        placeholder="e.g. 09XXXXXXXXX or +639XXXXXXXXX" required />
                                </div>
                                <button type="submit" class="btn btn-primary">Send OTP</button>
                            </form>

                            <div class="mt-3">
                                <a href="verify_otp.php" class="btn btn-link">Already have an OTP? Verify here</a>
                            </div>
                            <div class="mt-1">
                                <a href="../index.php" class="btn btn-link">Back to Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
