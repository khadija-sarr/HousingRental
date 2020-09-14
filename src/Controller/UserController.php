<?php
    namespace App\Controller;
    use App\Entity\Category;
    use App\Entity\User;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\CountryType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
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
         * @Route("/inscription", name="user_register", methods={"GET|POST"})
         * @param Request $request
         * @param UserPasswordEncoderInterface $encoder
         * @return RedirectResponse|Response
         */
        public function register(Request $request, UserPasswordEncoderInterface $encoder) {
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
            ->add('roles', ChoiceType::class,
                [
                    'label' => 'Status',
                    'attr' => [
                        'title' => 'Le statut indique quelle utilisation vous souhaitez avoir du site. 
                        Si vous choisissez d\'être un utilisateur, il vous sera uniquement possible de réserver des propriétés pour vos vacances. 
                        Si vous indiquez être propriétaire, en plus de pouvoir réserver, il vous sera notamment possible de proposer vos propres propriétés comme destination de vacances sur notre site.'
                    ],
                    'choices' => ['Utilisateur' => 'ROLE_USER', 'Propriétaire' => 'ROLE_OWNER'],
                    'multiple' => false,
                    'expanded' => true
                ])
            ->add('gender', ChoiceType::class, ['label' => 'Sexe', 'choices' => ['Homme' => 'Homme', 'Femme' => 'Femme'], 'multiple' => false, 'expanded' => true])
            ->add('phone', TextType::class, ['label' => 'Téléphone'])
            ->add('address', TextType::class, ['label' => 'Adresse'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('zipcode', TextType::class, ['label' => 'Code Postal'])
            ->add('country', CountryType::class, ['label' =>'Pays','preferred_choices' => ['value' => 'FR']])
            ->add('photo', FileType::class, ['label' => 'Photo', 'attr' => ['class' => 'dropify']])
            ->add('submit', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $encoded = $encoder->encodePassword($user, $form->get('password')->getData());
                $role = $form->get('roles')->getData();
                $photo = $form->get('photo')->getData();
                $user->setPassword($encoded)->setRoles([$role]);
                if($photo != null) {
                    $imageFile = $form->get('photo')->getData();
                    $newFilename = $user->getFirstname() . '-' . $user->getLastname() . '-' . uniqid() . '.' . $imageFile->guessExtension();
                    try {
                        $imageFile->move($this->getParameter('users_directory'), $newFilename);
                    } catch (FileException $e) {
                        die();
                    }
                    $user->setPhoto($newFilename);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Votre inscription est validée.');
                return $this->redirectToRoute('app_login');
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
         * @IsGranted("IS_AUTHENTICATED_FULLY")
         * @Route("/profil", name="user_profile", methods={"GET|POST"})
         */
        public function profil() {
            if(!$this->getUser()) {
                $this->addFlash('error', 'Vous devez être connecté.');
                return $this->redirectToRoute('app_login');
            }
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
         * @return Response
         */
        public function update(Request $request) {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $user = $this->getUser();
            $oldPhoto = $this->getDoctrine()->getRepository(User::class)->find($this->getUser())->getPhoto();
            $form = $this->createFormBuilder($user)
            ->add("email", EmailType::class, ['label' => 'Email'])
            ->add('country', CountryType::class, ['label' =>'Pays','preferred_choices' => ['value' => 'FR']])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('zipcode', TextType::class, ['label' => 'Code postal'])
            ->add("address", TextType::class, ['label' => 'Adresse'])
            ->add("phone", TextType::class, ['label' => 'Téléphone'])
            ->add('photo', FileType::class, ['label' => 'Photo', 'data_class' => null, 'attr' => ['class' => 'dropify', 'data-default-file' => '/uploads/users/' . $user->getPhoto()]])
            ->add('submit', SubmitType::class, ['label' => 'Modifier']);
            $form = $form->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $photo = $form->get('photo')->getData();
                if($photo != null) {
                    $imageFile = $form->get('photo')->getData();
                    $newFilename = $user->getFirstname() . '-' . $user->getLastname() . '-' . uniqid() . '.' . $imageFile->guessExtension();
                    try {
                        $imageFile->move($this->getParameter('users_directory'), $newFilename);
                    } catch (FileException $e) {
                        die();
                    }
                    $user->setPhoto($newFilename);
                } else {
                    $user->setPhoto($oldPhoto);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Votre profil a bien été modifié.');
                return $this->redirectToRoute('user_profile');
            }
            return $this->render('user/update.html.twig', ['form' => $form->createView(),
                'bannerTitle' => 'Modification du profil',
                'bannerText' => "Vous pouvez modifier vos informations en-dessous",
                'categories' => $categories
            ]);
        }
    }