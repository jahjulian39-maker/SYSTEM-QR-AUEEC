<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Error/Success Message Display -->
    <?php
        if(isset($_GET['error'])){
            echo "<div class='error-message' style='position: absolute; top: 10px; left: 50%; transform: translateX(-50%); background-color: red; color: white; padding: 10px; border-radius: 5px;'>
                " . htmlspecialchars($_GET['error']) . "
                </div>";
        }
        if(isset($_GET['success'])){
            echo "<div class='success-message' style='position: absolute; top: 10px; left: 50%; transform: translateX(-50%); background-color: green; color: white; padding: 10px; border-radius: 5px;'>
                " . htmlspecialchars($_GET['success']) . "
                </div>";
        }
    ?>

    <div class="container" id="signup" style="display:none;">
      <h1 class="form-title">Register</h1>
      <form method="post" action="register.php">
        <div class="input-group">
           <i class="fas fa-user"></i>
           <input type="text" name="fName" id="fName" placeholder="First Name" required>
           <label for="fName">First Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="lName" id="lName" placeholder="Last Name" required>
            <label for="lName">Last Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label for="password">Password</label>
        </div>

<div class="input-group">
    <i class="fas fa-user-tag"></i>
    <select name="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
    </select>
</div>


       <input type="submit" class="btn" value="Sign Up" name="signUp">
      </form>
      <p class="or">----------or--------</p>
      <div class="icons">
       
      </div>
      <div class="links">
        <p>Already Have Account ?</p>
        <button id="signInButton">Sign In</button>
      </div>
    </div>

    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="register.php">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="email" placeholder="Email" required>
              <label for="email">Email</label>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
              <label for="password">Password</label>
          </div>
          
         <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <p class="or">----------or--------</p>
        <div class="icons">
          
        </div>
        <div class="links">
          <p>Don't have account yet?</p>
          <button id="signUpButton">Sign Up</button>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
