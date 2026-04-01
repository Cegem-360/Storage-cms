<script data-navigate-once>
    document.addEventListener('alpine:init', () => {
        const store = Alpine.store('sidebar');

        ['toggle', 'open', 'close'].forEach((method) => {
            const original = store[method];
            store[method] = function(...args) {
                document.body.classList.add('fi-sidebar-animating');
                original.apply(this, args);
                setTimeout(() => document.body.classList.remove('fi-sidebar-animating'), 350);
            };
        });
    });
</script>
