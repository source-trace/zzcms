<?php
include("admin.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<title></title>
<script language="JavaScript" src="/js/gg.js"></script>
<script language="JavaScript" src="/js/jquery.js"></script>
<script language="JavaScript" type="text/JavaScript">
function ConfirmDelBig(){
   if(confirm("确定要删除此大类吗？删除此大类同时将删除所包含的小类，并且不能恢复！"))
     return true;
   else
     return false;	 
}
function ConfirmDelSmall(){
   if(confirm("确定要删除此小类吗？一旦删除将不能恢复！"))
     return true;
   else
     return false;	 
}
function CheckForm(){  
if (document.form1.classname.value==""){
    alert("名称不能为空！");
	document.form1.classname.focus();
	return false;
  }
}
//动态增加表单元素大类。
function AddElement_big(){   
//得到需要被添加的html元素。
var TemO=document.getElementById("add");   
//var newInput = document.createElement("<input type='text' size='50' maxlength='50' name='classname[]' value='大类别名称'>"); 
	if($.browser.msie) {
	var newInput = document.createElement("<input type='text' size='50' maxlength='50' name='classname[]' value='大类别名称'>");
	}else{
	var newInput = document.createElement("input");
	newInput.type = "text";
	newInput.name = "classname[]";
	newInput.size = "50";
	newInput.maxlength = "50";
	newInput.value = "大类别名称";
	}
TemO.appendChild(newInput); 
var newline= document.createElement("hr"); 
TemO.appendChild(newline);   
}   
//动态增加表单元素小类。
function AddElement_small(){   
//得到需要被添加的html元素。
var TemO=document.getElementById("add");   
//var newInput = document.createElement("<input type='text' size='50' maxlength='50' name='classname[]' value='小类别名称'>");
if($.browser.msie) {
	var newInput = document.createElement("<input type='text' size='50' maxlength='50' name='classname[]' value='小类别名称'>");
	}else{
	var newInput = document.createElement("input");
	newInput.type = "text";
	newInput.name = "classname[]";
	newInput.size = "50";
	newInput.maxlength = "50";
	newInput.value = "小类别名称";
	}
TemO.appendChild(newInput);     
var newline= document.createElement("hr"); 
TemO.appendChild(newline);   
}   
</script>
</head>
<body>
<?php
$dowhat=isset($_REQUEST['dowhat'])?$_REQUEST['dowhat']:'';
switch ($dowhat){
case "addbigclass";
checkadminisdo("advclass");
addbigclass();
break;
case "addsmallclass";
checkadminisdo("advclass");
addsmallclass();
break;
case "modifybigclass";
checkadminisdo("advclass");
modifybigclass();
break;
case "modifysmallclass";
checkadminisdo("advclass");
modifysmallclass();
break;
default;
showclass();
}
function showclass(){
$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
if ($action=="px") {
checkadminisdo("advclass");
$sql="Select * From zzcms_adclass where parentid='A'";
$rs=query($sql);
while ($row=fetch_array($rs)){
$xuhao=$_POST["xuhao".$row["classid"].""];//表单名称是动态显示的，并于FORM里的名称相同。
	   if (trim($xuhao) == "" || is_numeric($xuhao) == false) {
	       $xuhao = 0;
	   }elseif ($xuhao < 0){
	       $xuhao = 0;
	   }else{
	       $xuhao = $xuhao;
	   }
query("update zzcms_adclass set xuhao='$xuhao' where classid='".$row['classid']."'");
$sqln="Select * From zzcms_adclass where parentid='".$row["classname"]."'";
$rsn=query($sqln);
while ($rown=fetch_array($rsn)){
$xuhaos=$_POST["xuhaos".$rown["classid"].""];//表单名称是动态显示的，并于FORM里的名称相同。
	   if (trim($xuhaos) == "" || is_numeric($xuhaos) == false) {
	       $xuhaos = 0;
	   }elseif ($xuhaos < 0){
	       $xuhaos = 0;
	   }else{
	       $xuhaos = $xuhaos;
	   }
query("update zzcms_adclass set xuhao='$xuhaos' where classid='".$rown['classid']."'");
}
}
}

if ($action=="delbig") {
checkadminisdo("advclass");
$bigclassid=trim($_GET["bigclassid"]);
checkid($bigclassid);
if ($bigclassid<>"") {
$rsn=query("select classname from zzcms_adclass where classid='".$bigclassid."'");
$rown=fetch_array($rsn);
	query("delete from zzcms_adclass where parentid='".$rown['classname']."'");
	query("delete from zzcms_adclass where classid='" . $bigclassid. "'");
}   
echo "<script>location.href='?'</script>";
}

if ($action=="delsmall") {
checkadminisdo("advclass");
$smallclassid=trim($_GET["smallclassid"]);
checkid($smallclassid);
if ($smallclassid<>"") {
	query("delete from zzcms_adclass where classid='" . $smallclassid. "'");
}  
echo "<script>location.href='?#B".$_REQUEST["bigclassid"]."'</script>";
}
?> 
<div class="admintitle">广告类别设置</div>
<div class="border center"><input name="submit3" type="submit" class="buttons" onClick="javascript:location.href='?dowhat=addbigclass'" value="添加大类"></div>
<?php
$sql="Select * From zzcms_adclass where parentid='A' order by xuhao";
$rs=query($sql);
$row=num_rows($rs);
if (!$row){
echo "暂无分类信息";
}else{
?>
<form name="form1" method="post" action="?action=px">
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr> 
      <td width="22%" class="border" ><strong>类别名称</strong></td>
      <td width="25%" class="border" ><strong>排序</strong></td>
      <td width="28%" class="border" ><strong>操作</strong></td>
    </tr>
    <?php while ($row=fetch_array($rs)){?>
    <tr bgcolor="#F1F1F1"> 
      <td style="font-weight:bold"><a name="B<?php echo $row["classid"]?>"></a><img src="image/icobig.gif" width="9" height="9"> 
        <?php echo $row["classname"]?></td>
      <td width="25%" > <input name="<?php echo "xuhao".$row["classid"]?>" type="text"  value="<?php echo $row["xuhao"]?>" size="4"> 
        <input type="submit" name="Submit" value="更新序号"></td>
      <td width="28%" >[ <a href="?dowhat=modifybigclass&classid=<?php echo $row["classid"]?>">修改</a> 
        | <a href="?action=delbig&bigclassid=<?php echo $row["classid"]?>" onClick="return ConfirmDelBig();">删除</a> 
        | <a href="?dowhat=addsmallclass&bigclassid=<?php echo $row["classid"]?>">添加子栏目</a> 
        ] </td>
    </tr>
    <?php
	$n=0;
	$sqln="Select * From zzcms_adclass Where parentid='" . $row["classname"] . "' order by xuhao";
	$rsn=query($sqln);
	$rown=num_rows($rsn);
	if ($rown){
	while ($rown=fetch_array($rsn)){
	?>
    <tr class="bgcolor1" onMouseOver="fSetBg(this)" onMouseOut="fReBg(this)">  
      <td ><a name="S<?php echo $rown["classid"]?>"></a><img src="image/icosmall.gif" width="23" height="11"> 
        <?php echo $rown["classname"]?></td>
      <td><input name="<?php echo "xuhaos".$rown["classid"]?>" type="text"  value="<?php echo $rown["xuhao"]?>" size="4"> 
        <input name="checked" type="submit" id="checked" value="更新序号"></td>
      <td>[ <a href="?dowhat=modifysmallclass&classid=<?php echo $rown["classid"]?>">修改</a> 
        | <a href="?action=delsmall&smallclassid=<?php echo $rown["classid"]?>&bigclassid=<?php echo $row["classid"]?>" onClick="return ConfirmDelSmall();">删除</a> 
        ] </td>
    </tr>
    <?php
		$n=$n+1;
		}
	 }
	}
	  ?>
  </table>
</form>
<?php
	$sqln="select * from zzcms_adclass where parentid='' order by xuhao";
	$rsn=query($sqln);
	$rown=num_rows($rsn);
	if ($rown){
	echo"出现以下无大类的子类，请删除<br>"; 
	while ($rown=fetch_array($rsn)){ 
    echo $rown["classname"]."<a href='?action=delsmall&smallclassid=".$rown["classid"]."'>删除</a><br>";
    $n=$n+1;
	}
	}
} 
}

function addbigclass(){
$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
$FoundErr=0;
$ErrMsg="";
if ($action=="add"){
for($i=0; $i<count($_POST['classname']);$i++){
	$classname=($_POST['classname'][$i]);
	if ($classname!=''){
	$sql="Select * From zzcms_adclass Where classname='" . $classname . "'";
	$rs=query($sql);
	$row=num_rows($rs);
		if (!$row) {
		query("insert into zzcms_adclass (classname,parentid)values('$classname','A')");	
		}
	}
}	
$rsbcid=query("select classid from zzcms_adclass where classname='".$classname."'");
$rowbcid=fetch_array($rsbcid);
$bcid=$rowbcid["classid"];
echo "<script>location.href='?#B".$bcid."'</script>";	
}
if ($FoundErr==1){
WriteErrMsg($ErrMsg);
}else{
?>
<div class="admintitle">添加大类</div>
<form name="form1" method="post" action="?dowhat=addbigclass" onSubmit="return CheckForm();">
  <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr> 
      <td width="100%" class="border">
<div id="add">
	  <input name="classname[]" type="text" id="classname[]" size="50" maxlength="50"  value='大类别名称' >
	  <label for='isshowininfo'></label>
	  <hr/>
	  </div>	  
	   <img src="image/icobigx.gif" width="23" height="11"> <a href="#" onClick='AddElement()'><img src='image/icobig.gif' border="0"> 添加新类别</a>
	  <input name="action" type="hidden" id="action" value="add"> 
        <input name="add" type="submit" value="提交">
	  </td>
    </tr>
  </table>
</form>
<?php
}
}

function addsmallclass(){
$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
$bigclassid=trim($_REQUEST["bigclassid"]);
$FoundErr=0;
$ErrMsg="";
if ($action=="add") {
    for($i=0; $i<count($_POST['classname']);$i++){
    $classname=($_POST['classname'][$i]);
		if ($classname!=''){
		$sql="Select * From zzcms_adclass Where parentid='" . $bigclassid . "' AND classname='" . $classname . "'";
		$rs=query($sql);
		$row=num_rows($rs);
			if (!$row) {
			query("insert into zzcms_adclass (parentid,classname)values('$bigclassid','$classname')");
			}
		}
	}	
    echo "<script>location.href='?#B".$bigclassid."'</script>";	
}
if ($FoundErr==1){
WriteErrMsg($ErrMsg);
}else{
?>
<div class="admintitle">添加小类</div>
<form name="form" method="post" action="?dowhat=addsmallclass" onSubmit="return CheckForm();">
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr> 
      <td width="26%" align="right" class="border">所属大类：</td>
      <td width="74%" class="border"> 
        <?php
		$sqlb = "Select * From zzcms_adclass where parentid='A'";
	    $rsb=query($sqlb);
        $rowb=num_rows($rsb);
		if (!$rowb){
			echo "请先添加大类。";
		}else{
		?>
		<select name="bigclassid" id="bigclassid">
                <option value="" selected="selected">请选择类别</option>
                <?php while($rowb= fetch_array($rsb)){?>
                <option value="<?php echo $rowb["classname"]?>" <?php if ($rowb["classid"]==$bigclassid) { echo "selected";}?>><?php echo $rowb["classname"]?></option>
                <?php
		  }
		  ?>
        </select>
		<?php
		}
		?>		 </td>
    </tr>
    <tr class="tdbg"> 
      <td height="10" align="right" class="border">&nbsp;</td>
      <td class="border">
<div id="add">
	   <input name="classname[]" type="text" size="50" maxlength="50" value="小类别名称" style="margin:4px 0">
<hr/>
	  </div> 
	  <img src="image/icobigx.gif" width="23" height="11"> <a href="#" onClick='AddElement()'><img src='image/icobig.gif' border="0"> 添加新类别</a>	   
      <input name="action" type="hidden" id="action3" value="add">
      <input name="add2" type="submit" value="提交"></td>
    </tr>
  </table>
</form>
<?php
}
}

function modifybigclass(){
$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
$classid=isset($_REQUEST['classid'])?$_REQUEST['classid']:0;
checkid($classid,1);
$FoundErr=0;
$ErrMsg="";
if ($classid==0){
echo "<script>location.href='?'</script>";
}
if ($action=="modify"){
$classname=trim($_POST["classname"]);
$oldclassname=trim($_POST["oldclassname"]);
	$sql="Select * from zzcms_adclass where classid='" .$classid."'";
	$rs=query($sql);
	$row=num_rows($rs);
	if (!$row){
	$FoundErr=1;
	$ErrMsg=$ErrMsg . "<li>此大类不存在！</li>";
	}
	if ($classname<>$oldclassname) {
	$sqln="Select * from zzcms_adclass where parentid='A' and classname='".$classname."'";
	$rsn=query($sqln);
	$rown=num_rows($rsn);
	if ($rown){
	
		$FoundErr=1;
		$ErrMsg=$ErrMsg . "<li>此大类名称已存在！</li>";
	}
	}	
	if ($FoundErr==0){
	query("update zzcms_adclass set classname='$classname' where classid='" .$classid."'");
	
		if ($classname<>$oldclassname) {//类名改变的情况下
			query("update zzcms_adclass set parentid='".$classname."' where parentid='".$oldclassname."'");
			query("Update zzcms_ad set bigclassname='" . $classname . "'  where bigclassname='" . $oldclassname . "' ");	
			
		}	
		echo "<script>location.href='?#B".$classid."'</script>";
	}
}

if ($FoundErr==1){
WriteErrMsg($ErrMsg);
}else{
$sql="Select * from zzcms_adclass where classid=" .$classid."";
$rs=query($sql);
$row=fetch_array($rs);
?>
<div class="admintitle">修改大类</div>
<form name="form1" method="post" action="?dowhat=modifybigclass" onSubmit="return CheckForm();">
  <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr> 
      <td width="322" align="right" class="border">大类ID：</td>
      <td class="border"><?php echo $row["classid"]?> <input name="classid" type="hidden" id="classid" value="<?php echo $row["classid"]?>">      </td>
    </tr>
    <tr> 
      <td align="right" class="border">大类名称：</td>
      <td class="border"> <input name="classname" type="text" id="classname" value="<?php echo $row["classname"]?>" size="60" maxlength="30"> 
        <input name="oldclassname" type="hidden" id="oldclassname" value="<?php echo $row["classname"]?>" size="60" maxlength="30"></td>
    </tr>
    <tr> 
      <td class="border">&nbsp;</td>
      <td class="border"> <input name="action" type="hidden" id="action" value="modify"> 
        <input name="save" type="submit" id="save" value=" 修 改 "> </td>
    </tr>
  </table>
</form>
<?php
}
}

function modifysmallclass(){
$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
$classid=isset($_REQUEST['classid'])?$_REQUEST['classid']:0;
checkid($classid,1);
$FoundErr=0;
$ErrMsg="";
if ($classid==0){echo "<script>location.href='?'</script>";}
if ($action=="modify"){
$bigclassid=trim($_POST["bigclassid"]);
$oldbigclassid=trim($_POST["oldbigclassid"]);
$classname=trim($_POST["classname"]);
$oldclassname=trim($_POST["oldclassname"]);
	$sql="Select * from zzcms_adclass where classid='" .$classid."'";
	$rs=query($sql);
	$row=num_rows($rs);
	if (!$row){
	$FoundErr=1;
	$ErrMsg=$ErrMsg . "<li>此小类不存在！</li>";
	}
	if ($classname<>$oldclassname || $bigclassid<>$oldbigclassid) {
	$sqln="Select * from zzcms_adclass where parentid='".$bigclassid."' and classname='".$classname."'";
	$rsn=query($sqln);
	$rown=num_rows($rsn);
	if ($rown){
		$FoundErr=1;
		$ErrMsg=$ErrMsg . "<li>此小类名称已存在！</li>";
	}
	}
	if ($FoundErr==0) {
	query("update zzcms_adclass set parentid='$bigclassid',classname='$classname' where  classid=" .$classid."");
			if ($bigclassid<>$oldbigclassid) {//小类别改变所属大类情况下
				query("Update zzcms_ad set bigclassname='".$bigclassid ."' where bigclassname='" .$oldbigclassid ."' and smallclassname='" . $classname ."'");
			}
			if ($classname<>$oldclassname) {//小类名改变的情况下
				query("Update zzcms_ad set smallclassname='".$classname ."' where bigclassname='".$bigclassid ."' and smallclassname='".$oldclassname . "'");
			}
			echo "<script>location.href='?#S".$classid."'</script>";
	}
}
if ($FoundErr==1){
WriteErrMsg($ErrMsg);
}else{
$sql="Select * from zzcms_adclass where classid='".$classid."'";
$rs=query($sql);
$row=fetch_array($rs);
?>
<div class="admintitle">修改小类</div>
<form name="form1" method="post" action="?dowhat=modifysmallclass" onSubmit="return CheckForm();">
  <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr> 
      <td width="327" height="22" align="right" class="border">所属大类：</td>
      <td class="border"> 
	    <?php
		$sqlb = "Select * From zzcms_adclass where parentid='A'";
	    $rsb=query($sqlb);
        $rowb=num_rows($rsb);
		if (!$rowb){
			echo "请先添加大类。";
		}else{
		?>
		<select name="bigclassid" id="bigclassid">
                <option value="" selected="selected">请选择类别</option>
                <?php while($rowb= fetch_array($rsb)){?>
                <option value="<?php echo $rowb["classname"]?>" <?php if ($rowb["classname"]==$row["parentid"]) { echo "selected";}?>><?php echo $rowb["classname"]?></option>
                <?php
		  }
		  ?>
        </select>
		<?php
		}
		?>    
    <input name="oldbigclassid" type="hidden" id="oldbigclassid" value="<?php echo $row["parentid"]?>"></td>
    </tr>
    <tr> 
      <td height="11" align="right" class="border">小类名称：</td>
      <td class="border"> <input name="classname" type="text" id="classname" value="<?php echo $row["classname"]?>" size="60" maxlength="30">
        <input name="oldclassname" type="hidden" id="oldclassname" value="<?php echo $row["classname"]?>"></td>
    </tr>
    <tr> 
      <td height="22" class="border">&nbsp;</td>
      <td class="border"> <input name="classid" type="hidden" id="classid" value="<?php echo $row["classid"]?>">
        <input name="action" type="hidden" id="action4" value="modify"> 
        <input name="save" type="submit" id="save" value=" 修 改 "> </td>
    </tr>
  </table>
</form>
<?php
}
}
?>
</body>
</html>