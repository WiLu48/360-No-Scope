<?php
    include('_includes/config.inc');
    include('_includes/connect_db.php');
    include('_includes/header.html');
   if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $tourid = $_GET['tourid'];
    $sql = "SELECT * FROM tours WHERE tourid='$tourid'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result); 

    if (isset($_POST['submit'])) {
        if(!empty($_POST['TOURNAME'])){
            $txtTourName = mysqli_real_escape_string($conn, $_POST['TOURNAME']);
            if(isset($_POST['TOURVIS'])){
                $chkTourVis = 1;
            }
            else{
                $chkTourVis = 0;
            }
            
            $sql = "UPDATE tours SET tourname = '$txtTourName', tourvisible = '$chkTourVis' WHERE tourid = '$tourid'";
            $result = mysqli_query($conn,$sql);

            if ($conn->query($sql) === TRUE) {
                header("Refresh:0");
                exit();
            } 
            else{
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } 
        else{
            echo "Invalid inputs<br/>";
        } 
    } ?>

    <a href="index.php">Home</a><br/>
    <a href="dashboard.php">Dashboard</a><br/>
    <a href="account.php">Account</a><br/>
    <a href="logout.php">Logout</a>

    <h2>Edit Tour</h2>
    <form style="width:400px;" method="post">
        <div style="width:100%" class="form-group">
            <label for="exampleFormControlInput1">ID</label>
            <input readonly style="width:100%;" class="form-control" id="exampleFormControlInput1" value="<?php echo $tourid; ?>">
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Tour Name</label>
            <input  style="width:100%;" name="TOURNAME" class="form-control" id="exampleFormControlInput1" value="<?php echo $row['tourname']; ?>">
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Visibility</label>
            <input type="checkbox" name="TOURVIS" class="form-control" id="exampleFormControlInput1" <?php if($row['tourvisible'] == 1){echo "checked";}else{echo "";} ?>>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Views</label>
            <input  readonly style="width:100%;" class="form-control" id="exampleFormControlInput1" value="<?php echo $row['tourviews']; ?>">
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Created</label>
            <input  readonly style="width:100%;" class="form-control" id="exampleFormControlInput1" value="<?php echo $row['tourcreated']; ?>">
        </div>
        <input value="Update Details" type="submit" name="submit" class="btn btn-primary"/>
    </form>
    <a href="viewtour.php?tourid=<?php echo $tourid ?>">View Tour</a>

    <?php
    include('_includes/footer.html');
}
else {
    echo '<h3 style="color:RED">404: Page could not be found.</h3>
            <p>You will be redirected shortly.</p>';
            header( "refresh:1;url=login.php" );
}
?>