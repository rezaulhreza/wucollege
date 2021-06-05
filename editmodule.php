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
    
    $module = $pdo->prepare('SELECT * FROM modules WHERE module_id=:id');
    $values2=[
        'id' => $id
    ];
    $module->execute($values2);
    $edited = $module->fetch();

    $course = $pdo->prepare('SELECT * FROM courses WHERE course_id=:id');
    $values=[
        'id' => $edited['course_id']
    ];
    $course->execute($values);
    $selected = $course->fetch();
    

    if(isset($_POST['edit'])){ //Edit admins account details

        $update=$pdo->prepare('UPDATE modules SET module_title = :module_title, module_code = :module_code, level = :level, points = :points, course_id = :course_id WHERE module_id = :module_id');
        $values=[
            'module_title' => $_POST['module_title'],
            'module_code' => $_POST['module_code'],
            'level' => $_POST['level'],
            'points' => $_POST['points'],
            'course_id' => $_POST['course_id'],
            'module_id' => $_POST['module_id']
        ];
        $update->execute($values);
        header('Location: managemodules.php');
        
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

    <title>Edit module</title>

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
                    <form action="editmodule.php?id="<?=$id?> method="POST" class="d-flex flex-column"> <!-- Admin account details edit form -->
                        <input type="hidden" name="module_id" value="<?php echo $edited['module_id']; ?>" />
                        <label class="h4 text-dark mt-2">Edit <?php echo $edited['module_title'] . ' (' . $edited['module_code'] . ')';?>:</label>

                        <label class="mt-2">Module code:</label>
                        <input class="col-3" type="text" name="module_code" value="<?=$edited['module_code'];?>" required>

                        <label class="mt-2">Title:</label>
                        <input class="col-3" type="text" name="module_title" value="<?=$edited['module_title'];?>" required>

                        <label class="mt-2">Year:</label>
                        <select class="select col-3" name="level">
                            <?php
                                echo '<option value="' . $edited['level'] . '">Year ' . $edited['level'] . '</option>';
                                echo '<option value="1">Year 1</option>';
                                echo '<option value="2">Year 2</option>';
                                echo '<option value="3">Year 3</option>';
                            ?>
                        </select>

                        <label class="mt-2">Points:</label>
                        <select class="select col-3" name="points">;
                            <?php
                                echo '<option value="' . $edited['points'] . '">' . $edited['points'] . '</option>';
                                echo '<option value="20">20</option>';
                                echo '<option value="40">40</option>';
                            ?>
                        </select>

                        <?php
                            $courseID = $pdo->prepare('SELECT * FROM courses');
                            $courseID->execute();
                            echo '<label class="mt-2">Course ID:</label><select class="select col-3" name="course_id">';
                            echo '<option value="' . $selected['course_id'] . '">' . $selected['course_title'] . ' (' . $selected['course_id'] . ')' . '</option>';
                                foreach($courseID as $id){
                                    echo '<option value="' . $id['course_id'] . '">' . $id['course_title'] . ' (' . $id['course_id'] . ')' . '</option>';
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