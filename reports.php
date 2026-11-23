<?php
require 'config/db.php';require 'includes/auth.php';require_login();$uid=$_SESSION['user_id'];
$p=[];$s=$pdo->prepare('SELECT platform,SUM(amount) amount FROM income WHERE user_id=? GROUP BY platform');$s->execute([$uid]);foreach($s as $r)$p[$r['platform']]=$r['amount'];
$c=[];$s=$pdo->prepare('SELECT category,SUM(amount) amount FROM expenses WHERE user_id=? GROUP BY category');$s->execute([$uid]);foreach($s as $r)$c[$r['category']]=$r['amount'];
$totalI=array_sum($p);$totalE=array_sum($c);$net=$totalI-$totalE;
$page_title='Reports';include 'includes/header.php';?>
<h2 class="page-title mb-4">Reports</h2>
<div class="row g-4"><div class="col-lg-6"><div class="card stat-card p-4"><h5>Platform-wise Income</h5><canvas id="r1"></canvas></div></div><div class="col-lg-6"><div class="card stat-card p-4"><h5>Expense Categories</h5><canvas id="r2"></canvas></div></div><div class="col-12"><div class="card stat-card p-4"><h5>Income vs Expense vs Net</h5><canvas id="r3"></canvas></div></div></div>
<script>
new Chart(document.getElementById('r1'),{type:'bar',data:{labels:['Rapido','Zomato','Swiggy','Uber'],datasets:[{label:'Income',data:[<?=floatval($p['Rapido']??0)?>,<?=floatval($p['Zomato']??0)?>,<?=floatval($p['Swiggy']??0)?>,<?=floatval($p['Uber']??0)?>]}]}});
new Chart(document.getElementById('r2'),{type:'pie',data:{labels:['Petrol','Home'],datasets:[{data:[<?=floatval($c['Petrol']??0)?>,<?=floatval($c['Home']??0)?>]}]}});
new Chart(document.getElementById('r3'),{type:'bar',data:{labels:['Total Income','Total Expenses','Net Income'],datasets:[{label:'Amount (₹)',data:[<?=floatval($totalI)?>,<?=floatval($totalE)?>,<?=floatval($net)?>]}]}});
</script><?php include 'includes/footer.php'; ?>