<?php
/**
 * FindTrade.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-04-10  16:45
 */

namespace WannanBigPig\Alipay\Payment\Trade;

use WannanBigPig\Alipay\Kernel\Exceptions\SignException;
use WannanBigPig\Alipay\Kernel\Support\Support;
use WannanBigPig\Alipay\Payment\DoctorInterface;
use WannanBigPig\Supports\AccessData;
use WannanBigPig\Supports\Exceptions\BusinessException;
use WannanBigPig\Supports\Exceptions\InvalidArgumentException;

class QueryTrade implements DoctorInterface
{
    /**
     * alipay.trade.query (统一收单线下交易查询)
     * 该接口提供所有支付宝支付订单的查询，商户可以通过该接口主动查询订单状态，完成下一步的业务逻辑。
     * 需要调用查询接口的情况： 当商户后台、网络、服务器等出现异常，商户系统最终未接收到支付通知；
     * 调用支付接口后，返回系统错误或未知交易状态情况； 调用 alipay.trade.pay，返回 INPROCESS 的状态；
     * 调用 alipay.trade.cancel 之前，需确认支付状态；
     *
     * @var string
     */
    private $pay = 'alipay.trade.query';

    /**
     * alipay.trade.fastpay.refund.query (统一收单交易退款查询)
     * 商户可使用该接口查询自已通过 alipay.trade.refund 提交的退款请求是否执行成功。
     * 该接口的返回码 10000，仅代表本次查询操作成功，不代表退款成功。
     * 如果该接口返回了查询数据，则代表退款成功，如果没有查询到则代表未退款成功，可以调用退款接口进行重试。重试时请务必保证退款请求号一致。
     *
     * @var string
     */
    private $refund = 'alipay.trade.fastpay.refund.query';

    /**
     * 业务参数
     *
     * @var
     */
    private $params;

    /**
     * exec
     *
     * @param  array  $params
     *
     * @return $this|mixed
     */
    public function exec(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * payQuery
     *
     * @return AccessData
     *
     * @throws InvalidArgumentException
     * @throws SignException
     * @throws BusinessException
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-12  11:27
     */
    public function pay()
    {
        return Support::executeApi($this->params, $this->pay);
    }

    /**
     * refundQuery
     *
     * @return AccessData
     *
     * @throws BusinessException
     * @throws InvalidArgumentException
     * @throws SignException
     *
     * @author   liuml  <liumenglei0211@163.com>
     * @DateTime 2019-04-12  11:28
     */
    public function refund()
    {
        return Support::executeApi($this->params, $this->refund);
    }
}
