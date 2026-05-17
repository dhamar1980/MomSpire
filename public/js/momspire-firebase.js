/**
 * MomSpire Firebase Service
 * Real-time messaging service like WhatsApp
 *
 * Firebase Project: momspire
 * Database URL: https://momspire-default-rtdb.firebaseio.com
 */

// Firebase configuration dari Firebase Console
const firebaseConfig = {
    apiKey: "AIzaSyCPg3wsZCxK6Bjc1_YibQmi6jir_3zhG1Q",
    authDomain: "momspire.firebaseapp.com",
    databaseURL: "https://momspire-default-rtdb.firebaseio.com",
    projectId: "momspire",
    storageBucket: "momspire.firebasestorage.app",
    messagingSenderId: "14853541777",
    appId: "1:14853541777:web:e41071557361f2ad159430"
};

// Firebase references
let app = null;
let database = null;
let firebaseInitialized = false;

// Load Firebase SDK from CDN
function loadFirebaseSDK() {
    return new Promise((resolve) => {
        // Check if already loaded
        if (window.firebase && window.firebase.database) {
            resolve(true);
            return;
        }

        console.log('[Firebase] Loading SDK from CDN...');

        // Load Firebase compat SDK
        const script = document.createElement('script');
        script.src = 'https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js';
        script.async = true;

        script.onload = () => {
            console.log('[Firebase] App SDK loaded');

            const dbScript = document.createElement('script');
            dbScript.src = 'https://www.gstatic.com/firebasejs/10.7.0/firebase-database-compat.js';
            dbScript.async = true;

            dbScript.onload = () => {
                console.log('[Firebase] Database SDK loaded');
                resolve(true);
            };

            dbScript.onerror = () => {
                console.error('[Firebase] Failed to load Database SDK');
                resolve(false);
            };

            document.head.appendChild(dbScript);
        };

        script.onerror = () => {
            console.error('[Firebase] Failed to load Firebase SDK');
            resolve(false);
        };

        document.head.appendChild(script);
    });
}

// Initialize Firebase
async function initFirebase() {
    const loaded = await loadFirebaseSDK();

    if (!loaded || !window.firebase) {
        console.warn('[Firebase] SDK not available - using Laravel API fallback');
        firebaseInitialized = false;
        return false;
    }

    try {
        console.log('[Firebase] Initializing with config:', firebaseConfig.databaseURL);

        // Initialize Firebase with config
        app = window.firebase.initializeApp(firebaseConfig);
        database = window.firebase.database();

        console.log('[Firebase] ✓ Connected to momspire project');
        console.log('[Firebase] ✓ Database: ' + firebaseConfig.databaseURL);

        firebaseInitialized = true;
        return true;
    } catch (error) {
        console.error('[Firebase] ✗ Initialization failed:', error);
        firebaseInitialized = false;
        return false;
    }
}

// Firebase paths
const PATHS = {
    conversations: 'conversations',
    messages: (convKey) => `messages/${convKey}`,
    users: 'users',
    notifications: (userId) => `notifications/${userId}`,
    online: 'online'
};

/**
 * MomSpire Chat Service
 */
class MomSpireChatService {
    constructor() {
        this.listeners = new Map();
        this.currentUserId = null;
        this.currentUserRole = null;
        this.initialized = false;
        this.initPromise = null;
    }

    async init() {
        if (this.initPromise) {
            return this.initPromise;
        }

        this.initPromise = this._doInit();
        return this.initPromise;
    }

    async _doInit() {
        if (this.initialized) return true;

        this.initialized = await initFirebase();
        return this.initialized;
    }

    setCurrentUser(userId, role) {
        this.currentUserId = userId;
        this.currentUserRole = role;

        // Set online status asynchronously (don't block)
        if (this.initialized && database) {
            this.setUserOnline(userId, true).catch(err => {
                console.warn('[Firebase] Set online failed:', err);
            });
        }
    }

    async setUserOnline(userId, isOnline = true) {
        if (!database || !this.initialized) {
            console.log('[Firebase] Skipping online status - not connected');
            return;
        }

        try {
            const timestamp = window.firebase.database.ServerValue.TIMESTAMP;
            await database.ref(`${PATHS.online}/${userId}`).update({
                online: isOnline,
                lastSeen: timestamp,
                role: this.currentUserRole
            });
            console.log('[Firebase] User online status updated:', isOnline);
        } catch (err) {
            console.warn('[Firebase] Online status error (non-critical):', err.message);
        }
    }

    getUserOnlineStatus(userId, callback) {
        if (!database || !this.initialized) return () => {};

        try {
            const statusRef = database.ref(`${PATHS.online}/${userId}`);

            statusRef.on('value', (snapshot) => {
                const data = snapshot.val();
                callback(data?.online || false, data?.lastSeen);
            }, (error) => {
                console.warn('[Firebase] Online status listener error:', error);
                callback(false, null);
            });

            return () => {
                try {
                    statusRef.off();
                } catch (e) {}
            };
        } catch (err) {
            console.warn('[Firebase] Get online status error:', err.message);
            return () => {};
        }
    }

    async getOrCreateConversation(penggunaId, professionalType, professionalId) {
        const convKey = `${penggunaId}_${professionalType}_${professionalId}`;

        if (!database || !this.initialized) {
            console.log('[Firebase] Skipping Firebase conversation - using Laravel API');
            return convKey;
        }

        try {
            const timestamp = window.firebase.database.ServerValue.TIMESTAMP;

            await database.ref(`${PATHS.conversations}/${convKey}`).update({
                pengguna_id: penggunaId,
                professional_type: professionalType,
                professional_id: professionalId,
                created_at: timestamp,
                last_message: '',
                last_message_at: timestamp
            });

            console.log('[Firebase] Conversation created/updated:', convKey);
        } catch (error) {
            console.warn('[Firebase] Firebase conversation error (non-critical):', error.message);
        }

        return convKey;
    }

    async sendMessage(conversationKey, senderType, senderId, message, senderName = '') {
        if (!message.trim()) return null;

        if (!database || !this.initialized) {
            console.log('[Firebase] Firebase not ready, message saved via Laravel API');
            return null;
        }

        try {
            const timestamp = window.firebase.database.ServerValue.TIMESTAMP;

            // Push new message
            const newMsgRef = database.ref(`${PATHS.messages}/${conversationKey}`).push();
            await newMsgRef.set({
                sender_type: senderType,
                sender_id: senderId,
                sender_name: senderName,
                message: message.trim(),
                timestamp: timestamp,
                is_read: false,
                conversation_key: conversationKey
            });

            // Update conversation last message
            await database.ref(`${PATHS.conversations}/${conversationKey}`).update({
                last_message: message.trim(),
                last_message_at: timestamp
            });

            console.log('[Firebase] Message sent to:', conversationKey);
            return newMsgRef.key;
        } catch (error) {
            console.warn('[Firebase] Firebase send error (non-critical):', error.message);
            return null;
        }
    }

    listenToMessages(conversationKey, callback) {
        if (!database || !this.initialized) {
            console.log('[Firebase] Firebase not ready for message listening');
            return () => {};
        }

        try {
            const messagesRef = database.ref(`${PATHS.messages}/${conversationKey}`);

            messagesRef.on('value', (snapshot) => {
                try {
                    const messages = [];
                    snapshot.forEach((child) => {
                        const msg = child.val();
                        msg.id = child.key;
                        messages.push(msg);
                    });
                    messages.sort((a, b) => (a.timestamp || 0) - (b.timestamp || 0));
                    callback(messages);
                } catch (err) {
                    console.warn('[Firebase] Message parse error:', err);
                }
            }, (error) => {
                console.warn('[Firebase] Messages listener error (non-critical):', error.message);
            });

            const listenerId = `${conversationKey}_messages`;
            this.listeners.set(listenerId, () => {
                try {
                    messagesRef.off();
                } catch (e) {}
            });

            return () => {
                try {
                    messagesRef.off();
                } catch (e) {}
                this.listeners.delete(listenerId);
            };
        } catch (err) {
            console.warn('[Firebase] Listen setup error:', err.message);
            return () => {};
        }
    }

    listenToConversations(userId, role, callback) {
        if (!database || !this.initialized) return () => {};

        try {
            const conversationsRef = database.ref(PATHS.conversations);

            conversationsRef.on('value', (snapshot) => {
                try {
                    const conversations = [];
                    snapshot.forEach((child) => {
                        const conv = child.val();
                        conv.key = child.key;

                        if (role === 'pengguna' && conv.pengguna_id === userId) {
                            conversations.push(conv);
                        } else if ((role === 'bidan' || role === 'dokter') && conv.professional_id === userId) {
                            conversations.push(conv);
                        }
                    });

                    conversations.sort((a, b) => (b.last_message_at || 0) - (a.last_message_at || 0));
                    callback(conversations);
                } catch (err) {
                    console.warn('[Firebase] Conversation parse error:', err);
                }
            }, (error) => {
                console.warn('[Firebase] Conversations listener error:', error.message);
            });

            const listenerId = `${userId}_conversations`;
            this.listeners.set(listenerId, () => {
                try {
                    conversationsRef.off();
                } catch (e) {}
            });

            return () => {
                try {
                    conversationsRef.off();
                } catch (e) {}
                this.listeners.delete(listenerId);
            };
        } catch (err) {
            console.warn('[Firebase] Conversations setup error:', err.message);
            return () => {};
        }
    }

    async markAsRead(conversationKey, currentUserId) {
        if (!database || !this.initialized) return;

        try {
            const messagesRef = database.ref(`${PATHS.messages}/${conversationKey}`);
            const snapshot = await messagesRef.once('value');

            const updates = {};
            snapshot.forEach((child) => {
                const msg = child.val();
                if (msg.sender_id !== currentUserId && !msg.is_read) {
                    updates[`${child.key}/is_read`] = true;
                }
            });

            if (Object.keys(updates).length > 0) {
                await messagesRef.update(updates);
                console.log('[Firebase] Messages marked as read:', Object.keys(updates).length);
            }
        } catch (error) {
            console.warn('[Firebase] Mark as read error (non-critical):', error.message);
        }
    }

    cleanup() {
        console.log('[Firebase] Cleaning up listeners...');

        this.listeners.forEach((cleanupFn) => {
            if (typeof cleanupFn === 'function') {
                try {
                    cleanupFn();
                } catch (e) {}
            }
        });
        this.listeners.clear();

        if (this.currentUserId && this.initialized) {
            this.setUserOnline(this.currentUserId, false).catch(() => {});
        }

        console.log('[Firebase] Cleanup completed');
    }

    isReady() {
        return firebaseInitialized && database !== null;
    }

    getDatabaseURL() {
        return firebaseConfig.databaseURL;
    }
}

// Singleton instance
const chatService = new MomSpireChatService();

// Auto-initialize on load (non-blocking)
(async () => {
    try {
        const ready = await chatService.init();
        if (ready) {
            console.log('[Firebase] ✓ MomSpire Chat Service ready!');
        } else {
            console.log('[Chat] Using Laravel API fallback (Firebase not connected)');
        }
    } catch (error) {
        console.log('[Chat] Using Laravel API fallback - error:', error.message);
    }
})();

// Export global (available immediately, async init)
window.MomSpireChat = {
    init: () => chatService.init(),
    setCurrentUser: (id, role) => chatService.setCurrentUser(id, role),
    sendMessage: (key, type, id, msg, name) => chatService.sendMessage(key, type, id, msg, name),
    listenToMessages: (key, cb) => chatService.listenToMessages(key, cb),
    markAsRead: (key, id) => chatService.markAsRead(key, id),
    isReady: () => chatService.isReady(),
    cleanup: () => chatService.cleanup(),
    getDatabaseURL: () => chatService.getDatabaseURL()
};

window.MOMSPIRE_PATHS = PATHS;

console.log('[Firebase] Service script loaded. Will initialize in background...');