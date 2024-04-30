<?php
require_once("connMysql.php");
session_start();
//檢查是否經過登入，若有登入則重新導向
if(isset($_SESSION["loginMember"]) && ($_SESSION["loginMember"]!="")){
	//若帳號等級為 member 則導向會員中心
	//if($_SESSION["memberLevel"]=="member"){
	//	header("Location: member_center.php");
	//否則則導向管理中心
	//}else{
	//	header("Location: member_admin.php");	
	//}

  header("Location: index.php");
}

//執行會員登入
if(isset($_POST["username"]) && isset($_POST["passwd"])){
	//繫結登入會員資料
	$query_RecLogin = "SELECT `Username`, `Password`, `ID` FROM `shopper` WHERE `Username`=?";
	$stmt=$db_link->prepare($query_RecLogin);
	$stmt->bind_param("s", $_POST["username"]);
	$stmt->execute();
	//取出帳號密碼的值綁定結果
	$stmt->bind_result($username, $passwd, $ID);	
	$stmt->fetch();
	$stmt->close();
	//比對密碼，若登入成功則呈現登入狀態
  $password_hash = password_hash($passwd, PASSWORD_DEFAULT);
	if(password_verify($_POST["passwd"], $password_hash)){
		//計算登入次數及更新登入時間
		$query_RecLoginUpdate = "UPDATE `shopper` SET `Login`=1, `logintime`=NOW() WHERE `Username`=?";
		$stmt=$db_link->prepare($query_RecLoginUpdate);
	    $stmt->bind_param("s", $username);
	    $stmt->execute();
	    $stmt->close();
		//設定登入者的名稱及等級
		$_SESSION["loginMember"]=$username;
		$_SESSION["memberID"]=$ID;
		//使用Cookie記錄登入資料
		if(isset($_POST["rememberme"])&&($_POST["rememberme"]=="true")){
			setcookie("remUser", $_POST["username"], time()+365*24*60);
			setcookie("remPass", $_POST["passwd"], time()+365*24*60);
		}else{
			if(isset($_COOKIE["remUser"])){
				setcookie("remUser", $_POST["username"], time()-100);
				setcookie("remPass", $_POST["passwd"], time()-100);
			}
		}
		//若帳號等級為 member 則導向會員中心
		//if($_SESSION["memberLevel"]=="member"){
		//	header("Location: member_center.php");
		//否則則導向管理中心
		//}else{
		//	header("Location: member_admin.php");	
		//}
    header("Location: index.php");
	}
  else{
    // echo $_POST["passwd"];
    // echo password_verify($_POST["passwd"],$passwd);
		header("Location: shopperlogin.php?errMsg=1");
    // echo "Fail";
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>3C交易平台</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
  <td height="150" align="center" background="images/mlogo.jpg" class="tdbline"></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td class="tdrline"><p class="title">歡迎光臨網站3C交易平台</p>
          <p>感謝各位來到3C交易平台， 所有的會員功能都必須經由登入後才能使用，請您在右方視窗中執行登入動作。</p>
          <p class="heading"> 本3C交易平台擁有以下的功能：</p>
          <ol>
            <li>免費加入會員 。</li>
            <li>每個會員可修改本身資料。</li>
            <li>若是遺忘密碼，會員可由系統發出電子信函通知。</li>
          </ol>
          <p class="heading">請各位會員遵守以下規則： </p>
          <ol>
            <li> 遵守政府的各項有關法律法規。</li>
            <li> 不得在發佈任何色情非法， 以及危害國家安全的言論。</li>
            <li>嚴禁連結有關政治， 色情， 宗教， 迷信等違法訊息。</li>
            <li> 承擔一切因您的行為而直接或間接導致的民事或刑事法律責任。</li>
            <li> 互相尊重， 遵守互聯網絡道德；嚴禁互相惡意攻擊， 漫罵。</li>
          </ol></td>
        <td width="200">
        <div class="boxtl"></div><div class="boxtr"></div>
<div class="regbox"><?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv"> 登入帳號或密碼錯誤！</div>
          <?php }?>
          <p class="heading">登入3C交易平台</p>
          <form name="form1" method="post" action="">
            <p>帳號：
              <br>
              <input name="username" type="text" class="logintextbox" id="username" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
            </p>
            <p>密碼：<br>
              <input name="passwd" type="password" class="logintextbox" id="passwd" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
            </p>
            <p>
              <input name="rememberme" type="checkbox" id="rememberme" value="true" checked>
記住我的帳號密碼。</p>
            <p align="center">
              <input type="submit" name="button" id="button" value="登入系統">
            </p>
            </form>
          <!-- <p align="center"><a href="admin_passmail.php">忘記密碼，補寄密碼信。</a></p> -->
          <hr size = "1" />
          <p class="heading">還沒有會員帳號?</p>
          <p>註冊帳號免費又容易</p>
          <p align="right"><a href="shopper_join.php">馬上申請會員</a></p>
          <p align="right"><a href="welcomepage.php">回到主頁面</a></p>
</div>
        <div class="boxbl"></div><div class="boxbr"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
	$db_link->close();
?>