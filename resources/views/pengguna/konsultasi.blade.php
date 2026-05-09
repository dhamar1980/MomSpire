@extends('pengguna.master')

@section('title', 'Konsultasi - MomSpire')
@section('header_title', 'Konsultasi')
@section('header_subtitle', '')

@push('head')
<script>
    window.__MOMSPIRE_HEADER_TITLE_LOCK = 'Konsultasi';
</script>
<style>
    .pengguna-dashboard-shell {
        position: relative;
        isolation: isolate;
        background: linear-gradient(180deg, #ffffff 0%, #f0f4f8 100%);
        min-height: 100vh;
    }

    .pengguna-dashboard-shell::before {
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

    .pengguna-dashboard-shell::after {
        content: '';
        position: fixed;
        inset: 0;
        background-image: linear-gradient(rgba(15, 23, 42, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, 0.015) 1px, transparent 1px);
        background-size: 42px 42px;
        opacity: .3;
        pointer-events: none;
        z-index: -1;
    }

    .konsultasi-container {
        display: grid;
        grid-template-columns: 320px minmax(0, 1fr);
        gap: 20px;
        min-height: calc(100vh - 200px);
    }

    .contacts-panel,
    .chat-panel {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .contacts-header {
        padding: 18px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        background: linear-gradient(135deg, rgba(7, 94, 84, 0.05), rgba(230, 57, 128, 0.04));
    }

    .contacts-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .contacts-subtitle {
        margin: 4px 0 0;
        font-size: 12px;
        color: #64748b;
    }

    .contacts-search {
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .contacts-search i {
        color: #94a3b8;
        font-size: 14px;
    }

    .contacts-search input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        font-size: 13px;
        color: #0f172a;
    }

    .contacts-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .contact-section {
        margin-bottom: 14px;
    }

    .contact-section-title {
        padding: 6px 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #94a3b8;
    }

    .contact-item {
        padding: 12px 10px;
        border-radius: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        position: relative;
        margin-bottom: 8px;
    }

    .contact-item:hover {
        background: rgba(230, 57, 128, 0.05);
        border-color: rgba(230, 57, 128, 0.14);
    }

    .contact-item.active {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.1), rgba(107, 66, 193, 0.05));
        border-left: 4px solid #e63980;
    }

    .contact-avatar,
    .chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        font-size: 14px;
        flex-shrink: 0;
    }

    .contact-avatar.bidan,
    .chat-avatar.bidan {
        background: linear-gradient(135deg, #6f42c1, #a855f7);
    }

    .contact-avatar.dokter,
    .chat-avatar.dokter {
        background: linear-gradient(135deg, #00b894, #00d4aa);
    }

    .contact-info {
        flex: 1;
        min-width: 0;
    }

    .contact-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .contact-role {
        font-size: 12px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .contact-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: #22c55e;
        margin-top: 4px;
    }

    .contact-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #22c55e;
    }

    .chat-shell {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 200px);
    }

    .chat-topbar {
        padding: 14px 18px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, rgba(7, 94, 84, 0.04), rgba(16, 185, 129, 0.03));
    }

    .chat-topbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .chat-subtitle {
        margin: 2px 0 0;
        font-size: 12px;
        color: #64748b;
    }

    .chat-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-action-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        background: #fff;
        color: #64748b;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .chat-window {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 18px 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
        background:
            radial-gradient(circle at top left, rgba(7, 94, 84, 0.05), transparent 22%),
            radial-gradient(circle at bottom right, rgba(230, 57, 128, 0.06), transparent 24%),
            linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .chat-messages::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(15, 23, 42, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, 0.03) 1px, transparent 1px);
        background-size: 28px 28px;
        opacity: .12;
        pointer-events: none;
    }

    .message-row {
        display: flex;
        position: relative;
        z-index: 1;
    }

    .message-row.sent {
        justify-content: flex-end;
    }

    .message-bubble {
        max-width: min(72%, 480px);
        padding: 10px 12px 8px;
        border-radius: 14px;
        word-break: break-word;
        font-size: 13px;
        line-height: 1.45;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.06);
    }

    .message-bubble.received {
        background: #fff;
        color: #1e293b;
        border-top-left-radius: 4px;
    }

    .message-bubble.sent {
        background: #d9fdd3;
        color: #0f172a;
        border-top-right-radius: 4px;
    }

    .message-meta {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        margin-top: 4px;
        font-size: 10px;
        color: rgba(15, 23, 42, 0.58);
    }

    .empty-state {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 12px;
        color: #94a3b8;
        padding: 24px;
    }

    .empty-state-icon {
        font-size: 52px;
    }

    .empty-state-text {
        text-align: center;
    }

    .empty-state-text h6 {
        margin: 0;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
    }

    .empty-state-text p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #94a3b8;
    }

    .chat-input-area {
        padding: 14px;
        border-top: 1px solid rgba(148, 163, 184, 0.12);
        background: #f8fafc;
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    .chat-input-wrap {
        flex: 1;
        display: flex;
        gap: 8px;
        align-items: flex-end;
        background: #fff;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        padding: 8px 10px 8px 14px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    }

    .chat-input-field {
        flex: 1;
        border: none;
        padding: 8px 0;
        font-size: 13px;
        resize: none;
        max-height: 108px;
        font-family: inherit;
        background: transparent;
    }

    .chat-input-field:focus {
        outline: none;
    }

    .btn-send {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #075e54;
        color: #fff;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-send:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(7, 94, 84, 0.28);
    }

    .btn-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    @media (max-width: 1024px) {
        .konsultasi-container {
            grid-template-columns: 280px 1fr;
        }
    }

    @media (max-width: 768px) {
        .konsultasi-container {
            grid-template-columns: 1fr;
            height: auto;
        }

        .contacts-panel {
            max-height: 300px;
        }

        .message-bubble {
            max-width: 80%;
        }

        .chat-panel {
            height: 500px;
        }
    }
</style>
@endpush

@section('content')
<div class="pengguna-dashboard-shell" style="position: relative; z-index: 0;">
    <div class="konsultasi-container">
        <div class="contacts-panel">
            <div class="contacts-header">
                    <div class="contacts-search">
                        <i class="bi bi-search"></i>
                        <input type="text" id="contactSearch" placeholder="Cari nama bidan atau dokter">
                    </div>
            </div>

            <div class="contacts-list" id="contactsList">
                <div class="contact-section">
                    <div class="contact-section-title">Bidan</div>
                    @forelse($bidan as $b)
                        <div class="contact-item" data-contact-name="{{ strtolower($b->name) }}" onclick="selectProfessional('bidan', {{ $b->id }}, @json($b->name), event)">
                            <div class="contact-avatar bidan">B</div>
                            <div class="contact-info">
                                <div class="contact-name">{{ $b->name }}</div>
                                <div class="contact-role">Bidan</div>
                                <div class="contact-status"><span class="contact-dot"></span>Online</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small px-2 pb-2">Belum ada bidan.</div>
                    @endforelse
                </div>

                <div class="contact-section">
                    <div class="contact-section-title">Dokter</div>
                    @forelse($dokter as $d)
                        <div class="contact-item" data-contact-name="{{ strtolower($d->name) }}" onclick="selectProfessional('dokter', {{ $d->id }}, @json($d->name), event)">
                            <div class="contact-avatar dokter">D</div>
                            <div class="contact-info">
                                <div class="contact-name">{{ $d->name }}</div>
                                <div class="contact-role">Dokter</div>
                                <div class="contact-status"><span class="contact-dot"></span>Online</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small px-2 pb-2">Belum ada dokter.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="chat-panel chat-shell">
            <div id="chatContainer" class="chat-window">
                <div class="empty-state">
                    <div class="empty-state-icon">💬</div>
                    <div class="empty-state-text">
                        <h6>Chat langsung dengan bidan atau dokter</h6>
                        <p>Pilih salah satu kontak di kiri untuk mulai konsultasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentConversation = null;
    let currentProfessional = null;

    async function selectProfessional(type, id, name, event) {
        document.querySelectorAll('.contact-item').forEach((item) => item.classList.remove('active'));
        if (event?.currentTarget) {
            event.currentTarget.classList.add('active');
        }

        try {
            const response = await fetch('{{ route("pengguna.konsultasi.start") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    professional_type: type,
                    professional_id: id,
                })
            });

            const data = await response.json();
            if (data.success) {
                currentConversation = data.conversation_id;
                currentProfessional = { type, id, name };
                loadChat(data.conversation_id, type, name);
            }
        } catch (error) {
            console.error('Error starting consultation:', error);
        }
    }

    async function loadChat(conversationId, type, name) {
        try {
            const response = await fetch(`/pengguna/konsultasi/${conversationId}/messages`);
            const data = await response.json();

            if (data.success) {
                renderChat(data.messages || [], type, name, conversationId);
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    function renderChat(messages, type, name, conversationId) {
        let html = `
            <div class="chat-topbar">
                <div class="chat-topbar-left">
                    <div class="chat-avatar ${type}">${type === 'bidan' ? 'B' : 'D'}</div>
                    <div>
                        <p class="chat-title">${escapeHtml(name)}</p>
                        <p class="chat-subtitle">${type === 'bidan' ? 'Bidan' : 'Dokter'} • online</p>
                    </div>
                </div>
                <div class="chat-actions">
                    <button type="button" class="chat-action-btn" aria-label="Panggilan suara"><i class="bi bi-telephone"></i></button>
                    <button type="button" class="chat-action-btn" aria-label="Menu"><i class="bi bi-three-dots-vertical"></i></button>
                </div>
            </div>
            <div class="chat-window">
                <div class="chat-messages" id="messageList">
        `;

        if (messages.length === 0) {
            html += `
                <div class="empty-state">
                    <div class="empty-state-icon">🟢</div>
                    <div class="empty-state-text">
                        <h6>Chat siap digunakan</h6>
                        <p>Kirim pesan pertama ke ${escapeHtml(name)}</p>
                    </div>
                </div>
            `;
        } else {
            messages.forEach((msg) => {
                const isOwn = msg.sender_type === 'pengguna';
                const time = new Date(msg.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                html += `
                    <div class="message-row ${isOwn ? 'sent' : 'received'}">
                        <div class="message-bubble ${isOwn ? 'sent' : 'received'}">
                            <div>${escapeHtml(msg.message).replace(/\n/g, '<br>')}</div>
                            <div class="message-meta">
                                <span>${time}</span>
                                ${isOwn ? '<i class="bi bi-check2-all"></i>' : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        html += `
                </div>
                <div class="chat-input-area">
                    <div class="chat-input-wrap">
                        <textarea class="chat-input-field" id="messageInput" placeholder="Ketik pesan..." rows="1"></textarea>
                        <button class="btn-send" id="sendBtn" onclick="sendMessage(${conversationId})" aria-label="Kirim pesan"><i class="bi bi-send-fill"></i></button>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('chatContainer').innerHTML = html;

        const input = document.getElementById('messageInput');
        input?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage(conversationId);
            }
        });

        input?.addEventListener('input', () => {
            input.style.height = 'auto';
            input.style.height = Math.min(input.scrollHeight, 108) + 'px';
        });

        const messagesContainer = document.getElementById('messageList');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    async function sendMessage(conversationId) {
        const input = document.getElementById('messageInput');
        const message = input?.value.trim();

        if (!message) {
            return;
        }

        try {
            const response = await fetch('{{ route("pengguna.konsultasi.send_message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    conversation_id: conversationId,
                    message: message,
                })
            });

            const data = await response.json();
            if (data.success) {
                input.value = '';
                input.style.height = 'auto';

                if (currentProfessional) {
                    loadChat(conversationId, currentProfessional.type, currentProfessional.name);
                }
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    }

    document.getElementById('contactSearch')?.addEventListener('input', function (e) {
        const keyword = e.target.value.trim().toLowerCase();

        document.querySelectorAll('.contact-section').forEach((section) => {
            let visibleCount = 0;
            section.querySelectorAll('.contact-item').forEach((item) => {
                const name = item.getAttribute('data-contact-name') || '';
                const match = name.includes(keyword);
                item.style.display = match ? '' : 'none';
                if (match) {
                    visibleCount += 1;
                }
            });

            section.style.display = visibleCount > 0 ? '' : 'none';
        });
    });
</script>
@endsection
