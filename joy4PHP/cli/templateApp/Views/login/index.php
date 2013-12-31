<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn" xml:lang="zh-cn" >
<head>
<title>登录</title>
<?php
$this->css(array("base","login"));
?>
<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="/media/css/ie.css" /><![endif]-->
<meta name="robots" content="NONE,NOARCHIVE" />
</head>

<body class="login">

<!-- Container -->
<div id="container"> 
  
  <!-- Header -->
  <div id="header">
    <div id="branding">
      <h1 id="site-name">Login</h1>
    </div>
  </div>
  <!-- END Header --> 
  <!-- Content -->
  <div id="content" class="colM">
    <div id="content-main">
      <form method="post" id="login-form">
        <div class="form-row">
          <label for="id_username">用户名：</label>
          <input type="text" name="username" id="id_username" />
        </div>
        <div class="form-row">
          <label for="id_password">密码：</label>
          <input type="password" name="password" id="id_password" />
        </div>
        <div class="submit-row">
          <label>&nbsp;</label>
          <input type="submit" value="登录" />
        </div>
      </form>
      <script type="text/javascript">
document.getElementById('id_username').focus()
</script> 
    </div>
    <br class="clear" />
  </div>
  <!-- END Content -->
  
  <div id="footer"></div>
</div>
<!-- END Container -->

</body>
</html>
