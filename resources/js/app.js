import './bootstrap';

document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        sidebarOpen: Alpine.$persist(true),
        toggle() {
            this.sidebarOpen = ! this.sidebarOpen
        }
    });
})

