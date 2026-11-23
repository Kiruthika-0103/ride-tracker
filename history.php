<?php
require 'config/db.php';require 'includes/auth.php';require_login();$uid=$_SESSION['user_id'];
$sql="SELECT income_date date,'Income' type,platform category,rides,amount,notes FROM income WHERE user_id=?
UNION ALL SELECT expense_date date,'Expense' type,category,0 rides,amount,description FROM expenses WHERE user_id=? ORDER BY date DESC";
$s=$pdo->prepare($sql);$s->execute([$uid,$uid]);$rows=$s->fetchAll();
$page_title='History';include 'includes/header.php';?>
<h2 class="page-title mb-4">Activity / History</h2>
<div class="card table-card shadow-sm"><div class="table-responsive"><table class="table mb-0 align-middle"><thead><tr><th>Date</th><th>Type</th><th>Platform/Category</th><th>Rides/Orders</th><th>Amount</th><th>Notes</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['date'])?></td><td><span class="badge <?=$r['type']==='Income'?'text-bg-success':'text-bg-danger'?>"><?=e($r['type'])?></span></td><td><?=e($r['category'])?></td><td><?=e($r['rides'])?></td><td>₹<?=number_format($r['amount'],2)?></td><td><?=e($r['notes'])?></td></tr><?php endforeach;?></tbody></table></div></div>
<?php include 'includes/footer.php'; ?>