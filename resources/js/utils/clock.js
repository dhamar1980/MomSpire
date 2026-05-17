// Real-time clock for Asia/Jakarta (WIB)
export function useRealTimeClock() {
    const updateClock = (element) => {
        if (!element) return;

        const now = new Date();

        // Format WIB (Asia/Jakarta)
        const options = {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };

        element.textContent = now.toLocaleString('id-ID', options);
    };

    const startClock = (element, interval = 1000) => {
        updateClock(element);
        return setInterval(() => updateClock(element), interval);
    };

    const formatDateTime = (date = new Date()) => {
        return date.toLocaleString('id-ID', {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
    };

    const formatDate = (date = new Date()) => {
        return date.toLocaleDateString('id-ID', {
            timeZone: 'Asia/Jakarta',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    const formatTime = (date = new Date()) => {
        return date.toLocaleTimeString('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
    };

    return {
        updateClock,
        startClock,
        formatDateTime,
        formatDate,
        formatTime
    };
}