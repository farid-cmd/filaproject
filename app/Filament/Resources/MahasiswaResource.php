<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Mahasiswa';

     public static function canAccess(): bool
    {
        $user = Auth::user();

        // Jika belum login, return false
        if (! $user) {
            return false;
        }

        // Hanya role mahasiswa yang boleh
        return $user->role === 'superadmin-logbook';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nim')
                ->label('NIM')
                ->required()
                ->maxLength(20),

            Forms\Components\Select::make('user_id')
                ->label('User')
                ->relationship('user', 'name')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nim')
                ->label('NIM')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('user.name')
                ->label('Nama User')
                ->sortable()
                ->searchable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit'   => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
