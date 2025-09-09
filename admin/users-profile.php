<?php 
include 'authentication.php';
checkLogin(); // Call the function to check if the user is logged in
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';
?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="assets/img/user-profile.png" alt="Profile" class="rounded-circle">
                        <h2><?= $username?></h2>
                        <h3><?= $role?></h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                    Profile</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#profile-change-password">Change Password</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">User Name</div>
                                    <div class="col-lg-9 col-md-8"><?= $user?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">
                                        <?= (empty(trim($email)) ? "N/A" : htmlspecialchars($email)) ?>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Role</div>
                                    <div class="col-lg-9 col-md-8"><?= $role?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Mobile Number</div>
                                    <div class="col-lg-9 col-md-8">
                                        <?= (empty(trim($mobile_no)) ? "N/A" : htmlspecialchars($mobile_no)) ?></div>

                                </div>
                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form id="profileEditForm">
                                    <div class="row mb-3">
                                        <label for="username" class="col-md-4 col-lg-3 col-form-label">Username <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="username" type="text" class="form-control" id="username"
                                                value="<?= htmlspecialchars($user) ?>" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="email"
                                                value="<?= htmlspecialchars($email) ?>" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="mobile_no" class="col-md-4 col-lg-3 col-form-label">Mobile
                                            Number</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="mobile_no" type="tel" class="form-control" id="mobile_no"
                                                value="<?= htmlspecialchars($mobile_no) ?>"
                                                placeholder="e.g., +63 912 345 6789">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" name="update_profile" class="btn btn-primary">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                                aria-hidden="true"></span>
                                            Save Changes
                                        </button>
                                    </div>
                                </form><!-- End Profile Edit Form -->
                                <div id="profileEditMsg" class="mt-2"></div>

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-settings">

                                <!-- Settings Form -->
                                <form>

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email
                                            Notifications</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="changesMade"
                                                    checked>
                                                <label class="form-check-label" for="changesMade">
                                                    Changes made to your account
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="newProducts"
                                                    checked>
                                                <label class="form-check-label" for="newProducts">
                                                    Information on new products and services
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="proOffers">
                                                <label class="form-check-label" for="proOffers">
                                                    Marketing and promo offers
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="securityNotify"
                                                    checked disabled>
                                                <label class="form-check-label" for="securityNotify">
                                                    Security alerts
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End settings Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form id="changePasswordForm">
                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">
                                            Current Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password" class="form-control"
                                                id="currentPassword" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">
                                            New Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword" type="password" class="form-control"
                                                id="newPassword" required minlength="6">
                                            <div class="form-text">Password must be at least 6 characters long.</div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">
                                            Re-enter New Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="renewpassword" type="password" class="form-control"
                                                id="renewPassword" required minlength="6">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <!-- Show Password Checkbox -->
                                    <div class="row mb-3">
                                        <div class="col-md-8 offset-md-4 col-lg-9 offset-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="showPasswords">
                                                <label class="form-check-label" for="showPasswords">
                                                    Show Passwords
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" name="change_password" class="btn btn-primary">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                                aria-hidden="true"></span>
                                            Change Password
                                        </button>
                                    </div>
                                </form><!-- End Change Password Form -->

                                <script>
                                const showPasswords = document.getElementById('showPasswords');
                                const passwordFields = [
                                    document.getElementById('currentPassword'),
                                    document.getElementById('newPassword'),
                                    document.getElementById('renewPassword')
                                ];

                                showPasswords.addEventListener('change', () => {
                                    const type = showPasswords.checked ? 'text' : 'password';
                                    passwordFields.forEach(input => input.type = type);
                                });
                                </script>

                                <div id="changePasswordMsg" class="mt-2"></div>

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php 
include './utils/footer.php';
?>
<script>
// Profile Edit AJAX
document.getElementById('profileEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const spinner = submitBtn.querySelector('.spinner-border');
    const msgDiv = document.getElementById('profileEditMsg');

    // Clear previous messages
    msgDiv.innerHTML = '';

    // Validate form
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    // Show loading state
    submitBtn.disabled = true;
    spinner.classList.remove('d-none');

    const formData = new FormData(form);
    formData.append('update_profile', '1');

    fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                msgDiv.innerHTML =
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                    data.message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                setTimeout(() => window.location.reload(), 1500);
            } else {
                msgDiv.innerHTML =
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    data.message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            }
        })
        .catch(() => {
            msgDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                'Server error. Please try again.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        })
        .finally(() => {
            // Hide loading state
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
});

// Change Password AJAX
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const spinner = submitBtn.querySelector('.spinner-border');
    const msgDiv = document.getElementById('changePasswordMsg');
    const newPassword = form.querySelector('#newPassword').value;
    const renewPassword = form.querySelector('#renewPassword').value;

    // Clear previous messages
    msgDiv.innerHTML = '';

    // Validate form
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    // Check if passwords match
    if (newPassword !== renewPassword) {
        msgDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
            'New passwords do not match.' +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        return;
    }

    // Show loading state
    submitBtn.disabled = true;
    spinner.classList.remove('d-none');

    const formData = new FormData(form);
    formData.append('change_password', '1');

    fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                msgDiv.innerHTML =
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                    data.message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                form.reset();
                form.classList.remove('was-validated');
            } else {
                msgDiv.innerHTML =
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    data.message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            }
        })
        .catch(() => {
            msgDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                'Server error. Please try again.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        })
        .finally(() => {
            // Hide loading state
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
});

// Real-time password confirmation validation
document.getElementById('renewPassword').addEventListener('input', function() {
    const newPassword = document.getElementById('newPassword').value;
    const renewPassword = this.value;
    const msgDiv = document.getElementById('changePasswordMsg');

    if (renewPassword && newPassword !== renewPassword) {
        this.setCustomValidity('Passwords do not match');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});

// Clear validation messages when user starts typing
document.querySelectorAll('#profileEditForm input').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        document.getElementById('profileEditMsg').innerHTML = '';
    });
});

document.querySelectorAll('#changePasswordForm input').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        document.getElementById('changePasswordMsg').innerHTML = '';
    });
});
</script>