<?php
session_start();
require 'configuration.php';
$today_date = date('Y-m-d');
$session = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values=[
        'id' => $_SESSION['loggedin']
    ];
    $session->execute($values);
    $login = $session->fetch();
    if(empty($_SESSION['loggedin']) || $login['permissions']!="admin"){
        header('Location: index.php');
    }

if(isset($_POST['newtimetable'])) { //Add new admins

    $stmt = $pdo->prepare('INSERT INTO timetables (module_id, room, date, time, tutor_id) 
    VALUES (:module_id, :room, :date, :time, :tutor_id)');
    $values = [
        'module_id' => $_POST['module_id'],
        'room' => $_POST['room'],
        'date' => $_POST['date'],
        'time' => $_POST['time'],
        'tutor_id' => $_POST['tutor_id']
    ];
    $stmt->execute($values);
    header('Location: managetimetable.php');
    
} else if(isset($_POST['edit'])){
    header('Location: edittimetable.php?id=' . $_POST['id']);

}else if(isset($_POST['deletetimetable'])){ //Delete admin accounts
    if($_POST['check']=="DELETE"){
        $delete = $pdo->prepare('DELETE FROM timetables WHERE id = :id LIMIT 1 ');
        $values= [
            'id' => $_POST['id']
        ];
        $delete->execute($values);
        header('Location: managetimetable.php');
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

    <title>Manage timetables</title>

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
                <form  action="managetimetable.php" method="POST" class="d-flex flex-column col-4"> <!-- Admin creation form -->
                <div class="row-6"><h3 class="bltext pb-3 pt-3">New slot</h3></div>

                <?php
                    $stmt = $pdo->prepare('SELECT * FROM modules');
                    $stmt->execute();
                    $name = $stmt->fetch();
                    echo '<label class="mt-2">Module ID:</label><select class="select col-8" name="module_id">';
                    foreach ($stmt as $details){
                        echo '<option value="' . $details['module_code'] . '">' . $details['module_title'] . ' (' . $details['module_code'] . ')</option>';
                    }
                    echo '</select>';
                ?>

                <?php
                    $stmt = $pdo->prepare('SELECT * FROM staff WHERE permissions="staff"');
                    $stmt->execute();
                    $name = $stmt->fetch();
                    echo '<label class="mt-2">Tutor ID:</label><select class="select col-8" name="tutor_id">';
                    foreach ($stmt as $details){
                        echo '<option value="' . $details['uni_id'] . '">' . $details['firstname'] . ' ' . $details['lastname'] . ' (' . $details['uni_id'] . ')</option>';
                    }
                    echo '</select>';
                ?>
                        
                <?php
                    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE type="class"');
                    $stmt->execute();
                    $name = $stmt->fetch();
                    echo '<label class="mt-2">Room ID:</label><select class="select col-8" name="room">';
                    foreach ($stmt as $details){
                        echo '<option value="' . $details['room_id'] . '">' . $details['room_id'] . ' - Capacity: ' . $details['cap'] . '</option>';
                    }
                    echo '</select>';
                ?>

                    <label class="mt-2">Date:</label>
                    <input class="col-8" type="date" name="date" min="<?=$today_date?>" required>

                    <label class="mt-2">Time:</label>
                    <input class="col-8" type="time" name="time" required>

                    <input class="col-4 mt-2 mb-4" type="submit" value="New slot" name="newtimetable" >
                </form>

                <form action="managetimetable.php" method="POST" class="d-flex flex-column col-4">
                <div class="row-3"><h3 class="bltext pb-3 pt-3">Edit existing slot</h3></div>
                    <?php
                    $select = $pdo->prepare('SELECT * FROM timetables WHERE date >= :date');
                    $values = [
                        'date' => $today_date
                    ];
                    $select->execute($values);
                    echo '<label>Edit slot:</label><select name="id" class="maxHeight30 col-8">';
                    foreach ($select as $data) {
                        $course = $pdo->prepare('SELECT * FROM staff WHERE uni_id = :uni_id');
                        $values = [
                            'uni_id' => $data['tutor_id']
                        ];
                        $course->execute($values);
                        $courseTutor = $course->fetch();
                        echo '<option value="' . $data['id'] . '">' . $data['room'] . ' - ' . $data['date'] . '/' . $data['time'] . ' (' . $data['module_id'] . ' - ' . $courseTutor['firstname'] . ' ' . $courseTutor['lastname'] . ')' . '</option>';
                    }
                    echo '<input class="mt-2 col-4 maxHeight30" type="submit" name="edit" value="Edit">';
                    echo '</select>';
                    ?>
                </form>

                <form  action="managetimetable.php" method="POST" class="d-flex flex-column col-4">
                    <?php
                    $deletetimetable = $pdo->prepare('SELECT * FROM timetables WHERE date >= :date'); //Display admin accounts to allow deletion
                    $values = [
                        'date' => $today_date
                    ];
                    $deletetimetable->execute($values);
                        echo '<div><h3 class="bltext pb-3 pt-3">Delete slots</h3></div>';
                        echo '<label>Delete slot:</label><select name="id" class="maxHeight30 col-8">';
                        foreach ($deletetimetable as $del) {
                            $course = $pdo->prepare('SELECT * FROM staff WHERE uni_id = :uni_id');
                            $values = [
                                'uni_id' => $del['tutor_id']
                            ];
                            $course->execute($values);
                            $courseTutor = $course->fetch();
                            echo '<option value="' . $del['id'] . '">' . $del['room'] . ' - ' . $data['date'] . '/' . $del['time'] . ' (' . $del['module_id'] . ' - ' . $courseTutor['firstname'] . ' ' . $courseTutor['lastname'] . ')' . '</option>';
                        }
                        echo '<input class="mt-2 maxHeight30 col-8" type="text" pattern="DELETE" name="check" placeholder="Type DELETE to confirm" required>';
                        echo '</select>';
                        echo '<input class="mt-2 mb-2 maxHeight30 col-4" type="submit" name="deletetimetable" value="Delete">';
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