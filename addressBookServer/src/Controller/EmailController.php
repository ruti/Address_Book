<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Email;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Psr\Log\LoggerInterface;

class EmailController extends ApiController
{

     /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


     /**
    * @Route("/emails/add/{id}", methods="POST")
    */
    public function addPhoneNumber($id, Request $request, ContactRepository $contactRepository, EntityManagerInterface $em)
    {
        $contact = $contactRepository->find($id);
        //Add the email
        $email = new Email;
        $email->setContact($contact);
        $email->setEmail($request->getContent());

        //Persist the entity
        $em->persist($email);
        $em->flush();
        return $this->respond($contact);
    }
}