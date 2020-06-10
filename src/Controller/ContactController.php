<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, \Swift_Mailer $mailer){

        $form = $this->createForm(ContactType::class);
        dump($form->handleRequest($request));
        if($form->isSubmitted() && $form->isValid()){
            $contactFormData = $form->getData();

            $message = (new \Swift_Message('You Got Mail!'))
                ->setFrom('test@gideonmkp.nl')
                ->setTo('test@gideonmkp.nl')
                ->setBody(
                    $this->render('emails/contact.html.twig', [
                        'data' => $contactFormData,
                    ]), 'text/html');
            $mailer->send($message);

            $this->addFlash('success', 'Message was sent!');
            return $this->redirectToRoute('contact');



        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}