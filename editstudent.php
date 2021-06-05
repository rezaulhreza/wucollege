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

    if(empty($_SESSION['loggedin']) || $login['permissions']!="admin"){
        header('Location: index.php');
    }

    $student = $pdo->prepare('SELECT * FROM students WHERE id=:id');
    $values2=[
        'id' => $id
    ];
    $student->execute($values2);
    $edit = $student->fetch();

    if(isset($_POST['edit'])){ //Edit admins account details

        $update=$pdo->prepare('UPDATE students SET firstname = :firstname, lastname = :lastname, year = :year, dob = :dob, mobile = :mobile, email = :email, guardianmobile = :guardianmobile, guardianemail = :guardianemail, course_id = :course_id, personaltutor = :personaltutor, status = :status, reason = :reason, nonterm_address = :nonterm_address, uni_id = :uni_id, startdate = :startdate  WHERE id = :id');
        $values3=[
            'id' => $id,
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'year' => $_POST['year'],
            'dob' => $_POST['dob'],
            'mobile' => $_POST['mobile'],
            'email' => $_POST['email'],
            'guardianmobile' => $_POST['guardianmobile'],
            'guardianemail' => $_POST['guardianemail'],
            'course_id' => $_POST['course_id'],
            'personaltutor' => $_POST['personaltutor'],
            'status' => $_POST['status'],
            'reason' => $_POST['reason'],
            'nonterm_address' => $_POST['nonterm_address'],
            'uni_id' => $_POST['uni_id'],
            'startdate' => $_POST['startdate']
        ];
        $update->execute($values3);
        header('Location: newstudent.php');
        
    } else {
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit student</title>

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
                    <form action="editstudent.php?id="<?=$id?> method="POST" class="d-flex flex-column"> <!-- Admin account details edit form -->
                        <input type="hidden" name="id" value="<?=$edit['id']; ?>" />
                        <label class="h4 text-dark mt-2">Edit <?=$edit['firstname'] . ' ' . $edit['lastname'] . ' (' . $edit['uni_id'] . ')';?>:</label>

                        <label class="mt-2">University ID:</label>
                        <input class="col-3" type="text" name="uni_id" value="<?=$edit['uni_id']?>" required>

                        <label class="mt-2">First Name:</label>
                        <input class="col-3" type="text" pattern="[A-Za-z]{1,55}" name="firstname" value="<?=$edit['firstname']?>" required>
                            
                        <label class="mt-2">Last Name:</label>
                        <input class="col-3" type="text" pattern="[A-Za-z]{1,55}" name="lastname" value="<?=$edit['lastname']?>" required>

                        <label class="mt-2">Email:</label>
                        <input class="col-3" type="email" pattern="[a-z0-9._]+@[a-z0-9.-]+\.[a-z]{2,}$" name="email" value="<?=$edit['email']?>" required>

                        <label class="mt-2">Address:</label>
                        <input class="col-6" type="text" name="address" value="<?=$edit['address']?>" required>

                        <label class="mt-2">Non-term address:</label>
                        <input class="col-6" type="text" name="nonterm_address" value="<?=$edit['nonterm_address']?>" required>

                        <label class="mt-2">Mobile:</label>
                        <input class="col-3" type="text" name="mobile" value="<?=$edit['mobile']?>" required>

                        <label class="mt-2">Date of birth:</label>
                        <input class="col-3" type="date" name="dob" value="<?=$edit['dob']?>" required>

                        <?php 
                            echo '<label class="mt-2">Year:</label><select class="select col-3" name="year" value="' . $edit['year'] . '">'; //Categories display
                                echo '<option value="1">1</option>';
                                echo '<option value="2">2</option>';
                                echo '<option value="3">3</option>';
                            echo '</select>';
                        ?>

                        <label class="mt-2">Guardian mobile:</label>
                        <input class="col-3" type="text" name="guardianmobile" value="<?=$edit['guardianmobile']?>" required>

                        <label class="mt-2">Guardian email:</label>
                        <input class="col-3" type="text" name="guardianemail" value="<?=$edit['guardianemail']?>" required>

                        <?php 
                            echo '<label class="mt-2">Status:</label><select class="select col-3" name="status" value="' . $edit['status'] . '">'; //Categories display
                                echo '<option value="live">Live</option>';
                                echo '<option value="dormant">Dormant</option>';
                            echo '</select>';

                            echo '<label class="mt-2">Reason:</label><select class="select col-3" name="reason" value="' . $edit['reason'] . '">'; //Categories display
                                echo '<option value="none">None</option>';
                                echo '<option value="withdrawn">Withdrawn</option>';
                                echo '<option value="terminated">Terminated</option>';
                            echo '</select>';

                            $courses = $pdo->prepare('SELECT * FROM courses');
                            $courses->execute();
                            echo '<label class="mt-2">Course ID:</label><select class="select col-3" name="course_id" value="' . $edit['course_id'] . '">'; //Categories display
                                foreach($courses as $course){
                                    echo '<option value="' . $course['course_id'] . '">' . $course['course_title'] . ' (' . $course['course_id'] . ')' . '</option>';
                                }
                            echo '</select>';
                        
                            $pt = $pdo->prepare('SELECT * FROM staff WHERE permissions = "staff"');
                            $pt->execute();
                            echo '<label class="mt-2">Personal tutor:</label><select class="select col-3" name="personaltutor" value="' . $edit['personaltutor'] . '">'; //Categories display
                                foreach($pt as $tutor){
                                    echo '<option value="' . $tutor['firstname'] . ' ' . $tutor['lastname'] . '">' . $tutor['firstname'] . ' ' . $tutor['lastname'] . ' (' . $tutor['uni_id'] . ')' . '</option>';
                                }
                            echo '</select>';

                        ?>
                        <label class="mt-2">Start date:</label>
                        <input class="col-3" type="date" name="startdate" value="<?=$edit['startdate']?>" required>

                        <input class="col-1 mt-2 mb-4" type="submit" name="edit" value="Change"/>
                    </form>
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

    <?php require 'logoutmodal.php'; ?>

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

<?php } ?>