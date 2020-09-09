<?php
    namespace App\Controller;
    use App\Entity\User;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\Extension\Core\Type\CountryType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    class UserController extends AbstractController {
        /**
         * Formulaire pour ajouter un nouveau membre
         * @Route("/inscription", name="user_register", methods={"GET|POST"})
         * @param Request $request
         * @param UserPasswordEncoderInterface $encoder
         * @return RedirectResponse|Response
         */
        public function newUser(Request $request, UserPasswordEncoderInterface $encoder) {
            $user = new User();
            $form = $this->createFormBuilder($user)
                ->add('firstname', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Prénom"]])
                ->add('lastname', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Nom"]])
                ->add('email', EmailType::class, ['label' => false, 'attr' => ['placeholder' => "Email"]])
                ->add('password', PasswordType::class, ['label' => false, 'attr' => ['placeholder' => "Mot de passe"]])
                ->add('phone', IntegerType::class, ['label' => false, 'attr' => ['placeholder' => "Téléphone"]])
                ->add('address', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Adresse"]])
                ->add('city', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Ville"]])
                ->add('zipcode', IntegerType::class, ['label' => false, 'attr' => ['placeholder' => "Code Postal"]])
                ->add('country', CountryType::class, ['label' => false])
                ->add('photo', FileType::class, ['label' => false, 'attr' => ['class' => 'dropify']])
                ->add('submit', SubmitType::class, ['label' => 'Valider'])
                ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $encoded = $encoder->encodePassword($user, $form->get('password')->getData());
                $user->setPassword($encoded)->setRoles(['ROLE_USER']);
                /**
                 * @var UploadedFile $imageFile
                 */
                $imageFile = $form->get('photo')->getData();
                $newFilename = $user->getFirstname() . '-' . $user->getLastname() . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($this->getParameter('users_directory'), $newFilename);
                } catch (FileException $e) {
                    die();
                }
                $user->setPhoto($newFilename);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('notice', 'Félicitations vous êtes inscrit !');
                return $this->redirectToRoute('default_home');
            }
            return $this->render('user/register.html.twig', ['form' => $form->createView()]);
        }
    }