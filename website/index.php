<?php
$insert = false;
$update = false;
$delete = false;

$host = "db";
$port = "3306";
$username = "root";
$password = "mypassword";
$database = "notes";

// Create a connection
$conn = mysqli_connect($host, $username, $password, $database, intval($port));
// Die if connection was not successful
if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}
if (isset($_GET['delete'])) {
  $srno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `notes`.`srno` = $srno";
  $result = mysqli_query($conn, $sql);
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (isset( $_POST['snoEdit'])){
    // Update the record
      $srno = $_POST["snoEdit"];
      $title = $_POST["titleEdit"];
      $description = $_POST["descriptionEdit"];
  
    // Sql query to be executed
    $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`srno` = $srno";
    $result = mysqli_query($conn, $sql);
    if($result){
      $update = true;
  }
  else{
      echo "We could not update the record successfully";
  }
  }
  else{
      $title = $_POST["title"];
      $description = $_POST["description"];
  
    // Sql query to be executed
    $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
    $result = mysqli_query($conn, $sql);
  
     
    if($result){ 
        $insert = true;
    }
    else{
        echo "The record was not inserted successfully because of this error ---> ". mysqli_error($conn);
    } 
  }
  }
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

  <title>iTODO's - make your everdays todo list</title>
</head>

<body>
  <!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
  Edit Modal
</button> -->

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="index.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="form-group">
              <label for="title">Note Title</label>
              <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
              <label for="desc">Note Description</label>
              <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div> 
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid d-flex justify-content-center align-items-center">
      <span class="navbar-brand mb-0 h1 fs-1"><img src="image.jpg" style="margin: 13px;" height="55px">iTODO's</span>
    </div>
  </nav>
<?php
if($insert){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> Your note has been inserted successfully
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>×</span>
  </button>
</div>";
}
?>
<?php
if($delete){
  echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> Your note has been deleted successfully
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>×</span>
  </button>
</div>";
}
?>
<?php
if($update){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success!</strong> Your note has been updated successfully
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>×</span>
  </button>
</div>";
}

?>

  <div class="container my-4">
    <h2>Add a Task</h2>
    <form action="index.php" method="post">
      <div class="mb-3">
        <label for="title" class="form-label">Notes Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">

        <div class="mb-3">
          <label for="description" class="form-label">Notes Description</label>
          <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
  </div>
  <div class="container my-4">

    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">Sr.No</th>
          <th scope="col">Title</th>
          <th scope="col">Task Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
      $sql = "SELECT * FROM `notes`";
      $result = mysqli_query($conn, $sql);
      $srno = 0;
      while ($row = mysqli_fetch_assoc($result)) {
        $srno = $srno + 1;
        echo "<tr>
        <th scope='row'>". $srno . "</th>
        <td>". $row['title'] . "</td>
        <td>". $row['description'] . "</td>
        <td> <button class='edit btn btn-sm btn-primary' id=".$row['srno']. ">Edit</button> <button class='delete btn btn-sm btn-primary' id=d".$row['srno']. ">Delete</button> </td>
      </tr>";
     
      }
      ?>

      </tbody> 
    </table>
  </div>
  <hr>

    <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable({
      responsive: true
    });

    });
  </script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[0].innerText;
        description = tr.getElementsByTagName("td")[1].innerText;
        console.log(title, description);
        titleEdit.value = title;
        descriptionEdit.value = description;
        snoEdit.value = e.target.id;
        console.log(e.target.id)
        $('#editModal').modal('toggle');
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        srno = e.target.id.substr(1,);
        
        if (confirm("Are you sure you want to delete this note?")) {
          console.log("yes");
          window.location = `index.php?delete=${srno}`;
        }
        else{
          console.log("no")
        }
      })
    })
</script>

  <!--
    // <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
  //   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script> -->
  <!-- //   --> 
</body>

</html>
