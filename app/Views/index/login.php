<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn" xml:lang="zh-cn" >
<head>
<title>登录</title>
<?php
$this->css(array("base","login"));
?>
<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="/media/css/ie.css" /><![endif]-->

<script type="text/javascript">window.__admin_media_prefix__ = "/media/";</script>
<meta name="robots" content="NONE,NOARCHIVE" />
</head>

<body class="login">

<!-- Container -->
<div id="container"> 
  
  <!-- Header -->
  <div id="header">
    <div id="branding">
      <h1 id="site-name">Django 管理</h1>
    </div>
  </div>
  <!-- END Header --> 
  <!-- Content -->
  <div id="content" class="colM">
    <div id="content-main">
      <form action="/" method="post" id="login-form">
        <div style='display:none'>
          <input type='hidden' name='csrfmiddlewaretoken' value='5e36c6579343a4e605a00a72b00993f4' />
        </div>
        <div class="form-row">
          <label for="id_username">用户名：</label>
          <input type="text" name="username" id="id_username" />
        </div>
        <div class="form-row">
          <label for="id_password">密码：</label>
          <input type="password" name="password" id="id_password" />
          <input type="hidden" name="this_is_the_login_form" value="1" />
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
