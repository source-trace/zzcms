<?php
set_time_limit(1800);
include("admin.php");
ob_end_clean();//��ֹ���塣�����Ͳ��õȵ���4096bytes�Ļ���֮��ű����ͳ�ȥ�ˡ�
echo str_pad(" ",256);//IE��Ҫ���ܵ�256���ֽ�֮��ſ�ʼ��ʾ��
include ("../3/mobile_msg/inc.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312"> <!--����GB2312�����򷵻���Ϣ�������ŷ���վӦ��BG2312-->
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="border" style="padding:10px"> 
<div style="padding:10px;background-color:#FFFFFF">
<?php
checkadminisdo("dl");
$id="";
if(!empty($_POST['id'])){
    for($i=0; $i<count($_POST['id']);$i++){
    $ids=$_POST['id'][$i];
	$ids=explode("|",$ids);
	//$id=$ids[0];
	$id=$id.($ids[0].',');
	}
	$id=substr($id,0,strlen($id)-1);//ȥ��������","
}else{
echo "<script lanage='javascript'>alert('����ʧ�ܣ�����Ҫѡ��һ����Ϣ��');window.opener=null;window.open('','_self');window.close()</script>";
exit;
}
if (strpos($id,",")>0){
$sql="select * from zzcms_dl where saver<>'' and id in (". $id .")";//û�н����˵ģ�������������÷���ʾ�ʼ���
}else{
$sql="select * from zzcms_dl where saver<>'' and id=".$id."";
}
$rs=query($sql);
$row=num_rows($rs);
	while($row=fetch_array($rs)){
	$rsn=query("select username,sex,mobile,somane from zzcms_user where username='".$row["saver"]."'");
	$rown=num_rows($rsn);
	if (!$rown){	
		echo "û������û�";
	}else{
		$rown=fetch_array($rsn);
		$fbr_mobile=$rown["mobile"];
		$somane=$rown["somane"];
		$sex=$rown["sex"];
		if ($sex==1) {
			$sex="����";
		}elseif ($sex==0) {
			$sex="Ůʿ";
		}
		$msg = $somane .$sex."���ã�������".sitename."�ϸ���������Ҫ".channeldl.$row["cp"]."���¼��վ�鿴����,��ַ��".siteurl."���ڱ�վע����û����ǣ�".$row["saver"];		$msg = iconv("UTF-8","GBK",$msg);
		//=============== �� �� ================
		$result = sendSMS($smsusername,$smsuserpass,$fbr_mobile,$msg,$apikey);
		echo $result."<br>";	
	}
	flush();  //���ڻ����еĻ���˵�Ǳ��ͷų��������ݷ��͵������  
	//sleep(5);��������		
}		      
?>
</div>
</div>
<div style="text-align:center;padding:10px" class="border">
    <input name="Submit" type="button" class="buttons" onClick="parent.window.opener=null;parent.window.open('','_self');parent.window.close();" value="close">
</div>
</body>
</html>