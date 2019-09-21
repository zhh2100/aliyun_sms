<?php
$sms=new Sms();
var_dump($sms->sendSms('19876926582', '{"code":"1234"}'));
class Sms
{
	
	public $config=array('appId'=>'oUU13b1Mo','appKey'=>'ebFsdd','tplId'=>'SMS_174580608','signName'=>'销量联盟');
    /**
     * 发送短信
     * @param $mobile 手机号
     * @param $TemplateParam  str      '{"code":"1234"}'
     *
     * @return mixed
     */
    public function sendSms($mobile, $TemplateParam)
    {
        // 获取配置信息
        $config = $this->config;
        $params = array (
            'SignName' => $config['signName'],
            'Format' => 'JSON',
            'Version' => '2017-05-25',
            'AccessKeyId' => $config['appId'],
            'SignatureVersion' => '1.0',
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureNonce' => uniqid(),
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'Action' => 'SendSms',
            'TemplateCode' => $config['tplId'],
            'PhoneNumbers' => $mobile,
            // 营销短信无需 TemplateParam 参数
            'TemplateParam' => $TemplateParam //'{"code":"' . $code . '"}'
        );

        // 计算签名并把签名结果加入请求参数
        $params ['Signature'] = $this->computeSignature($params, $config['appKey']);

        // 发送请求
        $url = 'https://dysmsapi.aliyuncs.com/?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
		$result=json_decode($result,true);
		return $result['Code']=='OK'?  true : false;
        //return $result;
    }

    /**
     * 短信签名计算
     * @param $parameters
     * @param $accessKeySecret
     *
     * @return string
     */
    protected function computeSignature($parameters, $accessKeySecret) {
        ksort($parameters);
        $canonicalizedQueryString = '';
        foreach ($parameters as $key => $value) {
            $canonicalizedQueryString .= '&' . $this->percentEncode($key) . '=' . $this->percentEncode($value);
        }
        $stringToSign = 'GET&%2F&' . $this->percentencode ( substr ( $canonicalizedQueryString, 1 ) );
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
        return $signature;
    }

    protected function percentEncode($string) {
        $string = urlencode($string);
        $string = preg_replace('/\+/', '%20', $string);
        $string = preg_replace('/\*/', '%2A', $string);
        $string = preg_replace('/%7E/', '~', $string);
        return $string;
    }
}
