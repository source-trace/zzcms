<?php
/*******************************************************************************
*  ���⣺PHP��ͼ�μ�������PHPGcount�� 
*  �汾��@ 2009��8��21��1.2
*  ���ߣ�MING MING
*******************************************************************************/
include("../../inc/config.php");
// �趨���ļ������������������������дȷ��׼ȷ���󣡣�

// �������ͳ������Ŀ¼��ַ������һ��β����/��!!!
$base_url = siteurl."/3/gcount/";

// ���Ŀ¼��Ĭ����WEB1�������������ӣ��뵽��ͳ������Ŀ¼/styles/������ӡ�
$default_style = 'web1';

// Default counter image extension
$default_ext = 'gif';

// ֻ���������PVô�� 1 =�ǣ�0 =�� ��Ĭ����0��
$count_unique = 0;

// ��������Сʱ���οͱ������ǡ�����IP��Ĭ��24Сʱ
$unique_hours = 24;

// Minimum number of digits shown (zero-padding). Set to 0 to disable.
$min_digits = 0;

#############################
#  ����Ĳ�Ҫ�༭O(��_��)O  #
#############################

/* Turn error notices off */
error_reporting(E_ALL ^ E_NOTICE);

/* Get page and log file names */
$logfile    = 'logs/test.txt';
/* Get style and extension information */
$style      = input($_GET['style']) or $style = $default_style;
$style_dir  = 'styles/' . $style . '/';
$ext        = input($_GET['ext']) or $ext = $default_ext;

/* Does the log exist? */
if (file_exists($logfile)) {

	/* Get current count */
	$count = intval(trim(file_get_contents($logfile))) or $count = 0;
	$cname = 'gcount_unique_test';

	if ($count_unique==0 || !isset($_COOKIE[$cname]))
    {
		/* Increase the count by 1 */
		$count = $count + 1;
		$fp = @fopen($logfile,'w+') or die('ERROR: Can\'t write to the log file ('.$logfile.'), please make sure this file exists and is CHMOD to 666 (rw-rw-rw-)!');
		flock($fp, LOCK_EX);
		fputs($fp, $count);
		flock($fp, LOCK_UN);
		fclose($fp);

		/* Print the Cookie and P3P compact privacy policy */
		header('P3P: CP="NOI NID"');
		setcookie($cname, 1, time()+60*60*$unique_hours);
	}

    /* Is zero-padding enabled? */
    if ($min_digits > 0)
    {
        $count = sprintf('%0'.$min_digits.'s',$count);
    }

    /* Print out Javascript code and exit */
    $len = strlen($count);
    for ($i=0;$i<$len;$i++)
    {
        echo 'document.write(\'<img src="'.$base_url . $style_dir . substr($count,$i,1) . '.' . $ext .'" border="0">\');';
    }
    exit();

}
else
{
    die('ERROR: Invalid log file!');
}

/* This functin handles input parameters making sure nothing dangerous is passed in */
function input($in)
{
    $out = htmlentities(stripslashes($in));
    $out = str_replace(array('/','\\'), '', $out);
    return $out;
}
?>
