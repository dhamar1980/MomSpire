# MomSpire Firebase Realtime Chat Setup Guide

## 📱 Fitur Konsultasi WhatsApp-Style

Fitur konsultasi memungkinkan pengguna (ibu hamil), bidan, dan dokter untuk berkomunikasi secara real-time seperti WhatsApp.

## 🏗️ Arsitektur

```
┌─────────────────────────────────────────────────────────────┐
│                      MomSpire App                            │
├─────────────────────────────────────────────────────────────┤
│  ┌──────────┐    ┌──────────┐    ┌──────────────┐         │
│  │ Pengguna │    │  Bidan   │    │   Dokter     │         │
│  │  (Ibu)   │    │          │    │              │         │
│  └────┬─────┘    └────┬─────┘    └──────┬───────┘         │
│       │               │                 │                   │
│       ▼               ▼                 ▼                   │
│  ┌────────────────────────────────────────────────────┐     │
│  │         WhatsApp-Style Chat Interface              │     │
│  │  • Real-time messages   • Read receipts           │     │
│  │  • Online status        • Typing indicators        │     │
│  └────────────────────────────────────────────────────┘     │
│       │               │                 │                   │
│       ▼               ▼                 ▼                   │
│  ┌────────────────────────────────────────────────────┐     │
│  │              Laravel Backend (PHP)                  │     │
│  │  • Authentication    • Message persistence          │     │
│  │  • Authorization     • Conversation management      │     │
│  └────────────────────────────────────────────────────┘     │
│       │                                               │      │
│       ▼                                               ▼      │
│  ┌───────────────┐                     ┌──────────────────┐ │
│  │    MySQL      │                     │  Firebase RTDB   │ │
│  │  (Primary DB) │                     │  (Real-time sync)│ │
│  └───────────────┘                     └──────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## 🚀 Setup Steps

### 1. Dapatkan Firebase Configuration

1. Buka [Firebase Console](https://console.firebase.google.com/u/0/project/momspire-default-rtdb/data)
2. Buka **Project Settings** → **General**
3. Scroll ke bagian **Your apps** → pilih **Web** (</>) atau buat baru
4. Copy Firebase config values:

```
apiKey: "..."
authDomain: "..."
databaseURL: "..."
projectId: "..."
storageBucket: "..."
messagingSenderId: "..."
appId: "..."
```

### 2. Update Firebase Config

Edit file `.env`:

```env
FIREBASE_API_KEY=YOUR_ACTUAL_API_KEY
FIREBASE_AUTH_DOMAIN=momspire-default-rtdb.firebaseapp.com
FIREBASE_DATABASE_URL=https://momspire-default-rtdb-default-rtdb.firebaseio.com
FIREBASE_PROJECT_ID=momspire-default-rtdb
FIREBASE_STORAGE_BUCKET=momspire-default-rtdb.appspot.com
FIREBASE_MESSAGING_SENDER_ID=YOUR_SENDER_ID
FIREBASE_APP_ID=YOUR_APP_ID
```

### 3. Enable Firebase Realtime Database

1. Di Firebase Console, buka **Build** → **Realtime Database**
2. Klik **Create Database**
3. Pilih region ( Indonesia: asia-southeast1 )
4. Start in **test mode** (untuk development)
5. Copy database URL ke `.env`

### 4. Configure Firebase Rules (Production)

Untuk production, update Firebase rules:

```json
{
  "rules": {
    "messages": {
      "$conversation_key": {
        ".read": "auth != null",
        ".write": "auth != null"
      }
    },
    "conversations": {
      "$conversation_key": {
        ".read": "auth != null",
        ".write": "auth != null"
      }
    },
    "online": {
      "$user_id": {
        ".read": "auth != null",
        ".write": "auth != null"
      }
    },
    "notifications": {
      "$user_id": {
        ".read": "auth != null",
        ".write": "auth != null"
      }
    }
  }
}
```

### 5. Install Dependencies

```bash
cd c:\xampp\htdocs\MomSpire
npm install
```

## 📁 File yang Dibuat/Dimodifikasi

### New Files:
- `public/js/momspire-firebase.js` - Firebase SDK service
- `app/Http/Controllers/FirebaseSyncController.php` - Laravel-Firebase sync
- `routes/api.php` - Firebase API routes
- `config/services.php` - Firebase configuration

### Modified Files:
- `package.json` - Added Firebase SDK dependency
- `.env` - Added Firebase credentials
- `resources/views/pengguna/konsultasi.blade.php` - WhatsApp-style UI
- `resources/views/bidan/konsultasi.blade.php` - WhatsApp-style UI
- `resources/views/dokter/konsultasi.blade.php` - WhatsApp-style UI

## 🎨 Fitur WhatsApp-Style

### User Interface:
- ✅ Green header dengan avatar dan status online
- ✅ Contact list dengan search
- ✅ Chat bubbles dengan timestamp
- ✅ Blue tick untuk pesan terkirim (sent)
- ✅ Date separator untuk grouping pesan
- ✅ Typing indicator animation
- ✅ Input dengan emoji support
- ✅ Background pattern seperti WhatsApp

### Real-time Features:
- ✅ Instant message delivery
- ✅ Online status indicators
- ✅ Read receipts (blue checkmarks)
- ✅ Typing indicators
- ✅ Unread message badges

## 🔧 Cara Testing

1. **Start Laravel server:**
   ```bash
   php artisan serve
   ```

2. **Login sebagai Pengguna:**
   - Buka `/pengguna/konsultasi`
   - Pilih bidan atau dokter untuk memulai chat

3. **Login sebagai Bidan/Dokter:**
   - Buka `/bidan/konsultasi` atau `/dokter/konsultasi`
   - Pilih conversation dari list
   - Kirim pesan balasan

4. **Test real-time (dengan Firebase):**
   - Buka 2 browser/tab
   - Login sebagai berbeda role
   - Kirim pesan dan lihat real-time update

## ⚡ Fallback Mechanism

Jika Firebase tidak dikonfigurasi atau tidak tersedia, sistem akan:
1. Menggunakan Laravel API untuk persistence
2. Polling setiap 5 detik untuk new messages
3. Tetap berfungsi normal tanpa real-time sync

## 🔒 Security

- Semua routes dilindungi middleware `auth`
- Users hanya bisa akses conversation mereka sendiri
- Sender validation di semua endpoints
- CSRF protection untuk semua POST requests

## 📞 Troubleshooting

### Firebase tidak connect?
1. Check `.env` credentials
2. Pastikan database URL benar (format: `https://PROJECT_ID-default-rtdb.firebaseio.com`)
3. Check browser console untuk error messages
4. Pastikan Firebase project aktif

### Messages tidak sync?
1. Check Laravel API routes berfungsi
2. Check MySQL connection
3. Check browser console untuk JavaScript errors
4. Clear browser cache

### Performance issues?
1. Polling interval bisa diubah di JS (default: 5 detik)
2. Firebase listener auto-cleanup saat page leave
3. Consider Firebase pagination untuk chat history panjang

## 🎯 Next Steps

1. Setup Firebase Authentication untuk production
2. Implement push notifications (FCM)
3. Add chat search functionality
4. Implement message attachments (images/files)
5. Add conversation archival

---

Created with ❤️ for MomSpire - Empowering Indonesian Mothers