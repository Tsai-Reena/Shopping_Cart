<?php
require_once("connMysql.php");
session_start();
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

//繫結產品目錄資料
$query_RecCategory = "SELECT DISTINCT BrandName FROM product";
$RecCategory = $db_link->query($query_RecCategory);

//繫結登入會員資料
$query_RecMember = "SELECT * FROM `shopper` WHERE `Username` = '{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember=$RecMember->fetch_assoc();

//計算資料總筆數
$query_RecTotal = "SELECT count(pID) as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();

// 取得 shopper username
// $query_RecSeller = "SELECT Username FROM shopper";
// $stmt_seller = $db_link->prepare($query_RecSeller);
// $stmt_seller->bind_param("i", $_GET["id"]);
// $stmt_seller->execute();
// $RecSeller = $stmt_seller->get_result();
// $row_RecSeller = $RecSeller->fetch_assoc();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>網路購物系統</title>
<!-- <link rel="stylesheet" href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
<script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="jqueryui/style.css">
<script>
$(function() {
  $( "#datepicker" ).datepicker();
});
</script> -->
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){	
	if(document.cartform.customername.value==""){
		alert("請填寫姓名!");
		document.cartform.customername.focus();
		return false;
	}
	if(document.cartform.customeremail.value==""){
		alert("請填寫電子郵件!");
		document.cartform.customeremail.focus();
		return false;
	}
	if(!checkmail(document.cartform.customeremail)){
		document.cartform.customeremail.focus();
		return false;
	}	
	if(document.cartform.customeraddress.value==""){
		alert("請填寫地址!");
		document.cartform.customeraddress.focus();
		return false;
	}
	return confirm('確定送出嗎？');
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;
	}
	alert("電子郵件格式不正確");
	return false;
}
</script>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
  <td height="150" align="center" background="images/mlogo.jpg" class="tdbline"></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr valign="top">
          <td width="200" class="tdrline"><div class="boxtl"></div>
            <div class="boxtr"></div>
            <div class="categorybox">
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 產品搜尋 <span class="smalltext">Search</span></p>
              <form name="form1" method="get" action="index.php">
                <p>
                  <input name="keyword" type="text" id="keyword" value="請輸入關鍵字" size="12" onClick="this.value='';">
                  <input type="submit" id="button" value="查詢">
                </p>
              </form>
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 價格區間 <span class="smalltext">Price</span></p>
              <form action="index.php" method="get" name="form2" id="form2">
                <p>
                  <input name="price1" type="text" id="price1" value="0" size="3">
                  -
                  <input name="price2" type="text" id="price2" value="0" size="3">
                  <input type="submit" id="button2" value="查詢">
                </p>
              </form>
            </div>
            <div class="boxbl"></div>
            <div class="boxbr"></div>
            <hr width="100%" size="1" />
            <div class="boxtl"></div>
            <div class="boxtr"></div>
            <div class="categorybox">
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 產品目錄 <span class="smalltext">Category</span></p>
              <ul>
                <li><a href="index.php">所有產品</a></li>
                <?php	while($row_RecCategory=$RecCategory->fetch_assoc()){ ?>
                <li><a href="index.php?cid=<?php echo $row_RecCategory["BrandName"];?>"><?php echo $row_RecCategory["BrandName"];?></a></li>
                <?php }?>
              </ul>
            </div>
            <div class="boxbl"></div>
            <div class="boxbr"></div></td>
          <td>
          <div class="subjectDiv"><span class="heading"><img src="images/16-cube-green.png" width="16" height="16" align="absmiddle"></span> 購物結帳</div>
            <div class="normalDiv">
              <?php if( $cart->getTotalitem( ) > 0) {?>
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 購物內容</p>
              <table width="90%" border="0" align="center" cellpadding="2" cellspacing="1">
                <tr>
                  <th bgcolor="#ECE1E1"><p>編號</p></th>
                  <th bgcolor="#ECE1E1"><p>產品名稱</p></th>
                  <th bgcolor="#ECE1E1"><p>數量</p></th>
                  <th bgcolor="#ECE1E1"><p>單價</p></th>
                  <th bgcolor="#ECE1E1"><p>小計</p></th>
                </tr>
                <?php		  
		  	$i=0;
        $allItems = $cart->getItems();
        foreach ($allItems as $items) {
          foreach ($items as $item) {
            $i++;
		  ?>
                <tr>
                  <td align="center" bgcolor="#F6F6F6" class="tdbline"><p><?php echo $i;?>.</p></td>
                  <td bgcolor="#F6F6F6" class="tdbline"><p><?php echo $item['attributes']['pname'];?></p></td>
                  <td align="center" bgcolor="#F6F6F6" class="tdbline"><p><?php echo $item['quantity'];?></p></td>
                  <td align="center" bgcolor="#F6F6F6" class="tdbline"><p>$ <?php echo number_format($item['attributes']['price']);?></p></td>
                  <td align="center" bgcolor="#F6F6F6" class="tdbline"><p>$ <?php echo number_format($item['quantity'] * $item['attributes']['price']);?></p></td>
                </tr>
                <?php }}?>
                <tr>
                  <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>總計</p></td>
                  <td valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                  <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                  <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                  <td align="center" valign="baseline" bgcolor="#F6F6F6"><p class="redword">$ <?php echo number_format($cart->getAttributeTotal('price'));?></p></td>
                </tr>
              </table>
              <hr width="100%" size="1" />
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 客戶資訊</p>
              <form action="cartreport.php" method="post" name="cartform" id="cartform" onSubmit="return checkForm();">
                <table width="90%" border="0" align="center" cellpadding="4" cellspacing="1">
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>消費者姓名</p></th>
                    <td bgcolor="#F6F6F6"><p><?php echo $row_RecMember["Username"];?></p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>付款方式</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <select name="paytype" id="paytype">
                          <option value="ATM匯款" selected>ATM匯款</option>
                          <option value="線上刷卡">線上刷卡</option>
                          <option value="貨到付款">貨到付款</option>
                        </select>
                      </p></td>
                  </tr>
                  <tr>
                    <td colspan="2" bgcolor="#F6F6F6"><p><font color="#FF0000">*</font> 表示為必填的欄位</p></td>
                  </tr>
                </table>
              <hr width="100%" size="1" />
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 運輸資訊</p>
                <table width="90%" border="0" align="center" cellpadding="4" cellspacing="1">
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>收件者姓名</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <input type="text" name="receivename" id="receivename">
                        <font color="#FF0000">*</font></p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>電子郵件</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <input type="text" name="customeremail" id="customeremail">
                        <font color="#FF0000">*</font></p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>住址(門市地址)</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <input name="shippingaddress" type="text" id="shippingaddress" size="40">
                        <font color="#FF0000">*</font></p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>運輸方式</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <select name="shiptype" id="shiptype">
                          <option value="7-11" selected>7-11 ($60)</option>
                          <option value="Family Mart">Family Mart ($60)</option>
                          <option value="OK">OK ($40)</option>
                          <option value="Fedex">Fedex ($80)</option>
                        </select>
                      </p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>送達日期</p></th>
                      <td bgcolor="#F6F6F6">
                      <input type="date" value="2017-06-01" name="dateshipped" id="dateshipped"/>
                      </td>
                  </tr>
                  <tr>
                    <td colspan="2" bgcolor="#F6F6F6"><p><font color="#FF0000">*</font> 表示為必填的欄位</p></td>
                  </tr>
                </table>
                <hr width="100%" size="1" />
                <p align="center">
                  <input name="cartaction" type="hidden" id="cartaction" value="update">
                  <input type="submit" name="updatebtn" id="button3" value="送出訂購單">
                  <input type="button" name="backbtn" id="button4" value="回上一頁" onClick="window.history.back();">
                </p>
              </form>
            </div>
            <?php }else{ ?>
            <div class="infoDiv">目前購物車是空的。</div>
            <?php } ?></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
<?php $db_link->close();?>