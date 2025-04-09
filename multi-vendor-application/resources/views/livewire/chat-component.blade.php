<div class="flex h-screen">
    <!-- Conversations List -->
    <div class="w-1/4 bg-gray-100 p-4">
        <h2 class="text-lg font-bold mb-4">Conversations</h2>
        <ul>
            @foreach ($conversations as $conversation)
                <li wire:click="selectConversation({{ $conversation->id }})"
                    class="p-2 cursor-pointer hover:bg-gray-200 {{ $selectedConversation && $selectedConversation->id === $conversation->id ? 'bg-gray-300' : '' }}">
                    {{ $conversation->user_id === auth()->id() ? $conversation->recipient->name : $conversation->user->name }}
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="w-3/4 flex flex-col">
        @if ($selectedConversation)
            <div class="flex-1 p-4 overflow-y-auto">
                @foreach ($messages as $message)
                    <div class="{{ $message['sender_id'] === auth()->id() ? 'text-right' : 'text-left' }} mb-2">
                        <span class="inline-block p-2 rounded bg-gray-200">{{ $message['body'] }}</span>
                        <small class="block text-gray-500">{{ $message['created_at'] }}</small>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t">
                <form wire:submit.prevent="sendMessage">
                    <input wire:model="newMessage" type="text" class="w-full p-2 border rounded" placeholder="Type a message...">
                    <button type="submit" class="mt-2 bg-blue-500 text-white p-2 rounded">Send</button>
                </form>
            </div>
        @else
            <div class="flex-1 flex items-center justify-center">
                <p>Select a conversation or start a new one</p>
            </div>
        @endif
    </div>
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const conversationId = 1;
                    console.log('Subscribing to channel: conversation.' + conversationId);

                    window.Echo.channel(`conversation.${conversationId}`)
                        .listen('MessageSent', (event) => {
                            console.log('Message received:', event);
                            Livewire.dispatch('receiveMessage', event);
                        })
                        .subscribed(() => {
                            console.log('Successfully subscribed to channel: conversation.' + conversationId);
                        })
                        .error((error) => {
                            console.error('Channel subscription error:', error);
                        });
                });

                window.addEventListener('message-received', () => {
                    console.log('Livewire received message event');
                });
            </script>
</div>
