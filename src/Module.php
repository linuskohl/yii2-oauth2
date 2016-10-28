<?php
/**
 * Module.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2016 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version XXX
 * @link http://www.sweelix.net
 * @package sweelix\oauth2\server
 */
namespace sweelix\oauth2\server;

use OAuth2\Server;
use sweelix\oauth2\server\services\Oauth;
use sweelix\oauth2\server\services\Redis;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;
use yii\console\Application as ConsoleApplication;
use Yii;

/**
 * Oauth2 server Module definition
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2016 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version XXX
 * @link http://www.sweelix.net
 * @package sweelix\oauth2\server
 * @since XXX
 */
class Module extends BaseModule implements BootstrapInterface
{
    /**
     * @var string backend to use, available backends are 'redis'
     */
    public $backend;
    /**
     * This user class will be used to link oauth2 authorization system with the application.
     * The class must implement \sweelix\oauth2\server\interfaces\UserInterface
     * If not defined, the Yii::$app->user->identityClass value will be used
     * @var string|array user class definition.
     */
    public $identityClass;

    /**
     * @var string change base end point
     */
    public $baseEndPoint = '';

    /**
     * @var bool configure oauth server (use_jwt_access_tokens)
     */
    public $allowJwtAccesToken = false;

    /**
     * @var bool configure oauth server (store_encrypted_token_string)
     */
    public $storeEncryptedTokenString = true;

    /**
     * @var bool configure oauth server (use_openid_connect)
     */
    public $allowOpenIdConnect = false;

    /**
     * @var int configure oauth server (id_lifetime)
     */
    public $idTTL = 3600;

    /**
     * @var int configure oauth server (access_lifetime)
     */
    public $accessTokenTTL = 3600;

    /**
     * @var int configure oauth server (refresh_token_lifetime)
     */
    public $refreshTokenTTL = 1209600;

    /**
     * @var string configure oauth server (www_realm)
     */
    public $realm = 'Service';

    /**
     * @var string configure oauth server (token_param_name)
     */
    public $tokenQueryName = 'access_token';

    /**
     * @var string configure oauth server (token_bearer_header_name)
     */
    public $tokenBearerName = 'Bearer';

    /**
     * @var bool configure oauth server (enforce_state)
     */
    public $enforceState = true;

    /**
     * @var bool configure oauth server (require_exact_redirect_uri)
     */
    public $allowOnlyRedirectUri = true;

    /**
     * @var bool configure oauth server (allow_implicit)
     */
    public $allowImplicit = false;

    /**
     * @var bool configure oauth server (allow_credentials_in_request_body)
     */
    public $allowCredentialsInRequestBody = true;

    /**
     * @var bool configure oauth server (allow_public_clients)
     */
    public $allowPublicClients = true;

    /**
     * @var bool configure oauth server (always_issue_new_refresh_token)
     */
    public $alwaysIssueNewRefreshToken = true;

    /**
     * @var bool configure oauth server (unset_refresh_token_after_use)
     */
    public $unsetRefreshTokenAfterUse = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Load dataservices in container
     * @param \yii\base\Application $app
     * @since XXX
     */
    protected function setUpDi($app)
    {
        if (Yii::$container->has('scope') === false) {
            Yii::$container->set('scope', 'sweelix\oauth2\server\validators\ScopeValidator');
        }
        if (Yii::$container->has('sweelix\oauth2\server\interfaces\AccessTokenModelInterface') === false) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\AccessTokenModelInterface', 'sweelix\oauth2\server\models\AccessToken');
        }
        if (Yii::$container->has('sweelix\oauth2\server\interfaces\AuthCodeModelInterface') === false) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\AuthCodeModelInterface', 'sweelix\oauth2\server\models\AuthCode');
        }
        if (Yii::$container->has('sweelix\oauth2\server\interfaces\ClientModelInterface') === false) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\ClientModelInterface', 'sweelix\oauth2\server\models\Client');
        }
        if (Yii::$container->has('sweelix\oauth2\server\interfaces\CypherKeyModelInterface') === false) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\CypherKeyModelInterface', 'sweelix\oauth2\server\models\CypherKey');
        }
        if (Yii::$container->has('sweelix\oauth2\server\interfaces\JtiModelInterface') === false) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\JtiModelInterface', 'sweelix\oauth2\server\models\Jti');
        }
        if (Yii::$container->has('sweelix\oauth2\server\interfaces\JwtModelInterface') === false) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\JwtModelInterface', 'sweelix\oauth2\server\models\Jwt');
        }
        if (Yii::$container->has('sweelix\oauth2\server\interfaces\RefreshTokenModelInterface') === false) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\RefreshTokenModelInterface', 'sweelix\oauth2\server\models\RefreshToken');
        }
        if (Yii::$container->has('sweelix\oauth2\server\interfaces\ScopeModelInterface') === false) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\ScopeModelInterface', 'sweelix\oauth2\server\models\Scope');
        }
        if ((Yii::$container->has('sweelix\oauth2\server\interfaces\UserModelInterface') === false) && ($this->identityClass !== null)) {
            Yii::$container->set('sweelix\oauth2\server\interfaces\UserModelInterface', $this->identityClass);
        }
        if ($this->backend === 'redis') {
            Redis::register($app);
        }
        Oauth::register($app);

    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // use the registered identity class if not overloaded
        if (($this->identityClass === null) && (isset($app->user) === true)) {
            $this->identityClass = $app->user->identityClass;
        }
        $this->setUpDi($app);
        if (empty($this->baseEndPoint) === false) {
            $this->baseEndPoint = trim($this->baseEndPoint, '/').'/';
        }

        if ($app instanceof ConsoleApplication) {
            $this->mapConsoleControllers($app);
        } else {
            $app->getUrlManager()->addRules([
                ['verb' => 'POST', 'pattern' => $this->baseEndPoint.'token', 'route' => $this->id.'/token/index'],
                ['verb' => 'GET', 'pattern' => $this->baseEndPoint.'authorize', 'route' => $this->id.'/authorize/index'],
            ]);
        }
    }

    /**
     * Update controllers map to add console commands
     * @param ConsoleApplication $app
     * @since XXX
     */
    protected function mapConsoleControllers(ConsoleApplication $app)
    {
        $app->controllerMap['oauth2:client'] = [
            'class' => 'sweelix\oauth2\server\commands\ClientController',
        ];
        $app->controllerMap['oauth2:scope'] = [
            'class' => 'sweelix\oauth2\server\commands\ScopeController',
        ];
    }
}
