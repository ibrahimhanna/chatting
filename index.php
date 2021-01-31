
<?php
// Include config file
require_once "./config.php";


// Define variables and initialize with empty values
$username = $password =  "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    }

   else if(empty(trim($_POST["password"]))){
       $password_err = "Please enter a password.";
   }
    else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ? or email= ? AND password=?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username,$param_email,$param_password);

            // Set parameters
            $param_username = trim($_POST["username"]);
            $param_email =  trim($_POST["username"]);
            $param_password =  trim($_POST["password"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) >0){
                    // redirect to home page
                      $login_err  = "";
                } else{
                $login_err = "login error please try again";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

}
    if(empty($username_err) && empty($password_err) &&
     empty($login_err)){

       header("Location: ./home.php");
       exit();


   }
    }

    ?>


<!DOCTYPE html>
<html>
<head>
<title>Chatting</title>
 <script src="bootstrap-3.3.7/js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="bootstrap-3.3.7/css/bootstrap-theme.min.css"/>
  <link rel="stylesheet" href="bootstrap-3.3.7/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="index_css/index.css"/>


</head>
<body>

  <div class="panel panel-default">
    <div class="panel-body">
      Chatting
    </div>
  </div>

   <br/><br/><br/>

   <div class="panel panel-default">
  <div class="panel-body">
    <div class="col-xs-4">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>

     <table>
        <tr>
          <td class="col-xs-4">

            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
              <span class="help-block"><?php echo $username_err; ?></span>
            </div>
              <label for="username_txt">Username/Email</label>
          </td>

          <td>
    <input class="form-control" id="username_txt" name = "username" type="text" placeholder="Username/Email">
          </td>
        </tr>


        <tr>
          <td class="col-xs-4">
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
              <span class="help-block"><?php echo $password_err; ?></span>
              </div>
              <label for="password_txt">Password</label>
          </td>

          <td>
    <input class="form-control" id="password_txt" type="password" name="password"  placeholder="Password">
          </td>
        </tr>
      </table>
         <br/>
         <div class="form-group <?php echo (!empty($login_err)) ? 'has-error' : ''; ?>">
           <span class="help-block"><?php echo $login_err; ?></span>
           </div>
  <button type="submit" class="btn btn-primary">Login</button>

   <a href="./registeration.php">Register me</a>




     </table>

</form>

    </div>
<br/>


  </div>
</div>




</body>
</html>
