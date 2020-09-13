<?php
    namespace App\Controller;
    use App\Entity\Category;
    use App\Entity\House;
    use App\Entity\User;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    class AdminController extends AbstractController {
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin", name="admin_backOffice", methods={"GET|POST"})
         * @param Request $request
         * @param UserPasswordEncoderInterface $encoder
         * @return Response
         */
        public function backOffice(Request $request, UserPasswordEncoderInterface $encoder) {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            $houses = $this->getDoctrine()->getRepository(House::class)->findAll();
            $admin = $this->getUser();
            return $this->render('admin/backoffice.html.twig',
                [
                    'users' => $users,
                    'categories' => $categories,
                    'houses' => $houses,
                    'bannerTitle' => 'Bonjour ' . $admin->getFirstname(),
                    'bannerText' => 'Bienvenue sur le dashboard administrateur'
                ]);
        }
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin/utilisateur/supprimer/{id}", name="admin_deleteUser", methods={"GET"})
         * @param $id
         * @return Response
         */
        public function deleteUser($id) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            if($user) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash('success', 'L\'utilisateur a bien été supprimé.');
            } else {
                $this->addFlash('error', 'Impossible de supprimer l\'utilisateur.');
            }
            return $this->redirectToRoute('admin_backOffice');
        }
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin/location/supprimer/{id}", name="admin_deleteRental", methods={"GET"})
         * @param $id
         * @return Response
         */
        public function deleteRental($id) {
            $rental = $this->getDoctrine()->getRepository(House::class)->find($id);
            if($rental) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($rental);
                $entityManager->flush();
                $this->addFlash('success', 'La propriété à bien été supprimée.');
            } else {
                $this->addFlash('error', 'Impossible de supprimer la propriété.');
            }
            return $this->redirectToRoute('admin_backOffice');
        }
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin/profil/modifier/{id}", name="admin_updateProfile", methods={"GET|POST"})
         * @param Request $request
         * @param UserPasswordEncoderInterface $encoder
         * @return Response
         */
        public function updateAdmin(Request $request, UserPasswordEncoderInterface $encoder) {
            $adminForm = $this->createFormBuilder($this->getUser())
            ->add('firstname', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Prénom"]])
            ->add('lastname', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Nom"]])
            ->add('email', EmailType::class, ['label' => false, 'attr' => ['placeholder' => "Email"]])
            ->add('password', PasswordType::class, ['label' => false, 'attr' => ['placeholder' => "Mot de passe"]])
            ->add('phone', IntegerType::class, ['label' => false, 'attr' => ['placeholder' => "Téléphone"]])
            ->add('address', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Adresse"]])
            ->add('city', TextType::class, ['label' => false, 'attr' => ['placeholder' => "Ville"]])
            ->add('zipcode', IntegerType::class, ['label' => false, 'attr' => ['placeholder' => "Code Postal"]])
            ->add('country', CountryType::class, ['label' => false])
            ->add('photo', FileType::class, ['label' => false, 'attr' => ['class' => 'dropify'], 'data_class' => null])
            ->add('submit', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
            $adminForm->handleRequest($request);
            $admin = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
            if($adminForm->isSubmitted() && $adminForm->isValid()) {
                $encoded = $encoder->encodePassword($admin, $adminForm->get('password')->getData());
                $admin->setPassword($encoded)->setRoles(['ROLE_ADMIN']);
                /**
                 * @var UploadedFile $imageFile
                 */
                $imageFile = $adminForm->get('photo')->getData();
                $newFilename = $admin->getFirstname() . '-' . $admin->getLastname() . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($this->getParameter('users_directory'), $newFilename);
                } catch (FileException $e) {
                    die();
                }
                $admin->setPhoto($newFilename);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                $this->addFlash('success', 'Félicitations, votre profil a bien été mis à jour !');
                return $this->redirectToRoute('admin_backOffice');
            }
            return $this->render('admin/edit/admin.html.twig', ['adminForm' => $adminForm->createView()]);
        }
    }