<?php
require 'config/db.php';require 'includes/auth.php';require_login();$uid=$_SESSION['user_id'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 $category=$_POST['category'];$amount=(float)$_POST['amount'];$date=$_POST['expense_date'];$desc=trim($_POST['description']);
 if(in_array($category,['Petrol','Home'],true)&&$amount>=0&&$date){$s=$pdo->prepare('INSERT INTO expenses(user_id,category,amount,expense_date,description) VALUES(?,?,?,?,?)');$s->execute([$uid,$category,$amount,$date,$desc]);header('Location: expenses.php?saved=1');exit;}
 $error='Please enter valid expense details.';
}
$rows=$pdo->prepare('SELECT * FROM expenses WHERE user_id=? ORDER BY expense_date DESC,id DESC LIMIT 100');$rows->execute([$uid]);
$tot=[];$s=$pdo->prepare('SELECT category,SUM(amount) amount FROM expenses WHERE user_id=? GROUP BY category');$s->execute([$uid]);foreach($s as $r)$tot[$r['category']]=$r['amount'];
$page_title='Expenses';include 'includes/header.php'; ?>
<h2 class="page-title mb-4">Expenses</h2>
<?php if(isset($_GET['saved'])):?><div class="alert alert-success auto-dismiss">Expense saved successfully.</div><?php endif;?>
<div class="row g-3 mb-4"><div class="col-md-4"><div class="card stat-card p-3"><small>Petrol</small><div class="money text-danger">₹<?=number_format($tot['Petrol']??0,2)?></div></div></div><div class="col-md-4"><div class="card stat-card p-3"><small>Home</small><div class="money text-danger">₹<?=number_format($tot['Home']??0,2)?></div></div></div><div class="col-md-4"><div class="card stat-card p-3"><small>Total Expenses</small><div class="money text-danger">₹<?=number_format(array_sum($tot),2)?></div></div></div></div>
<div class="card table-card shadow-sm p-4 mb-4"><h5>Add Expense</h5><form method="post" class="row g-3">
<div class="col-md-3"><label class="form-label">Category</label><select name="category" class="form-select"><option>Petrol</option><option>Home</option></select></div>
<div class="col-md-3"><label class="form-label">Amount (₹)</label><input type="number" step="0.01" min="0" name="amount" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Date</label><input type="date" name="expense_date" value="<?=date('Y-m-d')?>" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Description</label><input name="description" class="form-control" placeholder="Optional"></div>
<div class="col-12"><button class="btn btn-success">Save Expense</button></div></form></div>
<div class="card table-card shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Date</th><th>Category</th><th>Amount</th><th>Description</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['expense_date'])?></td><td><?=e($r['category'])?></td><td>₹<?=number_format($r['amount'],2)?></td><td><?=e($r['description'])?></td></tr><?php endforeach;?></tbody></table></div></div>
<?php include 'includes/footer.php'; ?>