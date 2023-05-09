<?php

namespace App\Controller\Api;

use App\Enum\HddType;
use App\Enum\SizeUnit;
use App\Service\ServerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            // Check valid HDD types by enum
            if (isset($filterParams['hddType'])) {
                if (!HddType::tryFrom($filterParams['hddType'])) {
                    return $this->json(
                        [
                            'status' => false,
                            'data' => [],
                            'message' => '',
                        ],
                        Response::HTTP_BAD_REQUEST,
                    );
                }
            }

            $filterParams = $serverService->getFormattedFilterParams($filterParams);

            $servers = $serverService->getAll($filterParams);
            if (empty($servers)) {
                return $this->json(
                    [
                        'status' => false,
                        'data' => [],
                        'message' => 'Servers cannot be found!',
                    ],
                    Response::HTTP_OK,
                );
            }

            return $this->json(
                [
                    'status' => true,
                    'data' => $servers,
                    'message' => '',
                ],
                Response::HTTP_OK,
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'data' => [],
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

    }
}
