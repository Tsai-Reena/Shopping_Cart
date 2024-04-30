<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once("connMysql.php");
session_start();
//繫結登入會員資料
$query_RecMember = "SELECT * FROM `shopper` WHERE `Username` = '{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember=$RecMember->fetch_assoc();
if(true){
	//購物車開始
	require_once("class.Cart.php");
	//購物車初始化
	$cart = new Cart([
		// 可增加到購物車的商品最大值, 0 = 無限
		'cartMaxItem' => 0,
		// 可增加到購物車的每個商品數量最大值, 0 = 無限
		'itemMaxQuantity' => 0,
		// 不要使用cookie，關閉瀏覽器後購物車物品將消失
		'useCookie' => false,
	]);
	//購物車結束

	// 設定 paytype
	if($_POST["paytype"] == "ATM匯款") $paytype = "Money_Transfer";
	else if($_POST["paytype"] == "線上刷卡") $paytype = "Credit_Card";
	else $paytype = "Cash";

	// 設定 shipping type & cost
	if($_POST["shiptype"] == "7-11") {
		$shiptype = "711";
		$shipcost = 60;
	}
	else if($_POST["shiptype"] == "Family") {
		$shiptype = "Family";
		$shipcost = 60;
	}
	else if($_POST["shiptype"] == "OK") {
		$shiptype = "OK";
		$shipcost = 40;
	}
	else {
		$shiptype = "Fedex";
		$shipcost = 80;
	}

	//新增訂單資料
	$sql_query = "INSERT INTO `order` (`CustomerName`, `DateShipped`, `Total`, `PayType`, `Checkout_time`) VALUES (?, ?, ?, ?, NOW())";
	$stmt = $db_link->prepare($sql_query);
	$stmt->bind_param("ssis", $row_RecMember["Username"], date('Y-m-d', strtotime($_POST['dateshipped'])), $cart->getAttributeTotal('price'), $paytype);
	$stmt->execute();
	//取得新增的訂單編號
	$o_pid = $stmt->insert_id;
	$stmt->close();
	// echo $o_pid;

	// 新增運輸資料
	$sql_query="INSERT INTO `shipping_info` (`orderID`, `ShippingCost` ,`ShippingType`, `ReceiveName`, `ShippingAddress`) VALUES (?, ?, ?, ?, ?)";
	$stmt = $db_link->prepare($sql_query);
	$stmt->bind_param("iisss", $o_pid, $shipcost, $shiptype, $_POST["receivename"], $_POST["shippingaddress"]);
	$stmt->execute();
	$stmt->close();

	//新增訂單內貨品資料
	if($cart->getTotalitem( ) > 0) {
		// echo "test";
		$allItems = $cart->getItems();
		foreach ($allItems as $items) {
			foreach ($items as $item) {
				// 取得 product ID
				$query_RecProduct = "SELECT pID FROM product WHERE ProductName=?";
				$stmt_product = $db_link->prepare($query_RecProduct);
				$stmt_product->bind_param("s", $item['attributes']['pname']);
				$stmt_product->execute();
				$RecProduct = $stmt_product->get_result();
				$row_RecProduct = $RecProduct->fetch_assoc();

				$sql_query="INSERT INTO includes (`orderID`, `productID` , `Quantity`) VALUES (?, ?, ?)";
				$stmt = $db_link->prepare($sql_query);
				echo $item['quantity'];
				$stmt->bind_param("iii", $o_pid, $row_RecProduct["pID"], $item['quantity']);
				$stmt->execute();
				$stmt->close();
			}
		}
	}
	//郵寄通知
	require '..\..\xampp\php\phpmailer\src\Exception.php';
    require '..\..\xampp\php\phpmailer\src\PHPMailer.php';
    require '..\..\xampp\php\phpmailer\src\SMTP.php';

	$mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '/* Email Address */';
    $mail->Password = '/* Password */';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

	$cname = $row_RecMember["Username"];
	$cmail = $_POST["customeremail"];
	$caddress = $_POST["shippingaddress"];
	$cpaytype = $_POST["paytype"];
	$total = $cart->getAttributeTotal('price');
	$mailcontent=<<<msg
	親愛的 $cname 您好：
	感謝您的光臨
	本次消費詳細資料如下：
	--------------------------------------------------
	訂單編號： $o_pid 
	客戶姓名：$cname 
	電子郵件： $cmail 
	住址： $caddress 
	付款方式： $cpaytype 
	消費金額： $total 
	--------------------------------------------------
	希望能再次為您服務 
	
	網路購物公司 敬上
msg;
	// $mailFrom="=?UTF-8?B?" . base64_encode("網路購物系統") . "?= <service@e-happy.com.tw>";
	// $mailto = $_POST["customeremail"];
	// $mailSubject="=?UTF-8?B?" . base64_encode("網路購物系統訂單通知"). "?=";
	// $mailHeader="From:".$mailFrom."\r\n";
	// $mailHeader.="Content-type:text/html;charset=UTF-8";
	$mail->setFrom('/* Email Address */', '/* Email Sender */'); // 發件人
    $mail->addAddress("{$cmail}", "{$cname}"); // 收件人
    $mail->Subject = "=?UTF-8?B?" . base64_encode("網路購物系統訂單通知"). "?=";
    $mail->Body = $mailcontent;
	if ($mail->send()) $mail_sent = true;
    else $mail_sent = false;
	// if(!mail($mailto,$mailSubject,nl2br($mailcontent),$mailHeader)) die("郵寄失敗！");
	//清空購物車
	$cart->clear();
}	
?>
<script language="javascript">
alert("感謝您的購買，我們將儘快進行處理。");
window.location.href="index.php";
</script>
