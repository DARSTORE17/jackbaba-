// Seller Sidebar Toggler
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            // Save the state to localStorage
            const isActive = sidebar.classList.contains('active');
            localStorage.setItem('sidebarActive', isActive);
        });

        // Restore sidebar state on page load
        const savedSidebarState = localStorage.getItem('sidebarActive');
        if (savedSidebarState === 'true') {
            sidebar.classList.add('active');
        }
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (event) {
        if (window.innerWidth <= 768 && sidebar && sidebarToggle) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('active');
                localStorage.setItem('sidebarActive', 'false');
            }
        }
    });
});
