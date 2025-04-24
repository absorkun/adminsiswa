<?php

namespace App\Filament\Resources;

use App\Filament\Exports\TeacherExporter;
use App\Filament\Imports\TeacherImporter;
use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-s-academic-cap';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Data Guru';

    protected static ?string $navigationGroup = 'Guru';


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
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name', function ($query) {
                        $query->where('role', 'guru');
                    })
                    ->live(true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('name', User::find($state)->name))
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->hidden(fn(Get $get) => ! $get('user_id'))
                    ->required(),
                Forms\Components\TextInput::make('nuptk')
                    ->label('NUPTK/NIK')
                    ->required(),
                Forms\Components\Radio::make('gender')
                    ->label('Jenis Kelamin')
                    ->options(['l' => 'Laki-laki', 'p' => 'Perempuan'])
                    ->required(),
                Forms\Components\DatePicker::make('birthday')
                    ->label('Tanggal Lahir')
                    ->locale('id')
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nuptk')
                    ->label('NUPTK/NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Tanggal Lahir')
                    ->date('d F Y'),
                Tables\Columns\TextColumn::make('gender')
                    ->formatStateUsing(fn($state) => ['l' => 'Laki-laki', 'p' => 'Perempuan'][$state] ?? null)
                    ->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('subject.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->fileDisk('public')
                    ->exporter(TeacherExporter::class),
                ImportAction::make()
                    ->importer(TeacherImporter::class),
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
