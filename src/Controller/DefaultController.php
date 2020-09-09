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
            $houses = $this->getDoctrine()
                ->getRepository(House::class)
                ->findBy([], ['id' => 'DESC'], 4);
            return $this->render('default/home.html.twig', ['houses' => $houses]);
        }
        /**
         * @Route("/categorie/{alias}", name="default_category", methods={"GET"})
         * @param Category $category
         * @return Response
         */
        public function category(Category $category) {
            $houses = $this->getDoctrine()
            ->getRepository(House::class)
            ->findBy(['category' => $category]);
            return $this->render('default/category.html.twig', ['category' => $category, 'houses' => $houses]);
        }
        /**
         * @Route("/categorie/{category}/{alias}_{id}.html", name="default_house", methods={"GET"})
         * @param House|null $house
         * @return Response
         */
        public function house(House $house = null) {
            if ($house === null) {
                return $this->redirectToRoute('/');
            }
            return $this->render('default/house.html.twig', ['house' =>$house]);
        }
    }