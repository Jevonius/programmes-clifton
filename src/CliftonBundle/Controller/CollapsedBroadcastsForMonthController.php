<?php

namespace BBC\CliftonBundle\Controller;

use BBC\CliftonBundle\ApsMapper\CollapsedBroadcastMapper;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CollapsedBroadcastsForMonthController extends BaseApsController
{
    public function collapsedBroadcastsForMonthAction(
        Request $request,
        string $pid,
        string $year,
        string $month
    ): JsonResponse {
        $pid = new Pid($pid);

        // Only valid for Brands and Series
        $programme = $this->get('pps.programmes_service')->findByPid($pid, "ProgrammeContainer");
        if (!$programme) {
            throw $this->createNotFoundException('Not Found');
        }

        $bs = $this->get('pps.collapsed_broadcasts_service');
        $broadcastsByMonth = $bs->findByProgrammeAndMonth($programme, $year, $month, $bs::NO_LIMIT);

        if (empty($broadcastsByMonth)) {
            throw $this->createNotFoundException('Not Found');
        }

        $mappedBroadcasts = $this->mapManyApsObjects(new CollapsedBroadcastMapper(), $broadcastsByMonth);

        return $this->json(['broadcasts' => $mappedBroadcasts]);
    }
}