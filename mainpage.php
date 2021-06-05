<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->

<div class="container-fluid file-color mainpage">
    <div class="row-12"><h3 class="bltext pb-3 pt-3">Welcome, <?=$login['firstname']?> </h3></div>
    <p class="text-blue">Today is <?=date("l jS \of F Y") . "<br>";?></p>
    <p>You can check students by year using the sidebar.</p>
    <p>You can add a new grade from the student's profile page.</p>
    <p>You can also check your profile on the top right corner or choose to logout.</p>
    <p>Settings and activity log not yet functional.</p>
    <p class="pb-4">Thank you for using the records management system.</p>
    <h3>Would you like to:</h3>
    <div class="d-flex flex-column">
    <?php if($login['permissions'] == "admin"){ ?>
        <a class="text-primary btn col-2" href="newstudent.php">Add a new student?</a>
        <a class="text-primary btn col-2" href="admins.php">Add a new member of staff?</a>
        <a class="text-primary btn col-2" href="manageannouncements.php">Add a new announcement?</a>
        <a class="text-primary btn col-2" href="managecourses.php">Add new course?</a>
        <a class="text-primary btn col-2" href="managemodules.php">Add new module?</a>
        <a class="text-primary btn col-2" href="profile.php">Check your profile?</a>
        <a class="text-primary btn col-2" href="announcements.php">Check announcements?</a>
        <a class="text-primary btn col-2" href="managetimetable.php">Manage timetables?</a>
        <a class="text-primary btn col-2" href="manageassessments.php">Manage assessments?</a>
    <?php } else if ($login['permissions'] == "staff"){ ?>
        <a class="text-primary btn col-2" href="manageannouncements.php">Add a new announcement?</a>
        <a class="text-primary btn col-2" href="profile.php">Check your profile?</a>
        <a class="text-primary btn col-2" href="announcements.php">Check announcements?</a>
        <a class="text-primary btn col-2" href="manageassessments.php">Manage assessments?</a>
    <?php } ?>
    </div>
</div>



</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->