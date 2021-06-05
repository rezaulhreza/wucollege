<?php
    require 'configuration.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Woodlands University College</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="login">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                        <img class="imgSize" src="img/woodlands.png" />
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">WOODLANDS UNIVERSITY COLLEGE</h1>
                                    </div>
                                    <?php
                                    if (isset($_POST['submit'])) {
                                        $stmt = $pdo->prepare('SELECT * FROM staff WHERE username = :username');
                                        $values = [
                                            'username' => $_POST['username']
                                        ];
                                        $stmt->execute($values);
                                        $data = $stmt->fetch();
                                        $pass = password_verify($_POST['password'], $data['password']);
                                        if ($stmt->rowCount() > 0 && $pass){
                                            $user = $data['id']; 
                                            $_SESSION['loggedin'] = $user;
                                            header('Location: index.php');
                                        } else {
                                            echo 'Sorry, your username and password could not be found. Go to <a href = "login.php" class="noStyle link">login</a> page';
                                        }
                                    } else { ?>
                                    <form action="login.php" method="POST" class="d-flex flex-column">
                                        <label class="m-2">Username: </label><input class="form-control form-control-user" type="username" pattern="[A-Za-z0-9]{1,25}" name="username" required/>
                                        <label class="m-2">Password: </label><input class="form-control form-control-user" type="password" name="password" required />
                                        <input class="mt-2" type="submit" name="submit" value="Log In" />
                                    </form>
                                    <?php } ?>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>