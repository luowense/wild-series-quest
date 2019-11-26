<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\program;
use App\Entity\Category;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


Class WildController extends AbstractController
{
    /**
     * @Route("/index", name="app_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render(
            'Wild/index.html.twig',
            ['programs' => $programs,]);
    }

    /**
     * @Route("wild/show/{slug}", defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"} ,requirements={"slug"="[a-z0-9\-]+"}, name="wild_show")
     */
    public function show($slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug gas been sent to fin a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), '-')
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        return $this->render('Wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug
        ]);
    }

    /**
     * @Route("wild/category/{category}", name="show_category")
     */
    public function showByCategory($category): Response
    {
        $programByCategory = '';

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Program::class);

        $repositoryCategory = $this->getDoctrine()
            ->getManager()
            ->getRepository(Category::class);

        $cat = $repositoryCategory->findOneBy(['name' => $category]);

        if ($category !=  $cat->getName() || empty($category)) {
            throw $this->createNotFoundException(
                'No category found in program\'s table.'
            );
        }

        if ($category == $cat->getName()) {
            $programByCategory = $repository->findBy(array('category' => 1,), array('id' => 'desc'), 3, 0 );
        }
        return $this->render('Wild/category.html.twig', [
           'programs' => $programByCategory,
        ]);
    }

    /**
     * @Route("wild/program/{slug}", name="program_show")
     */
    public function showByProgram($slug): Response
    {

        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug gas been sent to fin a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), '-')
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }


        return $this->render('Wild/program.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("wild/season/{id}", name="episode_show")
     */
    public function showBySeason(int $id): Response
    {
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);

        $finalProgram = $seasons->getProgram();
        $finalEpisode = $seasons->getEpisodes();

        return $this->render('Wild/season.html.twig', [
            'seasons' => $seasons,
            'finalProgram' => $finalProgram,
            'finalEpisode' => $finalEpisode
        ]);
    }
}
