<?php

namespace App\Filament\Resources\Enquiries\Pages;

use App\Filament\Resources\Enquiries\EnquiryResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEnquiry extends EditRecord
{
    protected static string $resource = EnquiryResource::class;

    public function getTitle(): string
    {
        return $this->record->reference.' — '.$this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Reply by email')
                ->icon('heroicon-o-envelope')
                ->color('gray')
                ->url(fn () => 'mailto:'.$this->record->email.'?subject='.rawurlencode('Your enquiry '.$this->record->reference))
                ->openUrlInNewTab(),

            Action::make('markContacted')
                ->label('Mark as contacted')
                ->icon('heroicon-o-check-circle')
                ->visible(fn () => $this->record->status === 'new')
                ->action(function () {
                    $this->record->update([
                        'status' => 'contacted',
                        'contacted_at' => now(),
                        'contacted_by' => auth()->id(),
                    ]);

                    $this->refreshFormData(['status', 'contacted_at']);
                }),

            DeleteAction::make(),
        ];
    }
}
