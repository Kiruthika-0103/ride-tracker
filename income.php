<?php
require 'config/db.php';require 'includes/auth.php';require_login();$uid=$_SESSION['user_id'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 $platform=$_POST['platform'];$rides=max(0,(int)$_POST['rides']);$amount=(float)$_POST['amount'];$date=$_POST['income_date'];$notes=trim($_POST['notes']);
 if(in_array($platform,['Rapido','Zomato','Swiggy','Uber'],true)&&$amount>=0&&$date){$s=$pdo->prepare('INSERT INTO income(user_id,platform,rides,amount,income_date,notes) VALUES(?,?,?,?,?,?)');$s->execute([$uid,$platform,$rides,$amount,$date,$notes]);header('Location: income.php?saved=1');exit;}
 $error='Please enter valid income details.';
}
$rows=$pdo->prepare('SELECT * FROM income WHERE user_id=? ORDER BY income_date DESC,id DESC LIMIT 100');$rows->execute([$uid]);
$totals=[];$s=$pdo->prepare('SELECT platform,SUM(amount) amount,SUM(rides) rides FROM income WHERE user_id=? GROUP BY platform');$s->execute([$uid]);foreach($s as $r)$totals[$r['platform']]=$r;
$page_title='Income';include 'includes/header.php'; ?>
<h2 class="page-title mb-4">Income Tracking</h2>
<?php if(isset($_GET['saved'])):?><div class="alert alert-success auto-dismiss">Income saved successfully.</div><?php endif;?>
<?php if(!empty($error)):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<div class="row g-3 mb-4"><?php foreach(['Rapido','Zomato','Swiggy','Uber'] as $p):?><div class="col-6 col-lg-3"><div class="card platform-card shadow-sm p-3"><h5><?=$p?></h5><div class="money">₹<?=number_format($totals[$p]['amount']??0,2)?></div><small class="text-muted"><?=number_format($totals[$p]['rides']??0)?> rides/orders</small></div></div><?php endforeach;?></div>
<div class="card table-card shadow-sm p-4 mb-4"><h5>Add Income</h5><form method="post" class="row g-3">
<div class="col-md-3"><label class="form-label">Platform</label><select name="platform" class="form-select" required><?php foreach(['Rapido','Zomato','Swiggy','Uber'] as $p):?><option><?=$p?></option><?php endforeach;?></select></div>
<div class="col-md-2"><label class="form-label">Rides/Orders</label><input type="number" min="0" name="rides" class="form-control" required></div>
<div class="col-md-2"><label class="form-label">Earnings (₹)</label><input type="number" step="0.01" min="0" name="amount" class="form-control" required></div>
<div class="col-md-2"><label class="form-label">Date</label><input type="date" name="income_date" value="<?=date('Y-m-d')?>" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Notes</label><input name="notes" class="form-control" placeholder="Optional"></div>
<div class="col-12"><button class="btn btn-success">Save Income</button></div>
</form></div>
<div class="card table-card shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Date</th><th>Platform</th><th>Rides/Orders</th><th>Amount</th><th>Notes</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['income_date'])?></td><td><?=e($r['platform'])?></td><td><?=e($r['rides'])?></td><td>₹<?=number_format($r['amount'],2)?></td><td><?=e($r['notes'])?></td></tr><?php endforeach;?></tbody></table></div></div>
<?php include 'includes/footer.php'; ?>