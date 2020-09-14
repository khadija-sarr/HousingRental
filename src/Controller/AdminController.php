<?php
    namespace App\Controller;
    use App\Entity\Category;
    use App\Entity\House;
    use App\Entity\User;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    class AdminController extends AbstractController {
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin", name="admin_backOffice", methods={"GET|POST"})
         * @return Response
         */
        public function backOffice() {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            $houses = $this->getDoctrine()->getRepository(House::class)->findAll();
            $admin = $this->getUser();
            return $this->render('admin/backoffice.html.twig',
                [
                    'users' => $users,
                    'categories' => $categories,
                    'houses' => $houses,
                    'bannerTitle' => 'Bonjour ' . $admin->getFirstname(),
                    'bannerText' => 'Bienvenue sur votre espace administrateur'
                ]);
        }
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin/utilisateur/supprimer/{id}", name="admin_deleteUser", methods={"GET"})
         * @param $id
         * @return Response
         */
        public function deleteUser($id) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            if($user) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash('success', 'L\'utilisateur a bien été supprimé.');
            } else {
                $this->addFlash('error', 'Impossible de supprimer l\'utilisateur.');
            }
            return $this->redirectToRoute('admin_backOffice');
        }
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin/location/supprimer/{id}", name="admin_deleteRental", methods={"GET"})
         * @param $id
         * @return Response
         */
        public function deleteRental($id) {
            $rental = $this->getDoctrine()->getRepository(House::class)->find($id);
            if($rental) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($rental);
                $entityManager->flush();
                $this->addFlash('success', 'La propriété à bien été supprimée.');
            } else {
                $this->addFlash('error', 'Impossible de supprimer la propriété.');
            }
            return $this->redirectToRoute('admin_backOffice');
        }
    }