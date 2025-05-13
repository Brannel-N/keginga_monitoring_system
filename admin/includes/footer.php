<?php 
// Set the timezone to your desired region
date_default_timezone_set('Africa/Nairobi');
?>
<footer class="text-center pt-4 bg-gray-100 border-t border-gray-300">
    <p class="text-gray-600">&copy; <?php echo date('Y'); ?> Monitoring System. All rights reserved.</p>
    <p class="text-gray-600">
        <i class="fas fa-clock"></i> Current Time: <span id="current-time"><?php echo date('h:i:s A'); ?></span>
    </p>
</footer>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    function updateTime() {
        const now = new Date();
        const formattedTime = now.toLocaleTimeString('en-US', { hour12: true });
        document.getElementById('current-time').textContent = formattedTime;
    }
    setInterval(updateTime, 1000);
</script>
</footer>