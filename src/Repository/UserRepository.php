<?php

declare(strict_types=1);

namespace Innova\Mvc\Repository;

use Innova\Mvc\Entity\User;
use PDO;

class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(User $user): bool
    {
        $passwordHash = password_hash($user->password, PASSWORD_ARGON2I);
        $sql = 'INSERT INTO users (email, password) VALUES (:email, :password);';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('email', $user->email);
        $stmt->bindValue('password', $passwordHash);

        $result = $stmt->execute();
        $id = $this->pdo->lastInsertId();

        $user->setId(intval($id));

        return $result;
    }

    public function login(User $user): bool
    {
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $user->email);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        $correctPassword = password_verify($user->password, $userData['password'] ?? '');

        return $correctPassword;
    }

     public function findByEmail(string $email): ?User
    {
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userData === false) {
            return null;
        }

        return new User(
            $userData['email'],
            $userData['password'],
            (int) $userData['id']
        );
    }

    public function verifyPassword(User $user, string $password): bool
    {
        return password_verify($password, $user->password);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return password_needs_rehash($hashedPassword, PASSWORD_ARGON2ID);
    }

    public function rehashPassword(User $user, string $newPassword): bool
    {
        $newHashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);
        $sql = 'UPDATE users SET password = ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $newHashedPassword);
        $stmt->bindValue(2, $user->id);

        return $stmt->execute();
    }
}