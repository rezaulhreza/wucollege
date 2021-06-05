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

    $staff = $pdo->prepare('SELECT * FROM staff WHERE id=:id');
    $values2=[
        'id' => $id
    ];
    $staff->execute($values2);
    $edited = $staff->fetch();

    if(isset($_POST['edit'])){ //Edit admins account details

        $update=$pdo->prepare('UPDATE staff SET username = :username, firstname = :firstname, lastname = :lastname, email = :email, address = :address, mobile = :mobile, dob = :dob, status = :status, reason = :reason, role = :role, subject = :subject, uni_id = :uni_id WHERE id = :id');
        $values3=[
            'uni_id' => $_POST['uni_id'],
            'username' => $_POST['username'],
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'mobile' => $_POST['mobile'],
            'dob' => $_POST['dob'],
            'status' => $_POST['status'],
            'reason' => $_POST['reason'],
            'role' => $_POST['role'],
            'subject' => $_POST['subject'],
            'id' => $_POST['id']
        ];
        $update->execute($values3);
        header('Location: admins.php');
        
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

    <title>Edit staff</title>

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
                    <form action="editstaff.php?id="<?=$id?> method="POST" class="d-flex flex-column"> <!-- Admin account details edit form -->
                        <input type="hidden" name="id" value="<?php echo $edited['id']; ?>" />
                        <label class="h4 text-dark mt-2">Edit <?php echo $edited['username'] . ' (' . $edited['firstname'] . ' ' . $edited['lastname'] . ')';?>:</label>

                        <label class="mt-2">University ID:</label>
                        <input class="col-4" type="text" name="uni_id" value="<?=$edited['uni_id'];?>" required>

                        <label>Username:</label>
                        <input class="col-2" type="text" name="username" value="<?=$edited['username'];?>" required>

                        <label>First Name:</label>
                        <input class="col-2" type="text" pattern="[A-Za-z]{1,55}" name="firstname" value="<?=$edited['firstname'];?>" required>
                            
                        <label>Last Name:</label>
                        <input class="col-2" type="text" pattern="[A-Za-z]{1,55}" name="lastname" value="<?=$edited['lastname'];?>" required>

                        <label>Email:</label>
                        <input class="col-2" type="email" pattern="[a-z0-9._]+@[a-z0-9.-]+\.[a-z]{2,}$" name="email" value="<?=$edited['email'];?>" required>

                        <label>Address:</label>
                        <input class="col-4" type="text" name="address" value="<?=$edited['address'];?>" required>

                        <label>Mobile:</label>
                        <input class="col-2" type="text" name="mobile" value="<?=$edited['mobile'];?>" required>

                        <label>Date of birth:</label>
                        <input class="col-2" type="text" name="dob" value="<?=$edited['dob'];?>" required>

                        <?php 
                        echo '<label class="mt-2">Status:</label><select class="select col-2" name="status">'; //Categories display
                            echo '<option value="live">Live</option>';
                            echo '<option value="dormant">Dormant</option>';
                        echo '</select>';
                        ?>

                        <label class="mt-2">Reason (none if status is live):</label>
                        <select class="select col-2" name="reason">
                        <?php 
                                echo '<option value="' . $edited['reason'] . '">' . $edited['reason'] . '</option>';
                                echo '<option value="none">None</option>';
                                echo '<option value="withdrawn">Withdrawn</option>';
                                echo '<option value="terminated">Terminated</option>';
                            echo '</select>';
                        ?>

                        <?php 
                            echo '<label class="mt-2">Role:</label><select class="select col-2" name="role">'; //Categories display
                                echo '<option value="' . $edited['role'] . '">' . $edited['role'] . '</option>';
                                echo '<option value="AD">Admin</option>';
                                echo '<option value="CL/ML/PT">Course Leader + Module Leader + Personal Tutor</option>';
                                echo '<option value="ML/PT">Module Leader + Personal Tutor</option>';
                                echo '<option value="CL/ML">Course Leader + Module Leader</option>';
                                echo '<option value="CL/PT">Course Leader + Personal Tutor</option>';
                                echo '<option value="TC">Teacher</option>';
                            echo '</select>';
                        ?>

                        <label class="mt-2">Subject:</label>
                        <input class="col-2" type="text" name="subject" value="<?=$edited['subject'];?>">

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