<?php


namespace App\Controller;




use App\Entity\Category;
use App\Entity\House;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /*
     *  Page d'accueil
     */
    public function home()
    {

        $houses = $this->getDoctrine()
            ->getRepository(House::class)
            ->findBy([], ['id' => 'DESC'], 6);

        # 2. Transmettre données à la vue (View)
        return $this->render('default/home.html.twig', [
            'houses' => $houses
        ]);
    }

    /**
     * @Route("/category/{alias}", name="default_category", methods={"GET"})
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function category(Category $category)
    {
        return $this->render('default/category.html.twig', [
            'category' => $category,
        ]);
    }







}