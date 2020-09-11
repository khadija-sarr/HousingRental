<?php
    namespace App\Controller;
    use App\Entity\Category;
    use App\Entity\User;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\Extension\Core\Type\CountryType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
            if($this->getUser()) {
                return $this->redirectToRoute('default_home');
            }
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $user = new User();
            $form = $this->createFormBuilder($user)
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('phone', IntegerType::class, ['label' => 'Téléphone'])
            ->add('address', TextType::class, ['label' => 'Adresse'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('zipcode', IntegerType::class, ['label' => 'Code Postal'])
            ->add('country', CountryType::class, ['label' =>'Pays','preferred_choices' => ['value' => 'FR']])
            ->add('photo', FileType::class, ['label' => 'Photo', 'attr' => ['class' => 'dropify']])
            ->add('submit', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $encoded = $encoder->encodePassword($user, $form->get('password')->getData());
                $user->setPassword($encoded)->setRoles(['ROLE_USER']);
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
            return $this->render('user/register.html.twig',
                [
                    'form' => $form->createView(),
                    'categories' => $categories,
                    'bannerTitle' => 'Inscription',
                    'bannerText' => 'Inscrivez-vous sur notre site pour pouvoir louer la propriété de vos rêves !'
                ]);
        }
        /**
         * @Route("/profil", name="user_profile", methods={"GET|POST"})
         */
        public function profil() {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $user = $this->getUser();
            return $this->render('user/profile.html.twig',
                [
                    'user' => $user,
                    'categories' => $categories,
                    'bannerTitle' => 'Profil',
                    'bannerText' => 'Toutes vos information et réservations sont en-dessous'
                ]);
        }
        /**
         * @Route("/modification", name="user_update", methods={"GET|POST"} )
         * @param Request $request
         * @param UserPasswordEncoderInterface $encoder
         * @return Response
         */
        public function update(Request $request, UserPasswordEncoderInterface $encoder) {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $user = $this->getUser();
            $form = $this->createFormBuilder($user)
                ->add("email", EmailType::class, ['label' => 'Email'])
                ->add("phone", NumberType::class, ['label' => 'Téléphone'])
                ->add("address", TextType::class, ['label' => 'Adresse'])
                ->add('zipcode', TextType::class, ['label' => 'Code postal'])
                ->add('city', TextType::class, ['label' => 'Ville'])
                ->add('submit', SubmitType::class, ['label' => 'Modifier']);
            $form = $form->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('notice', 'Vos modifications ont bien été prises en compte !');
                return $this->redirectToRoute('user_profile');
            }
            return $this->render('user/update.html.twig', ['form' => $form->createView(),
                'bannerTitle' => 'Modification du profil',
                'bannerText' => "Vous pouvez modifier vos informations en-dessous",
                'categories' => $categories
            ]);
        }
    }