<?php

namespace App\Controller;

use App\Entity\Candidate;
use Doctrine\Common\Persistence\ObjectManager;

class VoteIncrementationController{


    
    public function __invoke(Candidate $data,ObjectManager $om)
    {
        die($data);
    //     $data->setNbVotes($data->getNbVotes()+1);
    //     $om->flush();
    //    return($data);
    }
     
}