<?php

namespace App\Controller;

use App\Entity\Temperature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/log', name: 'api_log', methods: ['POST'])]
    public function log(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $expectedToken = $this->getParameter('app.api_token');

        if ($authorizationHeader !== 'Bearer ' . $expectedToken) {
            return new JsonResponse(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['temperature']) || !is_numeric($data['temperature'])) {
            return new JsonResponse(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $temperature = new Temperature();
        $temperature->setTemperature($data['temperature']);

        $entityManager->persist($temperature);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Temperature logged'], Response::HTTP_CREATED);
    }
}
