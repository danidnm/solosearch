<?php

namespace SoloSearch\User\Console;

use SoloSearch\Core\Console\CommandInterface;
use SoloSearch\User\Model\UserRepository;
use SoloSearch\User\Model\UserFactory;

class CreateUser implements CommandInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserFactory
     */
    private UserFactory $userFactory;

    /**
     * @param UserRepository $userRepository
     * @param UserFactory $userFactory
     */
    public function __construct(
        UserRepository $userRepository,
        UserFactory $userFactory
    ) {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
    }

    /**
     * Runs command
     */
    public function run()
    {
        echo "--- Create New User ---\n";

        $email = $this->ask("Email: ");
        if (empty($email)) {
            echo "Error: Email is required.\n";
            return;
        }

        $password = $this->ask("Password: ", true);
        if (empty($password)) {
            echo "Error: Password is required.\n";
            return;
        }

        $firstName = $this->ask("First Name (optional): ");
        $lastName = $this->ask("Last Name (optional): ");
        
        $role = $this->ask("Role (admin/user) [user]: ");
        if (empty($role)) {
            $role = 'user';
        }

        if (!in_array($role, ['admin', 'user'])) {
            echo "Error: Invalid role. Must be 'admin' or 'user'.\n";
            return;
        }

        try {
            $user = $this->userFactory->create();
            $user->setData([
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'role' => $role
            ]);

            $this->userRepository->save($user);

            echo "\nUser created successfully! (ID: {$user->getId()})\n";
        } catch (\Exception $e) {
            echo "\nError creating user: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Prompt user for input
     * 
     * @param string $prompt
     * @param bool $hidden
     * @return string
     */
    private function ask(string $prompt, bool $hidden = false): string
    {
        echo $prompt;
        
        if ($hidden) {
            // Simple mask for passwords in CLI if supported, 
            // otherwise just read. (On most Unix-like systems, 
            // we could use 'stty -echo', but keeping it simple for now)
            system('stty -echo');
            $input = trim(fgets(STDIN));
            system('stty echo');
            echo "\n";
            return $input;
        }

        return trim(fgets(STDIN));
    }
}
