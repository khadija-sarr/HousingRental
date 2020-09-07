<?php




namespace App\Controller;

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


    class ContactController extends AbstractController {
        /**
         * @Route("/contact", name="contact_new", methods={"GET|POST"})
         * @param Request $request
         * @param MailerInterface $mailer
         * @return Response
         */
        public function newContact(Request $request, MailerInterface $mailer) {
            $form = $this->createFormBuilder()
            ->add('fullname',TextType::class, ['label' => false, 'attr'  => ['placeholder' => 'Nom et Prénom']])
            ->add('email', EmailType::class, ['label' => false, 'attr'  => ['placeholder' => 'Adresse email']])
            ->add('subject',TextType::class, ['label' => false, 'attr'  => ['placeholder' => 'Sujet']])
            ->add('message', TextareaType::class, ['label' => false,'attr'  => ['placeholder' => 'Message']])
                ->add('Envoyer', SubmitType::class, ['label' => 'Valider'])

            ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $email = (new Email())
                ->from($form->get('email')->getData())
                ->to('lacwill@outlook.com')
                ->subject('Nouveau message de :' . $form->get('fullname')->getData())
                ->text($form->get('message')->getData());
                try {
                    $mailer->send($email);
                } catch(TransportExceptionInterface $e) {
                    dd($e);
                }
            }
            return $this->render('contact/new.html.twig', ['form' => $form->createView()]);
        }
//        private $emailVide;
//        public function from($adresseEmailObtenue) {
//            $this->emailVide = $adresseEmailObtenue;
//        }
//        public function to() {
//
//        }
//        public function subject() {
//
//        }
//        $email = {
//            'from': "l'adresse de l'utilisateur"
//            'to': 'l\'adresse du destinataire ( souvent l\'administrateur )'
//            'subject': 'L\'objet du mail'
//            'text': 'Le contenu du mail'
//        }
    }
