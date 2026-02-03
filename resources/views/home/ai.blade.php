<x-home-layout>
    <main class="container py-4">
        <!-- Page Header -->
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <img src="{{asset('assets/francis_bg.png')}}" alt="Francis" class="img-fluid" style="width: 70px; height: 70px;">
            </div>
            <h1 class="display-4 fw-bold mb-3">Francis Your AI Chef Assistant</h1>
            <p class="lead text-muted">Get personalized recipes, ingredients lists, cooking videos, and shop from local vendors - all in one place!</p>
        </div>

        <div class="row">
            <!-- Chat Interface -->
            <div class="col-lg-8 mb-4">
                <div class="card chat-card shadow-sm" style="height: 80vh; min-height: 600px;">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0 text-white">
                            <i class="bi bi-stars me-2"></i>
                            AI Chef Assistant
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column chat-body">
                        <!-- Messages Container -->
                        <div class="flex-grow-1 overflow-auto mb-2" id="chat-messages">
                            <!-- AI Welcome Message -->
                            <div class="d-flex mb-3">
                                <div class="me-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-robot text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bg-light rounded p-3">
                                        <p class="mb-1">🍳 <strong>Welcome to your AI Culinary Assistant!</strong></p>
                                        <p class="mb-1">I can help you with:</p>
                                        <ul class="mb-2 small">
                                            <li>Complete recipes with detailed ingredient lists</li>
                                            <li>YouTube cooking tutorials and videos</li>
                                            <li>Product recommendations from our store</li>
                                            <li>Cooking tips and techniques</li>
                                        </ul>
                                        <p class="mb-1">What would you like to cook today?</p>
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
                                    <small class="text-muted">AI is preparing your culinary guidance...</small>
                                </div>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="input-group chat-input sticky-bottom">
                            <input type="text" class="form-control" placeholder="Ask me for recipes, ingredients, cooking tips..." id="message-input" onkeypress="if(event.key === 'Enter' && !event.shiftKey){event.preventDefault(); sendMessage();}">
                            <button class="btn btn-primary" onclick="sendMessage()" id="send-button">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Recipe Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-lightbulb text-warning me-2"></i>
                            Quick Recipe Requests
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Chicken tikka masala recipe with ingredients')">
                                🍛 Chicken Tikka Masala Recipe
                            </button>
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Vegetarian pasta recipe with ingredient list')">
                                🍝 Vegetarian Pasta Recipe
                            </button>
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Mediterranean quinoa bowl ingredients and recipe')">
                                🥗 Mediterranean Quinoa Bowl
                            </button>
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Japanese ramen recipe with full ingredients')">
                                🍜 Japanese Ramen Recipe
                            </button>
                            <button class="btn btn-outline-primary text-start" onclick="sendQuickMessage('Quick dinner ideas for tonight')">
                                ⚡ Quick Dinner Ideas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cooking Tips -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-question-circle text-info me-2"></i>
                            Need Help With?
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-secondary text-start btn-sm" onclick="sendQuickMessage('Cooking techniques for beginners')">
                                Basic Cooking Techniques
                            </button>
                            <button class="btn btn-outline-secondary text-start btn-sm" onclick="sendQuickMessage('Ingredient substitutions for recipes')">
                                Ingredient Substitutions
                            </button>
                            <button class="btn btn-outline-secondary text-start btn-sm" onclick="sendQuickMessage('Meal prep ideas for the week')">
                                Meal Prep Planning
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Featured Products -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-star text-warning me-2"></i>
                            Featured Products
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="border rounded p-3 mb-3">
                            <img src="https://images.pexels.com/photos/18543471/pexels-photo-18543471.jpeg" class="w-100 rounded mb-2" style="height: 80px; object-fit: cover;">
                            <h6 class="mb-1 small">Authentic Spice Blends</h6>
                            <button class="btn btn-primary btn-sm w-100" onclick="sendQuickMessage('Show me spice blend recipes')">
                                Get Recipes
                            </button>
                        </div>

                        <div class="border rounded p-3 mb-3">
                            <img src="https://images.unsplash.com/photo-1544982503-9f984c14501a?auto=format&fit=crop&w=300&q=80" class="w-100 rounded mb-2" style="height: 80px; object-fit: cover;">
                            <h6 class="mb-1 small">Fresh Produce</h6>
                            <button class="btn btn-primary btn-sm w-100" onclick="sendQuickMessage('Vegetable recipes and ingredients')">
                                Get Recipes
                            </button>
                        </div>

                        <div class="border rounded p-3">
                            <img src="https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?auto=format&fit=crop&w=300&q=80" class="w-100 rounded mb-2" style="height: 80px; object-fit: cover;">
                            <h6 class="mb-1 small">International Ingredients</h6>
                            <button class="btn btn-primary btn-sm w-100" onclick="sendQuickMessage('International cuisine recipes')">
                                Get Recipes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Features Section -->
        <div class="mt-5">
            <h2 class="text-center fw-bold mb-5">What Your AI Chef Can Do</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-list-check text-primary mb-3" style="font-size: 2.5rem;"></i>
                            <h6 class="fw-semibold mb-3">Complete Ingredient Lists</h6>
                            <p class="text-muted small">Get detailed ingredients with exact measurements for any recipe</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-play-circle text-danger mb-3" style="font-size: 2.5rem;"></i>
                            <h6 class="fw-semibold mb-3">Cooking Video Links</h6>
                            <p class="text-muted small">YouTube tutorials to guide you through each recipe step by step</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-cart text-success mb-3" style="font-size: 2.5rem;"></i>
                            <h6 class="fw-semibold mb-3">Smart Product Matching</h6>
                            <p class="text-muted small">Find relevant products in our store that match your recipe needs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-stars text-warning mb-3" style="font-size: 2.5rem;"></i>
                            <h6 class="fw-semibold mb-3">Expert Cooking Tips</h6>
                            <p class="text-muted small">Professional techniques and insider tips for perfect results</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const CART_ADD_URL = "{{ route('cart.add') }}";
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
                // If products were returned, render them with Add to Cart buttons
                if (data.products && Array.isArray(data.products) && data.products.length > 0) {
                    addProductsMessage(data.products);
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
                // Enhanced AI message formatting with better markdown support
                const formattedMessage = formatAIMessage(message);
                messageHtml = `
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-robot text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1" style="max-width: 85%;">
                            <div class="bg-light rounded p-3">
                                <div class="mb-1">${formattedMessage}</div>
                                <small class="text-muted">${timestamp}</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            chatMessages.insertAdjacentHTML('beforeend', messageHtml);
        }

        function addProductsMessage(products) {
            const chatMessages = document.getElementById('chat-messages');
            const timestamp = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            const items = products.slice(0, 6).map(p => `
                <div class="col-12 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        ${p.image ? `<img src="${p.image}" class="card-img-top" alt="${escapeHtml(p.name)}" style="height: 140px; object-fit: cover;">` : ''}
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="fw-semibold text-truncate me-2">${escapeHtml(p.name)}</div>
                                <div class="text-primary fw-bold">£${Number(p.price).toFixed(2)}</div>
                            </div>
                            ${p.cuisine ? `<span class="badge bg-light text-dark small mb-2">${escapeHtml(p.cuisine)}</span>` : ''}
                            ${p.description ? `<div class="text-muted small mb-2">${escapeHtml(p.description).slice(0, 120)}${p.description.length > 120 ? '…' : ''}</div>` : ''}
                            <form action="${CART_ADD_URL}" method="POST" class="add-to-cart-form d-grid">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="product_id" value="${p.id}">
                                <button type="submit" class="btn btn-sm ${p.stock > 0 ? 'btn-primary' : 'btn-secondary'} w-100" ${p.stock > 0 ? '' : 'disabled'}>
                                    <i class="bi bi-cart me-1"></i> ${p.stock > 0 ? 'Add' : 'Out of Stock'}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            `).join('');

            const html = `
                <div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-robot text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1" style="max-width: 85%;">
                        <div class="bg-light rounded p-3">
                            <div class="fw-bold text-primary mb-2"><span class="me-2">🛒</span>Recommended Products</div>
                            <div class="row g-3">${items}</div>
                            <small class="text-muted d-block mt-2">${timestamp}</small>
                        </div>
                    </div>
                </div>`;

            chatMessages.insertAdjacentHTML('beforeend', html);
        }

        function formatAIMessage(message) {
            // Convert markdown-like formatting to HTML
            let formatted = escapeHtml(message);
            
            // Convert **bold** to <strong>
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            
            // Convert bullet points
            formatted = formatted.replace(/^• (.*$)/gim, '<li class="mb-1">$1</li>');
            formatted = formatted.replace(/(<li.*<\/li>)/s, '<ul class="mb-2">$1</ul>');
            
            // Convert YouTube links to clickable links
            formatted = formatted.replace(/\[([^\]]+)\]\((https:\/\/www\.youtube\.com[^)]+)\)/g, '<a href="$2" target="_blank" class="btn btn-sm btn-outline-danger me-1 mb-1"><i class="bi bi-play-circle me-1"></i>$1</a>');
            
            // Convert section headers with emojis
            formatted = formatted.replace(/^(🎥|🛒|🍳|👨‍🍳|🍽️|🌱) \*\*(.*?)\*\*/gim, '<div class="fw-bold text-primary mb-2 mt-3"><span class="me-2">$1</span>$2</div>');
            
            // Convert line breaks to proper HTML
            formatted = formatted.replace(/\n\n/g, '</p><p class="mb-2">');
            formatted = '<p class="mb-2">' + formatted + '</p>';
            
            // Clean up empty paragraphs
            formatted = formatted.replace(/<p class="mb-2"><\/p>/g, '');
            
            return formatted;
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

        // Intercept add-to-cart form submissions to keep user in chat (progressive enhancement)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form && form.classList.contains('add-to-cart-form')) {
                e.preventDefault();
                const formData = new FormData(form);
                fetch(CART_ADD_URL, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data && data.success) {
                        const cartCount = document.getElementById('cart-count');
                        if (cartCount) cartCount.textContent = data.cart_count;
                    } else {
                        alert((data && data.message) || 'Failed to add to cart');
                    }
                })
                .catch(() => alert('Network error while adding to cart'));
            }
        });

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

        // Add some CSS for better message formatting
        const style = document.createElement('style');
        style.textContent = `
            #chat-messages ul {
                padding-left: 1.2rem;
                margin-bottom: 0.5rem;
            }
            #chat-messages li {
                list-style-type: disc;
                margin-bottom: 0.25rem;
            }
            #chat-messages .btn-outline-danger:hover {
                background-color: #dc3545;
                color: white;
            }
            .bg-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            }
            #chat-messages .card-img-top { border-top-left-radius: .5rem; border-top-right-radius: .5rem; }
            #chat-messages .card { border-radius: .5rem; }
            .chat-card { overflow: hidden; }
            .chat-body { height: 100%; display: flex; flex-direction: column; }
            #chat-messages { max-height: none; }
            .chat-input { position: sticky; bottom: 0; background: #fff; z-index: 2; }
            .chat-input .form-control { border-top-right-radius: 0; border-bottom-right-radius: 0; }
            .chat-input .btn { border-top-left-radius: 0; border-bottom-left-radius: 0; }
        `;
        document.head.appendChild(style);
    </script>
</x-home-layout>