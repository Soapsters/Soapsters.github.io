<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
     // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM blog_user WHERE username = ?";
        
        if($statement = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            //mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
			$statement->execute([$username]);
			/* store result */
			//mysqli_stmt_store_result($stmt);
			$results = $statement->fetch(PDO::FETCH_ASSOC);
			$tempjson = json_encode($results);
			$newresult = json_decode($tempjson, true);
			//$dbusername = $newresult['username'];
			//$dbpassword = $newresult['password'];
			//$dbid = $newresult['id'];
			
			if($statement->rowCount() > 0){
				$username_err = "This username is already taken.";
			} else{
				
				// Check input errors before inserting in database
				if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
					
					// Prepare an insert statement
					$sqlid = "select MAX(id) from blog_user";
					$statementid = $db->prepare($sqlid);
					$statementid->execute();
					$resultmax = $statementid->fetch(PDO::FETCH_ASSOC);
					$tempjson = json_encode($resultmax);
					$newresult = json_decode($tempjson, true);
					$resultid = $newresult['max'];
					$id = (int)$resultid + 1;
					
					$sql = "INSERT INTO blog_user (id, username, password) VALUES (?, ?, ?)";
					// Attempt to execute the prepared statement
					$statement = $db->prepare($sql);
					$statement->execute([$id, $username, $password]);
					header("location: login.php");
					}
				}
			}
		
           
        }
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>