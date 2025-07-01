<?php

namespace App\Controller;

use App\Repository\TemperatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard_home')]
    public function home(TemperatureRepository $temperatureRepository): Response
    {
        $lastTemperature = $temperatureRepository->findLast();

        return $this->render('dashboard/home.html.twig', [
            'lastTemperature' => $lastTemperature,
        ]);
    }

    #[Route('/today', name: 'dashboard_today')]
    public function today(TemperatureRepository $temperatureRepository): Response
    {
        $todayTemperatures = $temperatureRepository->findTodayTemperatures();

        $labels = [];
        $data = [];
        foreach ($todayTemperatures as $temp) {
            $labels[] = $temp['created_at'];
            $data[] = $temp['temperature'];
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Température du jour',
                    'data' => $data,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'fill' => false,
                ],
            ],
        ];

        return $this->render('dashboard/today.html.twig', [
            'chartData' => $chartData,
        ]);
    }

    #[Route('/history', name: 'dashboard_history')]
    public function history(TemperatureRepository $temperatureRepository): Response
    {
        $dailyAverages = $temperatureRepository->findLast30DaysDailyAverages();

        $chartData = [
            'labels' => array_column($dailyAverages, 'day'),
            'datasets' => [
                [
                    'label' => 'Température moyenne par jour',
                    'data' => array_column($dailyAverages, 'avg_temp'),
                    'borderColor' => 'rgb(255, 99, 132)',
                ],
            ],
        ];

        return $this->render('dashboard/history.html.twig', [
            'chartData' => $chartData,
        ]);
    }
}
