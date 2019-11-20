<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() : Response
    {
        return $this->render('Wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/wild/show/{slug}", defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"} ,requirements={"slug"="[a-z0-9\-]+"}, name="wild_show")
     */
    public function show($slug) : Response
    {
        $finalSlug = $slug;
        if($slug == 'ma-superbe-serie' || $slug == 'house-of-cards') {
            $slug = ucfirst($slug);
            $finalSlug = str_replace('-', ' ', $slug);
        }

        return $this->render('Wild/show.html.twig', ['slug' => $finalSlug]);
    }
}
