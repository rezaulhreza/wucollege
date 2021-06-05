<?php
    session_start();
    require 'configuration.php';
    $id = $_REQUEST['id'];
    $session = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values1=[
        'id' => $_SESSION['loggedin']
    ];
    $session->execute($values1);
    $login = $session->fetch();
    if(empty($_SESSION['loggedin']) || ($login['permissions']!="staff" && $login['permissions']!="admin")){
        header('Location: index.php');
    }

    $students = $pdo->prepare('SELECT * FROM students JOIN courses ON students.course_id = courses.course_id WHERE students.id=:id');
    $values2=[
        'id' => $id
    ];
    $students->execute($values2);
    $student = $students->fetch();

    $courseLeader = $pdo->prepare('SELECT * FROM courses JOIN staff ON courses.course_leader = staff.username WHERE courses.course_id = :id');
    $values = [
        'id' => $student['course_id']
    ];
    $courseLeader->execute($values);
    $leader = $courseLeader->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WUC Records Management System</title>

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
                <div class="container-fluid file-color mainpage">
                <div class="row-12"><h3 class="bltext pb-3 pt-3">Student Details</h3></div>
                <div class="d-flex justify-content-between row-12">
                    <ul class="col-5">
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Student ID - <?= $student['uni_id'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Firstname - <?= $student['firstname'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Lastname - <?= $student['lastname'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Address - <?= $student['address'] ?></p></li>
                    </ul>
                    <ul class="col-4">
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Date of birth - <?= $student['dob'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Non-term - <?= $student['address'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Persoanl Tutor - <?= $student['personaltutor'] ?></p></li>
                    </ul>
                    <ul class="col-3">
                        <img src="img/undraw_profile_2.svg" class="profile" />
                    </ul>
                </div>
                <div class="row-12"><h3 class="bltext pb-3">Contact Details</h3></div>
                <div class="d-flex justify-content-between row-12">
                    <ul class="col-5">
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Student Mobile Number - <?= $student['mobile'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Guardian Mobile Number - <?= $student['guardianmobile'] ?></p></li>
                    </ul>
                    <ul class="col-5">
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Student Email Address - <?= $student['email'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Guardian Email Address - <?= $student['guardianemail'] ?></p></li>
                    </ul>
                    <ul class="col-2"></ul>
                </div>
                <div class="row-12"><h3 class="bltext pb-3 pt-5">Course Details</h3></div>
                <div class="d-flex justify-content-between row-12">
                    <ul class="col-5">
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Course Title - <?= $student['course_title'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Course Code - <?= $student['course_id'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Course Leader - <?= $leader['firstname'] . ' ' . $leader['lastname'] ?></p></li>
                    </ul>
                    <ul class="col-5">
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Start Date - <?= $student['startdate'] ?></p></li>
                        <li class="row"><p class="bg-white pl-5 pr-5 round">Year - <?= $student['year'] ?></p></li>
                    </ul>
                    <ul class="col-2"></ul>
                </div>
                <div class="d-flex justify-content-around row-12 p-4">
                        <a class="col-3 bg-white round text-center" href="#">Placement Details</a>
                        <a class="col-3 bg-white round text-center" href="assessments.php?id=<?=$id?>">Assessments</a>
                        <a class="col-3 bg-white round text-center" href="grades.php?id=<?=$id?>">Grades</a>
                </div>
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