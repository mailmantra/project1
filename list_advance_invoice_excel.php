<?php
set_time_limit(0);
include('include/admin_privilege.php');
?>
<?php
include('../../class/mysql_class.php'); 
include('../../lib/my_function.php');
$db = new Database();
$db2 = new Database();
$sql=$_SESSION['excel'];

$db->connect();
$db->sql($sql);
$db->disconnect();
$result = $db->getResult();

$today= today();
$filename = "list_of_advance_invoice" .$today. ".xls"; 
header("Content-Disposition: attachment; filename=\"$filename\""); 
header("Content-Type: application/vnd.ms-excel");



	echo 'Sl.No'."\t".'Username'."\t".'Invoice No'."\t".'Categories'."\t".'Description'."\t".'Customer ID'."\t".'Customer Name'."\t".'Mobile No'."\t".'Amount(Rs)'."\t".'Payment Voucher Wallet'."\t".'Payment Cheque'."\t".'Payment NEFT'."\t".'Payment E-Wallet'."\t".'Payment Cash'."\t".'Payment RTGS'."\t".'Payment IMPS'."\t".'Payment Cash at Bank'."\t".'Payment Cheque at Bank'."\t".'Payment Payu Money'."\t".'Date'."\t". 'IP'."\n";
foreach($result as $r1)
{
	$i++;
	$ainv_no=$r1['ainv_no'];
	
	$db->connect();
	$sql2="select * from inv_advance_payment where apay_inv_no='$ainv_no'";
	$db->sql($sql2);
	$db->disconnect();
	$result2 = $db->getResult();
	
	$pay='';
	$paymode='';
	$payment_e_wallet = 0.00;
	$payment_voucher_wallet = 0.00;
	$payment_cash = 0.00;
	$payment_cheque = 0.00;
	$payment_neft = 0.00;

	$rtgs_total = 0.00;
	$imps_total = 0.00;
	$cash_at_bank_total = 0.00;
	$cheque_at_bank_total = 0.00;
	$payu_total = 0.00;

	foreach($result2 as $r2)
	{
		if(($r2['apay_payment_type']=='Voucher Wallet') || ($r2['apay_payment_type']=='Voucher_Wallet'))
		{
			$payment_voucher_wallet = $r2['apay_payment_amount'];
		}
		elseif($r2['apay_payment_type']=='Cheque')
		{
			$payment_cheque = $r2['apay_payment_amount'];
		}
		elseif($r2['apay_payment_type']=='NEFT')
		{
			$payment_neft = $r2['apay_payment_amount'];
		}
		elseif(($r2['apay_payment_type']=='E-Wallet') || ($r2['apay_payment_type']=='E Wallet') || ($r2['apay_payment_type']=='E_Wallet'))
		{
			$payment_e_wallet = $r2['apay_payment_amount'];
		}
		elseif($r2['apay_payment_type']=='Cash')
		{
			$payment_cash = $r2['apay_payment_amount'];
		}
		elseif(($r2['apay_payment_type']=='RTGS') || ($r2['apay_payment_type']=='R T G S') || ($r2['apay_payment_type']=='R.T.G.S.') || ($r2['apay_payment_type']=='R.T.G.S'))
		{
			$rtgs_total = excel_text_value($r2['apay_payment_amount']);
		}
		elseif(($r2['apay_payment_type']=='IMPS') || ($r2['apay_payment_type']=='I M P S') || ($r2['apay_payment_type']=='I.M.P.S.') || ($r2['apay_payment_type']=='I.M.P.S'))
		{
			$imps_total = excel_text_value($r2['apay_payment_amount']);
		}
		elseif(($r2['apay_payment_type']=='Cash-at-bank') || ($r2['apay_payment_type']=='Cash_at_bank') || ($r2['apay_payment_type']=='Cash at bank'))
		{
			$cash_at_bank_total = excel_text_value($r2['apay_payment_amount']);
		}
		elseif(($r2['apay_payment_type']=='Cheque-at-Bank') || ($r2['apay_payment_type']=='Cheque at Bank') || ($r2['apay_payment_type']=='Cheque_at_Bank'))
		{
			$cheque_at_bank_total = excel_text_value($r2['apay_payment_amount']);
		}
		elseif(($r2['apay_payment_type']=='PayU Money') || ($r2['apay_payment_type']=='Pay U Money') || ($r2['apay_payment_type']=='PayU-Money') || ($r2['apay_payment_type']=='PayU_Money') || ($r2['apay_payment_type']=='PayUMoney') || ($r2['apay_payment_type']=='Pay_U_Money') || ($r2['apay_payment_type']=='PayUMoney'))
		{
			$payu_total = excel_text_value($r2['apay_payment_amount']);
		}
	}
	
	
	$db->connect();
	$sql3="select * from inv_advance_dtls where adtls_inv_no='$ainv_no'";
	$db->sql($sql3);
	$db->disconnect();
	$result3 = $db->getResult();
	
	$all_cat='';
	foreach($result3 as $r3)
	{
		$all_cat= $r3['adtls_product_name'] . ',' . $all_cat;
	}
		$exc_user_un = excel_text_value($r1['user_un']);
		$exc_ainv_no = excel_text_value($r1['ainv_no']);
		$exc_ainv_desc = excel_text_value($r1['ainv_desc']);
		$exc_ainv_mem_code = excel_text_value($r1['ainv_mem_code']);
		$exc_ainv_mem_name = excel_text_value($r1['ainv_mem_name']);
		$exc_ainv_mem_mobile = excel_text_value($r1['ainv_mem_mobile']);
		$exc_ainv_total_amount = excel_text_value($r1['ainv_total_amount']);
		$dmy_time_ainv_dt = dmy_time($r1['ainv_dt']);
		$exc_ainv_ip = excel_text_value($r1['ainv_ip']);
		
	echo $i."\t".$exc_user_un."\t".$exc_ainv_no."\t".$all_cat."\t".$exc_ainv_desc."\t".$exc_ainv_mem_code."\t".$exc_ainv_mem_name."\t".$exc_ainv_mem_mobile."\t".$exc_ainv_total_amount."\t".$payment_voucher_wallet."\t".$payment_cheque."\t".$payment_neft."\t".$payment_e_wallet."\t".$payment_cash."\t".$rtgs_total."\t".$imps_total."\t".$cash_at_bank_total."\t".$cheque_at_bank_total."\t".$payu_total."\t".$dmy_time_ainv_dt."\t".$exc_ainv_ip."\n";
}








?>

