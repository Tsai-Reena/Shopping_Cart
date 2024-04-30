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
// 新增購物車內容
if(isset($_POST["cartaction"]) && ($_POST["cartaction"]=="add")){
  $cart->add($_POST['id'], $_POST['qty'], [
      'price' => $_POST['price'],
      'pname' => $_POST['name'],
  ]);  
	header("Location: cart.php");
}
//購物車結束

//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: shopperlogin.php");
}

//繫結登入會員資料
$query_RecMember = "SELECT * FROM `shopper` WHERE `Username` = '{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember=$RecMember->fetch_assoc();

//繫結產品資料
$query_RecProduct = "SELECT * FROM product WHERE pID=?";
$stmt = $db_link->prepare($query_RecProduct);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$RecProduct = $stmt->get_result();
$row_RecProduct = $RecProduct->fetch_assoc();

// 取得 seller username
$query_RecSeller = "SELECT s.Username FROM seler AS s, product AS p WHERE p.pID=? AND p.sellerID=s.ID";
$stmt_seller = $db_link->prepare($query_RecSeller);
$stmt_seller->bind_param("i", $_GET["id"]);
$stmt_seller->execute();
$RecSeller = $stmt_seller->get_result();
$row_RecSeller = $RecSeller->fetch_assoc();

// 取得 shopper username
// $query_RecSeller = "SELECT s.Username FROM shopper AS s, product AS p WHERE p.pID=? AND p.sellerID=s.ID";
// $stmt_seller = $db_link->prepare($query_RecSeller);
// $stmt_seller->bind_param("i", $_GET["id"]);
// $stmt_seller->execute();
// $RecSeller = $stmt_seller->get_result();
// $row_RecSeller = $RecSeller->fetch_assoc();

//繫結產品目錄資料
$query_RecCategory = "SELECT DISTINCT BrandName FROM product";
$RecCategory = $db_link->query($query_RecCategory);

//計算資料總筆數
$query_RecTotal = "SELECT count(pID) as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();

//預設每頁筆數
$pageRow_records = 5;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecBoard = "SELECT * FROM `comments_on` ORDER BY `shopperID` DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecBoard = $query_RecBoard." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $RecBoard 中
$RecBoard = $db_link->query($query_limit_RecBoard);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecBoard 中
$all_RecBoard = $db_link->query($query_RecBoard);
//計算總筆數
// $total_records = $all_RecBoard->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
// $total_pages = ceil($total_records/$pageRow_records);

function GetSQLValueString($theValue, $theType) {
  switch ($theType) {
    case "string":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_ADD_SLASHES) : "";
      break;
    case "int":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
      break;     
  }
  return $theValue;
}

// if(isset($_POST["action"])&&($_POST["action"]=="comment")){
// 	$query_insert = "INSERT INTO `comments_on` (`Subject` , `Feedback`, `shopperID`, `productID`, `CommentsDate`) VALUES (?, ?, ?, ?, NOW())";
// 	$stmt = $db_link->prepare($query_insert);
// 	$stmt->bind_param("ssii",
// 		GetSQLValueString($_POST["boardsubject"], 'string'),
// 		GetSQLValueString($_POST["boardcontent"], 'string'),
//     GetSQLValueString($row_RecMember["ID"], 'string'),
//     GetSQLValueString($row_RecProduct["pID"], 'string'));
// 	$stmt->execute();
// 	$stmt->close();
// 	$db_link->close();
// 	//重新導向回到主畫面
// 	// header("Location: product.php?id=".$_GET["id"]);
//   echo $row_RecProduct["pID"];
// }	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>3C交易平台</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){
	if(document.formPost.boardsubject.value==""){
		alert("請填寫標題!");
		document.formPost.boardsubject.focus();
		return false;
	}
	if(document.formPost.boardcontent.value==""){
		alert("請填寫留言內容!");
		document.formPost.boardcontent.focus();
		return false;
	}
	return confirm('確定送出嗎？');
}
</script>
</head>

<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
  <td height="150" align="center" background="images/mlogo.jpg" class="tdbline"></td>  </tr>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td width="200" class="tdrline">
            <div class="boxtl"></div>
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
        <td><div class="subjectDiv"> <span class="heading"><img src="images/16-cube-green.png" width="16" height="16" align="absmiddle"></span> 產品詳細資料</div>
          <div class="actionDiv"><p align="right"><?php echo $row_RecMember["Username"];?>，歡迎！ | <a href="cart.php">我的購物車</a> | <a href="?logout=true">登出系統</a></p></div>
          <div class="albumDiv">
            <div class="picDiv">
              <?php if($row_RecProduct["BrandName"]==""){?>
              <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
              <?php  }else{?>
                <img src="images/brandname/<?php
                if($row_RecProduct["BrandName"] == "ASUS") echo "ASUS_Zenbook";
                else if($row_RecProduct["BrandName"] == "Apple") echo "iPad";
                else if($row_RecProduct["BrandName"] == "Google") echo "Google";
                else if($row_RecProduct["BrandName"] == "Samsung") echo "Samsung_Phone";
                else if($row_RecProduct["BrandName"] == "ACER") echo "ACER";
                else if($row_RecProduct["BrandName"] == "MSI") echo "MSI";
                else echo "nopic";
                ?>.png" alt="<?php echo $row_RecProduct["ProductName"];?>" width="135" height="135" border="0" />
              <?php  }?>
            </div>
            <div class="albuminfo"><span class="smalltext">特價 </span><span class="redword"><?php echo $row_RecProduct["Price"];?></span><span class="smalltext"> 元</span>            </div>
          </div>
          <div class="titleDiv">
            <?php echo $row_RecProduct["ProductName"];?></div>
          <div class="dataDiv">
            <p><?php echo nl2br($row_RecProduct["Description"]);?></p>
            <ul>
              <li>產品品牌：<?php echo $row_RecProduct["ProductName"];?></li>
              <li><?php if($row_RecProduct["SecondHand"]) echo "二手";
              else echo "全新";?></li>
              <li>庫存數量：<span class="redword"><?php echo $row_RecProduct["Quantity"];?></span></li>
              <li>賣家名稱：<?php echo $row_RecSeller["Username"];?></li>
            </ul>
            <hr width="100%" size="1" />
            <form name="form3" method="post" action="">
              <input name="id" type="hidden" id="id" value="<?php echo $row_RecProduct["pID"];?>">
              <input name="name" type="hidden" id="name" value="<?php echo $row_RecProduct["ProductName"];?>">
              <input name="price" type="hidden" id="price" value="<?php echo $row_RecProduct["Price"];?>">
              <input name="qty" type="hidden" id="qty" value="1">
              <input name="cartaction" type="hidden" id="cartaction" value="add">
              <input type="submit" name="button3" id="button3" value="加入購物車">
              <input type="button" name="button4" id="button4" value="回上一頁" onClick="window.history.back();">
            </form>
          </div>
        </td>
        </tr>
    </table></td>
  </tr>
  <!-- <tr>
    <?php	
    // $i=0;
    // while($row_RecBoard=$RecBoard->fetch_assoc()){ 
    //   $i++; 
    ?>
      <tr valign="top">
        <td class="underline">
            <span class="smalltext">[<?php // echo $i;?>]</span>
            <span class="heading"> <?php // echo $row_RecBoard["Subject"];?></span>
            <p><?php // echo nl2br($row_RecBoard["Feedback"]);?></p>
            <p align="right" class="smalltext">
            <?php // echo $row_RecBoard["CommentsDate"];?>
            </p>
        </td>
      </tr>
    <?php // }?>
  </tr> -->
</table>
<!-- <form action="" method="post" name="formPost" id="formPost" onSubmit="return checkForm();">
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
<tr valign="top">
      <td width="80" align="center"><img src="images/talk.gif" alt="我要留言" width="80" height="80"><span class="heading">留言</span></td>
      <td>
        <p>標題：<input type="text" name="boardsubject" id="boardsubject"></p>
        <p>姓名：<?php // echo $row_RecMember["Username"];?></p>
        <p>性別：<?php // echo $row_RecMember["Gender"];?></p>
      </td>
      <td align="center">
        <textarea name="boardcontent" id="boardcontent" cols="40" rows="10"></textarea>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="3" align="center" valign="middle">
        <input name="action" type="hidden" id="action" value="comment">
        <input type="submit" name="button5" id="button5" value="送出留言">
        <input type="reset" name="button6" id="button6" value="重設資料">
        <input type="button" name="button7" id="button7" value="回上一頁" onClick="window.history.back();"></td>
    </tr>
    </table>
      </form> -->
</body>
</html>