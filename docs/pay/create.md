# 小程序支付

## 调用示例

```php
$result = $alipay->create([
    'out_trade_no' => Str::getRandomInt('lml', 3),
    'total_amount' => 100,
    // ...
]);

// 返回值 WannanBigPig\Supports\AccessData 实现了接口（IteratorAggregate, ArrayAccess, Serializable, Countable）
// 直接 echo 或者调用$result->toJson()方法则返回json字符串
// echo $result['code']; // 10000
echo $result;
```

### 传入参数说明

所有参数请参考[alipay.trade.create\(统一收单交易创建接口\)](https://docs.open.alipay.com/api_1/alipay.trade.create/) ，查看「请求参数」一栏。

### 返回参数

与支付宝返回参数一致\(经过签名验证，剔除了支付宝返回的签名，只保留支付宝接口返回的业务数据\)。

```text
{
    "code": "10000",
    "msg": "Success",
    ...
}
```

ps:小程序支付流程请查看 [小程序支付](https://docs.alipay.com/mini/introduce/pay)

