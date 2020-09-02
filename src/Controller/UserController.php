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




/**
 * Class PostController
 * @package App\Controller
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Formulaire pour ajouter un nouveau membre
     * @Route("/register", name="user_register", methods={"GET|POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */
    public function newUser(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Your firstname"
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Your lastname"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Your email"
                ]
            ])

            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Your password"
                ]
            ])
            ->add('phone', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Your phone number"
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Your address"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Your City"
                ]
            ])
            ->add('zipcode', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Your Zip Code"
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => false
            ])
            ->add('photo', FileType::class, [
                'label' => false,

                'attr' => [
                    'class' => 'dropify'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon inscription'
            ])

            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $encoded = $encoder->encodePassword($user, $form->get('password')->getData());
            $user->setPassword($encoded)->setRoles(['ROLE_USER']);

            # Upload de l'image
            /**
             * @var UploadedFile $imageFile
             */
            $imageFile = $form->get('photo')->getData();
            $newFilename = $user->getFirstname() . '-' . $user->getLastname() . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('users_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                # TODO Handle catch exception
            }


            $user->setPhoto($newFilename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('notice',
                'Félicitations vous êtes inscrit !');

            return $this->redirectToRoute('home');


        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

}