<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Feedback;
use App\Entity\Node;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/feedback')]
class FeedbackController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    #[Route('/new', methods: ['POST'], name: 'app_feedback_new')]
    public function new(Request $request): Response
    {
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        $phone = $request->request->get('phone');
        $email = $request->request->get('email');
        // $title = $request->request->get('title');
        $body = $request->request->get('body');
        $nid = $request->request->get('nid');
        $pid = $request->request->get('pid');
        $country = $request->request->get('country');
        
        $node = $this->doctrine->getRepository(Node::class)->find($pid);
        
        $em = $this->doctrine->getManager();
        $f = new Feedback();
        $f->setFirstname($firstname);
        $f->setLastname($lastname);
        $f->setPhone($phone);
        $f->setEmail($email);
        $f->setTitle($node->getTitle());
        $f->setBody($body);
        // $f->setNode($node);
        $f->setCountry($country);
        $em->persist($f);
        $em->flush();
        
        return $this->json('OK');
    }
}
