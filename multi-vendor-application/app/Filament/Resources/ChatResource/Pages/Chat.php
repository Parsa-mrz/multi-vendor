<?php
namespace App\Filament\Resources\ChatResource\Pages;

use App\Filament\Resources\ChatResource;
use Filament\Resources\Pages\Page;

class Chat extends Page
{
    protected static string $resource = ChatResource::class;

    protected static string $view = 'filament.resources.chat-resource.pages.chat';
    public $recipientId;

    public function mount($recipientId = null): void
    {
        $this->recipientId = $recipientId;
    }

    public function getTitle(): string
    {
        return 'Chat';
    }
}
