<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth\Middleware;

use Alexzy\HyperfAuth\Annotation\Auth;
use Alexzy\HyperfAuth\Exception\NeedLoginException;
use Alexzy\HyperfAuth\Exception\NeedRightException;
use FastRoute\Dispatcher;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var \Alexzy\HyperfAuth\Auth
     */
    protected $auth;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        [$no_need_login, $no_need_right] = $this->checkWhiteList($request);
        // 无需登录直接执行
        if ($no_need_login) {
            return $handler->handle($request);
        }
        //未登录返回错误信息
        if (!$this->auth->isLogin()) {
            throw new NeedLoginException('请先登录');
        }
        //无需权限认证直接执行
        if ($no_need_right) {
            return $handler->handle($request);
        }

        $uri = $this->request->path();
        if (!$this->auth->check($uri)) {
            throw new NeedRightException('您没有权限');
        }
        return $handler->handle($request);
    }

    public function checkWhiteList(ServerRequestInterface $request)
    {
        $dispatched = $request->getAttribute(Dispatched::class);
        if ($dispatched->status !== Dispatcher::FOUND) {
            return true;
        }
        $action = $dispatched->handler->callback;;
        if (is_string($action)) {
            $division = strstr($action, '@') ? '@' : "::";
            $action = explode($division, $action);
        }
        list($class, $method) = $action;
        $annotations = AnnotationCollector::getClassMethodAnnotation($class, $method);
        if (isset($annotations[Auth::class])) {
            $white_list = $annotations[Auth::class];
            $no_need_login = $white_list->noNeedLogin;
            $no_need_right = $white_list->noNeedRight;
        } else {
            $no_need_login = false;
            $no_need_right = false;
        }
        return [$no_need_login, $no_need_right];
    }
}