<?php

namespace app\common\controller;

use app\admin\model\wechat\User;
use app\common\library\Auth;
use app\common\library\token\TokenExpirationException;
use think\exception\HttpResponseException;
use think\facade\Event;
use Throwable;

class Frontend extends Api
{
    /**
     * 无需登录的方法
     * 访问本控制器的此方法，无需会员登录
     * @var array
     */
    protected array $noNeedLogin = [];

    /**
     * 无需鉴权的方法
     * @var array
     */
    protected array $noNeedPermission = [];

    /**
     * 权限类实例
     * @var Auth
     */
    protected Auth $auth;
    protected  $openid;
    protected  $wechatUser;

    /**
     * 初始化
     * @throws Throwable
     * @throws HttpResponseException
     */
    public function initialize(): void
    {
        parent::initialize();

        $needLogin = !action_in_arr($this->noNeedLogin);

        try {

            // 初始化会员鉴权实例
            $this->auth = Auth::instance();
            $token      = get_auth_token(['ba', 'user', 'token']);
            if ($token) $this->auth->init($token);

        } catch (TokenExpirationException) {
            if ($needLogin) {
                $this->error(__('Token expiration'), [], 409);
            }
        }

        if ($needLogin) {
            if (!$this->auth->isLogin()) {
                $this->error(__('Please login first'), [
                    'type' => $this->auth::NEED_LOGIN
                ], $this->auth::LOGIN_RESPONSE_CODE);
            }
            if (!action_in_arr($this->noNeedPermission)) {
                $routePath = ($this->app->request->controllerPath ?? '') . '/' . $this->request->action(true);
                if (!$this->auth->check($routePath)) {
                    $this->error(__('You have no permission'), [], 401);
                }
            }
        }


        // 会员验权和登录标签位
        Event::trigger('frontendInit', $this->auth);
    }

    public function checkUser($allowActions = []): bool
    {
        if(in_array($this->request->action(), $allowActions)) {

            return true;
        }
        $openid = $this->request->header('openid');
        if (!$openid) $this->internetError('请求错误');
        $this->openid = $openid;
        $this->wechatUser = User::where('openid',$openid)->find();
        return true;

    }
}