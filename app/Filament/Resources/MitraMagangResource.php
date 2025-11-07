<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraResource\Pages;
use App\Models\Mitra;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MitraResource extends Resource
{
    protected static ?string $model = Mitra::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Data Master';

    /**
     * 🔒 Hanya user dengan role superadmin-logbook yang bisa mengakses
     */
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user && $user->role === 'superadmin-logbook';
    }

    /**
     * 📝 Form untuk Create/Edit
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ✅ FIELD USER ACCOUNT (hanya untuk create)
                Forms\Components\Fieldset::make('Akun Login Mitra')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->label('Nama User')
                            ->required()
                            ->maxLength(255)
                            ->default(fn ($operation) => $operation === 'create' ? '' : null)
                            ->dehydrated(fn ($operation) => $operation === 'create'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email Login')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignoreRecord: true)
                            ->maxLength(255)
                            ->default(fn ($operation) => $operation === 'create' ? '' : null)
                            ->dehydrated(fn ($operation) => $operation === 'create'),

                        Forms\Components\Hidden::make('user_password')
                            ->default(Hash::make('password123')) // ✅ Password default
                            ->dehydrated(fn ($operation) => $operation === 'create'),
                    ])
                    ->columns(2)
                    ->visibleOn('create'), // ✅ Hanya tampil saat create

                // ✅ FIELD DATA MITRA
                Forms\Components\Fieldset::make('Data Mitra')
                    ->schema([
                        Forms\Components\TextInput::make('nama_mitra')
                            ->label('Nama Mitra')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->nullable()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('kontak')
                            ->label('Kontak/Telepon')
                            ->tel()
                            ->nullable()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('pic')
                            ->label('PIC (Penanggung Jawab)')
                            ->nullable()
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    /**
     * 📋 Tabel daftar Mitra
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_mitra')
                    ->label('Nama Mitra')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.username')
                    ->label('Nama User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('pic')
                    ->label('PIC')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dibuat dari'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai'),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($action, $records) {
                            // Hapus user terkait
                            foreach ($records as $record) {
                                if ($record->user) {
                                    $record->user->delete();
                                }
                            }
                        }),
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
            'index'  => Pages\ListMitras::route('/'),
            'create' => Pages\CreateMitra::route('/create'),
            'edit'   => Pages\EditMitra::route('/{record}/edit'),
        ];
    }
}
