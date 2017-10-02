<?php

namespace AppBundle\Event;

use AppBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\Event;

class CommentCreatedEvent extends Event {

    const NAME = "app.comment_created";
    
    /**
     * @var Comment
     */
    private $comment;

    public function __construct(Comment $comment) {
       $this->comment = $comment;
    }
    
    /**
     * 
     * @return Comment
     */
    function getComment(): Comment {
        return $this->comment;
    }


    
}
