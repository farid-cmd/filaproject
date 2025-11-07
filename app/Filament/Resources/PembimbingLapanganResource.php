<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembimbingLapanganResource\Pages;
use App\Models\PembimbingLapangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembimbingLapanganResource extends Resource
{
    protected static ?string $model = PembimbingLapangan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Pembimbing Lapangan';
    protected static ?string $navigationGroup = 'Data Magang';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->label('User'),

            Forms\Components\Select::make('mitra_id')
                ->relationship('mitra', 'nama')
                ->required()
                ->label('Mitra'),

            Forms\Components\TextInput::make('nip')
                ->label('NIP')
                ->maxLength(30),

            Forms\Components\TextInput::make('nama')
                ->label('Nama Pembimbing')
                ->required(),

            Forms\Components\TextInput::make('jabatan')
                ->label('Jabatan')
                ->required(),

            Forms\Components\TextInput::make('kontak')
                ->label('No. Kontak')
                ->tel(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('mitra.nama')->label('Mitra'),
                Tables\Columns\TextColumn::make('nip')->label('NIP'),
                Tables\Columns\TextColumn::make('nama')->label('Nama'),
                Tables\Columns\TextColumn::make('jabatan')->label('Jabatan'),
                Tables\Columns\TextColumn::make('kontak')->label('Kontak'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembimbingLapangans::route('/'),
            'create' => Pages\CreatePembimbingLapangan::route('/create'),
            'edit' => Pages\EditPembimbingLapangan::route('/{record}/edit'),
        ];
    }
}
