
<?php
// Include config file
require_once "./config.php";


// Define variables and initialize with empty values
$username = $password = $firstname =  $lastname = $email =   "";
$username_err = $password_err = $firstname_err  = $lastname_err  = $email_err  ="";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }


// Validate firstName
    if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter First Name.";
    } elseif(strlen(trim($_POST["firstname"])) < 1){
        $firstname_err = "Please enter First Name.";
    } else{
        $firstname = trim($_POST["firstname"]);
    }


    // Validate lastName
        if(empty(trim($_POST["lastname"]))){
            $lastname_err = "Please enter Last Name.";
        } elseif(strlen(trim($_POST["lastname"])) < 1){
            $lastname_err = "Please enter Last Name.";
        } else{
            $lastname = trim($_POST["lastname"]);
        }


            // Validate email
                if(empty(trim($_POST["email"]))){
                    $email_err = "Please enter Email.";
                } elseif(strlen(trim($_POST["email"])) < 1){
                    $email_err = "Please enter Email.";
                }

                else{
                    // Prepare a select statement
                    $sqll = "SELECT id FROM users WHERE email = ?";

                    if($stmtt = mysqli_prepare($link, $sqll)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmtt, "s", $param_email);

                        // Set parameters
                        $param_email = trim($_POST["email"]);

                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmtt)){
                            /* store result */
                            mysqli_stmt_store_result($stmtt);

                            if(mysqli_stmt_num_rows($stmtt) > 1){
                                $email_err = "This email is already taken.";
                            } else{
                                $email = trim($_POST["email"]);
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }


                        // Close statement
                        mysqli_stmt_close($stmtt);
                    }

}



             if (!filter_var( trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
      $email_err = "Invalid email format";
    }


    // Check input errors before inserting in database
    if(empty($username_err)  && empty($firstname_err) &&
     empty($lastname_err) &&empty($email_err) && empty($password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username,email, password,firstname,lastname,active) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username,  $param_email ,$param_password,$param_firstname,$param_lastname,$param_active);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_active = true;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
              //  header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
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
      <a href="./"> Chatting </a>
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
              <label for="username_txt">Username</label>


              </div>
          </td>

          <td>
    <input class="form-control" id="username_txt" name="username" type="text" placeholder="Username" value="<?php echo $username; ?>">
          </td>
        </tr>


        <tr>
          <td class="col-xs-4">
            <div class="form-group <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
              <span class="help-block"><?php echo $firstname_err; ?></span>
              </div>
            <label for="firstname_txt">Firstname</label>

          </td>

          <td>
    <input class="form-control" id="firstname_txt" name="firstname" type="text" placeholder="Firstname">
          </td>
        </tr>


        <tr>
          <td class="col-xs-4">
            <div class="form-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
              <span class="help-block"><?php echo $lastname_err; ?></span>
              </div>
              <label for="lastname_txt">Lastname</label>
          </td>

          <td>
    <input class="form-control" id="lastname_txt" name="lastname" type="text" placeholder="Lastname">
          </td>
        </tr>

        <tr>
          <td class="col-xs-4">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
              <span class="help-block"><?php echo $email_err; ?></span>
              </div>
              <label for="email_txt">Email</label>
          </td>

          <td>
    <input class="form-control" id="email_txt" name="email" type="email" placeholder="Email">
          </td>
        </tr>

        <tr>
          <td class="col-xs-4">
              <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
                </div>
              <label for="passsword_txt">New Password</label>
          </td>

          <td>
      <input class="form-control" id="password_txt" name="password" type="password" placeholder="Password" value="<?php echo $password; ?>">

          </td>
        </tr>



      </table>
         <br/>

  <button type="submit" class="btn btn-primary">Register</button>



     </table>

</form>


    </div>
<br/>


  </div>
</div>




</body>
</html>
