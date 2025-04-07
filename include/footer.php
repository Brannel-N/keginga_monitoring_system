<footer class="bg-green-600 text-white text-center py-2 relative">
    <p>&copy; <?php echo date('Y'); ?> Keginga Tea Farmers. All rights reserved.</p>
    <button id="scrollToTop" class="scroll-hidden fixed bottom-4 right-4 bg-green-600 text-white p-3 rounded-full shadow-lg h-14 w-14 flex items-center justify-center hover:bg-green-700 transition duration-300 cursor-pointer">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

<script>
    const scrollToTopButton = document.getElementById('scrollToTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) { 
            scrollToTopButton.classList.remove('scroll-hidden');
        } else {
            scrollToTopButton.classList.add('scroll-hidden');
        }
    });

    scrollToTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>

<style>
    .scroll-hidden {
        display: none;
    }
</style>
