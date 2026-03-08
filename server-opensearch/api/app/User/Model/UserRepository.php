<?php

namespace SoloSearch\User\Model;

use SoloSearch\Core\Model\AbstractModel;
use SoloSearch\User\Model\Resource\User as UserResource;

class UserRepository extends AbstractModel
{
    /**
     * @var UserFactory
     */
    private UserFactory $userFactory;

    /**
     * @var UserResource
     */
    private UserResource $userResource;

    /**
     * @param UserFactory $userFactory
     * @param UserResource $userResource
     */
    public function __construct(
        UserFactory $userFactory,
        UserResource $userResource
    ) {
        $this->userFactory = $userFactory;
        $this->userResource = $userResource;
    }

    /**
     * Get user by ID
     * 
     * @param int $id
     * @param bool $skipCache
     * @return User|null
     */
    public function get(int $id, bool $skipCache = false)
    {
        $cacheKey = 'user_' . $id;

        // Check in-memory cache
        if (!$skipCache && $this->hasData($cacheKey)) {
            return $this->getData($cacheKey);
        }

        // Load from database
        $userModel = $this->userFactory->create();
        $this->userResource->load($userModel, $id);
        
        if (!$userModel->getId()) {
            return null;
        }

        // Store in-memory
        $this->setData($cacheKey, $userModel);

        return $userModel;
    }

    /**
     * Save user
     * 
     * @param User $user
     * @return $this
     */
    public function save(User $user)
    {
        if (!$user->hasData('created_at')) {
            $user->setData('created_at', date('Y-m-d H:i:s'));
        }
        $user->setData('updated_at', date('Y-m-d H:i:s'));

        $this->userResource->save($user);
        
        $id = $user->getId();
        if ($id) {
            $this->setData('user_' . $id, $user);
        }

        return $this;
    }

    /**
     * Delete user
     * 
     * @param User $user
     * @return $this
     */
    public function delete(User $user)
    {
        $id = $user->getId();
        $this->userResource->delete($user);
        
        if ($id) {
            $this->unsetData('user_' . $id);
        }

        return $this;
    }
}
