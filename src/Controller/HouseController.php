<?php
    namespace App\Controller;
    use App\Entity\Category;
    use App\Entity\House;
    use App\Entity\User;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\Extension\Core\Type\CountryType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\String\Slugger\SluggerInterface;
    class HouseController extends AbstractController {
        /**
         * @Route("/logement/nouveau", name="house_new", methods={"GET|POST"})
         * @param Request $request
         * @param SluggerInterface $slugger
         */
        public function newHouse(Request $request, SluggerInterface $slugger) {
            $house = new House();
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($this->getUser());
            $house->setUser($user);
            $houses = $this->getDoctrine()->getRepository(House::class)->findBy([], ['id' => 'DESC'], 2);
            $form = $this->createFormBuilder($house)
                ->add('name', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Nom de logement']])
                ->add('description', TextareaType::class, ['label' => false, 'attr' => ['placeholder' => 'Description']])
                ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name', 'label' => false])
                ->add('address' , TextType::class, ['label' => false, 'attr' =>  ['placeholder' => 'Adresse']  ])
                ->add('zipcode', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Code Postal']])
                ->add('city', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Ville']])
                ->add('country', CountryType::class, ['label' => false, 'preferred_choices' => ['value' => 'FR']])
                ->add('price', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Prix']])
                ->add('photo', FileType::class, ['label' => false, 'attr' => ['class' => 'dropify']])
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
                $this->addFlash('notice', 'Félicitation votre logement a bien été ajouté !');
                return $this->redirectToRoute('default_home', [
                    'category' => $house->getCategory()->getAlias(),
                    'alias' => $house->getAlias(),
                    'id' => $house->getId()]);
          }
        return $this->render('house/new.html.twig', ['form' => $form->createView(), 'houses' => $houses]);
    }
    /**
     * @Route ("/search", name="search_house", methods={"GET"})
     * @param Request $request
     */
    public function searchedHouse(Request $request) {
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('priceMin')
            ->add('priceMax')
            ->add('submit', SubmitType::class)
            ->getForm();
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
        return $this->render('house/searched.html.twig', [
            'form' => $form->createView()
        ]);
    }
}