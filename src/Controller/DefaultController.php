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
                    'bannerTitle' => 'En recherche de destination de vacances ?',
                    'bannerText' => 'Nous vous proposons des propriétés à louer pour tout les goûts !'
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
         *
         */
        public function countAll() : int
        { $result = $this->getDoctrine()->count();
            if (\is_null($result)) {
                throw new \UnexpectedValueException('Post total count error: list can not be generated!');
            }
            return $result;
        }
    }