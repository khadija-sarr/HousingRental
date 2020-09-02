<?php
    namespace App\Controller;
    use App\Entity\Category;
    use App\Entity\User;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    class AdminController extends AbstractController {
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/admin", name="admin_backOffice", methods={"GET"})
         * @return Response
         */
        public function backOffice() {
            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            return $this->render('admin/backoffice.html.twig', ['users' => $users, 'categories' => $categories]);
        }
        /**
         * @IsGranted("ROLE_ADMIN")
         * @Route("/utilisateur/supprimer/{id}", name="admin_deleteUser", methods={"GET"})
         * @param $id
         * @return Response
         */
        public function deleteUser($id) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_backOffice');
        }
    }