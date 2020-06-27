<?php
/**
 * UserVoter.
 */
namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserVoter
 */
class UserVoter extends Voter
{
    /**
     * Security helper.
     *
     * @var Security
     */
    private $security;

    /**
     * UserVoter constructor.
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['VIEW', 'EDIT_PASS', 'EDIT_EMAIL'])
            && $subject instanceof \App\Entity\User;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'VIEW':
            case 'EDIT_PASS':
            case 'EDIT_EMAIL':
                if ($subject === $user || $this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                // logic to determine if the user can EDIT
                // return true or false
                break;
            default:
                return false;
                break;
        }

        return false;
    }
}
