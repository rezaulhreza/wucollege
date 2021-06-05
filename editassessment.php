<?php
    session_start();
    require 'configuration.php';
    $id = $_REQUEST['id'];
    $today = date('Y-m-d', time());
    $session = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values=[
        'id' => $_SESSION['loggedin']
    ];
    $session->execute($values);
    $login = $session->fetch();
    if(empty($_SESSION['loggedin']) || ($login['permissions']!="staff" && $login['permissions']!="admin")){
        header('Location: index.php');
    }

    $assessment = $pdo->prepare('SELECT * FROM assessments WHERE id=:id');
    $values=[
        'id' => $id
    ];
    $assessment->execute($values);
    $edited = $assessment->fetch();

    $module = $pdo->prepare('SELECT * FROM modules WHERE module_code=:id');
    $values = [
        'id' => $edited['module_id']
    ];
    $module->execute($values);
    $newModule = $module->fetch();

    if(isset($_POST['edit'])){ //Edit admins account details

        $update=$pdo->prepare('UPDATE assessments SET title = :title, description = :description, submission_date = :submission_date, active = :active, module_id = :module_id WHERE id = :id');
        $values=[
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'submission_date' => $_POST['submission_date'],
            'active' => $_POST['active'],
            'module_id' => $_POST['module_id'],
            'id' => $_POST['id']
        ];
        $update->execute($values);
        header('Location: manageassessments.php');
        
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

    <title>Edit assessment</title>

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
                    <form action="editassessment.php?id="<?=$id?> method="POST" class="d-flex flex-column"> <!-- Admin account details edit form -->
                        <input type="hidden" name="id" value="<?php echo $edited['id']; ?>" />
                        <label class="h4 text-dark mt-2">Edit <?php echo $edited['title'] . ' (' . $edited['module_id'] . ')';?>:</label>

                        <label>Title:</label>
                        <input class="col-3" type="text" name="title" value="<?=$edited['title'];?>" required>

                        <label>Description:</label>
                        <textarea class="col-3" type="text" name="description" rows="5" required><?=$edited['description'];?></textarea>

                        <?php
                            $moduleID = $pdo->prepare('SELECT * FROM modules');
                            $moduleID->execute();
                            echo '<label class="mt-2">Module ID:</label><select class="select col-3" name="module_id">';
                            echo '<option value="' . $newModule['module_code'] . '">' . $newModule['module_title'] . ' (' . $newModule['module_code'] . ')' . '</option>';
                                foreach($moduleID as $id){
                                    echo '<option value="' . $id['module_code'] . '">' . $id['module_title'] . ' (' . $id['module_code'] . ')' . '</option>';
                                }
                            echo '</select>';
                        ?>

                        <label class="mt-2">Submission date:</label>
                        <input class="col-3" type="date" name="submission_date" min="<?=$today?>" value="<?=$edited['submission_date']?>" required>

                        <?php 
                            echo '<label class="mt-2">Active:</label><select class="select col-1" name="active">'; //Categories display
                                echo '<option value="1">Yes</option>';
                                echo '<option value="0">No</option>';
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