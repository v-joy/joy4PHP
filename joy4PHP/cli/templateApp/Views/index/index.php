<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn" xml:lang="zh-cn" >
<head>
<title>首页</title>
<?php
$this->css(array("base","login"));
?>
</head>

<body>

<!-- Container -->
<div id="container"> 
  
  <!-- Header -->
  <div id="header">
    <div id="branding">
      <h1 id="site-name">首页</h1>
    </div>
  </div>
  <!-- END Header --> 
  <!-- Content -->
  <div id="content" class="colM">
    <div id="content-main">
    这是主页面<br>
    点击 <a href="<?php echo $this->getIndexUrl() ?>/admin">此处</a> 进入管理界面
    </div>
    <br class="clear" />
  </div>
  <!-- END Content -->
  
  <div id="footer">powered by JOY4PHP</div>
</div>
<!-- END Container -->

</body>
</html>
