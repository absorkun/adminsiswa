<?php

namespace App\Filament\Resources;

use App\Filament\Exports\StudentExporter;
use App\Filament\Imports\StudentImporter;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Data Siswa';

    protected static ?string $navigationGroup = 'Siswa';

    public static function canCreate(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'guru']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name', function ($query) {
                        $query->where('role', 'siswa');
                    })
                    ->live(true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('name', User::find($state)->name))
                    ->required(),
                Forms\Components\Select::make('classroom_id')
                    ->label('Kelas')
                    ->relationship('classroom', 'name')
                    ->live(true),
                Forms\Components\TextInput::make('name')
                    ->hidden(fn(Get $get) => ! $get('user_id'))
                    ->required(),
                Forms\Components\Radio::make('gender')
                    ->label('Jenis Kelamin')
                    ->options(['l' => 'Laki-laki', 'p' => 'Perempuan'])
                    ->required(),
                Forms\Components\DatePicker::make('birthday')
                    ->label('Tanggal Lahir')
                    ->locale('id')
                    ->required(),
                Forms\Components\TextInput::make('nisn')
                    ->label('NISN')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->formatStateUsing(fn($state) => ['l' => 'Laki-laki', 'p' => 'Perempuan'][$state] ?? null)
                    ->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('birthday')
                    ->date('d F Y')
                    ->label('Tanggal Lahir'),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('Kelas')
                    ->numeric(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telepon')
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
                    ->exporter(StudentExporter::class),
                ImportAction::make()
                    ->importer(StudentImporter::class),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
