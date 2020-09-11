<?php
    namespace App\Controller;
    use App\Entity\Category;
    use App\Entity\House;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    class DefaultController extends AbstractController {
        /**
         * @Route("/", name="default_home", methods={"GET"})
         * @return Response
         */
        public function home() {
            $properties = $this->getDoctrine()->getRepository(House::class)->findAll();
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $lastHouses = $this->getDoctrine()
            ->getRepository(House::class)
            ->findBy([], ['id' => 'DESC'], 4);
            $apartments = $this->getDoctrine()
            ->getRepository(House::class)
            ->findBy(['category' => 1], ['id' => 'DESC'], 4);
            $houses = $this->getDoctrine()
            ->getRepository(House::class)
            ->findBy(['category' => 2], ['id' => 'DESC'], 4);
            $events = $this->getDoctrine()
            ->getRepository(House::class)
            ->findBy(['category' => 3], ['id' => 'DESC'], 4);
            return $this->render('default/home.html.twig',
                [
                    'lastHouses' => $lastHouses,
                    'apartments' => $apartments,
                    'houses' => $houses,
                    'events' => $events,
                    'categories' => $categories,
                    'properties' => $properties,
                    'bannerTitle' => 'En recherche de destination de vacances ?',
                    'bannerText' => 'Nous vous proposons des propriétés à louer pour tout les goûts !'
                ]);
        }
        /**
         * @Route("/monde/proprietes", name="default_worldwide", methods={"GET"})
         */
        public function worldwide() {
            return $this->render('default/worldwide.html.twig',
                [
                    'bannerTitle' => 'Monde',
                    'bannerText' => 'Des propriétés pour vos vacances partout dans le monde'
                ]);
        }
        /**
         * @Route("/proprietes/tarification", name="default_pricing", methods={"GET"})
         */
        public function pricing() {
            return $this->render('default/pricing.html.twig',
                [
                    'bannerTitle' => 'Tarification',
                    'bannerText' => 'Meilleur prix garanti pour chaque propriété en location'
                ]);
        }
        /**
         * @Route("/categorie/{alias}", name="default_category", methods={"GET"})
         * @param Category $category
         * @return Response
         */
        public function category(Category $category) {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $houses = $this->getDoctrine()
            ->getRepository(House::class)
            ->findBy(['category' => $category]);
            $bannerTitle = "";
            if($category->getName() == "Appartement") {
                $bannerTitle = "appartements";
            }
            if($category->getName() == "Maison") {
                $bannerTitle = "maisons";
            }
            if($category->getName() == "Évènement") {
                $bannerTitle = "propriétés pour mettre en place un évènement";
            }
            return $this->render('default/category.html.twig',
                [
                    'category' => $category,
                    'categories' => $categories,
                    'houses' => $houses,
                    'bannerTitle' => $category->getName(),
                    'bannerText' => 'Découvrez nos ' . $bannerTitle
                ]);
        }
        /**
         * @Route("/categorie/{category}/{alias}_{id}.html", name="default_house", methods={"GET"})
         * @param House|null $house
         * @return Response
         */
        public function house(House $house = null) {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            if ($house === null) {
                return $this->redirectToRoute('/');
            }
            return $this->render('default/house.html.twig',
                [
                    'house' => $house,
                    'categories' => $categories,
                    'bannerTitle' => $house->getName(),
                    'bannerText' => 'Découvrez plus d\'informations sur cette propriété juste en dessous !'
                ]);
        }
        /**
         * @Route("/politique-de-confidentialite", name="default_privacy")
         * @return Response
         */
        public function privacy() {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            return $this->render('default/privacy.html.twig', [
                'categories' => $categories,
                'bannerTitle' => 'Politique de confidentialité',
                'bannerText' => ''
            ]);
        }
    }