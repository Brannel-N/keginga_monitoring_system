<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keginga Tea Farmers</title>
    <!-- <link rel="stylesheet" href="./styles.css"> -->
     <!-- Font Awesome Icons -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Tailwind CSS -->
     <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
     <style>
        @keyframes fade-in-down {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 1s ease-out;
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out;
        }
    </style>
</head>
<body>
    <!-- Navbar section -->
    <header>
        <nav x-data="{ open: false }"  class="flex h-auto w-full bg-green-600 text-white shadow-sm justify-between
            md:h-16">
            <div class="flex w-full justify-between p-4 lg:px-24">
                <div :class="open ? 'hidden':'flex'" 
                class="flex items-center font-semibold md:px-1 md:flex md:items-center md:justify-center"
                x-transition:enter="transition ease-out duration-300">
                    <a href="./index.php">Keginga Tea Farmers</a>
                </div>
                <div  
                x-show="open" x-transition:enter="transition ease-in-out duration-300"
                class="flex flex-col w-full h-auto py-4 px-4 bg-green-600 text-white
                md:hidden">
                    <div class="flex flex-col gap-2">
                        <a href="./index.php" class="border-b border-gray-200 p-2 hover:bg-white hover:text-black rounded-sm hover:border-none hover:shadow-lg">Home</a>
                        <a href="#about-us" class="border-b border-gray-200 p-2 hover:bg-white hover:text-black rounded-sm hover:border-none hover:shadow-lg">About Us</a>
                        <a href="#contacts" class="border-b border-gray-200 p-2 hover:bg-white hover:text-black rounded-sm hover:border-none hover:shadow-lg">Contacts</a>
                        <a href="#getting-started" class="border-b border-gray-200 p-2 hover:bg-white hover:text-black rounded-sm hover:border-none hover:shadow-lg">Get Started</a>
                    </div>
                </div>
                <div class="hidden space-x-8 items-center justify-evenly font-semibold
                md:flex">
                    <a href="./index.php" class="border-b border-gray-200 p-2 hover:bg-white hover:text-black rounded-sm hover:border-none hover:shadow-lg">Home</a>
                    <a href="#about-us" class="border-b border-gray-200 p-2 hover:bg-white hover:text-black rounded-sm hover:border-none hover:shadow-lg">About Us</a>
                    <a href="#contacts" class="border-b border-gray-200 p-2 hover:bg-white hover:text-black rounded-sm hover:border-none hover:shadow-lg">Contacts</a>
                    <a href="#getting-started" class="bg-white border-b border-gray-200 py-2 hover:bg-green-500 text-green-500 hover:text-white rounded-full px-4 hover:border-none hover:shadow-lg">Get Started</a>
                </div>
                <button class="text-gray-500 w-10 h-10 relative focus:outline-none bg-white
                                md:hidden
                                " @click="open = !open">
                    <span class="sr-only">Open main menu</span>
                    <div class="block w-5 absolute left-1/2 top-1/2   transform  -translate-x-1/2 -translate-y-1/2 bg-green-600">
                        <span aria-hidden="true" class="block absolute h-0.5 w-5 bg-current transform transition duration-500 ease-in-out" :class="{'rotate-45': open,' -translate-y-1.5': !open }"></span>
                        <span aria-hidden="true" class="block absolute  h-0.5 w-5 bg-current   transform transition duration-500 ease-in-out" :class="{'opacity-0': open } "></span>
                        <span aria-hidden="true" class="block absolute  h-0.5 w-5 bg-current transform  transition duration-500 ease-in-out" :class="{'-rotate-45': open, ' translate-y-1.5': !open}"></span>
                    </div>
                </button>
            </div>
        </nav>
    </header>
    <!-- Hero section -->
    <section class="relative h-[calc(100vh-4rem)] w-full">
        <img src="./imgz/hero-image.jpg" alt="Hero Image" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-black opacity-60 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in-down">Welcome to Keginga Tea Farmers</h1>
                <p class="text-lg md:text-2xl animate-fade-in-up">Empowering tea farmers with modern tools for growth and success.</p>
            </div>
        </div>
    </section>
    <!-- About Us section -->
    <section id="about-us" class="py-16 bg-green-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-green-700 mb-8">About Us</h2>
            <p class="text-center text-lg text-green-800 mb-12">
                Keginga Farmers is a community-driven initiative dedicated to supporting and empowering farmers in the Keginga region. 
                Our mission is to promote sustainable farming practices, improve livelihoods, and connect farmers with the resources they need to thrive.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mission -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Our Mission</h3>
                    <p class="text-green-800">
                        To empower Keginga farmers by providing access to education, modern farming techniques, and market opportunities. 
                        We aim to create a sustainable and prosperous farming community that can feed its people and contribute to the broader agricultural sector.
                    </p>
                </div>
                <!-- Vision -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Our Vision</h3>
                    <p class="text-green-800">
                        We envision a future where Keginga farmers are self-reliant, equipped with the knowledge and tools to practice sustainable agriculture, 
                        and able to access fair markets for their produce.
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                <!-- History -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Our History</h3>
                    <p class="text-green-800">
                        Founded in 1978, Keginga Farmers has worked closely with local farmers, government agencies, and NGOs to implement programs 
                        that improve crop yields, promote environmental sustainability, and enhance the quality of life for farmers and their families.
                    </p>
                </div>
                <!-- Objectives -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Our Objectives</h3>
                    <ul class="list-disc list-inside text-green-800">
                        <li>Provide training and resources on sustainable farming practices.</li>
                        <li>Promote the use of modern technology in agriculture.</li>
                        <li>Support the development of community-based farming cooperatives.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- New Section: Getting Started -->
    <section id="getting-started" class="py-16 bg-green-200">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-green-700 mb-8">Getting Started</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <i class="fas fa-user-plus text-green-600 text-4xl mb-4"></i>
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Sign Up</h3>
                    <p class="text-green-800">
                        Create an account to join our community and access exclusive resources tailored for Keginga farmers.
                    </p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <i class="fas fa-laptop text-green-600 text-4xl mb-4"></i>
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Access the Platform</h3>
                    <p class="text-green-800">
                        Log in to explore tools, resources, and updates designed to help you grow and succeed in your farming journey.
                    </p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <i class="fas fa-check-circle text-green-600 text-4xl mb-4"></i>
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Confirm Tea Sale</h3>
                    <p class="text-green-800">
                        Easily confirm your tea sales and track your progress through our user-friendly platform.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Contacts section -->
    <section id="contacts" class="py-16 bg-green-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-green-700 mb-8">Contact Us</h2>
            <p class="text-center text-lg text-green-800 mb-12">
                Have questions or need assistance? Reach out to us, and we'll be happy to help.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Contact Form -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <form action="./processes/contact_form_handler.php" method="POST">
                        <div class="mb-4">
                            <label for="name" class="block text-green-700 font-semibold mb-2">Name</label>
                            <input type="text" id="name" name="name" class="w-full p-3 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-green-700 font-semibold mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full p-3 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-green-700 font-semibold mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" class="w-full p-3 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-300">
                            Send Message
                        </button>
                    </form>
                </div>
                <!-- Contact Information -->
                <div class="flex flex-col justify-center items-center text-center bg-white p-6 rounded-lg shadow-lg">
                    <div class="mb-6">
                        <i class="fas fa-phone-alt text-green-600 text-4xl"></i>
                        <p class="text-green-800 mt-2">+254110028413</p>
                    </div>
                    <div class="mb-6">
                        <i class="fas fa-envelope text-green-600 text-4xl"></i>
                        <p class="text-green-800 mt-2">brannelnyakundi@gmail.com</p>
                    </div>
                    <div>
                        <i class="fas fa-map-marker-alt text-green-600 text-4xl"></i>
                        <p class="text-green-800 mt-2">Keginga, Kisii County</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer section -->
    <?php include './include/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</body>
</html>