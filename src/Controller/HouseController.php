<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\House;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class HouseController
 * @package App\Controller
 * @Route("/logement")
 */
class HouseController extends AbstractController
{

    /**
     * Formulaire pour créer un logement
     * @Route("/nouveau", name="logement_new", methods={"GET|POST"})
     * @param Request $request
     * @param SluggerInterface $slugger
     */
    public function newHouse(Request $request, SluggerInterface $slugger)
    {
        # Créer un nouvelau logement
        $house = new House();

        # Récupération d'un User dans la BDD
        # TODO : Remplacer par l'utilisateur connecté en session.
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find(2);

        # Affectation du User à l'article
        $house->setUser($user);

        # Création du formulaire
        $form = $this->createFormBuilder($house)
            ->add('photo', FileType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'dropify'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom de logement'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'catégorie'
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])
            ->add('zipcode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code Postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('country', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Pays'
                ]
            ])
            ->add('price', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn-block btn-dark'
                ]
            ])
            ->getForm();

        # Permet à symfony de traiter les données reçues.
        $form->handleRequest($request);

        # Vérifier si le formulaire est soumis
        # Vérifier si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            # Génération de l'alias
            $house->setAlias(
                $slugger->slug(
                    $house->getName()
                )
            );

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('photo')->getData();
            $newFilename = $house->getAlias() . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->getParameter('houses_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                # TODO handle catch exception
            }

            // updates the 'imageFilename' property to store the PDF file name
            // instead of its contents

            # ------------------ Upload de l'image
            $house->setPhoto($newFilename);

            #dd ($user);
            # Enclencher la sauvegarde des données

            $em = $this->getDoctrine()->getManager();
            $em->persist($house);
            $em->flush();

            # Notification Flash(éphémère) qui se base sur les sessions.
            $this->addFlash('notice',
                'Félicitation votre article est en ligne !');


            # Redirection
            return $this->redirectToRoute('default_house', [
                'category' => $house->getCategory()->getAlias(),
                'alias' => $house->getAlias(),
                'id' => $house->getId()
            ]);
        }

        # Transmission du formulaire à la vue
        return $this->render('house/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


}