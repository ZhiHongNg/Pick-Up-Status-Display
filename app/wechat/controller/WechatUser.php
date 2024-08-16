<?php

namespace app\wechat\controller;

use app\admin\model\wechat\User;
use app\common\controller\Frontend;
use app\wechat\WXBizDataCrypt;
use GuzzleHttp\Client;

class WechatUser extends Frontend
{
    protected array $noNeedLogin = ['*'];

    protected array $noNeedPermission = ['*'];
    private $appid;
    private $secret;
    private $sessionKey;
    public $OK = 0;
    private $IllegalAesKey = -41001;
    private $IllegalIv = -41002;
    private $IllegalBuffer = -41003;
    private $DecodeBase64Error = -41004;

    public function initialize(): void
    {

        $this->appid = "wx56cc7140a5d6e684";
        $this->secret = "942d3f38c961892182df5a27c17600fd";
        parent::initialize();
    }

    public function index()
    {
        echo 123;
        exit;

    }

    public function login()
    {
        $code = $this->request->param("code");

        if ($code) {
            $response = $this->request("https://api.weixin.qq.com/sns/jscode2session?appid=" . $this->appid . "&secret=".$this->secret."&js_code=" . $code . "&grant_type=authorization_code", 'GET', [
                'appid' => $this->appid,
                "secret" => $this->secret,
                "js_code" => $code,
                "grant_type" => "authorization_code"
            ]);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                $user = (new User)->where('openid', $data['openid'])->find();
                if (empty($user)) {
                    (new User)->save([
                        'openid' => $data['openid'],
                        'session_key' => $data['session_key'],
                    ]);
                }else{
                    $user->session_key = $data['session_key'];
                    $user->save();
                }
                return $this->success('登录成功', $data['openid']);
            } else {
                return $this->error('登录失败', []);

            }

        } else {
            return $this->responseJson(422, [], "参数错误");
            return $this->validate('登录失败', []);
        }
    }

    public function getPhone()
    {
        $openid = $this->request->param('openid');
        $user = (new User)->where("openid", $openid)->find();

        $pc = new WXBizDataCrypt($this->appid, $user->session_key);
        $l = $pc->decryptData($this->request->param('encryptedData'), $this->request->param('iv'), $data);
        $l = json_decode($data, 1);
        $user->phone = $l['phoneNumber'];
        $this->success('',['phoneNumber'=>$l['phoneNumber']]);
        $user->save();

    }

    public function request($url, $method = "GET", $params = [])
    {
        $client = new Client(['verify' => false]);
        $options = [
            'headers' => [
                'Content-Type' => "application/x-www-form-urlencoded",
            ],
        ];

        if ($method == "GET") {
            $options['query'] = $params;
            $url = $url . http_build_query($params);
            $response = $client->get($url, $options);
        } elseif ($method == "PUT") {
            $options['json'] = $params;
            $response = $client->put($url, $options);
        } elseif ($method == "DELETE") {
            $options['query'] = $params;
            $response = $client->delete($url, $options);
        } else {
            $options['form_params'] = $params;
            $response = $client->post($url, $options);
        }

        return $response;
    }


}