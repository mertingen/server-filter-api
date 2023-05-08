<?php

namespace App\Controller\Api;

use App\Service\ServerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_server')]
class ServerController extends AbstractController
{
    #[Route('/servers', name: '_index')]
    public function index(Request $request, ServerService $serverService): JsonResponse
    {
        try {
            $filterParams = [
                'ram' => $request->query->get('ram'),
                'storage' => $request->query->get('storage'),
                'hddType' => $request->query->get('hddType'),
                'location' => $request->query->get('location')
            ];

            $filterParams = $serverService->getFormattedFilterParams($filterParams);

            if ($filterParams['status']) {
                $servers = $serverService->getAll($filterParams['data']);
                if ($servers['status']) {
                    $response = [
                        'status' => true,
                        'data' => $servers['data'],
                        'message' => '',
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'data' => [],
                        'message' => $servers['message'],
                    ];
                }

            } else {
                $response = [
                    'status' => false,
                    'data' => [],
                    'message' => $filterParams['message'],
                ];
            }

            return $this->json($response);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'data' => [],
                    'message' => $e->getMessage(),
                ]
            );
        }

    }
}
