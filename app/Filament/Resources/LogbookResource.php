<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogbookResource\Pages;
use App\Models\Logbook;
use App\Models\Bimbingan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LogbookResource extends Resource
{
    protected static ?string $model = Logbook::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Logbook Mahasiswa';

    public static function canAccess(): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        $allowedRoles = ['superadmin-logbook', 'mahasiswa', 'dosen'];
        return in_array($user->role, $allowedRoles);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('mahasiswa_nim')
                ->default(fn () => Auth::user()->username),

            Forms\Components\DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal'),

            Forms\Components\Textarea::make('kegiatan')
                ->required()
                ->label('Kegiatan')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mahasiswa.user.name')
                    ->label('Nama Mahasiswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kegiatan')
                    ->label('Kegiatan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn () => in_array(Auth::user()?->role, ['mahasiswa', 'dosen'])),

                Tables\Actions\EditAction::make()
                    ->visible(fn () => Auth::user()?->role === 'mahasiswa'),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()?->role === 'mahasiswa'),

                Tables\Actions\Action::make('print')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('filament.resources.logbooks.print', $record), true),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => Auth::user()?->role === 'mahasiswa'),

                Tables\Actions\BulkAction::make('printAll')
                    ->label('Cetak Semua')
                    ->icon('heroicon-o-printer')
                    ->action(function ($records) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.logbook-bulk', [
                            'logbooks' => $records,
                        ]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'logbook-semua.pdf'
                        );
                    }),
            ]);
    }

    /**
     * 🔍 Filter data logbook sesuai peran user
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user  = Auth::user();

        if (! $user) {
            return $query;
        }

        // 🧑‍🎓 Mahasiswa → hanya logbook miliknya
        if ($user->role === 'mahasiswa') {
            $nim = optional($user->mahasiswa)->nim;
            return $query->where('mahasiswa_nim', $nim);
        }

        // 👨‍🏫 Dosen → logbook mahasiswa bimbingannya
        if ($user->role === 'dosen') {
            $dosenId = optional($user->dosen)->id;

            // ambil semua NIM mahasiswa yang dibimbing
            $mahasiswaNims = Bimbingan::where('dosen_id', $dosenId)
                ->with('mahasiswa')
                ->get()
                ->pluck('mahasiswa.nim')
                ->filter()
                ->toArray();

            return $query->whereIn('mahasiswa_nim', $mahasiswaNims);
        }

        // 🧑‍💼 Superadmin → bisa lihat semua
        return $query;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLogbooks::route('/'),
            'create' => Pages\CreateLogbook::route('/create'),
            'edit'   => Pages\EditLogbook::route('/{record}/edit'),
            'print'  => Pages\PrintLogbook::route('/{record}/print'),
        ];
    }
}
