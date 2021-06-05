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
    

    $course = $pdo->prepare('SELECT * FROM courses WHERE id=:id');
    $values2=[
        'id' => $id
    ];
    $course->execute($values2);
    $edited = $course->fetch();
    
    $courseL = $pdo->prepare('SELECT * FROM staff WHERE username = :username');
    $values2=[
        'username' => $edited['course_leader']
    ];
    $courseL->execute($values2);
    $editedL = $courseL->fetch();

    if(isset($_POST['edit'])){ //Edit admins account details

        $update=$pdo->prepare('UPDATE courses SET course_title = :course_title, course_leader = :course_leader, course_id = :course_id WHERE id = :id');
        $values3=[
            'course_title' => $_POST['course_title'],
            'course_leader' => $_POST['course_leader'],
            'course_id' => $_POST['course_id'],
            'id' => $_POST['id']
        ];
        $update->execute($values3);
        header('Location: managecourses.php');
        
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

    <title>Edit course</title>

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
                    <form action="editcourse.php?id="<?=$id?> method="POST" class="d-flex flex-column"> <!-- Admin account details edit form -->
                        <input type="hidden" name="id" value="<?php echo $edited['id']; ?>" />
                        <label class="h4 text-dark mt-2">Edit <?php echo $edited['course_title'] . ' (' . $edited['course_id'] . ')';?>:</label>

                        <label class="mt-2">Course ID:</label>
                        <input class="col-3" type="text" name="course_id" value="<?=$edited['course_id'];?>" required>

                        <label class="mt-2">Title:</label>
                        <input class="col-3" type="text" name="course_title" value="<?=$edited['course_title'];?>" required>

                        <?php
                        $courseLeader = $pdo->prepare('SELECT * FROM staff WHERE permissions = "staff"');
                        $values = [
                            'username' => $edited['course_leader']
                        ];
                        $courseLeader->execute($values);
                        echo '<label class="mt-2">Course leader:</label><select class="select col-3" name="course_leader">'; //Categories display
                            echo '<option value="' . $editedL['username'] . '">' . $editedL['firstname'] . ' ' . $editedL['lastname'] . ' (' . $editedL['uni_id'] . ')' . '</option>';
                            foreach($courseLeader as $leader){
                                echo '<option value="' . $leader['username'] . '">' . $leader['firstname'] . ' ' . $leader['lastname'] . ' (' . $leader['uni_id'] . ')' . '</option>';
                            }
                        echo '</select>';
                        ?>

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