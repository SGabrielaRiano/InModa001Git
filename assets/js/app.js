// assets/js/app.js
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar submenu toggles (if any)
    document.querySelectorAll('.sidebar-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('active');
            const submenu = parent.querySelector('.submenu');
            if (submenu) submenu.classList.toggle('active');
        });
    });

    // Notes modal open/close
    const notesBtn = document.querySelectorAll('.open-notes');
    const overlay = document.querySelector('.notes-modal-overlay');
    if (notesBtn && overlay) {
        notesBtn.forEach(b => b.addEventListener('click', () => overlay.classList.add('active')));
        document.querySelectorAll('.btn-cancel-notes').forEach(b => b.addEventListener('click', () => overlay.classList.remove('active')));
    }
});
