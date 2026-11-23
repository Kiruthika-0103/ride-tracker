<?php
require 'config/db.php'; require 'includes/auth.php';
if(!empty($_SESSION['user_id'])){header('Location: dashboard.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $username=trim($_POST['username']);$password=$_POST['password'];
 $stmt=$pdo->prepare('SELECT * FROM users WHERE username=?');$stmt->execute([$username]);$u=$stmt->fetch();
 if($u && password_verify($password,$u['password'])){session_regenerate_id(true);$_SESSION['user_id']=$u['id'];$_SESSION['user_name']=$u['name'];header('Location: dashboard.php');exit;}
 $error='Invalid username or password.';
}
$page_title='Login'; include 'includes/header.php'; ?>
<div class="auth-box card shadow-sm border-0 p-4">
<div class="text-center"><i class="bi bi-wallet2 brand-icon text-success"></i><h2 class="fw-bold">Ride Tracker</h2><p class="text-muted">Income & Expense Dashboard</p></div>
<?php if(isset($_GET['registered'])): ?><div class="alert alert-success">Registration successful. Please login.</div><?php endif; ?>
<?php if(!empty($error)): ?><div class="alert alert-danger"><?=e($error)?></div><?php endif; ?>
<form method="post">
<input class="form-control mb-3" name="username" placeholder="User ID / Username" required>
<input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
<div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="remember"><label class="form-check-label" for="remember">Remember me</label></div>
<button class="btn btn-success w-100">Login</button>
</form>
<p class="text-center mt-3 mb-0">New user? <a href="register.php">Create account</a></p>
</div><?php include 'includes/footer.php'; ?>