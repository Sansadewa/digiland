
    <!-- Main Content -->
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-serif font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Add New Gift
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?php echo base_url('admin/gifts'); ?>" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Back to Gifts
                </a>
            </div>
        </div>

        <!-- Add Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form method="POST" class="space-y-6 p-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Gift Name *</label>
                    <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-800 focus:border-amber-800" placeholder="e.g., Coffee Maker">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-800 focus:border-amber-800" placeholder="Brief description of the gift..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price *</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" id="price" name="price" required min="0" step="1000" class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-800 focus:border-amber-800" placeholder="150000">
                        </div>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="category" name="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-800 focus:border-amber-800">
                            <option value="">Select Category</option>
                            <option value="Kitchen">Kitchen</option>
                            <option value="Home Decor">Home Decor</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Bedding">Bedding</option>
                            <option value="Bath">Bath</option>
                            <option value="Dining">Dining</option>
                            <option value="Appliances">Appliances</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL *</label>
                    <input type="url" id="image_url" name="image_url" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-800 focus:border-amber-800" placeholder="https://example.com/image.jpg">
                    <p class="mt-1 text-sm text-gray-500">Enter a direct URL to the gift image</p>
                </div>

                <div>
                    <label for="store_url" class="block text-sm font-medium text-gray-700">Store URL</label>
                    <input type="url" id="store_url" name="store_url" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-800 focus:border-amber-800" placeholder="https://store.com/product">
                    <p class="mt-1 text-sm text-gray-500">Link to where guests can purchase this gift</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="<?php echo base_url('admin/gifts'); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-800 hover:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-800">
                        Add Gift
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>
