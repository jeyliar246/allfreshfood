<x-home-layout>
    <main class="container py-4">
        <!-- Page Header -->
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="bi bi-robot text-white" style="font-size: 3rem;"></i>
            </div>
            <h1 class="display-4 fw-bold mb-3">AI Shopping Assistant</h1>
            <p class="lead text-muted">Get personalized recipe suggestions and automatically add ingredients to your cart from the best local vendors</p>
        </div>

        <div class="row">
            <!-- Chat Interface -->
            <div class="col-lg-8 mb-4">
                <div class="card h-100" style="min-height: 600px;">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-stars text-primary me-2"></i>
                            Chat with AI Chef
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <!-- Messages Container -->
                        <div class="flex-grow-1 overflow-auto mb-3" id="chat-messages" style="max-height: 450px;">
                            <!-- AI Welcome Message -->
                            <div class="d-flex mb-3">
                                <div class="me-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-robot text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bg-light rounded p-3">
                                        <p class="mb-1">Hello! I'm your AI shopping assistant. I can help you discover recipes, suggest ingredients, and even add them to your cart from local vendors. What would you like to cook today?</p>
                                        <small class="text-muted">Now</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Typing Indicator -->
                        <div class="d-flex mb-3 d-none" id="typing-indicator">
                            <div class="me-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-robot text-primary"></i>
                                </div>
                            </div>
                            <div class="bg-light rounded p-3">
                                <div class="d-flex align-items-center">
                                    <div class="spinner-grow spinner-grow-sm text-primary me-2"></div>
                                    <small class="text-muted">AI is thinking...</small>
                                </div>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Ask me about recipes, ingredients, or cooking tips..." id="message-input" onkeypress="if(event.key === 'Enter' && !event.shiftKey){event.preventDefault(); sendMessage();}">
                            <button class="btn btn-primary" onclick="sendMessage()" id="send-button">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Suggest dinner for tonight')">
                                Suggest dinner for tonight
                            </button>
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Find vegetarian recipes')">
                                Find vegetarian recipes
                            </button>
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Plan weekly meals')">
                                Plan weekly meals
                            </button>
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Use leftover ingredients')">
                                Use leftover ingredients
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Popular Recipes -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Popular Recipes</h6>
                    </div>
                    <div class="card-body">
                        <div class="border rounded p-3 mb-3">
                            <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?auto=format&fit=crop&w=300&q=80" class="w-100 rounded mb-2" style="height: 100px; object-fit: cover;">
                            <h6 class="mb-1">Chicken Tikka Masala</h6>
                            <button class="btn btn-primary btn-sm w-100">Add Ingredients</button>
                        </div>

                        <div class="border rounded p-3 mb-3">
                            <img src="https://images.unsplash.com/photo-1544982503-9f984c14501a?auto=format&fit=crop&w=300&q=80" class="w-100 rounded mb-2" style="height: 100px; object-fit: cover;">
                            <h6 class="mb-1">Mediterranean Quinoa Bowl</h6>
                            <button class="btn btn-primary btn-sm w-100">Add Ingredients</button>
                        </div>

                        <div class="border rounded p-3">
                            <img src="https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?auto=format&fit=crop&w=300&q=80" class="w-100 rounded mb-2" style="height: 100px; object-fit: cover;">
                            <h6 class="mb-1">Japanese Ramen</h6>
                            <button class="btn btn-primary btn-sm w-100">Add Ingredients</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="mt-5">
            <h2 class="text-center fw-bold mb-5">What I Can Help You With</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-robot text-primary mb-3" style="font-size: 3rem;"></i>
                            <h5 class="fw-semibold mb-3">Recipe Suggestions</h5>
                            <p class="text-muted">Get personalized recipe recommendations based on your preferences and dietary requirements</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-cart text-success mb-3" style="font-size: 3rem;"></i>
                            <h5 class="fw-semibold mb-3">Smart Shopping</h5>
                            <p class="text-muted">Automatically add recipe ingredients to your cart from the best local vendors</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-stars text-info mb-3" style="font-size: 3rem;"></i>
                            <h5 class="fw-semibold mb-3">Cooking Tips</h5>
                            <p class="text-muted">Get expert cooking advice, substitution suggestions, and technique guidance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let isProcessing = false;

        function sendMessage() {
            if (isProcessing) return;
            
            let input = document.getElementById("message-input");
            let message = input.value.trim();
            let sendButton = document.getElementById("send-button");
            
            if (!message) {
                input.focus();
                return;
            }

            isProcessing = true;
            sendButton.disabled = true;

            // Add user message
            addMessage(message, 'user');
            input.value = "";

            // Show typing indicator
            document.getElementById("typing-indicator").classList.remove("d-none");
            scrollToBottom();

            fetch("{{ route('ai.chat') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ message })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                document.getElementById("typing-indicator").classList.add("d-none");
                
                if (data.reply) {
                    addMessage(data.reply, 'ai');
                } else {
                    addMessage("I apologize, but I couldn't process your request right now. Please try again.", 'ai');
                }
                
                scrollToBottom();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById("typing-indicator").classList.add("d-none");
                addMessage("I'm sorry, but I'm having trouble connecting right now. Please check your internet connection and try again.", 'ai');
                scrollToBottom();
            })
            .finally(() => {
                isProcessing = false;
                sendButton.disabled = false;
                input.focus();
            });
        }

        function addMessage(message, sender) {
            const chatMessages = document.getElementById("chat-messages");
            const timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            let messageHtml = '';
            
            if (sender === 'user') {
                messageHtml = `
                    <div class="d-flex justify-content-end mb-3">
                        <div class="bg-primary text-white rounded p-3" style="max-width: 80%;">
                            <p class="mb-1">${escapeHtml(message)}</p>
                            <small class="text-light opacity-75">${timestamp}</small>
                        </div>
                    </div>
                `;
            } else {
                messageHtml = `
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-robot text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1" style="max-width: 80%;">
                            <div class="bg-light rounded p-3">
                                <p class="mb-1">${escapeHtml(message)}</p>
                                <small class="text-muted">${timestamp}</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            chatMessages.insertAdjacentHTML('beforeend', messageHtml);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function scrollToBottom() {
            const chatBox = document.getElementById("chat-messages");
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        }

        function sendQuickMessage(msg) {
            if (isProcessing) return;
            document.getElementById("message-input").value = msg;
            sendMessage();
        }

        // Auto-focus input on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("message-input").focus();
        });

        // Handle Enter key properly
        document.getElementById("message-input").addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>
</x-home-layout>