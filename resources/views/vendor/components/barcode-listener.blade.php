<script>
document.addEventListener('barcode-scanned', (event) => {
    Livewire.dispatch('barcode-scanned', { barcode: event.detail });
});
</script>
