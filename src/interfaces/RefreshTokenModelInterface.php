<?php
/**
 * RefreshTokenModelInterface.php
 *
 * PHP version 5.6+
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version 1.2.0
 * @link http://www.sweelix.net
 * @package sweelix\oauth2\server\interfaces
 */

namespace sweelix\oauth2\server\interfaces;

/**
 * This is the refresh token model interface
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Philippe Gaultier
 * @license http://www.sweelix.net/license license
 * @version 1.2.0
 * @link http://www.sweelix.net
 * @package sweelix\oauth2\server\interfaces
 * @since 1.0.0
 *
 * @property string $id
 * @property string $clientId
 * @property string $userId
 * @property string $expiry
 * @property array $scopes
 */
interface RefreshTokenModelInterface extends BaseModelInterface
{
    /**
     * Find one refreshToken by its key
     *
     * @param string $id
     * @return RefreshTokenModelInterface|null
     * @since 1.0.0
     * @throws \yii\base\UnknownClassException
     */
    public static function findOne($id);

    /**
     * @param bool $runValidation
     * @param null $attributes
     * @return bool
     * @since 1.0.0
     * @throws \yii\base\UnknownClassException
     */
    public function save($runValidation = true, $attributes = null);

    /**
     * @return bool
     * @since 1.0.0
     * @throws \yii\base\UnknownClassException
     */
    public function delete();

    /**
     * @param string|integer $userId
     * @return RefreshTokenModelInterface[]
     * @since XXX
     * @throws \yii\base\UnknownClassException
     */
    public static function findAllByUserId($userId);

    /**
     * @param string|integer $userId
     * @return bool
     * @since XXX
     * @throws \yii\base\UnknownClassException
     */
    public static function deleteAllByUserId($userId);

    /**
     * @param string $clientId
     * @return RefreshTokenModelInterface[]
     * @since XXX
     * @throws \yii\base\UnknownClassException
     */
    public static function findAllByClientId($clientId);

    /**
     * @param string $clientId
     * @return bool
     * @since XXX
     * @throws \yii\base\UnknownClassException
     */
    public static function deleteAllByClientId($clientId);

    /**
     * @return bool
     * @throws \yii\base\UnknownClassException
     */
    public static function deleteAllExpired();

    /**
     * @return RefreshTokenModelInterface[]
     * @throws \yii\base\UnknownClassException
     */
    public static function findAll();
}
