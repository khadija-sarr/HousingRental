<?php
    namespace App\Controller;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Routing\Annotation\Route;
    class ContactController extends AbstractController {
        /**
         * @Route("/contact", name="contact_new", methods={"GET|POST"})
         */
        public function newContact() {
            $form = $this->createFormBuilder()
            ->add('fullname',TextType::class, ['label' => false, 'attr'  => ['placeholder' => 'Nom et Prénom']])
            ->add('message', TextareaType::class, ['label' => false,'attr'  => ['placeholder' => 'Message']])
            ->add('Envoyer', SubmitType::class, ['label' => false])
            ->getForm();
            return $this->render('contact/new.html.twig', ['form' => $form->createView()]);
        }
    }