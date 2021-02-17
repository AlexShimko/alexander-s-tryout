<?php

declare(strict_types=1);

namespace App\Controller;

use App\Facade\FeeCalculationFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @param Request $request
     * @param FeeCalculationFacade $feeCalculationFacade
     * @return JsonResponse
     */
    public function calculateFee(
        Request $request,
        FeeCalculationFacade $feeCalculationFacade
    ): JsonResponse {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $result = $feeCalculationFacade->calculateFeesFromFile($uploadedFile);

        return $this->json($result);
    }
}
