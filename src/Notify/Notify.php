<?php
/**
 * Notify.php
 *
 * Created by PhpStorm.
 *
 * author: liuml  <liumenglei0211@163.com>
 * DateTime: 2019-04-16  17:20
 */

namespace WannanBigPig\Alipay\Notify;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WannanBigPig\Alipay\Kernel\Events\SignFailed;
use WannanBigPig\Alipay\Kernel\Support\Support;
use WannanBigPig\Supports\AccessData;
use WannanBigPig\Supports\Events;
use WannanBigPig\Supports\Logs\Log;

trait Notify
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $response;

    /**
     * handle
     *
     * @param  \Closure    $closure
     * @param  array|null  $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handle(Closure $closure, $data = null): Response
    {
        try {
            $this->setData($data);
            $request = $this->getRequset();
            Log::info('支付宝回调数据', $request->get());

            // 签名验证
            if (Support::notifyVerify($request->get())) {
                $this->response = call_user_func($closure, $request, $this);
            } else {
                Events::dispatch(
                    SignFailed::NAME,
                    new SignFailed(
                        Support::$config->get('event.driver'),
                        Support::$config->get('event.method'),
                        $this->getData(),
                        '支付宝异步通知请求参数验签失败'
                    )
                );
                $this->response = $this->fail();
            }
        } catch (Throwable $e) {
            Events::dispatch(
                SignFailed::NAME,
                new SignFailed(
                    Support::$config->get('event.driver'),
                    Support::$config->get('event.method'),
                    $this->getData(),
                    $e->getMessage()
                )
            );
            $this->response = $this->fail();
        }

        return Response::create($this->response);
    }

    /**
     * setData
     *
     * @param $data
     */
    protected function setData($data)
    {
        $this->data = $data;
    }

    /**
     * getData
     *
     * @return mixed
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * getRequset
     *
     * @return \WannanBigPig\Supports\AccessData
     */
    protected function getRequset(): AccessData
    {
        if (is_null($this->data)) {
            $request = Request::createFromGlobals();

            $this->data = $request->request->count() > 0 ? $request->request->all() : $request->query->all();
        }

        return new AccessData($this->data);
    }

    /**
     * notifyIdVerify
     *
     * @param $partner
     * @param $notify_id
     *
     * @return bool
     */
    public function notifyIdVerify($partner, $notify_id)
    {
        if (Support::$config->get('env') === 'dev') {
            $res = file_get_contents(
                "https://mapi.alipaydev.com/gateway.do?service=notify_verify&partner={$partner}&notify_id={$notify_id}"
            );
        } else {
            $res = file_get_contents(
                "https://mapi.alipay.com/gateway.do?service=notify_verify&partner={$partner}&notify_id={$notify_id}"
            );
        }

        if ($res === 'true') {
            return true;
        }

        Log::error("[ Sign Failed ] 支付宝通知 notify_id 合法性校验失败 " . $res);

        return false;
    }

    /**
     * success
     *
     * @return string
     */
    public function success()
    {
        return self::SUCCESS;
    }

    /**
     * fail
     *
     * @return string
     */
    public function fail()
    {
        return self::FAIL;
    }
}
