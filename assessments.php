<?php
    session_start();
    require 'configuration.php';
    $id = $_REQUEST['id'];
    $session = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values=[
        'id' => $_SESSION['loggedin']
    ];
    $session->execute($values);
    $login = $session->fetch();

    $student = $pdo->prepare('SELECT course_id FROM students WHERE id = :id');
    $values = [
        'id' => $id
    ];
    $student->execute($values);
    $current = $student->fetch();
    if(empty($_SESSION['loggedin']) || ($login['permissions']!="staff" && $login['permissions']!="admin")){
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Assessments</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require 'sidebar.php' ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php 
                    require 'topbar.php';
                ?>
                <a class="col-3 bg-white round text-center" href="manageassessments.php"><i class="fas fa-fw fa-plus pb-3"></i>New assessment</a>
                <div class="container-fluid file-color mainpage d-flex row">
                    <?php
                        $stmt = $pdo->prepare('SELECT * FROM assessments JOIN modules ON assessments.module_id = modules.module_code WHERE modules.course_id = :id AND active = "1"');
                        $values = [
                            'id' => $current['course_id']
                        ];
                        $stmt->execute($values);
                        foreach($stmt as $assessment){
                            echo '<h4 class="col-12 bg-white round m-2">' . $assessment['module_title'] . ' (' . $assessment['module_code'] . ') - Submit by ' . $assessment['submission_date'] . '<h4>';
                            echo '<p class="col-12 m-2">' . $assessment['description'] .  '</p>';
                        }
                    ?>
                </div>
                <?php require 'footer.php' ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php require 'logoutmodal.php'?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>