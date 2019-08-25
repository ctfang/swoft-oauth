<?php


namespace Swoft\OAuth\Bean;

use Swlib\SaberGM;
use Swoft\Bean\Annotation\Mapping\Bean;
use \Exception;

/**
 * Class QqLogin
 * @package Swoft\OAuth\Bean
 * @Bean()
 */
class QQ
{
    public $config = [
        'appid' => '',
        'secret' => '',
    ];


    protected $userInfoUri = 'https://graph.qq.com/user/get_user_info?';

    /**
     * WeChat constructor.
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     */
    public function __construct()
    {
        $config = \config('oauth.wx');
        if ($config) {
            $this->config = $config;
        }
    }

    /**
     * 获取用户信息
     * @param string $openid
     * @param $access_token
     * @return mixed
     * @throws Exception
     */
    public function getUser(string $openid, string $access_token)
    {
        $param = [
            'access_token' => $access_token,
            'oauth_consumer_key' => $this->getAppId(),
            'openid' => $openid,
            'format' => 'json'
        ];
        $res = SaberGM::get($this->userInfoUri . http_build_query($param));
        if (!$res->isSuccess()) throw new Exception("链接QQ平台网络异常");

        $body = $res->getBody();

        return json_decode($body, true);
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getAppId(): string
    {
        return $this->config["appid"];
    }
}
