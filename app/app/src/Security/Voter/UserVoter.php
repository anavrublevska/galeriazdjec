<?php
///**
// * UserVoter.
// */
//namespace App\Security\Voter;
//
//use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
//use Symfony\Component\Security\Core\Authorization\Voter\Voter;
//use Symfony\Component\Security\Core\Security;
//use Symfony\Component\Security\Core\User\UserInterface;
//
///**
// * Class UserVoter
// */
//class UserVoter extends Voter
//{
//    /**
//     * Security helper.
//     *
//     * @var Security
//     */
//    private $security;
//
//    /**
//     * UserVoter constructor.
//     *
//     * @param Security $security
//     */
//    public function __construct(Security $security)
//    {
//        $this->security = $security;
//    }
//
//    protected function supports($attribute, $subject)
//    {
//        return in_array($attribute, ['VIEW', 'EDIT', 'DELETE'])
//            && $subject instanceof \App\Entity\BlogPost;
//    }
//
//    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
//    {
//        $user = $token->getUser();
//        // if the user is anonymous, do not grant access
//        if (!$user instanceof UserInterface) {
//            return false;
//        }
//
//        // ... (check conditions and return true to grant permission) ...
//        switch ($attribute) {
//            case 'POST_EDIT':
//                // logic to determine if the user can EDIT
//                // return true or false
//                break;
//            case 'POST_VIEW':
//                // logic to determine if the user can VIEW
//                // return true or false
//                break;
//        }
//
//        return false;
//    }
//}
