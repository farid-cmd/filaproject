<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BimbinganResource\Pages;
use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MitraMagang;
use App\Models\PembimbingLapangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BimbinganResource extends Resource
{
    protected static ?string $model = Bimbingan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Data Bimbingan';
    protected static ?string $navigationGroup = 'Bimbingan Mahasiswa';

    /**
     * 🔒 Hanya superadmin-logbook dan mitra yang bisa mengakses
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->role, ['superadmin-logbook']);
    }

    /**
     * 📝 Form untuk Create/Edit
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('dosen_id')
                ->label('Dosen Pembimbing')
                ->options(
                    Dosen::whereHas('user', function ($query) {
                        $query->where('role', 'dosen'); // ✅ hanya user dengan role dosen
                    })
                        ->with('user')
                        ->get()
                        ->mapWithKeys(fn($dosen) => [
                            $dosen->id => "{$dosen->nip} - {$dosen->user->name}",
                        ])
                        ->toArray()
                )
                ->searchable()
                ->required(),

            Forms\Components\Select::make('mahasiswa_id')
                ->label('Mahasiswa')
                ->options(
                    Mahasiswa::with('user')
                        ->get()
                        ->mapWithKeys(fn($mhs) => [
                            $mhs->id => "{$mhs->nim} - {$mhs->user->name}",
                        ])
                        ->toArray()
                )
                ->searchable()
                ->required(),

            Forms\Components\Select::make('pembimbing_lapangan_id')
                ->label('Pembimbing Lapangan')
                ->options(PembimbingLapangan::pluck('nama', 'id')->toArray())
                ->searchable(),

            Forms\Components\Select::make('mitra_id')
                ->label('Mitra Magang')
                ->options(MitraMagang::pluck('nama_mitra', 'id')->toArray())
                ->searchable(),
        ]);
    }

    /**
     * 📋 Tabel daftar Bimbingan
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dosen.user.name')
                    ->label('Dosen Pembimbing')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mitra.nama_mitra')
                    ->label('Mitra Magang')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y - H:i')
                    ->sortable(),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBimbingans::route('/'),
            'create' => Pages\CreateBimbingan::route('/create'),
            'edit'   => Pages\EditBimbingan::route('/{record}/edit'),
        ];
    }
}
