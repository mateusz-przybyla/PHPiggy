<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
  public function __construct(private Database $db)
  {
  }

  public function isEmailTaken(string $email)
  {
    $emailCount = $this->db->query(
      "SELECT COUNT(*) FROM `users` WHERE `email` = :email",
      ['email' => $email]
    )->count();

    if ($emailCount > 0) {
      throw new ValidationException(['email' => ['Email taken.']]);
    }
  }

  public function createNewUser(array $formData)
  {
    $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);

    $this->db->query(
      "INSERT INTO `users` (`email`, `password`, `age`, `country`, `social_media_url`) VALUES (:email, :password, :age, :country, :social_media_url)",
      [
        'email' => $formData['email'],
        'password' => $password,
        'age' => $formData['age'],
        'country' => $formData['country'],
        'social_media_url' => $formData['socialMediaURL']
      ]
    );

    session_regenerate_id();

    $_SESSION['user'] = $this->db->id();
  }

  public function verifyLoginData(array $formData)
  {
    $query = $this->db->query(
      "SELECT * FROM `users` WHERE `email` = :email",
      ['email' => $formData['email']]
    );

    $user = $query->retrieve();

    $passwordMatch = password_verify(
      $formData['password'],
      $user['password'] ?? ''
    );

    if (!$user || !$passwordMatch) {
      throw new ValidationException(['password' => ['Invalid credentials.']]);
    }

    session_regenerate_id();

    $_SESSION['user'] = $user['id'];
  }

  public function logout()
  {
    unset($_SESSION['user']);

    session_regenerate_id();
  }
}
