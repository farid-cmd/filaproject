<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BimbinganResource\Pages;
use App\Models\Bimbingan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BimbinganResource extends Resource
{
    protected static ?string $model = Bimbingan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Bimbingan';
    protected static ?string $navigationGroup = 'Data Magang';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('dosen_id')
                ->label('Dosen Pembimbing')
                ->relationship('dosen.user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('mahasiswa_id')
                ->label('Mahasiswa')
                ->relationship('mahasiswa.user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('mitra_id')
                ->label('Mitra Magang')
                ->relationship('mitra', 'nama_mitra')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status Bimbingan')
                ->options([
                    'proses' => 'Proses',
                    'selesai' => 'Selesai',
                ])
                ->default('proses')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dosen.user.name')->label('Dosen Pembimbing')->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.user.name')->label('Mahasiswa')->searchable(),
                Tables\Columns\TextColumn::make('mitra.nama_mitra')->label('Mitra Magang')->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'proses',
                        'success' => 'selesai',
                    ])
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBimbingans::route('/'),
            'create' => Pages\CreateBimbingan::route('/create'),
            'edit' => Pages\EditBimbingan::route('/{record}/edit'),
        ];
    }
}
