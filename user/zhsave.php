<?php
include("../inc/conn.php");
include("check.php");
$fpath="text/zhsave.txt";
$fcontent=file_get_contents($fpath);
$f_array=explode("|||",$fcontent) ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title></title>
<link href="style/<?php echo siteskin_usercenter?>/style.css" rel="stylesheet" type="text/css">
<?php
if (str_is_inarr(usergr_power,'zh')=="no" && $usersf=='个人'){
echo $f_array[0];
exit;
}
$page = isset($_POST['page'])?$_POST['page']:1;//返回列表页用
checkid($page);
$id = isset($_POST['id'])?$_POST['id']:0;
checkid($id);

if ($_POST["action"]=="add" && $editor<>''){//$editor<>''防垃圾信息
query("Insert into zzcms_zh(bigclassid,title,address,timestart,timeend,content,editor,sendtime) values('$bigclassid','$title','$address','$timestart','$timeend','$content','$editor','".date('Y-m-d H:i:s')."')") ;  
$id=insert_id();
		
}elseif ($_POST["action"]=="modify"){
query("update zzcms_zh set bigclassid='$bigclassid',title='$title',address='$address',timestart='$timestart',timeend='$timeend',content='$content',
editor='$editor',sendtime='".date('Y-m-d H:i:s')."' where id='$id'");
}		
passed("zzcms_zh");
?>
</head>

<body>
<div class="main">
<?php
include("top.php");
?>
<div class="pagebody">
<div class="left">
<?php
include("left.php");
?>
</div>
<div class="right">
<div class="content">
<table width="400" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td class="tstitle"> <?php
	if ($_REQUEST["action"]=="add") {
      echo $f_array[1];
	  }else{
	  echo $f_array[2];
	  }
	  echo $f_array[3];
     ?>      </td>
  </tr>
  <tr> 
    <td class="border3"><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr bgcolor="#FFFFFF">
        <td width="25%" align="right" bgcolor="#FFFFFF"><?php echo $f_array[4]?> </td>
        <td width="75%"><?php echo $title?></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td align="right" bgcolor="#FFFFFF"><?php echo $f_array[5]?></td>
        <td><?php echo  $address?></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td align="right" bgcolor="#FFFFFF"><?php echo $f_array[6]?></td>
        <td><?php echo $timestart?>-<?php echo $timeend?></td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="5" cellspacing="1" class="bgcolor">
        <tr> 
          <td width="120" align="center" class="bgcolor1"><a href="zhadd.php"><?php echo $f_array[7]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="zhmodify.php?id=<?php echo $id?>"><?php echo $f_array[8]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="zhmanage.php?page=<?php echo $page?>"><?php echo $f_array[9]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="<?php echo getpageurl("zh",$id)?>" target="_blank"><?php echo $f_array[10]?></a></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
unset ($f_array);
?>
</div>
</div>
</div>
</div>
</body>
</html>