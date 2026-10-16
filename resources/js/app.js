import qrScanner from './alpine/qr-scanner';
import themeSwitcher from './alpine/theme';
import ticketModal from './alpine/ticket-modal';

// Función universal global de cambio de tema (independiente de Alpine/Livewire)
window.toggleTheme = function() {
    const html = document.documentElement;
    const isDark = !html.classList.contains('dark');
    if (isDark) {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: isDark } }));
    return isDark;
};

const registerAlpineData = () => {
    if (window.Alpine) {
        window.Alpine.data('themeSwitcher', themeSwitcher);
        window.Alpine.data('ticketModal', ticketModal);
        window.Alpine.data('qrScanner', qrScanner);
    }
};

if (window.Alpine) {
    registerAlpineData();
} else {
    document.addEventListener('alpine:init', registerAlpineData);
}
