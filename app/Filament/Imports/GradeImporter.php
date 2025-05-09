<?php

namespace App\Filament\Imports;

use App\Models\Grade;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class GradeImporter extends Importer
{
    protected static ?string $model = Grade::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('student')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('subject')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('grade')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('term')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Grade
    {
        $grade = Grade::firstOrNew([
            'subject_id' => $this->data['subject_id'],
            'term' => $this->data['term'],
        ]);
    
        $grade->grade = $this->data['grade'];
        $grade->save();
    
        return $grade;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your grade import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
