import './bootstrap';

import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
Alpine.start();

// Initialize Lucide Icons globally
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// Re-initialize icons on Vite HMR
if (import.meta.hot) {
    import.meta.hot.accept(() => {
        createIcons({ icons });
    });
}
