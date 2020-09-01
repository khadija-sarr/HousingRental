<?php
    namespace App\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    class AdminController extends AbstractController {
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin", name="admin_backoffice", methods={"GET"})
         */
        public function backoffice() {
            if($this->getUser()) {
                return $this->render('admin/backoffice.html.twig', ['title' => 'Back Office']);
            } else {
                return $this->redirectToRoute('default_home');
            }
        }
    }