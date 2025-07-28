
    <!-- Main Content -->
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-serif font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Add New User
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?php echo base_url('admin/users'); ?>" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Back to Users
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form method="POST" class="space-y-6 p-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800" placeholder="+6281234567890">
                </div>

                <div>
                    <label for="show_gift_section" class="block text-sm font-medium text-gray-700">Show Gift Section</label>
                    <input type="checkbox" id="show_gift_section" name="show_gift_section" value="1">
                </div>

                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty</label>
                    <input type="number" id="difficulty" name="difficulty" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                </div>  

                <div class="flex justify-end space-x-3">
                    <a href="<?php echo base_url('admin/users'); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-800 hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-800">
                        Submit User
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html> 