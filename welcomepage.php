<?php
    include("connMysql.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<title>3C平台交易系統</title>
<!-- Style to create button -->
<style>
        .GFG {
            background-color: white;
            border: 2px solid black;
            color: green;
            padding: 5px 10px;
            text-align: center;
            display: inline-block;
            font-size: 20px;
            margin: 10px 30px;
            cursor: pointer;
        }
    </style>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
    <body>
    <table width="780" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
    <td height="80" align="center"><h1 align = "center">歡迎光臨3C平台交易系統，請選擇登入身分</h1></td>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </tr>
  <tr>
  <td class="tdbline" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="10">
<!-- Adding link to the button on the onclick event -->
<button class="GFG" onclick="window.location.href = 'sellerlogin.php';">賣家</button>
<button class="GFG" onclick="window.location.href = 'shopperlogin.php';">消費者</button>
</table>
</td>
</tr>
</table>
    </body>
</html>