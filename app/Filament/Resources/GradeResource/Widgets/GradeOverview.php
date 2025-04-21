<?php

namespace App\Filament\Resources\GradeResource\Widgets;

use App\Models\Grade;
use Filament\Widgets\ChartWidget;

class GradeOverview extends ChartWidget
{
    protected static ?string $heading = 'Nilai Rata-Rata per Semester';

    protected function getData(): array
    {
        $nilaiPerSemester = Grade::selectRaw('term, grade')
            ->groupBy('term')
            ->orderBy('term')
            ->get();

        $labels = $nilaiPerSemester->pluck('term')->map(fn ($s) => 'Semester ' . $s)->toArray();
        $data = $nilaiPerSemester->pluck('grade')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Nilai Rata-Rata Per-Semester',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6', // opsional: warna biru
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
