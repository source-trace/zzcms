<?php
/*--------------------------------
����:		PHP HTTP�ӿ� ���Ͷ���
�޸�����:	2013-05-08
˵��:		http://m.5c.com.cn/api/send/?username=�û���&password=����&mobile=�ֻ���&content=����&apikey=apikey
״̬:
	���ͳɹ�	success:msgid
	����ʧ��	error:msgid

ע�⣬��curl֧�֡�

����ֵ											˵��
success:msgid								�ύ�ɹ�������״̬���4.1
error:msgid								�ύʧ��
error:Missing username						�û���Ϊ��
error:Missing password						����Ϊ��
error:Missing apikey						APIKEYΪ��
error:Missing recipient					�ֻ�����Ϊ��
error:Missing message content				��������Ϊ��
error:Account is blocked					�ʺű�����
error:Unrecognized encoding				����δ��ʶ��
error:APIKEY or password error				APIKEY ���������
error:Unauthorized IP address				δ��Ȩ IP ��ַ
error:Account balance is insufficient		����
error:Black keywords is:������				���δ�
--------------------------------*/
//$username = 'test';		//�û��˺�
//$password = '123456';	//����
//$apikey = '��������Ա��ȡ';	//����
//$mobile	 = '13811299934,18610310066,15210954922';	//���ֻ���
//$content = '���Ķ�����֤���ǣ�ABCD��ע�⣬�����ǩ����ǩ����';		//����

//��ʱ����
//$result = sendSMS($username,$password,$mobile,$content,$apikey);
//echo $result;
function sendSMS($username,$password,$mobile,$content,$apikey){
	$url = 'http://m.5c.com.cn/api/send/?';
	$data = array
		(
		'username'=>$username,
		'password'=>$password,
		'mobile'=>$mobile,
		'content'=>$content,
		'apikey'=>$apikey,
		);
	$result= curlSMS($url,$data);
	return $result;
}

function curlSMS($url,$post_fields=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //60�� 
        curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_REFERER,'http://www.yourdomain.com');
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
        $data = curl_exec($ch);
        curl_close($ch);
        $res = explode("\r\n\r\n",$data);
        return $res[2]; 
}
?>