<?php
  $errors = "";
  session_start();
//connect to database
  $db = mysqli_connect('localhost', 'jan', 'janpass', 'tasks');
 
  $sql = "CREATE TABLE IF NOT EXISTS tasks(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(50),
    duedate VARCHAR(50),
    active VARCHAR(50))";
    $returnResult = $db->query($sql);
    if($returnResult)
        {
            echo " <script> console.log('Table Created Successfully'); </script>"; // error checking if Table was created successfully 
        }
    else 
        {
            echo "<p>Error occurred while creating the table.</p>" .mysqli_error($db);
            echo "<p>Exiting...</p>";
            exit();
        }
        $userid = $_SESSION["id"];
        $username = $_SESSION["username"];
        mysqli_query($db, "SELECT * FROM tasks WHERE id = $userid");
  if(isset($_POST['submit'])){
      $due = $_POST['duedate'];
      $task = $_POST['task'];
      $userid = $_SESSION["id"];
      if(empty($task)){
          $errors = "You must input a task.";
      }else{
          mysqli_query($db, "INSERT INTO tasks (task, duedate) VALUES ('$task', '$due')");
          header('location: todo.php');
      }
  }
  //delete task
  if(isset($_GET['del_task'])){
      $id = $_GET['del_task'];
      mysqli_query($db, "DELETE FROM tasks WHERE id=$id");
      header('location: todo.php');
  }
  $tasks = mysqli_query($db, "SELECT * FROM tasks WHERE userid=$userid  ORDER BY task");
  if(isset($_POST['update'])){
      $uTask = $_POST['uTask'];
      $uDate = $_POST['uDate'];
      $upT = $_POST['upT'];
     
          $sql = "UPDATE tasks SET task='$uTask', duedate='$uDate' WHERE id='$upT'";
          if ($db->query($sql) === TRUE) {
              echo "Record updated successfully <br>";
              header('location: todo.php');
          } else {
              echo "Error: " . $sql . "<br>" . $db->error;
          }
      
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>To-Do List</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-dark bg-dark" style="width: 100%;">
    <h1><a class="navbar-brand" style="color:#dc85bd;"> Organise Your Life</a></h1>

    <div class="dropdown">
  <button class="dropbtn">Menu</button>
  <div class="dropdown-content">
  <a href="reset-password.php"class="button">Reset Your Password</a>
  <a href="logout.php" class="button" >Sign Out of Your Account</a>
  </div>
</div>

 </nav>

  <div class="heading">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> welcome to the daily to do app.</h1>
  <h2>Here are the things your need to do:</h2>
  </div>
  <div class="formBg">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php if (isset($errors)){ ?>
                <p><?php echo $errors; ?></p>
                <?php  }?>
            <input type="text" name="task" class="task_input" placeholder="Insert task"><br><br>
            <input type="date" id="start" name="duedate" class="task_input" min="2019-05-21" max="2019-12-31"><br><br>
            <button type="submit" class="task_btn" name="submit">Add Task</button><br><br>
            <h4 class="tasks-update">CLICK HERE! To Update Task</h4>
            <div class="content">
            <input type="text" name="upT" class="task_input" placeholder="Insert task Number"><br><br>
            <input type="text" name="uTask" class="task_input"placeholder="Insert Changes"><br><br>
            <input type="date" id="update" name="uDate" class="task_input" min="2019-05-21" max="2019-12-31"><br><br>
            <button type="submit" class="task_btn" name="update">update</button>
            </div>
            </form>
      <table>
          <thead>
              <tr>
                  <th>id</th>
                  <th>Task</th>
                  <th>Due Date</th>
                  <th>Action</th>
              </tr>
          </thead>

          <tbody>
              <?php
              while($row = mysqli_fetch_array($tasks)){ ?>

                 <tr>
                  <td><?php echo $row['id'];?></td>
                  <td class="task"><?php echo $row['task'];?></td>
                  <td class="due"><?php echo $row['duedate'];?></td>
                  <td class="delete">
                      <a href="todo.php?del_task= <?php echo $row['id'];?>">x</a>
                  </td>
              </tr>
              <?php }; ?>
          </tbody>

      </table>

</body>
<script>
  $(document).ready(function () {
      $(".content").hide();
$(".tasks-update").click(function () {
  $(".content").slideToggle();
});
});
  </script>
</html>