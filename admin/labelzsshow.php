<?php 
include("admin.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<title></title>
<?php
$action = isset($_REQUEST['action'])?$_REQUEST['action']:"";
if ($action=="add") {
checkadminisdo("label");
$pic = isset($_POST['pic'])?$_POST['pic'][0]:0;
$flv = isset($_POST['flv'])?$_POST['flv'][0]:0;
$elite = isset($_POST['elite'])?$_POST['elite'][0]:0;

checkstr($numbers,'num','调用记录数');
checkstr($titlenum,'num','标题长度');
checkstr($column,'num','列数');
$start=stripfxg($_POST["start"],true);
$mids=stripfxg($_POST["mids"],true);
$ends=stripfxg($_POST["ends"],true);

$f="../template/".siteskin."/label/zsshow/".$title.".txt";
$fp=fopen($f,"w+");//fopen()的其它开关请参看相关函数
$str=$title . "|||" .$bigclassid . "|||".$smallclassid ."|||" . $groupid. "|||".$pic. "|||".$flv ."|||".$elite . "|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
fputs($fp,$str);
fclose($fp);
$title==$title_old ?$msg='修改成功':$msg='添加成功';
echo "<script>alert('".$msg."');location.href='?labelname=".$title.".txt'</script>";
}

if ($action=="del") {
checkadminisdo("label");
$f="../template/".siteskin."/label/zsshow/".nostr($_POST["title"]).".txt";
	if (file_exists($f)){
	unlink($f);
	}else{
	echo "<script>alert('请选择要删除的标签');history.back()</script>";
	}	
}

$sql = "select * from zzcms_zsclass order by classid asc";
$rs=query($sql);
?>
<script language = "JavaScript">
var onecount;
subcat = new Array();
        <?php
        $count = 0;
		while ($r=fetch_array($rs)){
        ?>
subcat[<?php echo $count?>] = new Array("<?php echo trim($r['classname'])?>","<?php echo trim($r['parentid'])?>","<?php echo trim($r['classzm'])?>");
        <?php
        $count = $count + 1;
        }
        ?>
onecount=<?php echo $count?>;

function changelocation(locationid){
    document.myform.smallclassid.length = 1; 
    var locationid=locationid;
    var i;
    for (i=0;i < onecount; i++)
        {
            if (subcat[i][1] == locationid)
            { 
                document.myform.smallclassid.options[document.myform.smallclassid.length] = new Option(subcat[i][0], subcat[i][2]);
            }        
        }
    }
	
function CheckForm(){
//创建正则表达式
var re=/^[0-9a-zA-Z_]{1,20}$/; //只输入数字和字母的正则
if (document.myform.title.value==""){
    alert("标签名称不能为空！");
	document.myform.title.focus();
	return false;
  }
if(document.myform.title.value.search(re)==-1)  {
    alert("标签名称只能用字母，数字，_ 。且长度小于20个字符！");
	document.myform.title.focus();
	return false;
  }  
if (document.myform.bigclassid.value=="") {
    alert("请选择大类别！");
	document.myform.bigclassid.focus();
	return false;
  } 
}  
</script>
</head>
<body>
<div class="admintitle"><?php echo channelzs?>内容标签</div>
<form action="" method="post" name="myform" id="myform" onSubmit="return CheckForm();">        
  <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr> 
      <td width="150" align="right" class="border" >现有标签：</td>
      <td class="border" >
	  <div class="boxlink"> 
        <?php
$labelname="";
if (isset($_GET['labelname'])){
$labelname=$_GET['labelname'];
if (substr($labelname,-3)!='txt'){
showmsg('只能是txt这种格式');//防止直接输入php 文件地址显示PHP代码
}
}
if (file_exists("../template/".siteskin."/label/zsshow")==false){
echo '文件不存在';
}else{			
$dir = opendir("../template/".siteskin."/label/zsshow");
while(($file = readdir($dir))!=false){
	if ($file!="." && $file!="..") { //不读取. ..
    //$f = explode('.', $file);//用$f[0]可只取文件名不取后缀。
		if ($labelname==$file){
  		echo "<li><a href='?labelname=".$file."' style='color:#000000;background-color:#FFFFFF'>".$file."</a></li>";
		}else{
		echo "<li><a href='?labelname=".$file."'>".$file."</a></li>";
		}
	} 
}
closedir($dir);	  
}	  
//读取现有标签中的内容
if (isset($_REQUEST["labelname"])){
$fp="../template/".siteskin."/label/zsshow/".$labelname;
$f=fopen($fp,"r");
$fcontent=fread($f,filesize($fp));
fclose($f);
} 

$fcontent=removeBOM($fcontent);//去除BOM信息，使修改时不用再重写标签名
$f=explode("|||",$fcontent) ;
$title=$f[0];
$bigclassid=$f[1];
$smallclassid=$f[2];
$groupid=$f[3];
$pic=$f[4];
$flv=$f[5];
$elite=$f[6];
$numbers=$f[7];
$orderby=$f[8];
$titlenum=$f[9];
$column=$f[10];
$start=htmlspecialchars($f[11]);//直接显示记事本中存储的HTML实体字符
$mids=htmlspecialchars($f[12]);
$ends=htmlspecialchars($f[13]);	
} 
	   ?>
	   </div>
      </td>
    </tr>
    <tr> 
      <td align="right" class="border" >标签名称：</td>
      <td class="border" >
<input name="title" type="text" id="title" value="<?php echo $title?>" size="50" maxlength="255">
<input name="title_old" type="hidden" id="title_old" value="<?php echo $title?>" size="50" maxlength="255">      </td>
    </tr>
    <tr> 
      <td align="right" class="border" >调用内容：</td>
      <td class="border" > <select name="bigclassid" onChange="changelocation(document.myform.bigclassid.options[document.myform.bigclassid.selectedIndex].value)" size="1">
          <option value="0" selected>不指定大类</option>
          <?php
       $sql = "select classid,classname from zzcms_zsclass where parentid=0 order by xuhao asc";
       $rs=query($sql);
		   while($r=fetch_array($rs)){
			?>
          <option value="<?php echo $r["classid"]?>" <?php if ($r["classid"]==$bigclassid) { echo "selected";}?>> 
         <?php echo trim($r["classname"])?></option>
          <?php   
    	     }	
		 ?>
        </select> <select name="smallclassid">
          <option value="0" selected>不指定小类</option>
          <?php if ($bigclassid<>0){
			$sql="select classid,classname from zzcms_zsclass where parentid='" . $bigclassid ."' order by classid asc";
			$rs=query($sql);
			while($r=fetch_array($rs)){
			?>
          <option value="<?php echo $r["classid"]?>" <?php if ($r["classid"]==$smallclassid) { echo "selected";}?>><?php echo $r["classname"]?></option>
          <?php   
			}
		}
			?>
        </select> 
		<select name="groupid">
		 <option value="0" >所的会员</option>
          <?php
			$rsn=query("select * from zzcms_usergroup order by groupid asc");
			$r=num_rows($rsn);
			if ($r){
			while ($r=fetch_array($rsn)){
				if ($r["groupid"]==$groupid){
			 	echo "<option value='".$r["groupid"]."' selected>".$r["groupname"]."</option>";
				}else{
				echo "<option value='".$r["groupid"]."' >".$r["groupname"]."</option>";
				}
			}
			}
			?>
        </select>
        <input name="pic[]" type="checkbox" id="pic" value="1" <?php if ($pic==1){ echo " checked";}?>>
        有图片的 
        <input name="flv[]" type="checkbox" id="flv[]" value="1" <?php if ($flv==1){ echo " checked";}?>>
        有视频的 
        <input name="elite[]" type="checkbox" id="elite" value="1" <?php if ($elite==1) { echo " checked";}?>>
        推荐的 </td>
    </tr>
    <tr> 
      <td align="right" class="border" >调用记录条数：</td>
      <td class="border" ><input name="numbers" type="text"  value="<?php echo $numbers?>" size="10" maxlength="255"> 
      </td>
    </tr>
    <tr > 
      <td align="right" class="border" >排序方式设置：</td>
      <td class="border" > <select name="orderby" id="orderby">
          <option value="id" <?php if ($orderby=="id") { echo "selected";}?>>最新发布</option>
		   <option value="sendtime" <?php if ($orderby=="sendtime") { echo "selected";} ?>>最近更新</option>
          <option value="hit" <?php if ($orderby=="hit") { echo "selected";}?>>最多点击</option>
        <option value="rand" <?php if ($orderby=="rand") { echo "selected";}?>>随机显示</option>
		</select></td>
    </tr>
    <tr > 
      <td align="right" class="border" >标题长度：</td>
      <td class="border" > <input name="titlenum" type="text" id="titlenum" value="<?php echo $titlenum?>" size="20" maxlength="255"></td>
    </tr>
    <tr> 
      <td align="right" class="border" >列数：</td>
      <td class="border" > <input name="column" type="text" id="column" value="<?php echo $column?>" size="20" maxlength="255">
        （分几列显示）</td>
    </tr>
    <tr> 
      <td align="right" class="border" >解释模板（开始）：</td>
      <td class="border" ><textarea name="start" cols="100" rows="6" id="start" style="width:100%"><?php echo $start?></textarea></td>
    </tr>
    <tr> 
      <td align="right" class="border" >解释模板（循环）：</td>
      <td class="border" ><textarea name="mids" cols="100" rows="6" id="mids" style="width:100%"><?php echo $mids ?></textarea> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="border" >解释模板（结束）：</td>
      <td class="border" ><textarea name="ends" cols="100" rows="6" id="ends" style="width:100%"><?php echo $ends ?></textarea></td>
    </tr>
    <tr> 
      <td align="right" class="border" >&nbsp;</td>
      <td class="border" > <input type="submit" name="Submit" value="添加/修改" onClick="myform.action='?action=add'"> 
        <input type="submit" name="Submit2" value="删除选中的标签" onClick="myform.action='?action=del'"></td>
    </tr>
  </table>
      </form>		  
</body>
</html>