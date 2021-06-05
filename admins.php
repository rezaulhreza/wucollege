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

if(isset($_POST['newadmin'])) { //Add new admins

    $stmt = $pdo->prepare('INSERT INTO staff (username, firstname, lastname, email, password, permissions, address, mobile, dob, status, reason, role, subject, uni_id) 
    VALUES (:username, :firstname, :lastname, :email, :password, :permissions, :address, :mobile, :dob, :status, :reason, :role, :subject, :uni_id)');
    $values = [
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
        'permissions' => $_POST['permissions'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    ];
    $stmt->execute($values);
    header('Location: admins.php');
    
} else if(isset($_POST['edit'])){
    header('Location: editstaff.php?id=' . $_POST['id']);

}else if(isset($_POST['deleteadmin'])){ //Delete admin accounts
    if($_POST['check']=="DELETE"){
        $delete = $pdo->prepare('DELETE FROM staff WHERE id = :id LIMIT 1 ');
        $values= [
            'id' => $_POST['id']
        ];
        $delete->execute($values);
        header('Location: admins.php');
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

    <title>Manage staff</title>

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
                <form  action="admins.php" method="POST" class="d-flex flex-column col-6"> <!-- Admin creation form -->
                <div class="row-6"><h3 class="bltext pb-3 pt-3">Create new staff account</h3></div>
                    <?php 
                        echo '<label class="mt-2">Permission:</label><select class="select col-4" name="permissions">'; //Categories display
                            echo '<option value="admin">Admin</option>';
                            echo '<option value="staff">Staff</option>';
                        echo '</select>';
                    ?>

                    <label class="mt-2">University ID:</label>
                    <input class="col-4" type="text" name="uni_id" required>

                    <label class="mt-2">Username:</label>
                    <input class="col-4" type="text" name="username" required>

                    <label class="mt-2">First Name:</label>
                    <input class="col-4" type="text" pattern="[A-Za-z]{1,55}" name="firstname" required>
                        
                    <label class="mt-2">Last Name:</label>
                    <input class="col-4" type="text" pattern="[A-Za-z]{1,55}" name="lastname" required>

                    <label class="mt-2">Email:</label>
                    <input class="col-4" type="email" pattern="[a-z0-9._]+@[a-z0-9.-]+\.[a-z]{2,}$" name="email" required>

                    <label class="mt-2">Password:</label>
                    <input class="col-4" type="password" name="password" required>

                    <label class="mt-2">Address:</label>
                    <input class="col-6" type="text" name="address" required>

                    <label class="mt-2">Mobile:</label>
                    <input class="col-4" type="text" name="mobile" required>

                    <label class="mt-2">Date of birth:</label>
                    <input class="col-4" type="date" name="dob" required>

                    <?php 
                        echo '<label class="mt-2">Status:</label><select class="select col-4" name="status">'; //Categories display
                            echo '<option value="live">Live</option>';
                            echo '<option value="dormant">Dormant</option>';
                        echo '</select>';
                    ?>

                    <label class="mt-2">Reason (none if status is live):</label>
                    <select class="select col-4" name="reason">
                    <?php 
                            echo '<option value="none">None</option>';
                            echo '<option value="withdrawn">Withdrawn</option>';
                            echo '<option value="terminated">Terminated</option>';
                        echo '</select>';
                    ?>


                    <?php 
                        echo '<label class="mt-2">Role:</label><select class="select col-4" name="role">'; //Categories display
                            echo '<option value="AD">Admin</option>';
                            echo '<option value="CL/ML/PT">Course Leader + Module Leader + Personal Tutor</option>';
                            echo '<option value="ML/PT">Module Leader + Personal Tutor</option>';
                            echo '<option value="CL/ML">Course Leader + Module Leader</option>';
                            echo '<option value="CL/PT">Course Leader + Personal Tutor</option>';
                            echo '<option value="TC">Teacher</option>';
                        echo '</select>';
                    ?>

                    <label class="mt-2">Subject:</label>
                    <input class="col-4" type="text" name="subject" required>

                    <input class="col-4 mt-2 mb-4" type="submit" value="Create staff account" name="newadmin" >
                </form>

                <form action="admins.php" method="POST" class="d-flex flex-column col-3">
                <div class="row-3"><h3 class="bltext pb-3 pt-3">Edit staff accounts</h3></div>
                    <?php
                    $select = $pdo->prepare('SELECT * FROM staff WHERE permissions = "admin" OR permissions = "staff"');
                    $select->execute();	                
                    echo '<label>Edit staff:</label><select name="id">';
                    foreach ($select as $data) {
                        echo '<option value="' . $data['id'] . '">' . $data['username'] . '</option>';
                    }
                    echo '<input class="mt-2" type="submit" name="edit" value="Edit">';
                    echo '</select>';
                    ?>
                </form>

                <form  action="admins.php" method="POST" class="d-flex flex-column col-3">
                    <?php
                    $administrators = $pdo->prepare('SELECT * FROM staff WHERE permissions = "staff" OR permissions = "admin"'); //Display admin accounts to allow deletion
                    $administrators->execute();
                        echo '<div><h3 class="bltext pb-3 pt-3">Delete staff accounts</h3></div>';
                        echo '<label>Delete staff:</label><select name="id">';
                        foreach ($administrators as $admin) {
                            echo '<option value="' . $admin['id'] . '">' . $admin['username'] . '</option>';
                        }
                        echo '<input class="mt-2" type="text" pattern="DELETE" name="check" placeholder="Type DELETE to confirm" required>';
                        echo '</select>';
                        echo '<input class="mt-2 mb-2" type="submit" name="deleteadmin" value="Delete">';
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