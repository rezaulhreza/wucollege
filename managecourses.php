<?php
session_start();
require 'configuration.php';
$date_posted=date('Y/m/d h:i:s');
$session = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values=[
        'id' => $_SESSION['loggedin']
    ];
    $session->execute($values);
    $login = $session->fetch();
    if(empty($_SESSION['loggedin']) || $login['permissions']!="admin"){
        header('Location: index.php');
    }

if(isset($_POST['newcourse'])) { //Add new admins

    $stmt = $pdo->prepare('INSERT INTO courses (course_title, course_leader, course_id) 
    VALUES (:course_title, :course_leader, :course_id)');
    $values = [
        'course_title' => $_POST['course_title'],
        'course_leader' => $_POST['course_leader'],
        'course_id' => $_POST['course_id']
    ];
    $stmt->execute($values);
    header('Location: managecourses.php');
    
} else if(isset($_POST['edit'])){
    header('Location: editcourse.php?id=' . $_POST['id']);

}else if(isset($_POST['deletecourse'])){ //Delete admin accounts
    if($_POST['check']=="DELETE"){
        $delete = $pdo->prepare('DELETE FROM courses WHERE id = :id LIMIT 1 ');
        $values= [
            'id' => $_POST['id']
        ];
        $delete->execute($values);
        header('Location: managecourses.php');
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

    <title>Manage courses</title>

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
                <form  action="managecourses.php" method="POST" class="d-flex flex-column col-4"> <!-- Admin creation form -->
                <div class="row-6"><h3 class="bltext pb-3 pt-3">New course</h3></div>

                    <label class="mt-2">Course ID:</label>
                    <input class="col-8" type="text" name="course_id" required>

                    <label class="mt-2">Title:</label>
                    <input class="col-8" type="text" name="course_title" required>

                    <?php
                    $courseLeader = $pdo->prepare('SELECT * FROM staff WHERE role = "ML/PT" OR role = "TC"');
                    $courseLeader->execute();
                    echo '<label class="mt-2">Course leader:</label><select class="select col-8" name="course_leader">'; //Categories display
                        foreach($courseLeader as $leader){
                            echo '<option value="' . $leader['username'] . '">' . $leader['firstname'] . ' ' . $leader['lastname'] . ' (' . $leader['uni_id'] . ')' . '</option>';
                        }
                    echo '</select>';
                    ?>

                    <input class="col-4 mt-2 mb-4" type="submit" value="New course" name="newcourse" >
                </form>


                <form action="managecourses.php" method="POST" class="d-flex flex-column col-4">
                <div class="row-3"><h3 class="bltext pb-3 pt-3">Edit existing courses</h3></div>
                    <?php
                    $select = $pdo->prepare('SELECT * FROM courses');
                    $select->execute();	                
                    echo '<label>Edit course:</label><select name="id" class="col-8 maxHeight30">';
                    foreach ($select as $data) {
                        echo '<option value="' . $data['id'] . '">' . $data['course_title'] . ' (' . $data['course_id'] . ')' . '</option>';
                    }
                    echo '<input class="mt-2 col-4 maxHeight30" type="submit" name="edit" value="Edit">';
                    echo '</select>';
                    ?>
                </form>

                <form  action="managecourses.php" method="POST" class="d-flex flex-column col-4">
                    <?php
                    $deletecourse = $pdo->prepare('SELECT * FROM courses'); //Display admin accounts to allow deletion
                    $deletecourse->execute();
                        echo '<div><h3 class="bltext pb-3 pt-3">Delete courses</h3></div>';
                        echo '<label>Delete course:</label><select name="id" class="col-8 maxHeight30">';
                        foreach ($deletecourse as $del) {
                            echo '<option value="' . $del['id'] . '">' . $del['course_title'] . ' (' . $del['course_id'] . ')' . '</option>';
                        }
                        echo '<input class="mt-2 col-8 maxHeight30" type="text" pattern="DELETE" name="check" placeholder="Type DELETE to confirm" required>';
                        echo '</select>';
                        echo '<input class="mt-2 mb-2 col-4 maxHeight30" type="submit" name="deletecourse" value="Delete">';
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