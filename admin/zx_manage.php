<?php
include("admin.php");
include("../inc/fy.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/js/gg.js"></script>
<?php
checkadminisdo("zx");
$action=isset($_REQUEST["action"])?$_REQUEST["action"]:'';
if( isset($_GET["page"]) && $_GET["page"]!="") {$page=$_GET['page'];}else{$page=1;}
checkid($page);
$shenhe=isset($shenhe)?$shenhe:'';
$keyword=isset($keyword)?$keyword:'';
$kind=isset($kind)?$kind:'title';
$b=isset($b)?$b:0;
$s=isset($s)?$s:0;

if ($action=="pass"){
if(!empty($_POST['id'])){
    for($i=0; $i<count($_POST['id']);$i++){
    $id=$_POST['id'][$i];
	checkid($id);
	$sql="select passed from zzcms_zx where id ='$id'";
	$rs = query($sql); 
	$row = fetch_array($rs);
		if ($row['passed']=='0'){
		query("update zzcms_zx set passed=1,sendtime='".date('Y-m-d H:i:s')."' where id ='$id'");
		}else{
		query("update zzcms_zx set passed=0,sendtime='".date('Y-m-d H:i:s')."' where id ='$id'");
		}
	}
}else{
echo "<script>alert('操作失败！至少要选中一条信息。');history.back()</script>";
}
echo "<script>location.href='?b=".$b."&keyword=".$keyword."&page=".$page."'</script>";	
}
?>

</head>
<body>
<div class="admintitle">资讯信息管理</div>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr> 
      <td class="border">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td><input name="submit3" type="submit" class="buttons" onClick="javascript:location.href='zx_add.php'" value="发布资讯信息"></td>
            <td align="right"> 
			  <form name="form1" method="post" action="?">
              <input type="radio" name="kind" value="editor" <?php if ($kind=="editor") { echo "checked";}?>>
              按发布人 
              <input type="radio" name="kind" value="title" <?php if ($kind=="title") { echo "checked";}?>>
              按标题 
              <input name="keyword" type="text" id="keyword" value="<?php echo $keyword?>"> 
              <input type="submit" name="Submit" value="查寻">
			  </form>
            </td>
          </tr>
        </table> </td>
    
  </tr>
  <tr> 
    <td class="border2">
 
    <?php	
$sql="select classid,classname from zzcms_zxclass where parentid=0 order by xuhao";
$rs = query($sql); 
while($row = fetch_array($rs)){
echo "<a href=?b=".$row['classid'].">";  
	if ($row["classid"]==$b) {
	echo "<b>".$row["classname"]."</b>";
	}else{
	echo $row["classname"];
	}
	echo "</a> | ";  
 }
 ?>

	</td>
  </tr>
</table>
<?php
$page_size=pagesize_ht;  //每页多少条数据
$offset=($page-1)*$page_size;
$sql="select count(*) as total from zzcms_zx where id<>0 ";
$sql2='';
if ($shenhe=="no") {  		
$sql2=$sql2." and passed=0 ";
}
if ($b<>0) {
$sql2=$sql2." and bigclassid='".$b."' ";
}
if ($s<>0) {
$sql=$sql." and smallclassid='".$s."' ";
}

if ($keyword<>"") {
	switch ($kind){
	case "editor";
	$sql2=$sql2. " and editor like '%".$keyword."%' ";
	break;
	case "title";
	$sql2=$sql2. " and title like '%".$keyword."%'";
	break;
	default:
	$sql2=$sql2. " and title like '%".$keyword."%'";
	}
}

$rs =query($sql.$sql2); 
$row = fetch_array($rs);
$totlenum = $row['total'];  
$totlepage=ceil($totlenum/$page_size);

$sql="select * from zzcms_zx where id<>0 ";
$sql=$sql.$sql2;
$sql=$sql . " order by id desc limit $offset,$page_size";
$rs = query($sql); 
if(!$totlenum){
echo "暂无信息";
}else{
?>
<form name="myform" method="post" action="">
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="border">
    <tr> 
      <td> 
        
          <input name="submit4" type="submit" onClick="myform.action='?action=pass'" value="【取消/审核】选中的信息">
        <input name="submit42" type="submit" onClick="myform.action='del.php';myform.target='_self';return ConfirmDel()" value="删除选中的信息"> 
        <input name="pagename" type="hidden"  value="zx_manage.php?b=<?php echo $b?>&shenhe=<?php echo $shenhe?>&page=<?php echo $page ?>"> 
        <input name="tablename" type="hidden"  value="zzcms_zx"> </td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr> 
      <td width="5%" align="center" class="border">  <label for="chkAll" style="text-decoration: underline;cursor: hand;">全选</label> </td>
      <td width="10%" class="border">所属类别</td>
      <td width="20%" class="border">标题</td>
      <td width="10%" class="border">img</td>
      <td width="5%" align="center" class="border">信息状态</td>
      <td width="5%" align="center" class="border">发布时间</td>
      <td width="10%" align="center" class="border">发布人</td>
      <td width="5%" align="center" class="border">点击次数</td>
      <td width="5%" align="center" class="border">操作</td>
    </tr>
<?php
while($row = fetch_array($rs)){
?>
    <tr class="bgcolor1" onMouseOver="fSetBg(this)" onMouseOut="fReBg(this)"> 
      <td align="center" class="docolor"> <input name="id[]" type="checkbox" id="id" value="<?php echo $row["id"]?>"></td>
      <td ><a href="?b=<?php echo $row["bigclassid"]?>"><?php echo $row["bigclassname"]?></a> - 
	  <a href="?b=<?php echo $row["bigclassid"]?>&s=<?php echo $row["smallclassid"]?>"><?php echo $row["smallclassname"]?></a> </td>
      <td ><a href="<?php echo getpageurl("zx",$row["id"])?>" target="_blank"><?php echo $row["title"]?></a></td>
      <td ><?php echo $row["img"]?></td>
      <td align="center" > <?php if ($row["passed"]==1){ echo"已审核";} else { echo"<font color=red>未审核</font>";}?><br>
<?php if ($row["elite"]<>0) { echo"<font color=red>被置顶(".$row["elite"].")</font>";}?> </td>
      <td align="center" title="<?php echo $row["sendtime"]?>"><?php echo date("Y-m-d",strtotime($row["sendtime"]))?></td>
      <td align="center"><?php echo $row["editor"]?></td>
      <td align="center"><?php echo $row["hit"]?></td>
      <td align="center" class="docolor"><a href="zx_modify.php?id=<?php echo $row["id"]?>&b=<?php echo $b?>&page=<?php echo $page?>">修改</a></td>
    </tr>
    <?php
}
?>
  </table>
  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="border">
    <tr> 
      <td> <input name="chkAll" type="checkbox" id="chkAll" onClick="CheckAll(this.form)" value="checkbox">
        <label for="chkAll">全选</label>
        <input name="submit5" type="submit" onClick="myform.action='?action=pass'" value="【取消/审核】选中的信息">
        <input name="submit422" type="submit" onClick="myform.action='del.php';myform.target='_self';return ConfirmDel()" value="删除选中的信息"> 
      </td>
    </tr>
  </table>
</form>
<div class="border center"><?php echo showpage_admin()?></div>
<?php
}

?>
</body>
</html>