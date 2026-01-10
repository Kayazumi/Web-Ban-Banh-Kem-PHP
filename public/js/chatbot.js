// Chatbot Widget - La Cuisine Ngọt
class Chatbot {
    constructor() {
        this.isOpen = false;
        this.conversationHistory = [];
        this.waitingForOrderCode = false;
        this.init();
    }

    init() {
        this.createWidget();
        this.attachEventListeners();
        this.greet();
    }

    createWidget() {
        const chatbotHTML = `
            <!-- Chatbot Toggle Button -->
            <button class="chatbot-toggle" id="chatbotToggle" aria-label="Mở chatbot">
                <i class="fas fa-comments"></i>
                <span class="chatbot-badge">1</span>
            </button>

            <!-- Chatbot Window -->
            <div class="chatbot-window" id="chatbotWindow">
                <!-- Header -->
                <div class="chatbot-header">
                    <div class="chatbot-header-info">
                        <div class="chatbot-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div>
                            <h3>La Cuisine Bot</h3>
                            <span class="chatbot-status">
                                <span class="status-dot"></span>
                                Trực tuyến
                            </span>
                        </div>
                    </div>
                    <button class="chatbot-close" id="chatbotClose">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Messages Area -->
                <div class="chatbot-messages" id="chatbotMessages">
                    <!-- Messages will be added here -->
                </div>

                <!-- Quick Replies -->
                <div class="chatbot-quick-replies" id="quickReplies">
                    <!-- Quick reply buttons will be added here -->
                </div>

                <!-- Input Area -->
                <div class="chatbot-input">
                    <input 
                        type="text" 
                        id="chatbotInput" 
                        placeholder="Nhập tin nhắn..."
                        autocomplete="off"
                    >
                    <button id="chatbotSend">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        `;

        const container = document.createElement('div');
        container.id = 'chatbotContainer';
        container.innerHTML = chatbotHTML;
        document.body.appendChild(container);
    }

    attachEventListeners() {
        // Toggle chatbot
        document.getElementById('chatbotToggle').addEventListener('click', () => {
            this.toggleChatbot();
        });

        // Close chatbot
        document.getElementById('chatbotClose').addEventListener('click', () => {
            this.toggleChatbot();
        });

        // Send message
        document.getElementById('chatbotSend').addEventListener('click', () => {
            this.sendMessage();
        });

        // Enter key to send
        document.getElementById('chatbotInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });
    }

    toggleChatbot() {
        this.isOpen = !this.isOpen;
        const window = document.getElementById('chatbotWindow');
        const toggle = document.getElementById('chatbotToggle');
        const badge = toggle.querySelector('.chatbot-badge');

        if (this.isOpen) {
            window.classList.add('open');
            toggle.classList.add('hidden');
            if (badge) badge.style.display = 'none';
        } else {
            window.classList.remove('open');
            toggle.classList.remove('hidden');
        }
    }

    greet() {
        setTimeout(() => {
            this.addBotMessage(
                'Xin chào! 👋 Tôi là La Cuisine Bot. Tôi có thể giúp gì cho bạn hôm nay?'
            );
            this.showQuickReplies([
                { text: '🎂 Xem sản phẩm', value: 'products' },
                { text: '📦 Theo dõi đơn hàng', value: 'track_order' },
                { text: '❓ Câu hỏi thường gặp', value: 'faq' },
                { text: '🛒 Hướng dẫn đặt hàng', value: 'how_to_order' }
            ]);
        }, 500);
    }

    sendMessage() {
        const input = document.getElementById('chatbotInput');
        const message = input.value.trim();

        if (!message) return;

        this.addUserMessage(message);
        input.value = '';

        // Process message
        this.processMessage(message);
    }

    addUserMessage(text) {
        const messagesContainer = document.getElementById('chatbotMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'chatbot-message user-message';
        messageDiv.innerHTML = `
            <div class="message-content">${this.escapeHtml(text)}</div>
        `;
        messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }

    addBotMessage(text, isHTML = false) {
        const messagesContainer = document.getElementById('chatbotMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'chatbot-message bot-message';

        const content = isHTML ? text : this.escapeHtml(text);
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">${content}</div>
        `;
        messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }

    showTypingIndicator() {
        const messagesContainer = document.getElementById('chatbotMessages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chatbot-message bot-message typing-indicator';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `
            <div class="message-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;
        messagesContainer.appendChild(typingDiv);
        this.scrollToBottom();
    }

    removeTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
    }

    showQuickReplies(replies) {
        const container = document.getElementById('quickReplies');
        container.innerHTML = '';

        replies.forEach(reply => {
            const button = document.createElement('button');
            button.className = 'quick-reply-btn';
            button.textContent = reply.text;
            button.onclick = () => {
                this.handleQuickReply(reply.value, reply.text);
            };
            container.appendChild(button);
        });
    }

    handleQuickReply(value, displayText) {
        this.addUserMessage(displayText);
        document.getElementById('quickReplies').innerHTML = '';
        this.processIntent(value);
    }

    async processMessage(message) {
        this.showTypingIndicator();

        // Simulate thinking time
        await this.wait(800);

        this.removeTypingIndicator();

        const lowerMessage = message.toLowerCase();

        // Check if waiting for order code
        if (this.waitingForOrderCode) {
            this.handleOrderTracking(message);
            return;
        }

        // Enhanced intent recognition with detailed consultation

        // Allergy and dietary restrictions
        if (lowerMessage.includes('dị ứng') || lowerMessage.includes('allerg')) {
            this.handleAllergyQuestion(message, lowerMessage);
        }
        // Ingredients and composition
        else if (lowerMessage.includes('thành phần') || lowerMessage.includes('ingredi') || lowerMessage.includes('làm từ')) {
            this.handleIngredientsQuestion(message, lowerMessage);
        }
        // Specific product questions
        else if (lowerMessage.includes('entremet') || lowerMessage.includes('mousse') || lowerMessage.includes('tiramisu') || lowerMessage.includes('cheesecake')) {
            this.handleSpecificProductQuestion(message, lowerMessage);
        }
        // Size and serving
        else if (lowerMessage.includes('kích thước') || lowerMessage.includes('size') || lowerMessage.includes('mấy người') || lowerMessage.includes('phục vụ')) {
            this.handleSizeQuestion();
        }
        // Shelf life / preservation
        else if (lowerMessage.includes('bảo quản') || lowerMessage.includes('để được') || lowerMessage.includes('hạn sử dụng')) {
            this.handlePreservationQuestion();
        }
        // Standard intents
        else if (lowerMessage.includes('sản phẩm') || lowerMessage.includes('bánh') || lowerMessage.includes('menu')) {
            this.processIntent('products');
        } else if (lowerMessage.includes('đơn hàng') || lowerMessage.includes('theo dõi') || lowerMessage.includes('kiểm tra')) {
            this.processIntent('track_order');
        } else if (lowerMessage.includes('đặt hàng') || lowerMessage.includes('mua') || lowerMessage.includes('order')) {
            this.processIntent('how_to_order');
        } else if (lowerMessage.includes('giờ') || lowerMessage.includes('mở cửa') || lowerMessage.includes('làm việc')) {
            this.processIntent('hours');
        } else if (lowerMessage.includes('thanh toán') || lowerMessage.includes('payment')) {
            this.processIntent('payment');
        } else if (lowerMessage.includes('giao hàng') || lowerMessage.includes('ship')) {
            this.processIntent('shipping');
        } else if (lowerMessage.includes('giá') || lowerMessage.includes('price')) {
            this.processIntent('price');
        } else {
            this.processIntent('fallback');
        }
    }

    async processIntent(intent) {
        switch (intent) {
            case 'products':
                await this.showProducts();
                break;
            case 'track_order':
                this.trackOrder();
                break;
            case 'how_to_order':
                this.howToOrder();
                break;
            case 'faq':
                this.showFAQ();
                break;
            case 'hours':
                this.showHours();
                break;
            case 'payment':
                this.showPaymentInfo();
                break;
            case 'shipping':
                this.showShippingInfo();
                break;
            case 'price':
                this.showPriceRange();
                break;
            case 'allergy_info':
                this.handleAllergyQuestion('dị ứng', 'dị ứng');
                break;
            case 'ingredients_general':
                this.handleIngredientsQuestion('', '');
                break;
            default:
                this.fallbackResponse();
        }
    }

    async showProducts() {
        try {
            console.log('Fetching products from API...');
            const response = await fetch('/api/products?limit=5');
            console.log('API Response status:', response.status);

            const data = await response.json();
            console.log('API Data:', data);

            if (data.data && data.data.length > 0) {
                this.addBotMessage('Đây là một số sản phẩm bánh kem nổi bật của chúng tôi:');

                let productsHTML = '<div class="product-list">';
                data.data.forEach(product => {
                    productsHTML += `
                        <div class="product-card">
                            <img src="${product.image_url}" alt="${product.product_name}">
                            <div class="product-info">
                                <h4>${product.product_name}</h4>
                                <p class="product-price">${this.formatPrice(product.price)} ₫</p>
                                <a href="/products/${product.ProductID}" class="product-link">Xem chi tiết →</a>
                            </div>
                        </div>
                    `;
                });
                productsHTML += '</div>';

                this.addBotMessage(productsHTML, true);
                this.addBotMessage('Bạn có thể xem tất cả sản phẩm tại trang <a href="/products" style="color: #324F29; font-weight: 600;">Sản phẩm</a> của chúng tôi.', true);
            } else {
                // No products found
                this.addBotMessage('Hiện tại chúng tôi đang cập nhật danh sách sản phẩm. Bạn có thể xem tất cả sản phẩm tại <a href="/products" style="color: #324F29; font-weight: 600;">trang Sản phẩm</a>.', true);
            }
        } catch (error) {
            console.error('Chatbot - Error fetching products:', error);
            this.addBotMessage('Xin lỗi, không thể tải sản phẩm lúc này. Bạn có thể xem trực tiếp tại <a href="/products" style="color: #324F29; font-weight: 600;">trang Sản phẩm</a> của chúng tôi.', true);
        }

        this.showQuickReplies([
            { text: '📦 Theo dõi đơn hàng', value: 'track_order' },
            { text: '🛒 Hướng dẫn đặt hàng', value: 'how_to_order' }
        ]);
    }

    trackOrder() {
        this.addBotMessage('Để tra cứu đơn hàng, vui lòng nhập mã đơn hàng của bạn (ví dụ: ORD001):');
        this.waitingForOrderCode = true;
    }

    async handleOrderTracking(orderCode) {
        this.waitingForOrderCode = false;
        this.showTypingIndicator();
        await this.wait(1000);
        this.removeTypingIndicator();

        if (window.Laravel && window.Laravel.isLoggedIn) {
            this.addBotMessage(`Bạn có thể xem chi tiết đơn hàng ${orderCode} tại trang <a href="/oderdetail" style="color: #324F29; font-weight: 600;">Đơn hàng của tôi</a>.`, true);
        } else {
            this.addBotMessage('Vui lòng <a href="/login" style="color: #324F29; font-weight: 600;">đăng nhập</a> để theo dõi đơn hàng của bạn.', true);
        }

        this.showQuickReplies([
            { text: '🎂 Xem sản phẩm', value: 'products' },
            { text: '❓ Câu hỏi khác', value: 'faq' }
        ]);
    }

    howToOrder() {
        this.addBotMessage('Hướng dẫn đặt hàng tại La Cuisine Ngọt rất đơn giản:');
        this.addBotMessage(`
            <ol style="margin: 0; padding-left: 1.2rem;">
                <li>Chọn bánh kem yêu thích từ <a href="/products" style="color: #324F29;">trang sản phẩm</a></li>
                <li>Thêm vào giỏ hàng và điều chỉnh số lượng</li>
                <li>Vào giỏ hàng, nhập thông tin giao hàng</li>
                <li>Chọn phương thức thanh toán</li>
                <li>Xác nhận đơn hàng và chờ xác nhận từ chúng tôi</li>
            </ol>
        `, true);

        this.showQuickReplies([
            { text: '🎂 Xem sản phẩm ngay', value: 'products' },
            { text: '💳 Thanh toán', value: 'payment' },
            { text: '🚚 Giao hàng', value: 'shipping' }
        ]);
    }

    showFAQ() {
        this.addBotMessage('Dưới đây là một số câu hỏi thường gặp:');
        this.showQuickReplies([
            { text: '⏰ Giờ mở cửa', value: 'hours' },
            { text: '💳 Thanh toán', value: 'payment' },
            { text: '🚚 Giao hàng', value: 'shipping' },
            { text: '💰 Giá cả', value: 'price' }
        ]);
    }

    showHours() {
        this.addBotMessage('🕐 Giờ làm việc của La Cuisine Ngọt:\n\nThứ 2 - Thứ 6: 8:00 - 20:00\nThứ 7 - Chủ nhật: 9:00 - 21:00');
        this.showBackToMenu();
    }

    showPaymentInfo() {
        this.addBotMessage('💳 Phương thức thanh toán:\n\n✅ Thanh toán khi nhận hàng (COD)\n✅ Thanh toán online qua VNPay\n✅ Chuyển khoản ngân hàng');
        this.showBackToMenu();
    }

    showShippingInfo() {
        this.addBotMessage('🚚 Thông tin giao hàng:\n\n📍 Giao hàng toàn TP. Hồ Chí Minh\n⏱️ Thời gian: 1-2 ngày\n💵 Phí ship: 20.000 - 40.000đ tùy khu vực\n🎁 Miễn phí ship cho đơn từ 500.000đ');
        this.showBackToMenu();
    }

    showPriceRange() {
        this.addBotMessage('💰 Giá bánh kem tại La Cuisine Ngọt:\n\n🎂 Bánh sinh nhật: 200.000đ - 1.500.000đ\n🧁 Cupcake: 30.000đ - 50.000đ/cái\n🍰 Bánh kem tươi: 150.000đ - 800.000đ');
        this.showQuickReplies([
            { text: '🎂 Xem sản phẩm', value: 'products' }
        ]);
    }

    fallbackResponse() {
        this.addBotMessage('Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể chọn một trong các tùy chọn sau:');
        this.showQuickReplies([
            { text: '🎂 Sản phẩm', value: 'products' },
            { text: '📦 Đơn hàng', value: 'track_order' },
            { text: '❓ FAQ', value: 'faq' }
        ]);
    }

    showBackToMenu() {
        this.showQuickReplies([
            { text: '🏠 Menu chính', value: 'faq' },
            { text: '🎂 Xem sản phẩm', value: 'products' }
        ]);
    }

    scrollToBottom() {
        const container = document.getElementById('chatbotMessages');
        container.scrollTop = container.scrollHeight;
    }

    wait(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }

    // ===== DETAILED CONSULTATION HANDLERS =====

    handleAllergyQuestion(message, lowerMessage) {
        // Detect specific allergens
        const allergens = {
            'dâu': 'dâu',
            'sữa': 'sữa',
            'trứng': 'trứng',
            'hạt': 'hạt',
            'socola': 'sô cô la',
            'chocolate': 'sô cô la',
            'gluten': 'gluten',
            'đậu': 'đậu'
        };

        let detectedAllergen = null;
        for (const [key, value] of Object.entries(allergens)) {
            if (lowerMessage.includes(key)) {
                detectedAllergen = value;
                break;
            }
        }

        if (detectedAllergen) {
            // Natural, friendly response
            this.addBotMessage(`Mình hiểu rồi, bạn dị ứng với ${detectedAllergen} đúng không? 😊`);

            // Specific recommendations based on allergen
            if (detectedAllergen === 'dâu') {
                this.addBotMessage('Bạn có thể ăn các loại bánh này nhé: Mousse Chanh Dây, Tiramisu, Cheesecake Xoài, hay các bánh chocolate không có dâu trang trí. Khi đặt hàng bạn nhớ ghi chú "không dùng dâu" để bên mình chú ý nha! 🎂');
            } else if (detectedAllergen === 'sữa') {
                this.addBotMessage('Ối, dị ứng sữa thì hơi khó vì hầu hết bánh của bên mình đều có kem tươi. Nhưng bạn có thể gọi cho mình qua số 0901 234 567 để được tư vấn làm bánh đặc biệt không sữa nhé! 📞');
            } else if (detectedAllergen === 'sô cô la') {
                this.addBotMessage('Không vấn đề gì cả! Bạn có thể chọn các bánh trái cây như Entremet Rose, Cheesecake Dâu, hay các bánh mousse vị chanh dây. Mình có nhiều lựa chọn không có chocolate lắm đâu! 🍓');
            } else {
                this.addBotMessage(`Để đảm bảo an toàn, bạn nên gọi cho bên mình qua số 0901 234 567 để được tư vấn kỹ hơn nhé. Nhiều sản phẩm mình có thể tùy chỉnh được theo yêu cầu của bạn đấy! 💚`);
            }

            this.showQuickReplies([
                { text: '🎂 Xem sản phẩm', value: 'products' },
                { text: '📋 Hỏi thành phần', value: 'ingredients_general' },
                { text: '❓ Câu hỏi khác', value: 'faq' }
            ]);
        } else {
            this.addBotMessage('Bạn dị ứng với cái gì vậy? Dâu, sữa, trứng, hạt, hay sô cô la? Bạn cho mình biết để mình tư vấn bánh phù hợp nhé! 😊');
        }
    }

    handleIngredientsQuestion(message, lowerMessage) {
        this.addBotMessage('Tất cả bánh của bên mình đều làm từ nguyên liệu cao cấp nha bạn! ✨');
        this.addBotMessage(`Mình dùng kem tươi Anchor từ New Zealand, bơ Président Pháp, trứng tươi từ trang trại, và chocolate Callebaut Bỉ. Trái cây thì mình chọn dâu Đà Lạt, xoài Hòa Lộc, rất tươi ngon! 🍓

100% không chất bảo quản và màu nhân tạo đâu nha. Nếu muốn xem thành phần chi tiết từng loại bánh, bạn vào <a href="/products" style="color: #324F29; font-weight: 600;">trang Sản phẩm</a> là thấy hết! 🎂`, true);

        this.showQuickReplies([
            { text: '🎂 Xem sản phẩm', value: 'products' },
            { text: '🏥 Hỏi về dị ứng', value: 'allergy_info' },
            { text: '❓ Câu hỏi khác', value: 'faq' }
        ]);
    }

    handleSpecificProductQuestion(message, lowerMessage) {
        let productInfo = '';

        if (lowerMessage.includes('entremet')) {
            this.addBotMessage('Ồ bạn hỏi về Entremet Rose à? Đây là bánh best-seller của bên mình đấy! 🌹');
            productInfo = `Bánh này có lớp mousse hoa hồng mềm mịn, kết hợp với vải thiều tươi, đế là bánh bông lan vanilla. Trang trí hoa hồng fondant thủ công rất đẹp luôn!

💰 Giá: 650.000đ (size 15cm, đủ cho 4-6 người)
🧊 Để tủ lạnh được 2-3 ngày nhé!`;
        } else if (lowerMessage.includes('mousse')) {
            this.addBotMessage('Mousse Chanh Dây là sự kết hợp tuyệt vời giữa chocolate đắng và chanh dây chua ngọt đấy! 🍫');
            productInfo = `Bánh có mousse chocolate Callebaut 70%, chanh dây tươi, kem tươi Anchor, đế brownie socola giòn giòn.

💰 Giá: 580.000đ (size 15cm - 4-6 người)
Rất hợp cho người thích vị hơi đắng nhẹ nha!`;
        } else if (lowerMessage.includes('tiramisu')) {
            this.addBotMessage('Tiramisu của mình làm theo công thức Ý nguyên bản, rất authentic! ☕');
            productInfo = `Phô mai Mascarpone nhập khẩu, cafe espresso Arabica đậm đà, bánh savoiardi giòn, rắc bột cacao nguyên chất. Có thêm một chút rượu Marsala nữa nha!

💰 Giá: 450.000đ / hộp (đủ 6 người)
⚠️ Lưu ý: Có caffeine và alcohol nhé, không phù hợp cho trẻ em!`;
        } else if (lowerMessage.includes('cheesecake')) {
            this.addBotMessage('Cheesecake Dâu kiểu New York, kem cheese đậm đà lắm! 🍰');
            productInfo = `Làm từ Philadelphia cream cheese chính hiệu, dâu tây Đà Lạt tươi ngon, đế bánh quy Graham giòn tan, kem chua tạo vị chua nhẹ.

💰 Giá: 520.000đ (18cm - 6-8 người)
Ai thích cheese chắc chắn mê luôn! 😍`;
        } else {
            this.addBotMessage('Bạn muốn hỏi về bánh nào cụ thể? Mình có Entremet Rose, Mousse Chanh Dây, Tiramisu, Cheesecake Dâu... Bạn thích loại nào? 🎂');

            this.showQuickReplies([
                { text: '🎂 Xem tất cả', value: 'products' },
                { text: '❓ Câu hỏi khác', value: 'faq' }
            ]);
            return;
        }

        this.addBotMessage(productInfo);

        this.showQuickReplies([
            { text: '🎂 Xem thêm bánh', value: 'products' },
            { text: '🛒 Đặt hàng luôn', value: 'how_to_order' }
        ]);
    }

    handleSizeQuestion() {
        this.addBotMessage('Bạn muốn chọn size bánh cho mấy người ăn vậy? Để mình tư vấn nè! 📏');
        this.addBotMessage(`<strong>Bánh kem tươi / Mousse:</strong><br>
🎂 Size 12cm: 2-3 người (mini, cute lắm!)<br>
🎂 Size 15cm: 4-6 người (phổ biến nhất)<br>
🎂 Size 18cm: 6-8 người<br>
🎂 Size 21cm: 8-10 người<br>
🎂 Size 24cm: 10-12 người<br><br>

<strong>Bánh sinh nhật tầng:</strong><br>
🎉 1 tầng (20cm): 8-12 người<br>
🎉 2 tầng: 15-20 người<br>
🎉 3 tầng: 25-30 người<br><br>

💡 <em>Tip của mình: Nên đặt size lớn hơn 1-2 người để chắc chắn đủ ăn nha!</em>`, true);

        this.showQuickReplies([
            { text: '🎂 Xem sản phẩm', value: 'products' },
            { text: '💰 Giá bao nhiêu?', value: 'price' }
        ]);
    }

    handlePreservationQuestion() {
        this.addBotMessage('Bánh bảo quản đúng cách mới ngon và an toàn nha bạn! 🧊');
        this.addBotMessage(`<strong>Bánh kem tươi / Mousse:</strong><br>
Để ngăn mát tủ lạnh (2-7°C) được 2-3 ngày. Lấy ra trước khi ăn 10-15 phút cho bánh mềm hơn nha!<br><br>

<strong>Cheesecake:</strong><br>
Để tủ lạnh 3-5 ngày, hoặc đông lạnh được 1 tháng luôn!<br><br>

<strong>Cupcake / Bánh bông lan:</strong><br>
Nhiệt độ phòng: 1-2 ngày<br>
Tủ lạnh: 5-7 ngày<br><br>

⚠️ <strong>Nhớ nha:</strong> Đừng để bánh ngoài nắng, phải đậy kín, và ăn trong ngày là ngon nhất! Có gì gọi mình 0901 234 567 nha! 📞`, true);

        this.showQuickReplies([
            { text: '🎂 Xem sản phẩm', value: 'products' },
            { text: '❓ Hỏi thêm', value: 'faq' }
        ]);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.chatbot = new Chatbot();
});
