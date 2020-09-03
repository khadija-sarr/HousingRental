<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\House;
use App\Entity\User;
use App\Repository\HouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class HouseController extends AbstractController
{

    /**
     * @Route("/logement/nouveau", name="house_new", methods={"GET|POST"})
     * @param Request $request
     * @param SluggerInterface $slugger
     */
    public function newHouse(Request $request, SluggerInterface $slugger)
    {
        $house = new House();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->getUser());
        $house->setUser($user);
        $form = $this->createFormBuilder($house)
            ->add('photo', FileType::class, ['label' => false, 'attr' => ['class' => 'dropify']])
            ->add('name', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Nom de logement']])
            ->add('description', TextareaType::class, ['label' => false])
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name', 'label' => 'catégorie'])
            ->add('address', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Adresse']])
            ->add('zipcode', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Code Postal']])
            ->add('city', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Ville']])
            ->add('country', CountryType::class, ['label' => false])
            ->add('price', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Prix']])
            ->add('submit', SubmitType::class, ['label' => 'Ajouter'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $house->setAlias($slugger->slug($house->getName()));
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('photo')->getData();
            $newFilename = $house->getAlias() . '-' . uniqid() . '.' . $imageFile->guessExtension();
            try {
                $imageFile->move($this->getParameter('houses_directory'), $newFilename);
            } catch (FileException $e) {
                die();
            }
            $house->setPhoto($newFilename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($house);
            $em->flush();
            $this->addFlash('notice', 'Félicitations votre logement a bien été ajouté !');
            return $this->redirectToRoute('default_home', [
                'category' => $house->getCategory()->getAlias(),
                'alias' => $house->getAlias(),
                'id' => $house->getId()]);
        }
        return $this->render('house/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route ("/search", name="search_house", methods={"GET"})
     * @param Request $request
     */
    public function searchedHouse(Request $request)
    {
        # Formulaire de recherche en GET
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('priceMin')
            ->add('priceMax')

            ->add('submit', SubmitType::class)
            ->getForm();

        # Récupération des données du formulaire
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $search = $form->getData();
            $priceMin = $search['priceMin'];
            $priceMax = $search['priceMax'];

            # Recherche dans la BDD
            $houses = $this->getDoctrine()->getRepository(House::class);
            $result = $houses->findHouses($priceMin, $priceMax);

            dump($search);
            dd($result);

        }

        # Affichage du formulaire
        return $this->render('house/searched.html.twig', [
            'form' => $form->createView()


        ]);


    }

}