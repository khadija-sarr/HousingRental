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
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\String\Slugger\SluggerInterface;
    class HouseController extends AbstractController {
        /**
         * @Route("/logement/nouveau", name="house_new", methods={"GET|POST"})
         * @param Request $request
         * @param SluggerInterface $slugger
         * @return RedirectResponse|Response
         */
        public function newHouse(Request $request, SluggerInterface $slugger) {
            $house = new House();
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find(2);
            $house->setUser($user);
            $form = $this->createFormBuilder($house)
                ->add('photo', FileType::class, ['label' => false, 'attr' => ['class' => 'dropify']])
                ->add('name', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Nom de logement']])
                ->add('description', TextareaType::class, ['label' => false])
                ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name', 'label' => 'catégorie'])
                ->add('address', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Adresse']])
                ->add('zipcode', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Code Postal']])
                ->add('city', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Ville']])
                ->add('country', CountryType::class, ['label' => false, 'attr' => ['placeholder' => 'Pays']])
                ->add('price', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Prix']])
                ->add('submit', SubmitType::class, ['label' => 'Ajouter', 'attr' => ['class' => 'btn-block btn-dark']])
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
                $this->addFlash('notice', 'Félicitation votre article est en ligne !');
                return $this->redirectToRoute('default_house', ['category' => $house->getCategory()->getAlias(), 'alias' => $house->getAlias(), 'id' => $house->getId()]);
            }
            return $this->render('house/new.html.twig', ['form' => $form->createView()]);
        }
    }