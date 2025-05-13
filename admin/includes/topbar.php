<div class="w-full flex flex-col md:flex-row items-center gap-2 md:justify-between md:items-center p-4 text-green-600">
    <div class="text-lg uppercase md:capitalize font-bold">
        Keginga E-Record Monitoring System
    </div>
    <div class="flex items-center space-x-4">
        <a href="../processes/logout.php"
            class="px-4 py-2 bg-red-300 hover:bg-red-500 text-red-600 hover:text-white rounded-full text-sm">Logout</a>
        <div class="flex items-center space-x-2">
            <span class="text-sm font-medium">Hi, <?php echo htmlspecialchars($_SESSION['fullName']); ?></span>
            <img src="https://thumbs2.imgbox.com/5d/c8/YEgf9i1F_t.png" alt="Profile Avatar"
                class="w-10 h-10 rounded-full">
        </div>
    </div>
</div>