<div x-data="{ open: false }" class="relative">
    <!-- Mobile Menu Button -->
    <button @click="open = !open"
        class="p-2 text-white focus:outline-none bg-green-600 rounded absolute top-4 left-2 md:hidden z-30">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
        </svg>
    </button>

    <!-- Sidebar -->
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="h-screen fixed top-0 left-0 bg-green-600 text-white transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 z-20">
        <div class="p-4 border-b border-white">
            <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
                <h2 class="text-lg font-bold">Admin Dashboard</h2>
            <?php else: ?>
                <h2 class="text-lg font-bold">Farmer Dashboard</h2>
            <?php endif; ?>
        </div>
        <nav class="mt-4">
            <ul class="list-none">
                <li>
                    <a href="index.php" @click="open = false"
                        class="block px-4 py-2 hover:bg-green-700">Dashboard</a>
                </li>
                <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
                    <li>
                        <a href="farmers.php" @click="open = false"
                            class="block px-4 py-2 hover:bg-green-700">Farmers</a>
                    </li>
                    <li>
                        <a href="sales.php" @click="open = false" class="block px-4 py-2 hover:bg-green-700">Deliveries</a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="download_history.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 focus:outline-none">
                        Download delivery History
                    </a>
                </li>
                <li>
                    <a href="../processes/logout.php" @click="open = false"
                        class="block px-4 py-2 hover:bg-green-700">Logout</a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Overlay for mobile -->
    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
