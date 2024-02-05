<?php

namespace App\Filament\Resources;

use App\Enum\StatusEnum;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->placeholder('Title'),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->placeholder('Description'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(StatusEnum::class)
                    ->required()
                    ->live()
                    ->default(StatusEnum::OPEN),
                Forms\Components\DateTimePicker::make('closed_at')
                    ->label('Closed At')
                    ->requiredif('status', StatusEnum::CLOSED->value)
                    ->placeholder('Closed At')
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('title')
                ->label('Title'),
            TextEntry::make('status')
                ->label('Status'),
            TextEntry::make('createdBy.name')
                ->label('Created By'),
            TextEntry::make('created_at')
                ->label('Created At'),
            TextEntry::make('closed_at')
                ->label('Closed At'),
            RepeatableEntry::make('comments')
                ->schema([
                    TextEntry::make('comment')
                        ->label('Comment'),
                    TextEntry::make('commentedBy.name')
                        ->label('Created By'),
                    TextEntry::make('created_at')
                        ->label('Created At'),
                ])->columnSpan(2)
        ]);
    }
}
