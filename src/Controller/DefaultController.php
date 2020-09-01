<?php
    namespace App\Controller;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    class DefaultController extends AbstractController {
        /**
         * @Route("/", name="default_home", methods={"GET"})
         */
        public function home() {
            return $this->render('default/home.html.twig');
        }
    }