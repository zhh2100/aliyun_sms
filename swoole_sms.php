<?php
// 使用swoole的协程版Http客户端发送短信
go(function(){
    $accessKeyId = '';
    $accessKeySecret = '';
    $params = array (
        'SignName' => $template_sign, // 模板签名
        'Format' => 'JSON',
        'Version' => '2017-05-25',
        'AccessKeyId' => $accessKeyId,
        'SignatureVersion' => '1.0',
        'SignatureMethod' => 'HMAC-SHA1',
        'SignatureNonce' => uniqid(),
        'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
        'Action' => 'SendSms',
        'TemplateCode' => $template_code, // 模板ID
        'PhoneNumbers' => $phone, // 手机号
    );
    // 计算签名并把签名结果加入请求参数
    $params['Signature'] = computeSignature($params, $accessKeySecret);
    
    // 发起GET请求
    $cli = new Swoole\Coroutine\Http\Client('dysmsapi.aliyuncs.com', 80);
    $cli->set(['timeout' => 1]);
    $cli->get('/?'. http_build_query($params));
    // 也可以并发发送 参考https://wiki.swoole.com/wiki/page/p-coroutine_multi_call.html
    
    // 解析json 获取发送状态
    $smsData = json_decode($cli->body, true);
    
    // 以 OK 判断并不准备，建议调用查询接口获取发送状态
    if ($smsData['Code'] == 'OK') {
        // 发送成功
    } else {
        // 失败
    }
});

/**
 * 阿里云短信签名计算
 * @param $parameters
 * @param $accessKeySecret
 *
 * @return string
 */
function computeSignature($parameters, $accessKeySecret) {
    ksort($parameters);
    $canonicalizedQueryString = '';
    foreach ($parameters as $key => $value) {
        $canonicalizedQueryString .= '&' . percentEncode($key) . '=' . percentEncode($value);
    }
    $stringToSign = 'GET&%2F&' . percentencode ( substr ( $canonicalizedQueryString, 1 ) );
    $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
    return $signature;
}

function percentEncode($string) {
    $string = urlencode($string);
    $string = preg_replace('/\+/', '%20', $string);
    $string = preg_replace('/\*/', '%2A', $string);
    $string = preg_replace('/%7E/', '~', $string);
    return $string;
}
