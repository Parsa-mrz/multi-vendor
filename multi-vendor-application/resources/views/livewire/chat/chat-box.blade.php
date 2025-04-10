<div class="flex h-[50vh]">
    <!-- Conversations Sidebar -->
    <div class="w-1/4 bg-gray-100 border-r overflow-y-auto">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Conversations</h2>
        </div>
        <ul class="divide-y">
            @forelse ($conversations as $conversation)
                <li
                    wire:click="selectConversation({{ $conversation->id }})"
                    class="p-4 cursor-pointer hover:bg-gray-200 transition-colors {{ $selectedConversation && $selectedConversation->id === $conversation->id ? 'bg-gray-200' : '' }}"
                >
                    <div class="flex items-center space-x-3">
                        <div class="flex-1">
                            @if($conversation->messages->isNotEmpty() && !$conversation->messages->last()->read && $conversation->messages->last()->sender_id !== auth()->id())
                                <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-blue-500 rounded-full"></span>
                            @endif
                            <p class="font-medium">
                                {{ $conversation->user_id === auth()->id()
                                    ? ($conversation->recipient?->profile?->first_name . ' ' . $conversation->recipient?->profile?->last_name ?? $conversation->recipient?->email ?? 'Unknown')
                                    : ($conversation->user?->profile?->first_name . ' ' . $conversation->user?->profile?->last_name ?? $conversation->user?->email ?? 'Unknown') }}
                            </p>
                            <p class="text-sm text-gray-600 truncate">
                                {{ $conversation->messages->last()?->body ?? '' }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-500">
                            {{ $conversation->messages->last() ? $conversation->messages->last()->created_at->diffForHumans(['parts' => 1]) : $conversation->created_at->diffForHumans(['parts' => 4]) }}
                        </span>
                    </div>
                </li>
            @empty
                <li class="p-4 text-gray-500">No conversations yet</li>
            @endforelse
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col">
        @if ($selectedConversation)
            <div class="p-4 border-b bg-white">
                <h3 class="text-lg font-medium">
                    {{ $selectedConversation->user_id === auth()->id()
                        ? ($selectedConversation->recipient?->profile?->first_name . ' ' . $conversation->recipient?->profile?->last_name ?? $selectedConversation->recipient?->email ?? 'Unknown')
                        : ($selectedConversation->user?->profile?->first_name . ' ' . $conversation->user?->profile?->last_name ?? $selectedConversation->user?->email ?? 'Unknown') }}
                </h3>
            </div>

            <div class="flex-1 p-4 overflow-y-auto">
                @foreach ($messages as $message)
                    <div class="{{ $message['sender_id'] === auth()->id() ? 'ml-auto' : 'mr-auto' }} max-w-[70%] mb-4">
                        <div class="{{ $message['sender_id'] === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200' }} p-3 rounded-lg">
                            {{ $message['body'] }}
                        </div>
                        <span class="text-xs text-gray-500 block mt-1">
                            {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                        </span>
                        @if($message['sender_id'] === auth()->id())
                            <span class="text-xs block mt-1 {{ $message['read'] ? 'text-green-600' : 'text-gray-600' }}">
                                        {{ $message['read'] ? 'Seen' : 'Sent' }}
                                </span>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t bg-white">
                <form wire:submit.prevent="sendMessage" class="flex space-x-2">
                    <input
                        wire:model.debounce.300ms="newMessage"
                        type="text"
                        class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Type a message..."
                    >
                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors"
                    >
                        Send
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex items-center justify-center text-gray-500">
                Select a conversation to start chatting
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            window.Echo.private(`user.${@js(auth()->id())}`)
                .listen('.conversation.started', (event) => {
                    console.log('New conversation:', event);
                    Livewire.dispatch('conversationStarted', {event:event});
                });

            let currentConversationId = null;
            @if ($selectedConversation)
                currentConversationId = {{ $selectedConversation->id }};
            window.Echo.private(`conversation.${currentConversationId}`)
                .listen('.message.sent', (event) => {
                    console.log('New message:', event);
                    Livewire.dispatch('messageReceived', {event:event});
                })
                .listen('.message.read', (event) => {
                    console.log('Message read:', event);
                    Livewire.dispatch('messageRead', {event:event});
                });
            @endif

            window.addEventListener('conversation-selected', (event) => {
                if (currentConversationId) {
                    window.Echo.leave(`conversation.${currentConversationId}`);
                }
                currentConversationId = event.detail.conversationId;
                window.Echo.private(`conversation.${currentConversationId}`)
                    .listen('.message.sent', (event) => {
                        console.log('New message:', event);
                        Livewire.dispatch('messageReceived', {event:event});
                    })
                    .listen('.message.read', (event) => {
                        console.log('Message read:', event);
                        Livewire.dispatch('messageRead', {event:event});
                    });
            });

            window.addEventListener('message-updated', () => {
                const chatArea = document.querySelector('.overflow-y-auto');
                if (chatArea) {
                    chatArea.scrollTop = chatArea.scrollHeight;
                }
            });

            window.addEventListener('conversation-added', () => {
                console.log('Conversation added');
            });
        });
    </script>
</div>
