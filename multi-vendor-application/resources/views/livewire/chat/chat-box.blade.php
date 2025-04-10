<div class="flex h-[80vh] max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Conversations Sidebar -->
    <div class="w-1/3 bg-gray-50 border-r border-gray-200 flex flex-col">
        <!-- Sidebar Header -->
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Messages</h2>
            <button class="text-blue-500 hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>

        <!-- Conversation List -->
        <ul class="flex-1 overflow-y-auto">
            @forelse ($conversations as $conversation)
                <li
                    wire:click="selectConversation({{ $conversation->id }})"
                    class="p-4 mb-4 cursor-pointer hover:bg-gray-100 transition-colors {{ $selectedConversation && $selectedConversation->id === $conversation->id ? 'bg-gray-300' : '' }}"
                >
                    <div class="flex items-center space-x-3">
                        <!-- Avatar -->
                        <div class="relative">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                {{ substr($conversation->user->id === auth()->id()
                                    ? ($conversation->recipient?->profile?->first_name ?? $conversation->recipient?->email[0])
                                    : ($conversation->user?->profile?->first_name ?? $conversation->user?->email[0]), 0, 1) }}
                            </div>
                            @if($conversation->messages->isNotEmpty() && !$conversation->messages->last()->read && $conversation->messages->last()->sender_id !== auth()->id())
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-blue-500 rounded-full"></span>
                            @endif
                        </div>

                        <!-- Conversation Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <p class="font-medium text-gray-900 truncate">
                                    {{ $conversation->user->id === auth()->id()
                                        ? ($conversation->recipient?->profile?->first_name . ' ' . $conversation->recipient?->profile?->last_name ?? $conversation->recipient?->email)
                                        : ($conversation->user?->profile?->first_name . ' ' . $conversation->user?->profile?->last_name ?? $conversation->user?->email) }}
                                </p>
                                <span class="text-xs text-gray-500 whitespace-nowrap">
                                    {{ $conversation->messages->last() ? $conversation->messages->last()->created_at->diffForHumans(['parts' => 1]) : $conversation->created_at->diffForHumans(['parts' => 4]) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-baseline">
                                <p class="text-sm text-gray-600 truncate">
                                    {{ $conversation->messages->last()?->body ?? '' }}
                                </p>
                                @if($conversation->messages->isNotEmpty() && $conversation->messages->last()->sender_id === auth()->id())
                                    <span class="text-xs">
                                        @if($conversation->messages->last()->read)
                                            <svg class="inline text-blue-500" width="16" height="16" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 10.5L8.5 15L16 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M10 10.5L14.5 15L22 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        @else
                                            <svg class="inline text-gray-400" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 10.5L8.5 15L16 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-8 text-center text-gray-500">
                    <p class="mt-2">No conversations yet</p>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col">
        @if ($selectedConversation)
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 bg-white flex items-center">
                <div>
                    <h3 class="font-medium text-gray-900">
                        {{ $selectedConversation->user_id === auth()->id()
                            ? ($selectedConversation->recipient?->profile?->first_name . ' ' . $conversation->recipient?->profile?->last_name ?? $selectedConversation->recipient?->email ?? 'Unknown')
                            : ($selectedConversation->user?->profile?->first_name . ' ' . $conversation->user?->profile?->last_name ?? $selectedConversation->user?->email ?? 'Unknown') }}
                    </h3>
                    <p class="text-xs text-gray-500">last seen recently</p>
                </div>
                <div class="ml-auto flex space-x-3">
                    <button class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </button>
                    <button class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <button class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 p-4 overflow-y-auto bg-gray-50" id="messages-container">
                @foreach ($messages as $message)
                    <div class="{{ $message['sender_id'] === auth()->id() ? 'flex justify-end' : 'flex justify-start' }} mb-4">
                        <div class="{{ $message['sender_id'] === auth()->id() ? 'bg-blue-500 text-white' : 'bg-white border border-gray-200' }} max-w-[75%] p-3 rounded-xl shadow-sm">
                            <div class="text-sm">{{ $message['body'] }}</div>
                            <div class="flex justify-end items-center mt-1 space-x-1">
                                <span class="text-xs {{ $message['sender_id'] === auth()->id() ? 'text-blue-100' : 'text-gray-400' }}">
                                    {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                                </span>
                                @if($message['sender_id'] === auth()->id())
                                    <span>
                                        @if($message['read'])
                                            <svg class="inline text-blue-300" width="16" height="16" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 10.5L8.5 15L16 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M10 10.5L14.5 15L22 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        @else
                                            <svg class="inline text-blue-300" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 10.5L8.5 15L16 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-gray-200 bg-white">
                <form wire:submit.prevent="sendMessage" class="flex items-center space-x-2">
                    <button type="button" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>
                    <input
                        wire:model.debounce.300ms="newMessage"
                        type="text"
                        class="flex-1 p-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Write a message..."
                    >
                    <button
                        type="submit"
                        class="bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="send-icon" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center bg-gray-50 text-gray-500 p-8">
                <h3 class="text-xl font-medium mb-2">Select a chat</h3>
                <p class="text-center max-w-md">Choose a conversation from the list to start messaging or create a new one.</p>
            </div>
        @endif
    </div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const userId = @js(auth()->id());
            let currentConversationId = @json(isset($selectedConversation) ? $selectedConversation->id : null);

            // Initialize user channel listeners
            const userChannel = window.Echo.private(`user.${userId}`);
            userChannel.listen('.conversation.started', (event) => {
                Livewire.dispatch('conversationStarted', { event });
            }).listen('.conversation.updated', (event) => {
                Livewire.dispatch('conversationUpdated', { event });
            });

            // Function to join a conversation channel
            const joinConversationChannel = (conversationId) => {
                return window.Echo.private(`conversation.${conversationId}`)
                    .listen('.message.sent', (event) => {
                        Livewire.dispatch('messageReceived', { event });
                    })
                    .listen('.message.read', (event) => {
                        Livewire.dispatch('messageRead', { event });
                    });
            };

            // Join the initial conversation channel if one is selected
            if (currentConversationId) {
                joinConversationChannel(currentConversationId);
            }

            // Handle conversation selection
            window.addEventListener('conversation-selected', (event) => {
                const newConversationId = event.detail.conversationId;

                // Leave the current conversation channel if it exists
                if (currentConversationId) {
                    window.Echo.leave(`conversation.${currentConversationId}`);
                }

                // Update the current conversation ID and join the new channel
                currentConversationId = newConversationId;
                if (currentConversationId) {
                    joinConversationChannel(currentConversationId);
                }
            });

            // Auto-scroll chat area when messages are updated
            window.addEventListener('message-updated', () => {
                const chatArea = document.querySelector('.overflow-y-auto');
                if (chatArea) {
                    chatArea.scrollTop = chatArea.scrollHeight;
                }
            });

        });
    </script>
</div>
