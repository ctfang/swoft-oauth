<?php


namespace Swoft\OAuth\Bean;


use Exception;
use Swlib\SaberGM;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class WeChat
 * @package Swoft\OAuth\Bean
 * @Bean()
 */
class WeChat
{
    public $config = [
        'appid' => '',
        'secret' => '',
    ];

    /**
     * @var string
     */
    protected $accessTokenURL = 'https://api.weixin.qq.com/sns/';

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
     * @param string $code app授权的code
     * @return array
     * @throws Exception
     */
    public function getAccessToken(string $code): array
    {
        $param = [
            'appid' => $this->getAppId(),
            'secret' => $this->getSecret(),
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];
        $res = SaberGM::get($this->getAccessTokenUri() . http_build_query($param));
        if (!$res->isSuccess()) throw new Exception("链接微信平台网络异常");

        $body = $res->getBody();

        return json_decode($body, true);
    }


    /**
     * @return string
     */
    private function getAppId(): string
    {
        return $this->config["appid"];
    }

    /**
     * @return string
     */
    private function getSecret(): string
    {
        return $this->config["secret"];
    }

    /**
     * @return string
     */
    private function getAccessTokenUri(): string
    {
        return $this->accessTokenURL . 'oauth2/access_token?';
    }
}
