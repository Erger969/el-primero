import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Toggle de comentarios en la página home
document.addEventListener('DOMContentLoaded', function() {
    // Manejar click en botón de toggle de comentarios
    const commentToggleBtns = document.querySelectorAll('.comments-toggle-btn');
    
    commentToggleBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const postId = this.getAttribute('data-post-id');
            const commentsSection = document.querySelector(`.comments-section[data-post-id="${postId}"]`);
            
            if (commentsSection) {
                // Toggle la clase hidden
                commentsSection.classList.toggle('hidden');
                
                // Cambiar el estilo del botón para indicar estado
                this.classList.toggle('bg-secondary');
                this.classList.toggle('text-white');
                this.classList.toggle('bg-gray-100');
                this.classList.toggle('dark:bg-gray-700');
                this.classList.toggle('text-gray-600');
                this.classList.toggle('dark:text-gray-300');
            }
        });
    });
});
