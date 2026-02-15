<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Inicjalizacja dropdown dla dynamicznych elementów -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reinicjalizacja dropdown po załadowaniu strony
    var dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(function(dropdown) {
        new bootstrap.Dropdown(dropdown);
    });
});
</script>