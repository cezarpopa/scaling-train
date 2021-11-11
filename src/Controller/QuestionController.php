<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class QuestionController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(QuestionRepository $repository)
    {
        $questions = $repository->findAllAskedByNewest([]);

        return $this->render(
            'question/homepage.html.twig',
            [
                'questions' => $questions
            ]
        );
    }

    /**
     * @Route("questions/new")
     * @throws \Exception
     */
    public function new(): Response
    {
        return new Response('Great feature to write',);
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show(Question $question): Response
    {
        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    /**
     * @Route("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager): Response
    {
        $direction = $request->request->get('direction');

        if($direction === 'up') {
            $question->upVote();
        } else {
            $question->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_question_show',  [
            'slug' => $question->getSlug(),
        ]);
    }
}
