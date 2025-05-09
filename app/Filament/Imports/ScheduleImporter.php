<?php

namespace App\Filament\Imports;

use App\Models\Schedule;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ScheduleImporter extends Importer
{
    protected static ?string $model = Schedule::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('subject')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('teacher')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('classroom')
                ->relationship(),
            ImportColumn::make('day')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('time')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Schedule
    {
        // return Schedule::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Schedule();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your schedule import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
