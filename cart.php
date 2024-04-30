<?php
require_once("connMysql.php");
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
//更新購物車內容
if (isset($_POST["cartaction"]) && ($_POST["cartaction"] == "update")) {
  if (isset($_POST["updateid"])) {
    $i = count($_POST["updateid"]);
    for ($j = 0; $j < $i; $j++) {
      $product = $cart->getItem($_POST['updateid'][$j]);
      $cart->update($product['id'], $_POST['qty'][$j], [
        'price' => $product['attributes']['price'],
        'pname' => $product['attributes']['pname'],
      ]);
    }
  }
  header("Location: cart.php");
}
//移除購物車內容
if (isset($_GET["cartaction"]) && ($_GET["cartaction"] == "remove")) {
  $rid = intval($_GET['delid']);
  $cart->remove($rid);
  header("Location: cart.php");
}
//清空購物車內容
if (isset($_GET["cartaction"]) && ($_GET["cartaction"] == "empty")) {
  $cart->clear();
  header("Location: cart.php");
}
//購物車結束

//繫結產品目錄資料
$query_RecCategory = "SELECT DISTINCT BrandName FROM product";
$RecCategory = $db_link->query($query_RecCategory);

//計算資料總筆數
$query_RecTotal = "SELECT count(pID) as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>網路購物系統</title>
  <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
  <table width="780" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
      <td height="150" align="center" background="images/mlogo.jpg" class="tdbline"></td>
    </tr>
    <tr>
      <td class="tdbline">
        <table width="100%" border="0" cellspacing="0" cellpadding="10">
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
                <!-- <ul>
                  <li><a href="index.php?">所有產品 <span class="categorycount">(<?php // echo $row_RecTotal["totalNum"]; ?>)</span></a></li>
                  <?php // while ($row_RecCategory = $RecCategory->fetch_assoc()) { ?>
                  <?php // } ?>
                </ul> -->
              </div>
              <div class="boxbl"></div>
              <div class="boxbr"></div>
            </td>
            <td>
              <div class="subjectDiv"> <span class="heading"><img src="images/16-cube-green.png" width="16" height="16" align="absmiddle"></span> 購物車內容</div>
              <div class="normalDiv">
                <?php if ($cart->getTotalitem() > 0) { ?>
                  <form action="" method="post" name="cartform" id="cartform">
                    <table width="98%" border="0" align="center" cellpadding="2" cellspacing="1">
                      <tr>
                        <th bgcolor="#ECE1E1"><p>刪除</p></th>
                        <th bgcolor="#ECE1E1"><p>產品名稱</p></th>
                        <th bgcolor="#ECE1E1"><p>數量</p></th>
                        <th bgcolor="#ECE1E1"><p>單價</p></th>
                        <th bgcolor="#ECE1E1"><p>小計</p></th>
                      </tr>
                      <?php
                      $allItems = $cart->getItems();
                      foreach ($allItems as $items) {
                        foreach ($items as $item) {
                      ?>
                      <tr>
                        <td align="center" bgcolor="#F6F6F6" class="tdbline">
                          <p><a href="?cartaction=remove&delid=<?php echo $item['id']; ?>">移除</a></p>
                        </td>
                        <td bgcolor="#F6F6F6" class="tdbline">
                          <p><?php echo $item['attributes']['pname']; ?></p>
                        </td>
                        <td align="center" bgcolor="#F6F6F6" class="tdbline">
                          <p><input name="updateid[]" type="hidden" id="updateid[]" value="<?php echo $item['id']; ?>"><input name="qty[]" type="text" id="qty[]" value="<?php echo $item['quantity']; ?>" size="1"></p>
                        </td>
                        <td align="center" bgcolor="#F6F6F6" class="tdbline">
                          <p>$ <?php echo number_format($item['attributes']['price']); ?></p>
                        </td>
                        <td align="center" bgcolor="#F6F6F6" class="tdbline">
                          <p>$ <?php echo number_format($item['quantity'] * $item['attributes']['price']); ?></p>
                        </td>
                      </tr>
                      <?php }} ?>
                      <tr>
                        <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>總計</p></td>
                        <td valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                        <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                        <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                        <td align="center" valign="baseline" bgcolor="#F6F6F6"><p class="redword">$ <?php echo number_format($cart->getAttributeTotal('price')); ?></p></td>
                      </tr>
                    </table>
                    <hr width="100%" size="1" />
                    <p align="center">
                      <input name="cartaction" type="hidden" id="cartaction" value="update">
                      <input type="submit" name="updatebtn" id="button3" value="更新購物車">
                      <input type="button" name="emptybtn" id="button5" value="清空購物車" onClick="window.location.href='?cartaction=empty'">
                      <input type="button" name="button" id="button6" value="前往結帳" onClick="window.location.href='checkout.php';">
                      <input type="button" name="backbtn" id="button4" value="回上一頁" onClick="window.history.back();">
                    </p>
                  </form>
              </div>
            <?php } else { ?>
              <div class="infoDiv">目前購物車是空的。</div>
            <?php } ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <!-- <tr>
      <td height="30" align="center" background="images/album_r2_c1.jpg" class="trademark">© 2008 eHappy Studio All Rights Reserved.</td>
    </tr> -->
  </table>
</body>

</html>
<?php $db_link->close(); ?>