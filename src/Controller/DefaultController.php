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
         */
        public function home() {

            $houses = $this->getDoctrine()
                ->getRepository(House::class)
                ->findBy([], ['id' => 'DESC'], 6);

            return $this->render('default/home.html.twig', [
                'houses' => $houses
            ]);
        }

        /**
         * @Route("/categorie/{alias}", name="default_category", methods={"GET"})
         * @param Category $category
         * @return Response
         */

        public function category(Category $category)
        {
            return $this->render('default/category.html.twig', [
                'category' => $category,
            ]);
        }


    }