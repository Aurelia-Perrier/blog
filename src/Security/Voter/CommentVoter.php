<?php

namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CommentVoter extends Voter
{

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, ['comment_validate', 'comment_refuse'])) {
            return false;
        }

        if (!$subject instanceof Post) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

         /** @var Post $post */
         $post = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'comment_validate':
                
                if($user === $post->getAuthor()){
                    return true;
                }
                break;
            case 'comment_refuse':
                if($user === $post->getAuthor()){
                    return true;
                }
                break;
        }

        return false;
    }
}
