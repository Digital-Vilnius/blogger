<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePassword
{
    /**
     * @Assert\NotBlank(message="field_is_required")
     * @SecurityAssert\UserPassword(message = "wrong_value_for_your_current_password")
     */
    private $currentPassword;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "password_should_by_at_least_6_characters_long",
     * )
     * @Assert\Type(
     *     type="alnum",
     *     message="password_should_contains_only_numbers_and_letters"
     * )
     */
    private $newPassword;

    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword($currentPassword)
    {
        $this->currentPassword = $currentPassword;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
}