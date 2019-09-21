# aliyun-sms
:point_right:阿里云短信要什么发送类，直接写方法

## 使用方法

1、复制`Sms.php`文件中的 3 个方法，放到某个控制器或文件中

2、修改获取配置信息

```php
$config = $this->config['sms'];
```

`sendSms`方法中的`$config`为获取的如下配置信息

```php
$sms = [
    'appId'  => '', // 阿里云 AccessKeyID
    'appKey' => '', // 阿里云 AccessKeySecret
    'tplId' => 'SMS_888888888', // 短信模板ID
    'signName' => '', // 短信签名
];
```

3、修改`TemplateParam`短信模板变量

发送验证码和短信通知，需要替换短信模板变量JSON串，修改为你所需要对应的变量，如

```json
{"code":"1234","product":"syz"}
```

推广短信、营销短信不支持在模板中添加变量，也就不需要这个参数，直接注释掉即可

4、调用`sendSms`方法发送短信

## 错误码列表

|           Code            |             描述             |
| :-----------------------: | :--------------------------: |
|            OK             |           请求成功           |
|  isp.RAM_PERMISSION_DENY  |         RAM权限DENY          |
|    isv.OUT_OF_SERVICE     |           业务停机           |
| isv.PRODUCT_UN_SUBSCRIPT  | 未开通云通信产品的阿里云客户 |
|  isv.PRODUCT_UNSUBSCRIBE  |          产品未开通          |
|  isv.ACCOUNT_NOT_EXISTS   |          账户不存在          |
|   isv.ACCOUNT_ABNORMAL    |           账户异常           |
| isv.SMS_TEMPLATE_ILLEGAL  |        短信模板不合法        |
| isv.SMS_SIGNATURE_ILLEGAL |        短信签名不合法        |
| isv.INVALID_PARAMETERS |	参数异常 |
| isp.SYSTEM_ERROR |	系统错误 |
| isv.MOBILE_NUMBER_ILLEGAL |	非法手机号 |
| isv.MOBILE_COUNT_OVER_LIMIT |	手机号码数量超过限制	|
| isv.TEMPLATE_MISSING_PARAMETERS |	模板缺少变量 |
| isv.BUSINESS_LIMIT_CONTROL | 业务限流 |
| isv.INVALID_JSON_PARAM | JSON参数不合法，只接受字符串值 |
| isv.BLACK_KEY_CONTROL_LIMIT |	黑名单管控 |
| isv.PARAM_LENGTH_LIMIT | 参数超出长度限制 |
| isv.PARAM_NOT_SUPPORT_URL |	不支持URL |
| isv.AMOUNT_NOT_ENOUGH |	账户余额不足 |
