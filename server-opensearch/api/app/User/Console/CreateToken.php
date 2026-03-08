<?php

namespace SoloSearch\User\Console;

use SoloSearch\Core\Console\CommandInterface;
use SoloSearch\User\Model\UserRepository;
use SoloSearch\User\Model\TokenRepository;
use SoloSearch\User\Model\TokenFactory;

class CreateToken implements CommandInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var TokenRepository
     */
    private TokenRepository $tokenRepository;

    /**
     * @var TokenFactory
     */
    private TokenFactory $tokenFactory;

    /**
     * @param UserRepository $userRepository
     * @param TokenRepository $tokenRepository
     * @param TokenFactory $tokenFactory
     */
    public function __construct(
        UserRepository $userRepository,
        TokenRepository $tokenRepository,
        TokenFactory $tokenFactory
    ) {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->tokenFactory = $tokenFactory;
    }

    /**
     * Runs command
     */
    public function run()
    {
        echo "--- Create User Token ---\n";

        $userId = trim(readline("User ID: "));
        if (empty($userId)) {
            echo "Error: User ID is required.\n";
            return;
        }

        $user = $this->userRepository->get((int)$userId);
        if (!$user) {
            echo "Error: User not found.\n";
            return;
        }

        try {
            $tokenValue = bin2hex(random_bytes(16));
            $token = $this->tokenFactory->create();
            $token->setData([
                'user_id' => $user->getId(),
                'token' => $tokenValue,
                'created_at' => date('Y-m-d H:i:s'),
                'expire_at' => date('Y-m-d H:i:s', strtotime('+1 year'))
            ]);

            $this->tokenRepository->save($token);

            echo "\nToken created successfully!\n";
            echo "Token: {$tokenValue}\n";
        } catch (\Exception $e) {
            echo "\nError creating token: " . $e->getMessage() . "\n";
        }
    }
}
