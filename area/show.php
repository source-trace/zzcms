<?php
include("../inc/conn.php");
include("../inc/top.php");
include("../inc/bottom.php");
include("../zs/subzs.php");
include("../label.php");

if (isset($_GET["province"])){
$province=$_GET["province"];
}else{
$province="";
}
$provincezm=$province;
$province=trim(province_zm2hz($province));//ʡ�����Ӽ��±��е������ж����ģ��ü�trimȥ��ȥ���˿հ����ݣ������޷������ݿ��ж�ȡ������
$fp="../template/".$siteskin."/area_show.htm";
$f = fopen($fp,'r');
$strout = fread($f,filesize($fp));
fclose($f);
$strout=str_replace("{#siteskin}",$siteskin,$strout) ;
$strout=str_replace("{#sitename}",sitename,$strout) ;
$strout=str_replace("{#siteurl}",siteurl,$strout) ;
$strout=str_replace("{#pagetitle}",$province.sitetitle,$strout);
$strout=str_replace("{#pagekeywords}",$province.sitekeyword,$strout);
$strout=str_replace("{#pagedescription}",sitedescription,$strout);
$strout=str_replace("{#province}",$province,$strout) ;
$strout=str_replace("{#sitebottom}",sitebottom(),$strout);
$strout=str_replace("{#sitetop}",sitetop(),$strout);
$strout=showlabel($strout);
echo  $strout;
?>