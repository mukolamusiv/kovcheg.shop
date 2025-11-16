document.addEventListener('DOMContentLoaded', () => {
    let buffer = '';
    let lastTime = Date.now();

    document.addEventListener('keypress', (e) => {
        const now = Date.now();

        if (now - lastTime > 50) {
            buffer = '';
        }

        lastTime = now;

        if (e.key === 'Enter') {
            Livewire.dispatch('barcode-scanned', buffer);
            buffer = '';
            return;
        }

        buffer += e.key;
    });
});
