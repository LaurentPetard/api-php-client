<?php

namespace Akeneo;

/**
 * Class Authentication
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Authentication
{
    /** @var string */
    protected $clientId;

    /** @var string */
    protected $secret;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var string */
    protected $refreshToken;

    /** @var string */
    protected $accessToken;

    /**
     * @param string $clientId
     * @param string $secret
     * @param string $username
     * @param string $password
     */
    public function __construct($clientId, $secret, $username, $password)
    {
        $this->clientId = $clientId;
        $this->secret = $secret;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }
}
