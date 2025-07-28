
    <!-- Main Content -->
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-serif font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Gift
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?php echo base_url('admin/gifts'); ?>" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Back to Gifts
                </a>
            </div>
        </div>

        <!-- Gift Status Info -->
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <?php if ($gift['status'] === 'available'): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Available
                        </span>
                    <?php elseif ($gift['status'] === 'booked'): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Booked
                        </span>
                    <?php elseif ($gift['status'] === 'purchased'): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Purchased
                        </span>
                    <?php endif; ?>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Current Status: <strong><?php echo ucfirst($gift['status']); ?></strong>
                        <?php if ($gift['status'] === 'booked' && $gift['booked_by_name']): ?>
                            - Booked by <?php echo $gift['booked_by_name']; ?>
                        <?php elseif ($gift['status'] === 'purchased' && $gift['purchased_by_name']): ?>
                            - Purchased by <?php echo $gift['purchased_by_name']; ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form method="POST" class="space-y-6 p-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Gift Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($gift['name']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800"><?php echo htmlspecialchars($gift['description']); ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price *</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" id="price" name="price" value="<?php echo $gift['price']; ?>" required min="0" step="100" class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                        </div>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="category" name="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                            <option value="">Select Category</option>
                            <option value="Kitchen" <?php echo ($gift['category'] === 'Kitchen') ? 'selected' : ''; ?>>Kitchen</option>
                            <option value="Home Decor" <?php echo ($gift['category'] === 'Home Decor') ? 'selected' : ''; ?>>Home Decor</option>
                            <option value="Electronics" <?php echo ($gift['category'] === 'Electronics') ? 'selected' : ''; ?>>Electronics</option>
                            <option value="Bedding" <?php echo ($gift['category'] === 'Bedding') ? 'selected' : ''; ?>>Bedding</option>
                            <option value="Bath" <?php echo ($gift['category'] === 'Bath') ? 'selected' : ''; ?>>Bath</option>
                            <option value="Dining" <?php echo ($gift['category'] === 'Dining') ? 'selected' : ''; ?>>Dining</option>
                            <option value="Appliances" <?php echo ($gift['category'] === 'Appliances') ? 'selected' : ''; ?>>Appliances</option>
                            <option value="Other" <?php echo ($gift['category'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL *</label>
                    <input type="url" id="image_url" name="image_url" value="<?php echo htmlspecialchars($gift['image_url']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                    <p class="mt-1 text-sm text-gray-500">Enter a direct URL to the gift image</p>
                </div>

                <div>
                    <label for="store_url" class="block text-sm font-medium text-gray-700">Store URL</label>
                    <input type="url" id="store_url" name="store_url" value="<?php echo htmlspecialchars($gift['store_url']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                    <p class="mt-1 text-sm text-gray-500">Link to where guests can purchase this gift</p>
                </div>

                <!-- Image Preview -->
                <?php if (!empty($gift['image_url'])): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Image</label>
                    <div class="mt-1">
                        <img src="<?php echo htmlspecialchars($gift['image_url']); ?>" alt="<?php echo htmlspecialchars($gift['name']); ?>" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex justify-end space-x-3">
                    <a href="<?php echo base_url('admin/gifts'); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-800 hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-800">
                        Update Gift
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>
