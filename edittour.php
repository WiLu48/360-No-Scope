<?php
    include('_includes/config.inc');
    include('_includes/connect_db.php');
    include('_includes/header.html');

    // Check if user is signed in and url is correct
    if(isset($_SESSION['userid']) && isset($_GET['tourid']) && $_GET['tourid'] != NULL){
        $userid = $_SESSION['userid'];
        $tourid = $_GET['tourid'];
        // SQL query getting tour details
        $sql = "SELECT * FROM tours WHERE tourid='$tourid'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($result);
        // SQL query getting room details
        $sql1 = "SELECT * FROM rooms WHERE tourid='$tourid'";
        $result1 = mysqli_query($conn,$sql1);

        // When user submits to save details
        if(isset($_POST['submit'])) {
            if(!empty($_POST['TOURNAME'])){
                $txtTourName = mysqli_real_escape_string($conn, $_POST['TOURNAME']);
                if(isset($_POST['TOURVIS'])){
                    $chkTourVis = 1;
                }
                else{
                    $chkTourVis = 0;
                }
                
                // SQL saving onto database
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
        }
        if(isset($_POST['submitDelFP'])) {
            unlink('uploadsFP/' . $_POST['submitDelFP']);
            $sqldel = "UPDATE tours SET tourfloorplan = NULL WHERE tourid = '$tourid'";
            $resultdel = mysqli_query($conn,$sqldel);
            header("Refresh:0");          
        }
        
        ?>

        <!-- Temp Navigation -->
        <a href="index.php">Home</a><br/>
        <a href="dashboard.php">Dashboard</a><br/>
        <a href="account.php">Account</a><br/>
        <a href="logout.php">Logout</a>

        <!-- Edit tour details -->
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
            <div style="width:100%" class="form-group">
                <label for="exampleFormControlInput1">Link To View (If visible)</label>
                <input readonly style="width:100%;" class="form-control" id="exampleFormControlInput1" value="<?php echo "www.360noscope.com/viewtour.php?tourid=" . $tourid; ?>">
            </div>
            <input value="Update Details" type="submit" name="submit" class="btn btn-primary"/>
        </form>

        <!-- Upload floor plan -->
        <?php if($row['tourfloorplan'] != NULL){ ?>
            <!-- If there's a floor plan, show image -->
            <img src="uploadsFP/<?php echo $tourid; ?>_floorplan.jpg" style="width:500px;" />
            <form action="edittour.php?tourid=<?php echo $tourid;?>" method="post" enctype="multipart/form-data">
                <input type="submit" value="Delete" name="submitDelFP">
            </form>     
        <?php }
        else{ ?>
            <!-- Floor plan upload -->
            <h2>Upload Floor Plan</h2>
            <form action="uploadFP.php?tourid=<?php echo $tourid;?>" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submitFP">
            </form>
        <?php } ?>

        
        <br/> <br/><a href="viewtour.php?tourid=<?php echo $tourid ?>">View Tour</a>

        <!-- Add room -->
        <h2>Add Room</h2>
        <form action="_includes/submitaddroom.php?tourid=<?php echo $tourid; ?>" method="POST" style="width:100%;">
            <div class="form-group">
                <label>Room Name</label>
                <input type="text" class="form-control" name="roomname">
            </div>  
            <button type="submit" name="action" style="width: 300px; font-size: 1.3em;margin-top:10px;">Submit</button>
        </form>

        <!-- Listing rooms associated with tour -->
        <h2>Rooms</h2>
        <table style="width:500px;border-style: solid;">
            <tr>
                <th>Thumbnail</th>
                <th>Name</th>
                <th>Floor</th>
            </tr>
            <tr>
                <?php while($row1 = mysqli_fetch_assoc($result1)){ ?>
                    <tr style="cursor:pointer;" onclick="window.location='editroom.php?roomid=<?php echo $row1["roomid"]; ?>'">
                        <td> <?php echo $row1["roomimage"];?> </td>
                        <td> <?php echo $row1["roomname"];?> </td>
                        <td> <?php echo $row1["roomfloor"];?> </td>
                    </tr>
                <?php } ?>
            </tr>
        </table>

        <?php
        include('_includes/footer.html');
    }
    else {
    echo '<h3 style="color:RED">404: Page could not be found.</h3>
            <p>You will be redirected shortly.</p>';
            header( "refresh:1;url=login.php" );
    }
?>