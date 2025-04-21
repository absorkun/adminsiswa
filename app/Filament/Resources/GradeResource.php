<?php

namespace App\Filament\Resources;

use App\Filament\Exports\GradeExporter;
use App\Filament\Imports\GradeImporter;
use App\Filament\Resources\GradeResource\Pages;
use App\Filament\Resources\GradeResource\RelationManagers;
use App\Models\Grade;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Nilai Siswa';

    protected static ?string $navigationGroup = 'Siswa';


    public static function canCreate(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'guru']);
    }

    public static function canEdit(Model $record): bool
    {
        return in_array(Auth::user()->role, ['admin', 'guru']);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Siswa')
                    ->relationship('student', 'name')
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship('subject', 'name')
                    ->required(),
                Forms\Components\TextInput::make('grade')
                    ->label('Nilai')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('term')
                    ->label('Semester')
                    ->options([
                        1 => 'Kelas VII Semester 1',
                        2 => 'Kelas VII Semester 2',
                        3 => 'Kelas VIII Semester 1',
                        4 => 'Kelas VIII Semester 2',
                        5 => 'Kelas IX Semester 1',
                        6 => 'Kelas IX Semester 2',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role != 'admin' && Auth::user()->role != 'guru') {
                    $query->where('student_id', Auth::user()->student->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Siswa')
                    ->visible(fn() => Auth::user()->role == 'admin')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grade')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('term')
                    ->formatStateUsing(function ($state) {
                        $semesters = [
                            1 => 'Kelas VII Semester 1',
                            2 => 'Kelas VII Semester 2',
                            3 => 'Kelas VII Semester 1',
                            4 => 'Kelas VII Semester 2',
                            5 => 'Kelas IX Semester 1',
                            6 => 'Kelas IX Semester 2',
                        ];
                        return $semesters[$state] ?? null;
                    })
                    ->label('Semester')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('term')
                    ->label('Semester')
                    ->options([
                        1 => 'Kelas VII Semester 1',
                        2 => 'Kelas VII Semester 2',
                        3 => 'Kelas VII Semester 1',
                        4 => 'Kelas VII Semester 2',
                        5 => 'Kelas IX Semester 1',
                        6 => 'Kelas IX Semester 2',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(GradeExporter::class)
                    ->fileDisk('public')
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ]),
                ImportAction::make()
                    ->importer(GradeImporter::class),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrade::route('/create'),
            'edit' => Pages\EditGrade::route('/{record}/edit'),
        ];
    }
}
