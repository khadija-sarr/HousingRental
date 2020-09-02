<?php
    namespace App\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    class AdminController extends AbstractController {
        /**
         * @Route("/admin", name="admin_backoffice", methods={"GET"})
         */
        public function backoffice() {
            return $this->render('admin/backoffice.html.twig', ['title' => 'Back Office']);
//            if($this->getUser()) {
//            } else {
//                return $this->redirectToRoute('default_home');
//            }
        }
    }