<?php
session_start();
require 'configuration.php';
$session = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values=[
        'id' => $_SESSION['loggedin']
    ];
    $session->execute($values);
    $login = $session->fetch();
if(empty($_SESSION['loggedin']) || $login['permissions']!="admin"){
    header('Location: index.php');
}

if(isset($_POST['newstudent'])) { //Add new admins

    $stmt = $pdo->prepare('INSERT INTO students (firstname, lastname, year, dob, mobile, email, guardianmobile, guardianemail, course_id, personaltutor, status, reason, nonterm_address, uni_id, startdate, address) 
    VALUES (:firstname, :lastname, :year, :dob, :mobile, :email, :guardianmobile, :guardianemail, :course_id, :personaltutor, :status, :reason, :nonterm_address, :uni_id, :startdate, :address)');
    $values = [
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
        'startdate' => $_POST['startdate'],
        'address' => $_POST['address']
    ];
    $stmt->execute($values);
    header('Location: newstudent.php');
    
} else if(isset($_POST['edit'])){
    header('Location: editstudent.php?id=' . $_POST['id']);

}else if(isset($_POST['deletestudent'])){ //Delete admin accounts
    if($_POST['check']=="DELETE"){
        $delete = $pdo->prepare('DELETE FROM students WHERE id = :id LIMIT 1 ');
        $values= [
            'id' => $_POST['id']
        ];
        $delete->execute($values);
        header('Location: newstudent.php');
    } else {
        header('Location: error.php');
    }
    

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

    <title>Manage students</title>

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
        <div id="content-wrapper">

            <!-- Main Content -->
            <div id="content">
                <?php 
                    require 'topbar.php';
                ?>
                <div class="container-fluid file-color mainpage d-flex flex-row">
                <form  action="newstudent.php" method="POST" class="d-flex flex-column col-6"> <!-- Admin creation form -->
                    <div class="row-6"><h3 class="bltext pb-3 pt-3">Create new student account</h3></div>

                    <label class="mt-2">University ID:</label>
                    <input class="col-3" type="text" name="uni_id" required>

                    <label class="mt-2">First Name:</label>
                    <input class="col-3" type="text" pattern="[A-Za-z]{1,55}" name="firstname" required>
                        
                    <label class="mt-2">Last Name:</label>
                    <input class="col-3" type="text" pattern="[A-Za-z]{1,55}" name="lastname" required>

                    <label class="mt-2">Email:</label>
                    <input class="col-3" type="email" pattern="[a-z0-9._]+@[a-z0-9.-]+\.[a-z]{2,}$" name="email" required>

                    <label class="mt-2">Address:</label>
                    <input class="col-8" type="text" name="address" required>

                    <label class="mt-2">Non-term address:</label>
                    <input class="col-8" type="text" name="nonterm_address" required>

                    <label class="mt-2">Mobile:</label>
                    <input class="col-3" type="text" name="mobile" required>

                    <label class="mt-2">Date of birth:</label>
                    <input class="col-3" type="date" name="dob" required>

                    <?php 
                        echo '<label class="mt-2">Year:</label><select class="select col-3" name="year">'; //Categories display
                            echo '<option value="1">1</option>';
                            echo '<option value="2">2</option>';
                            echo '<option value="3">3</option>';
                        echo '</select>';
                    ?>

                    <label class="mt-2">Guardian mobile:</label>
                    <input class="col-3" type="text" name="guardianmobile" required>

                    <label class="mt-2">Guardian email:</label>
                    <input class="col-3" type="text" name="guardianemail" required>

                    <?php 
                        echo '<label class="mt-2">Status:</label><select class="select col-3" name="status">'; //Categories display
                            echo '<option value="live">Live</option>';
                            echo '<option value="dormant">Dormant</option>';
                        echo '</select>';

                        echo '<label class="mt-2">Reason:</label><select class="select col-3" name="reason">'; //Categories display
                            echo '<option value="none">None</option>';
                            echo '<option value="withdrawn">Withdrawn</option>';
                            echo '<option value="terminated">Terminated</option>';
                        echo '</select>';

                        $courses = $pdo->prepare('SELECT * FROM courses');
                        $courses->execute();
                        echo '<label class="mt-2">Course ID:</label><select class="select col-5" name="course_id">'; //Categories display
                            foreach($courses as $course){
                                echo '<option value="' . $course['course_id'] . '">' . $course['course_title'] . ' (' . $course['course_id'] . ')' . '</option>';
                            }
                        echo '</select>';
                    
                        $pt = $pdo->prepare('SELECT * FROM staff WHERE permissions = "staff"');
                        $pt->execute();
                        echo '<label class="mt-2">Personal tutor:</label><select class="select col-4" name="personaltutor">'; //Categories display
                            foreach($pt as $tutor){
                                echo '<option value="' . $tutor['firstname'] . ' ' . $tutor['lastname'] . '">' . $tutor['firstname'] . ' ' . $tutor['lastname'] . ' (' . $tutor['uni_id'] . ')' . '</option>';
                            }
                        echo '</select>';

                    ?>
                    <label class="mt-2">Start date:</label>
                    <input class="col-3" type="date" name="startdate" required>

                    <input class="col-3 mt-2 mb-4" type="submit" value="Create student account" name="newstudent" >
                </form>

                <form action="newstudent.php" method="POST" class="d-flex flex-column col-3">
                <div class="row-3"><h3 class="bltext pb-3 pt-3">Edit existing students</h3></div>
                    <?php
                    $select = $pdo->prepare('SELECT * FROM students');
                    $select->execute();	                
                    echo '<label>Edit student:</label><select name="id">';
                    foreach ($select as $data) {
                        echo '<option value="' . $data['id'] . '">' . $data['firstname'] . ' ' . $data['lastname'] . ' (' . $data['uni_id'] . ')' . '</option>';
                    }
                    echo '<input class="mt-2" type="submit" name="edit" value="Edit">';
                    echo '</select>';
                    ?>
                </form>

                <form  action="newstudent.php" method="POST" class="d-flex flex-column col-3">
                    <?php
                    $deletestudents = $pdo->prepare('SELECT * FROM students'); //Display admin accounts to allow deletion
                    $deletestudents->execute();
                        echo '<div><h3 class="bltext pb-3 pt-3">Delete student accounts</h3></div>';
                        echo '<label>Delete student:</label><select name="id">';
                        foreach ($deletestudents as $del) {
                            echo '<option value="' . $del['id'] . '">' . $del['firstname'] . ' ' . $del['lastname'] . ' (' . $del['uni_id'] . ')' . '</option>';
                        }
                        echo '<input class="mt-2" type="text" pattern="DELETE" name="check" placeholder="Type DELETE to confirm" required>';
                        echo '</select>';
                        echo '<input class="mt-2 mb-2" type="submit" name="deletestudent" value="Delete">';
                    ?>
                </form>
                <?php }?>
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