<?php
    session_start();
    require 'configuration.php';
    $id = $_REQUEST['id'];
    $date_posted=date('Y/m/d h:i:s');
    $session = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values1=[
        'id' => $_SESSION['loggedin']
    ];
    $session->execute($values1);
    $login = $session->fetch();

    if(empty($_SESSION['loggedin']) || $login['permissions']!="admin"){
        header('Location: index.php');
    }

    $timetable = $pdo->prepare('SELECT * FROM timetables WHERE id=:id');
    $values2=[
        'id' => $id
    ];
    $timetable->execute($values2);
    $edited = $timetable->fetch();

    $course = $pdo->prepare('SELECT * FROM staff WHERE uni_id = :uni_id');
    $values = [
        'uni_id' => $edited['tutor_id']
    ];
    $course->execute($values);
    $courseTutor = $course->fetch();

    if(isset($_POST['edit'])){ //Edit admins account details

        $update=$pdo->prepare('UPDATE timetables SET module_id = :module_id, room = :room, date = :date, time = :time, tutor_id = :tutor_id WHERE id = :id');
        $values3=[
            'module_id' => $_POST['module_id'],
            'room' => $_POST['room'],
            'date' => $_POST['date'],
            'time' => $_POST['time'],
            'tutor_id' => $_POST['tutor_id'],
            'id' => $_POST['id']
        ];
        $update->execute($values3);
        header('Location: managetimetable.php');
        
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

    <title>Edit slot</title>

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
                    <form action="edittimetable.php?id="<?=$id?> method="POST" class="d-flex flex-column"> <!-- Admin account details edit form -->
                        <input type="hidden" name="id" value="<?php echo $edited['id']; ?>" />
                        <label class="h4 text-dark mt-2">Edit <?php echo $edited['room'] . ' - ' . $edited['date'] . '/' . $edited['time'] . ' (' . $edited['module_id'] . ' - ' . $courseTutor['firstname'] . ' ' . $courseTutor['lastname'] . ')';?>:</label>

                        <?php
                            $stmt = $pdo->prepare('SELECT * FROM modules');
                            $stmt->execute();
                            $name = $stmt->fetch();
                            $editModule = $pdo->prepare('SELECT * FROM modules WHERE module_code = :module_id');
                            $values = [
                                'module_id' => $edited['module_id']
                            ];
                            $editModule->execute($values);
                            $em = $editModule->fetch();
                            echo '<label class="mt-2">Module ID:</label><select class="select col-3" name="module_id">';
                            echo '<option value="' . $em['module_code'] . '">' . $em['module_title'] . ' (' . $em['module_code'] . ')</option>';
                            foreach ($stmt as $details){
                                echo '<option value="' . $details['module_code'] . '">' . $details['module_title'] . ' (' . $details['module_code'] . ')</option>';
                            }
                            echo '</select>';
                        ?>

                        <?php
                            $stmt = $pdo->prepare('SELECT * FROM staff WHERE permissions="staff"');
                            $stmt->execute();
                            $name = $stmt->fetch();
                            $editTutor = $pdo->prepare('SELECT * FROM staff WHERE uni_id = :uni_id');
                            $values = [
                                'uni_id' => $edited['tutor_id']
                            ];
                            $editTutor->execute($values);
                            $et = $editTutor->fetch();
                            echo '<label class="mt-2">Tutor ID:</label><select class="select col-3" name="tutor_id">';
                            echo '<option value="' . $et['uni_id'] . '">' . $et['firstname'] . ' ' . $et['lastname'] . ' (' . $et['uni_id'] . ')</option>';
                            foreach ($stmt as $details){
                                echo '<option value="' . $details['uni_id'] . '">' . $details['firstname'] . ' ' . $details['lastname'] . ' (' . $details['uni_id'] . ')</option>';
                            }
                            echo '</select>';
                        ?>
                                
                        <?php
                            $stmt = $pdo->prepare('SELECT * FROM rooms WHERE type="class"');
                            $stmt->execute();
                            $name = $stmt->fetch();
                            $editRoom = $pdo->prepare('SELECT * FROM rooms WHERE room_id = :room_id');
                            $values = [
                                'room_id' => $edited['room']
                            ];
                            $editRoom->execute($values);
                            $er = $editRoom->fetch();
                            echo '<label class="mt-2">Room ID:</label><select class="select col-3" name="room">';
                            echo '<option value="' . $er['room_id'] . '">' . $er['room_id'] . ' - Capacity: ' . $er['cap'] . '</option>';
                            foreach ($stmt as $details){
                                echo '<option value="' . $details['room_id'] . '">' . $details['room_id'] . ' - Capacity: ' . $details['cap'] . '</option>';
                            }
                            echo '</select>';
                        ?>

                            <label class="mt-2">Date:</label>
                            <input class="col-3" type="date" name="date" value="<?=$edited['date'];?>" required>

                            <label class="mt-2">Time:</label>
                            <input class="col-3" type="time" name="time" value="<?=$edited['time'];?>" required>

                        <input class="col-2 mt-2 mb-4" type="submit" name="edit" value="Change"/>
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