<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\User;
use Rem\BadgeBundle\Entity\Badge;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;

class AppMailer {

    /**
     * @var EngineInterface
     */
    private $template;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(Swift_Mailer $mailer, EngineInterface $template) {
        
        $this->mailer = $mailer;
        $this->template = $template;
    }


    public function badgeUnlocked(Badge $badge, User $user){
        $message = Swift_Message::newInstance()
         //$message = (new Swift_Message('Hello Email'))
                ->setSubject('Vous avez débloqué le badge' . $badge->getName())
                ->setTo($user->getEmail())
                ->setFrom('noreply@doe.fr')
                ->setBody($this->template->render('emails/badge.text.twig', [
                    'badge' => $badge,
                    'user' => $user
                ]));
        //var_dump($message);
         
         
        return $this->mailer->send($message);
    }

}
