<?php
require 'config/db.php'; require 'includes/auth.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $name=trim($_POST['name']); $email=trim($_POST['email']); $username=trim($_POST['username']); $password=$_POST['password']; $confirm=$_POST['confirm'];
 if(!$name||!filter_var($email,FILTER_VALIDATE_EMAIL)||!$username||strlen($password)<6||$password!==$confirm){$error='Enter valid details. Password must be 6+ characters and match confirmation.';}
 else{
  $check=$pdo->prepare('SELECT id FROM users WHERE email=? OR username=?'); $check->execute([$email,$username]);
  if($check->fetch()) $error='Email or username already exists.';
  else{$stmt=$pdo->prepare('INSERT INTO users(name,email,username,password) VALUES(?,?,?,?)');$stmt->execute([$name,$email,$username,password_hash($password,PASSWORD_DEFAULT)]);header('Location: login.php?registered=1');exit;}
 }
}
$page_title='Register'; include 'includes/header.php'; ?>
<div class="auth-box card shadow-sm border-0 p-4">
<h2 class="fw-bold mb-3">Create Account</h2>
<?php if(!empty($error)): ?><div class="alert alert-danger"><?=e($error)?></div><?php endif; ?>
<form method="post">
<input class="form-control mb-3" name="name" placeholder="Full Name" required>
<input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
<input class="form-control mb-3" name="username" placeholder="User ID / Username" required>
<input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
<input class="form-control mb-3" type="password" name="confirm" placeholder="Confirm Password" required>
<button class="btn btn-success w-100">Register</button>
</form>
<p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login</a></p>
</div><?php include 'includes/footer.php'; ?>