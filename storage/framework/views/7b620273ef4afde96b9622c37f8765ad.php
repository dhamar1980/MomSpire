<?php $__env->startSection('title', 'Konsultasi - MomSpire'); ?>
<?php $__env->startSection('header_title', 'Konsultasi'); ?>
<?php $__env->startSection('header_subtitle', ''); ?>

<?php $__env->startPush('head'); ?>
<style>
    :root {
        --pengguna-primary: #e63980;
        --pengguna-secondary: #00b894;
        --pengguna-purple: #6f42c1;
    }

    /* Background: sama kayak dashboard pengguna */
    .pengguna-konsultasi-shell {
        position: relative;
        isolation: isolate;
        min-height: calc(100vh - 80px);
        padding: 0;
        background: linear-gradient(180deg, #ffffff 0%, #f0f4f8 100%);
    }

    .pengguna-konsultasi-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        background:
            radial-gradient(circle at 12% 8%, rgba(230, 57, 128, 0.15), transparent 28%),
            radial-gradient(circle at 92% 14%, rgba(107, 66, 193, 0.12), transparent 26%),
            radial-gradient(circle at 20% 80%, rgba(0, 184, 148, 0.08), transparent 32%);
        z-index: -2;
        pointer-events: none;
    }

    .pengguna-konsultasi-shell::after {
        content: '';
        position: fixed;
        inset: 0;
        background-image: linear-gradient(rgba(15, 23, 42, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, 0.015) 1px, transparent 1px);
        background-size: 42px 42px;
        opacity: .3;
        pointer-events: none;
        z-index: -1;
    }

    /* Main container - FULL width ke kiri-kanan */
    .wa-full-container {
        display: flex;
        max-width: 100%;
        height: calc(100vh - 80px);
        min-height: 500px;
        background: #fff;
        overflow: hidden;
    }

    /* Sidebar */
    .wa-sidebar {
        width: 500px;
        border-right: 1px solid #e8e8e8;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .wa-sidebar-header {
        padding: 0;
        height: 60px;
        background: linear-gradient(135deg, var(--pengguna-primary), #ff6b9d);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wa-sidebar-icon {
        font-size: 24px;
        color: #fff;
    }

    /* Search bar di sidebar */
    .wa-search-container {
        padding: 14px;
        background: #fafafa;
        border-bottom: 1px solid #eee;
    }

    .wa-search {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 24px;
        padding: 10px 18px;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #eee;
    }

    .wa-search i {
        color: #aaa;
        font-size: 16px;
    }

    .wa-search input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 14px;
        background: transparent;
    }

    /* Contacts list */
    .wa-contacts {
        flex: 1;
        overflow-y: auto;
    }

    .wa-contact-section {
        padding: 8px 0;
    }

    .wa-section-title {
        padding: 10px 20px;
        font-size: 11px;
        font-weight: 700;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        background: #fafafa;
    }

    .wa-contact-item {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        gap: 14px;
        cursor: pointer;
        transition: all 0.15s ease;
        border-bottom: 1px solid #f8f8f8;
    }

    .wa-contact-item:hover {
        background: #fef6f8;
    }

    .wa-contact-item.active {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.08), rgba(255, 107, 157, 0.04));
        border-left: 3px solid var(--pengguna-primary);
    }

    .wa-contact-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        position: relative;
    }

    .wa-contact-avatar.bidan {
        background: linear-gradient(135deg, var(--pengguna-purple), #a855f7);
    }

    .wa-contact-avatar.dokter {
        background: linear-gradient(135deg, var(--pengguna-secondary), #00cec9);
    }

    .wa-online-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 11px;
        height: 11px;
        background: #fff;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    .wa-contact-info {
        flex: 1;
        min-width: 0;
    }

    .wa-contact-name {
        font-size: 15px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 3px;
    }

    .wa-contact-role {
        font-size: 12px;
    }

    /* Chat area */
    .wa-chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
        min-width: 0;
    }

    /* Chat header */
    .wa-chat-header {
        height: 60px;
        padding: 0 20px;
        background: linear-gradient(135deg, var(--pengguna-primary), #ff6b9d);
        display: flex;
        align-items: center;
        gap: 14px;
        color: #fff;
        flex-shrink: 0;
    }

    .wa-chat-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        background: rgba(255,255,255,0.2);
    }

    .wa-chat-header-info {
        flex: 1;
    }

    .wa-chat-header-name {
        font-size: 15px;
        font-weight: 700;
    }

    .wa-chat-header-status {
        font-size: 11px;
        opacity: 0.85;
    }

    .wa-chat-search-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: rgba(255,255,255,0.2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
    }

    .wa-chat-search-btn:hover {
        background: rgba(255,255,255,0.3);
    }

    /* Chat search overlay */
    .wa-chat-search-wrap {
        display: none;
        padding: 10px 16px;
        background: #fafafa;
        border-bottom: 1px solid #eee;
        gap: 8px;
        align-items: center;
    }

    .wa-chat-search-wrap.active {
        display: flex;
    }

    .wa-chat-search-inner {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 8px 14px;
    }

    .wa-chat-search-inner input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 13px;
        background: transparent;
    }

    .wa-chat-search-close {
        background: none;
        border: none;
        color: #888;
        cursor: pointer;
        font-size: 14px;
    }

    /* Messages */
    .wa-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        background: #fff;
    }

    /* Message bubbles */
    .wa-message {
        display: flex;
        max-width: 72%;
        animation: msgSlide 0.2s ease;
    }

    @keyframes msgSlide {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .wa-message.sent { align-self: flex-end; }
    .wa-message.received { align-self: flex-start; }

    .wa-message-bubble {
        padding: 10px 14px;
        border-radius: 16px;
        word-break: break-word;
        line-height: 1.5;
        font-size: 14px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }

    .wa-message.sent .wa-message-bubble {
        background: linear-gradient(135deg, #d9fdd3, #c8f7c5);
        color: #1a1a1a;
        border-bottom-right-radius: 4px;
    }

    .wa-message.received .wa-message-bubble {
        background: #f3f4f6;
        color: #1a1a1a;
        border-bottom-left-radius: 4px;
    }

    .wa-message-text {
        margin-bottom: 4px;
    }

    .wa-message-meta {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        color: #aaa;
        margin-top: 4px;
    }

    .wa-read-check {
        color: #53bdeb;
        font-size: 14px;
    }

    /* Date separator */
    .wa-date-separator {
        text-align: center;
        padding: 12px 0;
    }

    .wa-date-separator span {
        background: #f3f4f6;
        color: #888;
        padding: 5px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Chat input */
    .wa-chat-input-container {
        padding: 12px 16px;
        background: #fafafa;
        display: flex;
        align-items: flex-end;
        gap: 12px;
        border-top: 1px solid #eee;
        flex-shrink: 0;
    }

    .wa-chat-input-wrap {
        flex: 1;
        display: flex;
        align-items: flex-end;
        gap: 10px;
        background: #fff;
        border-radius: 24px;
        padding: 10px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #eee;
    }

    .wa-chat-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 14px;
        resize: none;
        max-height: 100px;
        font-family: inherit;
        background: transparent;
        line-height: 1.4;
    }

    .wa-chat-input:focus { outline: none; }

    .wa-send-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--pengguna-primary), #ff6b9d);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(230, 57, 128, 0.3);
    }

    .wa-send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(230, 57, 128, 0.4);
    }

    .wa-send-btn:active { transform: scale(0.95); }

    /* Empty state */
    .wa-empty-chat {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #fff;
        text-align: center;
        padding: 40px;
    }

    .wa-empty-icon {
        font-size: 72px;
        margin-bottom: 16px;
    }

    .wa-empty-title {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }

    .wa-empty-subtitle {
        font-size: 14px;
        color: #888;
        max-width: 320px;
        line-height: 1.6;
    }

    /* Scrollbar */
    .wa-contacts::-webkit-scrollbar,
    .wa-messages::-webkit-scrollbar {
        width: 5px;
    }

    .wa-contacts::-webkit-scrollbar-thumb,
    .wa-messages::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 3px;
    }

    @media (max-width: 900px) {
        .wa-full-container { flex-direction: column; height: auto; }
        .wa-sidebar { width: 100%; height: auto; max-height: 350px; }
        .wa-chat-area { min-height: 500px; }
        .wa-message { max-width: 85%; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pengguna-konsultasi-shell">
    <div class="wa-full-container">
        <!-- Sidebar - Contacts -->
        <div class="wa-sidebar">
            <div class="wa-sidebar-header">
                <i class="bi bi-chat-dots-fill wa-sidebar-icon"></i>
            </div>

            <div class="wa-search-container">
                <div class="wa-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="contactSearch" placeholder="Cari bidan atau dokter..." oninput="filterContacts()">
                </div>
            </div>

            <div class="wa-contacts" id="contactsList">
                <div class="wa-contact-section">
                    <div class="wa-section-title">Bidan</div>
                    <?php $__empty_1 = true; $__currentLoopData = $bidan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $bLastSeen = $b->last_seen ? \Carbon\Carbon::parse($b->last_seen) : null;
                            $bDiff = $bLastSeen ? $bLastSeen->diffInMinutes(now()) : null;
                            $bIsOnline = $bDiff !== null && $bDiff <= 5;
                        ?>
                        <div class="wa-contact-item"
                             data-id="<?php echo e($b->id); ?>"
                             data-type="bidan"
                             data-name="<?php echo e(strtolower($b->name)); ?>"
                             data-is-online="<?php echo e($bIsOnline ? 'true' : 'false'); ?>"
                             onclick="selectProfessional(this)">
                            <div class="wa-contact-avatar bidan">
                                <?php echo e(strtoupper(substr($b->name, 0, 1))); ?>

                                <span class="wa-online-dot" style="background: <?php echo e($bIsOnline ? '#25d366' : '#ccc'); ?>;"></span>
                            </div>
                            <div class="wa-contact-info">
                                <div class="wa-contact-name"><?php echo e($b->name); ?></div>
                                <div class="wa-contact-role" style="color: <?php echo e($bIsOnline ? '#25d366' : '#888'); ?>;">
                                    <?php if($bIsOnline): ?>
                                        Online
                                    <?php elseif($bDiff !== null): ?>
                                        <?php if($bDiff < 60): ?>
                                            Aktif <?php echo e(round($bDiff)); ?> menit lalu
                                        <?php elseif($bDiff < 1440): ?>
                                            Aktif <?php echo e(floor($bDiff / 60)); ?> jam lalu
                                        <?php else: ?>
                                            Aktif <?php echo e($bLastSeen->diffForHumans()); ?>

                                        <?php endif; ?>
                                    <?php else: ?>
                                        Offline
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="wa-contact-item">
                            <div class="wa-contact-info">
                                <div class="wa-contact-role" style="color:#aaa; padding:10px 0;">Belum ada bidan tersedia</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="wa-contact-section">
                    <div class="wa-section-title">Dokter</div>
                    <?php $__empty_1 = true; $__currentLoopData = $dokter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $dLastSeen = $d->last_seen ? \Carbon\Carbon::parse($d->last_seen) : null;
                            $dDiff = $dLastSeen ? $dLastSeen->diffInMinutes(now()) : null;
                            $dIsOnline = $dDiff !== null && $dDiff <= 5;
                        ?>
                        <div class="wa-contact-item"
                             data-id="<?php echo e($d->id); ?>"
                             data-type="dokter"
                             data-name="<?php echo e(strtolower($d->name)); ?>"
                             data-is-online="<?php echo e($dIsOnline ? 'true' : 'false'); ?>"
                             onclick="selectProfessional(this)">
                            <div class="wa-contact-avatar dokter">
                                <?php echo e(strtoupper(substr($d->name, 0, 1))); ?>

                                <span class="wa-online-dot" style="background: <?php echo e($dIsOnline ? '#25d366' : '#ccc'); ?>;"></span>
                            </div>
                            <div class="wa-contact-info">
                                <div class="wa-contact-name"><?php echo e($d->name); ?></div>
                                <div class="wa-contact-role" style="color: <?php echo e($dIsOnline ? '#25d366' : '#888'); ?>;">
                                    <?php if($dIsOnline): ?>
                                        Online
                                    <?php elseif($dDiff !== null): ?>
                                        <?php if($dDiff < 60): ?>
                                            Aktif <?php echo e(round($dDiff)); ?> menit lalu
                                        <?php elseif($dDiff < 1440): ?>
                                            Aktif <?php echo e(floor($dDiff / 60)); ?> jam lalu
                                        <?php else: ?>
                                            Aktif <?php echo e($dLastSeen->diffForHumans()); ?>

                                        <?php endif; ?>
                                    <?php else: ?>
                                        Offline
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="wa-contact-item">
                            <div class="wa-contact-info">
                                <div class="wa-contact-role" style="color:#aaa; padding:10px 0;">Belum ada dokter tersedia</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="wa-chat-area" id="chatArea">
            <!-- Empty state -->
            <div class="wa-empty-chat" id="emptyState">
                <div class="wa-empty-icon">💬</div>
                <div class="wa-empty-title">Konsultasi Kesehatan</div>
                <div class="wa-empty-subtitle">
                    Pilih bidan atau dokter di sebelah kiri untuk memulai konsultasi.
                </div>
            </div>

            <!-- Chat container (shown when professional is selected) -->
            <div id="chatContainer" style="display:none; flex-direction:column; height:100%;">
                <div class="wa-chat-header">
                    <div class="wa-chat-avatar" id="chatAvatar">-</div>
                    <div class="wa-chat-header-info">
                        <div class="wa-chat-header-name" id="chatName">-</div>
                        <div class="wa-chat-header-status" id="chatStatus">
                            <span id="statusText">Offline</span>
                        </div>
                    </div>
                    <button class="wa-chat-search-btn" onclick="toggleChatSearch()" title="Cari dalam chat">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <div class="wa-chat-search-wrap" id="chatSearchWrap">
                    <div class="wa-chat-search-inner">
                        <i class="bi bi-search" style="color:#aaa; font-size:14px;"></i>
                        <input type="text" id="chatSearchInput" placeholder="Cari pesan..." oninput="filterMessages()">
                        <button class="wa-chat-search-close" onclick="toggleChatSearch()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="wa-messages" id="messageList"></div>

                <div class="wa-chat-input-container">
                    <div class="wa-chat-input-wrap">
                        <textarea class="wa-chat-input" id="messageInput" placeholder="Ketik pesan..." rows="1"></textarea>
                        <button class="wa-send-btn" onclick="sendMessage()">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let currentConversation = null;
    let currentProfessional = null;
    let isLoading = false;

    const currentUserId = <?php echo e(auth()->id()); ?>;
    const currentUserName = <?php echo json_encode(auth()->user()->name ?? 'Pengguna', 15, 512) ?>;

    console.log('[Konsultasi] User ID:', currentUserId);

    // Filter contacts
    function filterContacts() {
        const keyword = document.getElementById('contactSearch').value.toLowerCase();
        document.querySelectorAll('.wa-contact-item').forEach(item => {
            const name = item.getAttribute('data-name') || '';
            item.style.display = name.includes(keyword) ? 'flex' : 'none';
        });
    }

    // Select professional and start chat
    async function selectProfessional(element) {
        const type = element.getAttribute('data-type');
        const id = parseInt(element.getAttribute('data-id'));
        const name = element.querySelector('.wa-contact-name')?.textContent || 'User';
        const isOnline = element.getAttribute('data-is-online') === 'true';

        // Active state
        document.querySelectorAll('.wa-contact-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');

        // Show chat container
        const emptyState = document.getElementById('emptyState');
        const chatContainer = document.getElementById('chatContainer');
        if (emptyState) emptyState.style.display = 'none';
        if (!chatContainer) {
            alert('Error: Chat container not found');
            return;
        }
        chatContainer.style.display = 'flex';

        // Update header with online status
        const chatAvatar = document.getElementById('chatAvatar');
        const chatName = document.getElementById('chatName');
        if (chatAvatar) chatAvatar.textContent = type === 'bidan' ? 'B' : 'D';
        if (chatName) chatName.textContent = name;

        // Update status text in header
        const statusText = document.getElementById('statusText');
        if (statusText) {
            statusText.textContent = isOnline ? 'Online' : 'Offline';
            statusText.style.color = isOnline ? '#25d366' : '#888';
        }

        // Setup input handlers
        setupInputHandlers();

        // Debug: log what we're about to send
        console.log('[Konsultasi] Starting conversation with:', { type, id, name });

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const response = await fetch('<?php echo e(route("pengguna.konsultasi.start")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({
                    professional_type: type,
                    professional_id: id,
                })
            });

            // Debug: log raw response status
            console.log('[Konsultasi] Start response status:', response.status);

            const text = await response.text();
            console.log('[Konsultasi] Start response text:', text);

            let data;
            try {
                data = JSON.parse(text);
            } catch (parseError) {
                console.error('[Konsultasi] JSON parse error:', parseError);
                alert('Error parsing response from server');
                return;
            }

            if (data.success) {
                console.log('[Konsultasi] Conversation started, ID:', data.conversation_id);
                currentConversation = data.conversation_id;
                currentProfessional = { type, id, name };
                await loadMessages();
            } else {
                console.error('[Konsultasi] Start failed:', data);
                alert('Gagal: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('[Konsultasi] Error:', error);
            alert('Gagal memulai konsultasi: ' + error.message);
        }
    }

    // Load messages
    async function loadMessages() {
        if (!currentConversation) {
            console.log('[Konsultasi] loadMessages: no conversation ID, skipping');
            return;
        }

        try {
            const url = `/pengguna/konsultasi/${currentConversation}/messages`;
            console.log('[Konsultasi] Loading messages from:', url);
            const response = await fetch(url);
            console.log('[Konsultasi] Messages response status:', response.status);

            const text = await response.text();
            console.log('[Konsultasi] Messages response:', text.substring(0, 500));

            let data;
            try {
                data = JSON.parse(text);
            } catch (parseError) {
                console.error('[Konsultasi] JSON parse error:', parseError);
                return;
            }

            if (data.success) {
                console.log('[Konsultasi] Loaded', (data.messages || []).length, 'messages');
                renderMessages(data.messages || []);
            } else {
                console.error('[Konsultasi] Load failed:', data);
            }
        } catch (error) {
            console.error('[Konsultasi] Load error:', error);
        }
    }

    // Render messages
    function renderMessages(messages) {
        const messageList = document.getElementById('messageList');
        if (!messageList) {
            console.error('[Konsultasi] messageList element not found');
            return;
        }

        console.log('[Konsultasi] Rendering', messages.length, 'messages');

        let html = '';
        let lastDate = '';

        if (messages.length === 0) {
            html = `
                <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#aaa; padding:40px;">
                    <div style="font-size:48px; margin-bottom:12px;">👋</div>
                    <div style="font-size:16px; font-weight:500; color:#888;">Mulai percakapan</div>
                    <div style="font-size:13px; color:#aaa; margin-top:4px;">
                        Kirim pesan pertama ke ${escapeHtml(currentProfessional?.name || '')}
                    </div>
                </div>
            `;
        } else {
            let renderedCount = 0;
            messages.forEach((msg) => {
                const isOwn = msg.sender_type === 'pengguna';
                const messageDate = parseMessageDate(msg);
                const time = formatMessageTime(messageDate);
                const msgDate = messageDate ? messageDate.toDateString() : '';

                if (msgDate && msgDate !== lastDate) {
                    const dateStr = formatDateSeparator(messageDate);
                    html += `<div class="wa-date-separator"><span>${dateStr}</span></div>`;
                    lastDate = msgDate;
                }

                html += `
                    <div class="wa-message ${isOwn ? 'sent' : 'received'}">
                        <div class="wa-message-bubble">
                            <div class="wa-message-text">${escapeHtml(msg.message || '').replace(/\n/g, '<br>')}</div>
                            <div class="wa-message-meta">
                                <span>${time}</span>
                                ${isOwn ? '<i class="bi bi-check2-all wa-read-check"></i>' : ''}
                            </div>
                        </div>
                    </div>
                `;
                renderedCount++;
            });
            console.log('[Konsultasi] Rendered', renderedCount, 'messages');
        }

        messageList.innerHTML = html;
        scrollToBottom();
    }

    // Send message
    async function sendMessage() {
        if (isLoading) return;

        const input = document.getElementById('messageInput');
        const message = input?.value.trim();
        if (!message || !currentConversation) {
            if (!currentConversation) alert('Pilih bidan atau dokter terlebih dahulu!');
            return;
        }

        isLoading = true;

        // Optimistic UI - gunakan waktu lokal yang benar (WIB/UTC+7)
        const messageList = document.getElementById('messageList');
        const now = new Date();
        const time = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
        const optMsg = document.createElement('div');
        optMsg.className = 'wa-message sent';
        optMsg.innerHTML = `
            <div class="wa-message-bubble">
                <div class="wa-message-text">${escapeHtml(message).replace(/\n/g, '<br>')}</div>
                <div class="wa-message-meta">
                    <span>${time}</span>
                    <i class="bi bi-clock wa-read-check" style="opacity:0.4; font-size:12px;"></i>
                </div>
            </div>
        `;
        messageList.appendChild(optMsg);
        scrollToBottom();
        input.value = '';

        try {
            const response = await fetch('<?php echo e(route("pengguna.konsultasi.send_message")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    conversation_id: currentConversation,
                    message: message,
                })
            });

            const data = await response.json();
            console.log('[Konsultasi] Send response:', data);

            if (data.success) {
                optMsg.querySelector('.bi-clock').className = 'bi bi-check2-all wa-read-check';
                await loadMessages();
            } else {
                optMsg.remove();
                alert('Gagal mengirim pesan');
            }
        } catch (error) {
            console.error('[Konsultasi] Send error:', error);
            optMsg.remove();
            alert('Gagal mengirim pesan');
        } finally {
            isLoading = false;
        }
    }

    // Toggle chat search
    function toggleChatSearch() {
        const searchWrap = document.getElementById('chatSearchWrap');
        if (searchWrap) {
            searchWrap.classList.toggle('active');
            if (searchWrap.classList.contains('active')) {
                document.getElementById('chatSearchInput')?.focus();
            } else {
                document.getElementById('chatSearchInput') && (document.getElementById('chatSearchInput').value = '');
                filterMessages();
            }
        }
    }

    // Filter messages by search
    function filterMessages() {
        const keyword = document.getElementById('chatSearchInput')?.value.toLowerCase() || '';
        document.querySelectorAll('.wa-message').forEach(msg => {
            const text = msg.querySelector('.wa-message-text')?.textContent?.toLowerCase() || '';
            msg.style.display = text.includes(keyword) ? 'flex' : 'none';
        });
    }

    // Setup input handlers
    function setupInputHandlers() {
        const input = document.getElementById('messageInput');
        if (!input) return;

        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });

        input.onkeydown = function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        };

        input.focus();
    }

    function parseMessageDate(msg) {
        const raw = msg?.created_at;
        if (raw) {
            const date = new Date(raw);
            if (!Number.isNaN(date.getTime())) return date;
        }

        if (msg?.timestamp) {
            const timestamp = Number(msg.timestamp);
            const date = new Date(timestamp < 1000000000000 ? timestamp * 1000 : timestamp);
            if (!Number.isNaN(date.getTime())) return date;
        }

        return null;
    }

    function formatMessageTime(date) {
        if (!date) return '--:--';
        return date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        }).replace('.', ':');
    }

    function formatDateSeparator(dateInput) {
        const date = dateInput instanceof Date ? dateInput : new Date(dateInput);
        if (Number.isNaN(date.getTime())) return '';

        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        if (date.toDateString() === today.toDateString()) return 'Hari ini';
        if (date.toDateString() === yesterday.toDateString()) return 'Kemarin';

        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    }

    function scrollToBottom() {
        const messageList = document.getElementById('messageList');
        if (messageList) messageList.scrollTop = messageList.scrollHeight;
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('[Konsultasi] Ready!');
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('pengguna.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\MomSpire\resources\views/pengguna/konsultasi.blade.php ENDPATH**/ ?>