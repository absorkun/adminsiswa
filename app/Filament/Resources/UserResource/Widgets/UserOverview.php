<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $data = [
            'datasets' => [
                [
                    'label' => 'My Label',
                    'data' => [1, 2, 3, 6, 5, 4],
                ]
            ],
            'labels' => ['Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam'],
        ];

        return $data;
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
