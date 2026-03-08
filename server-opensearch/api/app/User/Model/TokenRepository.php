<?php

namespace SoloSearch\User\Model;

use SoloSearch\User\Model\Resource\Token as TokenResource;

class TokenRepository
{
    /**
     * @var array
     */
    protected array $entities = [];

    /**
     * @var TokenFactory
     */
    private TokenFactory $tokenFactory;

    /**
     * @var TokenResource
     */
    private TokenResource $tokenResource;

    /**
     * @param TokenFactory $tokenFactory
     * @param TokenResource $tokenResource
     */
    public function __construct(
        TokenFactory $tokenFactory,
        TokenResource $tokenResource
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->tokenResource = $tokenResource;
    }

    /**
     * Get token by string value
     * 
     * @param string $tokenValue
     * @return Token|null
     */
    public function getByToken(string $tokenValue)
    {
        $tokenModel = $this->tokenFactory->create();
        $this->tokenResource->loadByField($tokenModel, 'token', $tokenValue);
        
        if (!$tokenModel->getId()) {
            return null;
        }

        return $tokenModel;
    }

    /**
     * Save token
     * 
     * @param Token $token
     * @return $this
     */
    public function save(Token $token)
    {
        $this->tokenResource->save($token);
        return $this;
    }

    /**
     * Delete token
     * 
     * @param Token $token
     * @return $this
     */
    public function delete(Token $token)
    {
        $this->tokenResource->delete($token);
        return $this;
    }
}
