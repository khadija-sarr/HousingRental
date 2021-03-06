<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_form", methods={"GET|POST"})
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     */
    public function contactForm(Request $request, MailerInterface $mailer)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $form = $this->createFormBuilder()
            ->add('fullname', TextType::class, ['label' => 'Nom et Prénom', 'constraints' => new NotBlank()])
            ->add('email', EmailType::class, ['label' => 'Adresse email', 'constraints' => [new NotBlank(), new \Symfony\Component\Validator\Constraints\Email()]])
            ->add('subject', TextType::class, ['label' => 'Sujet', 'constraints' => new NotBlank()])
            ->add('message', TextareaType::class, ['label' => 'Message', 'constraints' => new NotBlank()])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from($form->get('email')->getData())
                ->to('lacwill@outlook.com')
                ->subject('Nouveau message de :' . $form->get('fullname')->getData())
                ->text($form->get('message')->getData());
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                dd($e);
            }
            $this->addFlash('success', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('default_home');
        }
        return $this->render('contact/new.html.twig',
            [
                'form' => $form->createView(),
                'categories' => $categories,
                'bannerTitle' => 'Contact',
                'bannerText' => 'Contactez nous pour toute question via le formulaire ci-dessous !'
            ]);
    }
}