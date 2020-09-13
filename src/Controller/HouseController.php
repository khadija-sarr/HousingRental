<?php
    namespace App\Controller;
    use App\Entity\Category;
    use App\Entity\House;
    use App\Entity\User;
    use phpDocumentor\Reflection\Types\Void_;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\Extension\Core\Type\CountryType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\String\Slugger\SluggerInterface;
    class HouseController extends AbstractController {
        /**
         * @IsGranted("ROLE_OWNER")
         * @Route("/logement/nouveau", name="house_new", methods={"GET|POST"})
         * @param Request $request
         * @param SluggerInterface $slugger
         * @return RedirectResponse|Response
         */
        public function newProperty(Request $request, SluggerInterface $slugger) {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $house = new House();
            $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->getUser());
            $house->setUser($user);
            $houses = $this->getDoctrine()->getRepository(House::class)->findBy([], ['id' => 'DESC'], 2);
            $form = $this->createFormBuilder($house)
            ->add('name', TextType::class, ['label' => 'Nom de logement'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name', 'label' => 'Catégorie'])
            ->add('zipcode', TextType::class, ['label' => 'Code Postal'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('country', CountryType::class, ['label' => 'Pays', 'preferred_choices' => ['value' => 'FR']])
            ->add('price', TextType::class, ['label' => 'Prix'])
            ->add('photo', FileType::class, ['label' => 'Photo', 'attr' => ['class' => 'dropify']])
            ->add('submit', SubmitType::class, ['label' => 'Ajouter'])
            ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $house->setAlias($slugger->slug($house->getName()));
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
                $this->addFlash('success', 'Votre location a bien été ajoutée.');
                return $this->redirectToRoute('default_home',
                    [
                        'category' => $house->getCategory()->getAlias(),
                        'alias' => $house->getAlias(),
                        'id' => $house->getId()
                    ]);
          }
        return $this->render('house/new.html.twig',
            [
                'categories' => $categories,
                'form' => $form->createView(),
                'houses' => $houses,
                'bannerTitle' => 'Location',
                'bannerText' => 'Proposez votre propriété comme destination de vacances !'
            ]);
    }
    /**
     * @Route ("/recherche", name="search_house", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function searchedHouse(Request $request) {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $houses = $this->getDoctrine()->getRepository(House::class);
        $result = [];
        $form = $this->createFormBuilder()
        ->setMethod('GET')
        ->add('priceMin', TextType::class, ['label' => 'Prix min / nuit'])
        ->add('priceMax', TextType::class, ['label' => 'Prix max / nuit'])
        ->add('submit', SubmitType::class, ['label' => 'Valider'])
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $search = $form->getData();
            $priceMin = $search['priceMin'];
            $priceMax = $search['priceMax'];
            $result = $houses->findHouses($priceMin, $priceMax);
            if(sizeof($result) < 1) {
                $this->addFlash('error', 'Aucune propriété n\'a été trouvée.');
            }
        }
        return $this->render('house/searched.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
            'result' => $result,
            'bannerTitle' => 'Recherche',
            'bannerText' => 'Trouvez une propriété sur mesure pour vos vacances !'
        ]);
    }
}