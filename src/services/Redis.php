<?php
/**
 * Redis.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version 1.2.0
 * @link http://www.sweelix.net
 * @package sweelix\oauth2\server\services
 */

namespace sweelix\oauth2\server\services;

use sweelix\oauth2\server\interfaces\ServiceBootstrapInterface;
use sweelix\oauth2\server\services\redis\AccessTokenService;
use sweelix\oauth2\server\services\redis\AuthCodeService;
use sweelix\oauth2\server\services\redis\ClientService;
use sweelix\oauth2\server\services\redis\CypherKeyService;
use sweelix\oauth2\server\services\redis\JtiService;
use sweelix\oauth2\server\services\redis\JwtService;
use sweelix\oauth2\server\services\redis\RefreshTokenService;
use sweelix\oauth2\server\services\redis\ScopeService;
use Yii;

/**
 * This is the service loader for redis
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version 1.2.0
 * @link http://www.sweelix.net
 * @package sweelix\oauth2\server\services
 * @since 1.0.0
 */
class Redis implements ServiceBootstrapInterface
{
    /**
     * @inheritdoc
     */
    public static function register($app)
    {
        if (Yii::$container->hasSingleton('sweelix\oauth2\server\interfaces\AccessTokenServiceInterface') === false) {
            Yii::$container->setSingleton('sweelix\oauth2\server\interfaces\AccessTokenServiceInterface', [
                'class' => AccessTokenService::class,
                'namespace' => 'oauth2:accessTokens',
                'userNamespace' => 'oauth2:users',
                'clientNamespace' => 'oauth2:clients',
            ]);
        }
        if (Yii::$container->hasSingleton('sweelix\oauth2\server\interfaces\AuthCodeServiceInterface') === false) {
            Yii::$container->setSingleton('sweelix\oauth2\server\interfaces\AuthCodeServiceInterface', [
                'class' => AuthCodeService::class,
                'namespace' => 'oauth2:authCodes',
            ]);
        }
        if (Yii::$container->hasSingleton('sweelix\oauth2\server\interfaces\ClientServiceInterface') === false) {
            Yii::$container->setSingleton('sweelix\oauth2\server\interfaces\ClientServiceInterface', [
                'class' => ClientService::class,
                'namespace' => 'oauth2:clients',
                'userNamespace' => 'oauth2:users',
            ]);
        }
        if (Yii::$container->hasSingleton('sweelix\oauth2\server\interfaces\CypherKeyServiceInterface') === false) {
            Yii::$container->setSingleton('sweelix\oauth2\server\interfaces\CypherKeyServiceInterface', [
                'class' => CypherKeyService::class,
                'namespace' => 'oauth2:cypherKeys',
            ]);
        }
        if (Yii::$container->hasSingleton('sweelix\oauth2\server\interfaces\JtiServiceInterface') === false) {
            Yii::$container->setSingleton('sweelix\oauth2\server\interfaces\JtiServiceInterface', [
                'class' => JtiService::class,
                'namespace' => 'oauth2:jti',
                'subjectNamespace' => 'oauth2:subject',
                'clientNamespace' => 'oauth2:clients',
            ]);
        }
        if (Yii::$container->hasSingleton('sweelix\oauth2\server\interfaces\JwtServiceInterface') === false) {
            Yii::$container->setSingleton('sweelix\oauth2\server\interfaces\JwtServiceInterface', [
                'class' => JwtService::class,
                'namespace' => 'oauth2:jwt',
            ]);
        }
        if (Yii::$container->hasSingleton('sweelix\oauth2\server\interfaces\RefreshTokenServiceInterface') === false) {
            Yii::$container->setSingleton('sweelix\oauth2\server\interfaces\RefreshTokenServiceInterface', [
                'class' => RefreshTokenService::class,
                'namespace' => 'oauth2:refreshTokens',
                'userNamespace' => 'oauth2:users',
                'clientNamespace' => 'oauth2:clients',
            ]);
        }
        if (Yii::$container->hasSingleton('sweelix\oauth2\server\interfaces\ScopeServiceInterface') === false) {
            Yii::$container->setSingleton('sweelix\oauth2\server\interfaces\ScopeServiceInterface', [
                'class' => ScopeService::class,
                'namespace' => 'oauth2:scopes',
            ]);
        }
    }
}
