@extends('bidan.master')

@section('title', 'Konsultasi Bidan - MomSpire')
@section('header_title', 'Konsultasi')
@section('header_subtitle', 'Balas pesan pengguna dari ruang chat bidan.')

@push('head')
<style>
    :root {
        --pengguna-primary: #e63980;
    }

    /* Background: inherit dari body admin-body yang punya background #f6f9fc */
    .bidan-konsultasi-shell {
        min-height: calc(100vh - 80px);
        padding: 0;
    }

    /* Main container - FULL width */
    .wa-container {
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
        padding: 0 20px;
        height: 60px;
        background: linear-gradient(135deg, var(--pengguna-primary), #ff6b9d);
        display: flex;
        align-items: center;
    }

    .wa-sidebar-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
    }

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

    .wa-search input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 14px;
        background: transparent;
    }

    .wa-conversations {
        flex: 1;
        overflow-y: auto;
    }

    .wa-conversation-item {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        gap: 14px;
        cursor: pointer;
        transition: background 0.15s;
        border-bottom: 1px solid #f8f8f8;
    }

    .wa-conversation-item:hover {
        background: #fef6f8;
    }

    .wa-conversation-item.active {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.08), rgba(255, 107, 157, 0.04));
        border-left: 3px solid var(--pengguna-primary);
    }

    .wa-conversation-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, var(--pengguna-primary), #a855f7);
        flex-shrink: 0;
        position: relative;
    }

    .wa-online-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    .wa-online-dot.online { background: #25d366; }
    .wa-online-dot.offline { background: #bbb; }

    .wa-conversation-info {
        flex: 1;
        min-width: 0;
    }

    .wa-conversation-name {
        font-size: 15px;
        font-weight: 600;
        color: #111;
        margin-bottom: 2px;
    }

    .wa-conversation-preview {
        font-size: 13px;
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wa-conversation-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
    }

    .wa-conversation-time {
        font-size: 11px;
        color: #888;
    }

    .wa-unread-badge {
        background: linear-gradient(135deg, var(--pengguna-primary), #ff6b9d);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 10px;
    }

    /* Chat area */
    .wa-chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
        min-width: 0;
    }

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
        position: relative;
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
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .wa-chat-header-status .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .wa-chat-header-status .status-dot.online { background: #25d366; }
    .wa-chat-header-status .status-dot.offline { background: #bbb; }

    .wa-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        background: #fff;
    }

    .wa-message {
        display: flex;
        max-width: 70%;
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
        border-bottom-right-radius: 4px;
    }

    .wa-message.received .wa-message-bubble {
        background: #f3f4f6;
        border-bottom-left-radius: 4px;
    }

    .wa-message-text { margin-bottom: 4px; color: #111; }

    .wa-message-meta {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        color: #aaa;
        margin-top: 4px;
    }

    .wa-read-check { color: #53bdeb; font-size: 14px; }

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

    .wa-empty-icon { font-size: 72px; margin-bottom: 16px; }
    .wa-empty-title { font-size: 22px; font-weight: 700; color: #333; margin-bottom: 8px; }
    .wa-empty-subtitle { font-size: 14px; color: #888; max-width: 320px; line-height: 1.6; }

    .wa-conversations::-webkit-scrollbar,
    .wa-messages::-webkit-scrollbar { width: 5px; }
    .wa-conversations::-webkit-scrollbar-thumb,
    .wa-messages::-webkit-scrollbar-thumb { background: #ddd; border-radius: 3px; }

    @media (max-width: 900px) {
        .wa-container { flex-direction: column; height: auto; }
        .wa-sidebar { width: 100%; height: 300px; }
    }
</style>
@endpush

@section('content')
<div class="bidan-konsultasi-shell">
    <div class="wa-container">
        <!-- Sidebar - Conversations -->
        <div class="wa-sidebar">
            <div class="wa-sidebar-header">
                <h4><i class="bi bi-chat-dots-fill me-2"></i>Percakapan</h4>
            </div>

            <div class="wa-search-container">
                <div class="wa-search">
                    <i class="bi bi-search" style="color:#aaa;"></i>
                    <input type="text" id="conversationSearch" placeholder="Cari nama pengguna..." onkeyup="filterConversations()">
                </div>
            </div>

            <div class="wa-conversations" id="conversationList">
                @forelse($conversations as $conversation)
                    @php
                        $pengguna = $conversation->pengguna;
                        // Hitung ulang isOnline berdasarkan last_seen (bukan dari DB)
                        $isOnline = $pengguna && $pengguna->last_seen && \Carbon\Carbon::parse($pengguna->last_seen)->diffInMinutes(now()) <= 5;
                        $lastSeenText = '';
                        if ($pengguna && $pengguna->last_seen) {
                            $diff = \Carbon\Carbon::parse($pengguna->last_seen)->diffInMinutes(now());
                            if ($diff < 1) {
                                $lastSeenText = 'Baru saja';
                            } elseif ($diff < 60) {
                                $lastSeenText = round($diff) . ' menit lalu';
                            } elseif ($diff < 1440) {
                                $lastSeenText = floor($diff / 60) . ' jam lalu';
                            } else {
                                $lastSeenText = \Carbon\Carbon::parse($pengguna->last_seen)->diffForHumans();
                            }
                        }
                    @endphp
                    <div class="wa-conversation-item {{ $selectedConversation && $selectedConversation->id === $conversation->id ? 'active' : '' }}"
                         data-conversation-id="{{ $conversation->id }}"
                         data-conversation-name="{{ strtolower($conversation->pengguna->name ?? '') }}"
                         data-pengguna-online="{{ $isOnline ? 'true' : 'false' }}"
                         data-pengguna-lastseen="{{ $lastSeenText }}"
                         onclick="selectConversation({{ $conversation->id }}, @json($conversation->pengguna->name ?? 'Pengguna'), {{ $isOnline ? 'true' : 'false' }}, @json($lastSeenText))">
                        <div class="wa-conversation-avatar">
                            {{ strtoupper(substr($conversation->pengguna->name ?? 'P', 0, 1)) }}
                            <span class="wa-online-dot {{ $isOnline ? 'online' : 'offline' }}"></span>
                        </div>
                        <div class="wa-conversation-info">
                            <div class="wa-conversation-name">{{ $conversation->pengguna->name ?? 'Pengguna' }}</div>
                            <div class="wa-conversation-preview">{{ $lastSeenText ? 'Aktif ' . $lastSeenText : 'Offline' }}</div>
                        </div>
                        <div class="wa-conversation-meta">
                            <span class="wa-conversation-time">{{ $conversation->last_message_at?->diffForHumans() ?? 'Baru' }}</span>
                            @if(($conversation->unread_count ?? 0) > 0)
                                <span class="wa-unread-badge">{{ $conversation->unread_count }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="wa-empty-chat" style="padding:20px;">
                        <div style="font-size:40px; margin-bottom:10px;">💬</div>
                        <div style="font-size:14px; color:#888;">Belum ada konsultasi masuk</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="wa-chat-area" id="chatArea">
            <div class="wa-empty-chat" id="emptyState">
                <div class="wa-empty-icon">💬</div>
                <div class="wa-empty-title">Pilih Percakapan</div>
                <div class="wa-empty-subtitle">
                    Pilih salah satu percakapan di kiri untuk memulai chat dengan pengguna.
                </div>
            </div>

            <div id="chatContainer" style="display:none; flex-direction:column; height:100%;">
                <div class="wa-chat-header">
                    <div class="wa-chat-avatar" id="chatAvatar">-</div>
                    <div class="wa-chat-header-info">
                        <div class="wa-chat-header-name" id="chatName">-</div>
                        <div class="wa-chat-header-status" id="chatStatus">
                            <span class="status-dot offline"></span>
                            <span id="chatStatusText">Offline</span>
                        </div>
                    </div>
                </div>

                <div class="wa-messages" id="messageList"></div>

                <div class="wa-chat-input-container">
                    <div class="wa-chat-input-wrap">
                        <textarea class="wa-chat-input" id="messageInput" placeholder="Ketik balasan..." rows="1" onkeydown="handleKeyDown(event)"></textarea>
                        <button class="wa-send-btn" onclick="sendMessage()">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@php
    $currentPenggunaOnline = false;
    $currentPenggunaLastSeen = 'Baru saja';
    if ($selectedConversation && $selectedConversation->pengguna && $selectedConversation->pengguna->last_seen) {
        $diff = \Carbon\Carbon::parse($selectedConversation->pengguna->last_seen)->diffInMinutes(now());
        $currentPenggunaOnline = $selectedConversation->pengguna->is_online && $diff <= 5;
        if ($diff < 1) {
            $currentPenggunaLastSeen = 'Baru saja';
        } elseif ($diff < 60) {
            $currentPenggunaLastSeen = round($diff) . ' menit lalu';
        } elseif ($diff < 1440) {
            $currentPenggunaLastSeen = round($diff / 60) . ' jam lalu';
        } else {
            $currentPenggunaLastSeen = \Carbon\Carbon::parse($selectedConversation->pengguna->last_seen)->diffForHumans();
        }
    }
@endphp
<script>
    let currentConversationId = {{ $selectedConversation?->id ?? 'null' }};
    let currentConversationName = @json($selectedConversation?->pengguna?->name ?? 'Pengguna');
    let currentPenggunaOnline = {{ $currentPenggunaOnline ? 'true' : 'false' }};
    let currentPenggunaLastSeen = @json($currentPenggunaLastSeen);
    let firebaseUnsubscribe = null;

    const bidanId = {{ auth()->id() }};
    const bidanName = @json(auth()->user()->name ?? 'Bidan');

    @if($selectedConversation)
        window.addEventListener('load', function() {
            selectConversation({{ $selectedConversation->id }}, @json($selectedConversation->pengguna->name ?? 'Pengguna'), currentPenggunaOnline, currentPenggunaLastSeen);
        });
    @endif

    function filterConversations() {
        const keyword = document.getElementById('conversationSearch').value.toLowerCase();
        document.querySelectorAll('.wa-conversation-item').forEach(item => {
            const name = item.getAttribute('data-conversation-name') || '';
            item.style.display = name.includes(keyword) ? 'flex' : 'none';
        });
    }

    async function selectConversation(conversationId, name, isOnline = false, lastSeen = '') {
        document.querySelectorAll('.wa-conversation-item').forEach(item => item.classList.remove('active'));
        const selectedItem = document.querySelector(`.wa-conversation-item[data-conversation-id="${conversationId}"]`);
        if (selectedItem) selectedItem.classList.add('active');

        currentConversationId = conversationId;
        currentConversationName = name;
        currentPenggunaOnline = selectedItem ? selectedItem.getAttribute('data-pengguna-online') === 'true' : false;
        currentPenggunaLastSeen = selectedItem ? selectedItem.getAttribute('data-pengguna-lastseen') || '' : '';

        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('chatContainer').style.display = 'flex';

        document.getElementById('chatAvatar').textContent = (name || 'P').substring(0, 1).toUpperCase();

        // Update header with online status
        updateChatHeader(name || 'Pengguna', currentPenggunaOnline, currentPenggunaLastSeen);

        await loadChat(conversationId, name);
        setupInputHandlers();
        setupFirebaseListener();
    }

    function updateChatHeader(name, isOnline, lastSeen) {
        document.getElementById('chatName').textContent = name;

        const statusContainer = document.getElementById('chatStatus');
        const statusDot = statusContainer.querySelector('.status-dot');
        const statusText = document.getElementById('chatStatusText');

        if (isOnline) {
            statusDot.className = 'status-dot online';
            statusText.textContent = 'Online';
        } else {
            statusDot.className = 'status-dot offline';
            if (lastSeen) {
                statusText.textContent = 'Aktif ' + lastSeen;
            } else {
                statusText.textContent = 'Offline';
            }
        }
    }

    async function loadChat(conversationId, name) {
        try {
            const response = await fetch(`{{ url('/bidan/konsultasi') }}/${conversationId}/messages`);
            const data = await response.json();
            if (data.success) {
                renderMessages(data.messages || []);
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    function renderMessages(messages) {
        const messageList = document.getElementById('messageList');
        if (!messageList) return;

        let html = '';
        let lastDate = '';

        if (messages.length === 0) {
            html = `
                <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#aaa; padding:40px;">
                    <div style="font-size:48px; margin-bottom:12px;">👋</div>
                    <div style="font-size:16px; font-weight:500; color:#888;">Mulai percakapan</div>
                    <div style="font-size:13px; color:#aaa; margin-top:4px;">Kirim pesan pertama ke ${escapeHtml(currentConversationName)}</div>
                </div>
            `;
        } else {
            messages.forEach((msg) => {
                const isOwn = msg.sender_type === 'bidan';
                const msgDate = new Date(msg.created_at).toDateString();
                const time = new Date(msg.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' });

                if (msgDate !== lastDate) {
                    const dateStr = formatDateSeparator(msg.created_at);
                    html += `<div class="wa-date-separator"><span>${dateStr}</span></div>`;
                    lastDate = msgDate;
                }

                html += `
                    <div class="wa-message ${isOwn ? 'sent' : 'received'}">
                        <div class="wa-message-bubble">
                            <div class="wa-message-text">${escapeHtml(msg.message).replace(/\n/g, '<br>')}</div>
                            <div class="wa-message-meta">
                                <span>${time}</span>
                                ${isOwn ? '<i class="bi bi-check2-all wa-read-check"></i>' : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        messageList.innerHTML = html;
        scrollToBottom();
    }

    async function sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input?.value.trim();
        if (!message || !currentConversationId) return;

        input.value = '';
        input.style.height = 'auto';

        const messageList = document.getElementById('messageList');
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' });
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

        try {
            const response = await fetch('{{ route("bidan.konsultasi.send_message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ conversation_id: currentConversationId, message })
            });

            const data = await response.json();
            if (data.success) {
                optMsg.querySelector('.bi-clock').className = 'bi bi-check2-all wa-read-check';
                await loadChat(currentConversationId, currentConversationName);
            } else {
                optMsg.remove();
                alert('Gagal mengirim pesan');
            }
        } catch (error) {
            console.error('Error sending:', error);
            optMsg.remove();
        }
    }

    function handleKeyDown(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    }

    function setupInputHandlers() {
        const input = document.getElementById('messageInput');
        if (!input) return;
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
        input.focus();
    }

    function setupFirebaseListener() {
        if (firebaseUnsubscribe) firebaseUnsubscribe();
        if (typeof window.MomSpireChat !== 'undefined' && window.MomSpireChat.isReady()) {
            window.MomSpireChat.setCurrentUser(bidanId, 'bidan');
            firebaseUnsubscribe = window.MomSpireChat.listenToMessages(`pengguna_bidan_${bidanId}`, function(messages) {
                renderMessages(messages);
            });
        }
    }

    function formatDateSeparator(dateStr) {
        const date = new Date(dateStr);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        const dateOnly = new Date(date.toLocaleString('id-ID', { timeZone: 'Asia/Jakarta', year: 'numeric', month: '2-digit', day: '2-digit' }));
        const todayOnly = new Date(today.toLocaleString('id-ID', { timeZone: 'Asia/Jakarta', year: 'numeric', month: '2-digit', day: '2-digit' }));
        const yesterdayOnly = new Date(yesterday.toLocaleString('id-ID', { timeZone: 'Asia/Jakarta', year: 'numeric', month: '2-digit', day: '2-digit' }));

        if (dateOnly.getTime() === todayOnly.getTime()) return 'Hari ini';
        if (dateOnly.getTime() === yesterdayOnly.getTime()) return 'Kemarin';
        return new Date(dateStr).toLocaleDateString('id-ID', { timeZone: 'Asia/Jakarta', day: 'numeric', month: 'long', year: 'numeric' });
    }

    function scrollToBottom() {
        const ml = document.getElementById('messageList');
        if (ml) ml.scrollTop = ml.scrollHeight;
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
        console.log('[Bidan Chat] Ready!');
    });
</script>
@endpush