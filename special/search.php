<?php
include("../inc/conn.php");
include("../inc/fy.php");
include("../inc/top.php");
include("../inc/bottom.php");
include("subzt.php");
include("../label.php");

$fp="../template/".$siteskin."/special_search.htm";
$f = fopen($fp,'r');
$strout = fread($f,filesize($fp));
fclose($f);

if (isset($_GET["page_size"])){
$page_size=$_GET["page_size"];
checkid($page_size);
setcookie("page_size_zt",$page_size,time()+3600*24*360);
}else{
$page_size=isset($_COOKIE["page_size_zt"])?$_COOKIE["page_size_zt"]:pagesize_qt;
}
$keyword=isset($_POST["keyword"])?$_POST["keyword"]:'';

if (isset($_GET['b'])){
$bNew=$_GET['b'];
checkid($bNew,1);
setcookie("ztb",$bNew,time()+3600*24);
$b=$bNew;
}else{
$b=isset($_COOKIE["ztb"])?$_COOKIE["ztb"]:0;
}

if (isset($_GET['s'])){
$sNew=$_GET['s'];
checkid($sNew,1);
setcookie("zts",$sNew,time()+3600*24);
$s=$sNew;
}else{
$s=isset($_COOKIE["zts"])?$_COOKIE["zts"]:0;
}
$bigclassname="";
$smallclassname="";
if ($b<>0){
$sql="select * from zzcms_specialclass where classid='$b'";
$rs=query($sql);
$row=fetch_array($rs);
if ($row){
$bigclassname=$row["classname"];
}
}
if ($s<>0) {
$sql="select * from zzcms_specialclass where classid='$s'";
$rs=query($sql);
$row=fetch_array($rs);
if ($row){
	$smallclassname=$row["classname"];
	}	
}
if (isset($_GET['delb'])){
setcookie("ztb","xxx",1);
echo "<script>location.href='search.php'</script>";
}
if (isset($_GET['dels'])){
setcookie("zts","xxx",1);
echo "<script>location.href='search.php'</script>";
}
$pagetitle=sitename."-专题-".$bigclassname;
$pagekeyword=sitename."-专题-".$bigclassname;
$pagedescription=sitename."-专题-".$bigclassname;

function formbigclass(){
		$str="";
        $sql = "select * from zzcms_specialclass where parentid=0";
        $rs=query($sql);
		$row=num_rows($rs);
		if (!$row){
		$str= "请先添加类别名称。";
		}else{
			while($row=fetch_array($rs)){
			$str=$str. "<a href=?b=".$row["classid"].">".$row["classname"]."</a>&nbsp;&nbsp;";
			}
		}
		return $str;
		}
		
		function formsmallclass($b){
		if ($b<>0){
		$str="";
        $sql="select * from zzcms_specialclass where parentid='".$b."' order by xuhao asc";
        $rs=query($sql);
		$row=num_rows($rs);
		if ($row){
			while($row=fetch_array($rs)){
			$str=$str. "<a href=?s=".$row["classid"].">".$row["classname"]."</a>&nbsp;&nbsp;";
			}
		}	
		return $str;
		}
		}

if ($b<>0 || $s<>0 ) {
		$selected="<tr>";
		$selected=$selected."<td align='right'>已选条件：</td>";
		$selected=$selected."<td class='a_selected'>";
			if ($b<>0) {
			$selected=$selected."<a href='?delb=Yes'>".$bigclassname."×</a>&nbsp;";
			}
			if ($s<>0){
			$selected=$selected."<a href='?dels=Yes'>".$smallclassname."×</a>&nbsp;";
			}
		$selected=$selected."</td>";
		$selected=$selected."</tr>";
		}else{
		$selected="";
		}
if( isset($_GET["page"]) && $_GET["page"]!="") {
    $page=$_GET['page'];
	checkid($page);
}else{
    $page=1;
}

$list=strbetween($strout,"{loop}","{/loop}");
$sql="select count(*) as total from zzcms_special where passed<>0 ";
$sql2='';
if ($b<>0){
$sql2=$sql2." and bigclassid='".$b."' ";
}
if ($s<>0 ) {
$sql2=$sql2." and smallclassid='".$s."' ";
}
if ($keyword<>"") {
$sql2=$sql2." and title like '%".$keyword."%' ";
}
$rs =query($sql.$sql2); 
$row = fetch_array($rs);
$totlenum = $row['total'];
$offset=($page-1)*$page_size;//$page_size在上面被设为COOKIESS
$totlepage=ceil($totlenum/$page_size);

$sql="select * from zzcms_special where passed=1 and elite=0";
$sql=$sql.$sql2;
$sql=$sql." order by id desc limit $offset,$page_size";
$rs = query($sql); 

if(!$totlenum){
$strout=str_replace("{#fenyei}","",$strout) ;
$strout=str_replace("{loop}".$list."{/loop}","暂无信息",$strout) ;
}else{
$list2='';
$shuxing='';
$i=0;
while($row= fetch_array($rs)){
if ($row["elite"]>0) {
$listimg="<font color=red>[置顶]</font>&nbsp;";
}elseif (time()-strtotime($row["sendtime"])<3600*24){
$listimg="[最新]&nbsp;" ;
}elseif ($row["hit"]>=1000) {
$listimg="[热门]&nbsp;";					
}else{
$listimg="";
}
if ($row["link"]<>""){
$link=$row["link"];
}else{
$link=getpageurl("special",$row["id"]);
}
if ($row["img"]<>"") {
	$shuxing="<font color='#FF6600'>(图)</font>";
}	
$list2 = $list2. str_replace("{#link}",$link,$list) ;
$list2 =str_replace("{#title}",cutstr($row["title"],30),$list2) ;
$list2 =str_replace("{#sendtime}",date("y-m-d",strtotime($row["sendtime"])),$list2) ;
$list2 =str_replace("{#listimg}" ,$listimg,$list2) ;
$list2 =str_replace("{#shuxing}" ,$shuxing,$list2) ;
$i=$i+1;
}
$strout=str_replace("{loop}".$list."{/loop}",$list2,$strout) ;
$strout=str_replace("{#fenyei}",showpage1("special"),$strout) ;
}
$strout=str_replace("{#siteskin}",$siteskin,$strout) ;
$strout=str_replace("{#sitename}",sitename,$strout) ;
$strout=str_replace("{#station}",getstation(0,"",0,"","",$keyword,"special"),$strout) ;
$strout=str_replace("{#pagetitle}",$pagetitle,$strout);
$strout=str_replace("{#pagekeywords}",$pagekeyword,$strout);
$strout=str_replace("{#pagedescription}",$pagedescription,$strout);
if ($b==0) {//当小类为空显示大类，否则只显小类
$strout=str_replace("{#formbigclass}",formbigclass(),$strout);
}else{
$strout=str_replace("{#formbigclass}","",$strout);
}
$strout=str_replace("{#formsmallclass}",formsmallclass($b),$strout);
$strout=str_replace("{#keyword}",$keyword,$strout);
$strout=str_replace("{#selected}",$selected,$strout);
$strout=str_replace("{#sitebottom}",sitebottom(),$strout);
$strout=str_replace("{#sitetop}",sitetop(),$strout);
$strout=showlabel($strout);
echo $strout;	
?>