<?php
require 'config/db.php'; require 'includes/auth.php'; require_login();
$uid=$_SESSION['user_id']; $today=date('Y-m-d');
$income=$pdo->prepare("SELECT COALESCE(SUM(amount),0) total, COALESCE(SUM(rides),0) rides FROM income WHERE user_id=?");$income->execute([$uid]);$i=$income->fetch();
$expense=$pdo->prepare("SELECT COALESCE(SUM(amount),0) total FROM expenses WHERE user_id=?");$expense->execute([$uid]);$ex=$expense->fetch();
$platform=[];$st=$pdo->prepare("SELECT platform,COALESCE(SUM(amount),0) amount,COALESCE(SUM(rides),0) rides FROM income WHERE user_id=? GROUP BY platform");$st->execute([$uid]);foreach($st as $r)$platform[$r['platform']]=$r;
$cats=[];$st=$pdo->prepare("SELECT category,COALESCE(SUM(amount),0) amount FROM expenses WHERE user_id=? GROUP BY category");$st->execute([$uid]);foreach($st as $r)$cats[$r['category']]=$r['amount'];
$page_title='Dashboard';include 'includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><h2 class="page-title">Welcome, <?=e($_SESSION['user_name'])?> 👋</h2><p class="text-muted mb-0">Your complete income & expense overview</p></div><a class="btn btn-success" href="income.php"><i class="bi bi-plus-lg"></i> Add Income</a></div>
<div class="row g-3 mb-4">
<?php $net=$i['total']-$ex['total']; ?>
<div class="col-6 col-lg-3"><div class="card stat-card p-3"><small class="text-muted">Total Income</small><div class="money text-success">₹<?=number_format($i['total'],2)?></div></div></div>
<div class="col-6 col-lg-3"><div class="card stat-card p-3"><small class="text-muted">Total Expenses</small><div class="money text-danger">₹<?=number_format($ex['total'],2)?></div></div></div>
<div class="col-6 col-lg-3"><div class="card stat-card p-3"><small class="text-muted">Net Income</small><div class="money <?= $net>=0?'text-success':'text-danger'?>">₹<?=number_format($net,2)?></div></div></div>
<div class="col-6 col-lg-3"><div class="card stat-card p-3"><small class="text-muted">Total Rides/Orders</small><div class="money"><?=number_format($i['rides'])?></div></div></div>
</div>
<div class="row g-4">
<div class="col-lg-7"><div class="card stat-card p-4"><h5>Income vs Expenses</h5><canvas id="incomeExpenseChart"></canvas></div></div>
<div class="col-lg-5"><div class="card stat-card p-4"><h5>Platform Income</h5><canvas id="platformChart"></canvas></div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded',()=>{new Chart(document.getElementById('incomeExpenseChart'),{type:'bar',data:{labels:['Income','Expenses','Net'],datasets:[{label:'Amount (₹)',data:[<?=floatval($i['total'])?>,<?=floatval($ex['total'])?>,<?=floatval($net)?>]}]},options:{responsive:true}});
new Chart(document.getElementById('platformChart'),{type:'doughnut',data:{labels:['Rapido','Zomato','Swiggy','Uber'],datasets:[{data:[<?=floatval($platform['Rapido']['amount']??0)?>,<?=floatval($platform['Zomato']['amount']??0)?>,<?=floatval($platform['Swiggy']['amount']??0)?>,<?=floatval($platform['Uber']['amount']??0)?>]}]},options:{responsive:true}});
});
</script><?php include 'includes/footer.php'; ?>