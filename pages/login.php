<?php

$error = false;

if (isset($_POST['login'])) {
    $dict = [
        'username' => '0',
        'password' => '0',
    ];

    foreach ($dict as $key => $value) {
        if (!isset($_POST[$key]) || trim($_POST[$key]) == '') {
            $error = true;
            echo $key . ' error';
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }

    if ($error == false) {
        if (
            $result = $db->row(
                'SELECT id, password from users WHERE username = ?',
                $dict['username']
            )
        ) {
            $id = $result['id'];
            $password_hash = $result['password'];
            if (password_verify($dict['password'], $password_hash)) {
                session_destroy();
                session_start();
                $_SESSION['user_id'] = $id;
                header('Location: index.php');
            } else {
                echo 'Login error';
            }
        } else {
            echo 'Login error';
        }
    }
}
?>

<body class="bg-default">
    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-gradient-primary py-7 py-lg-7 pt-lg-6">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-2 px-5">
                            <h1 class="text-white">Welcome to ChanChan's Point of Sale System</h1>
                            <p class="text-lead text-white">Please enter your username and email to log into the system.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--9 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary border-0 mb-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center text-muted mb-4">
                                <small>Sign in with username and password</small>
                            </div>
                            <form role="form" action="" method="post">
                                <fieldset>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                            </div>
                                            <input class="form-control" placeholder="Username" name="username"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i
                                                        class="ni ni-lock-circle-open"></i></span>
                                            </div>
                                            <input class="form-control" placeholder="Password" name="password"
                                                type="password">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="login" class="btn btn-primary my-4">Sign in</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready( function () {
            window.scroll(0, 100);
        })
    </script>