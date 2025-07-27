<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Digiland Wedding Registry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #F9F8F6;
        }
        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- Navigation -->
    <nav class="bg-amber-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-serif font-bold">Digiland Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo base_url('admin'); ?>" class="hover:text-amber-200">Dashboard</a>
                    <a href="<?php echo base_url('admin/users'); ?>" class="hover:text-amber-200">Users</a>
                    <a href="<?php echo base_url('admin/gifts'); ?>" class="hover:text-amber-200">Gifts</a>
                    <a href="<?php echo base_url('admin/logout'); ?>" class="hover:text-amber-200">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-serif font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit User
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
                    <input type="text" id="username" value="<?php echo $user['username']; ?>" disabled class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                    <p class="mt-1 text-sm text-gray-500">Username cannot be changed</p>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-800 focus:border-amber-800">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $user['phone']; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-800 focus:border-amber-800" placeholder="+6281234567890">
                </div>

                <div>
                    <label for="registry_url" class="block text-sm font-medium text-gray-700">Registry URL</label>
                    <input type="text" id="registry_url" value="<?php echo base_url($user['username']); ?>" disabled class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                    <p class="mt-1 text-sm text-gray-500">
                        <a href="<?php echo base_url($user['username']); ?>" target="_blank" class="text-amber-600 hover:text-amber-900">View Registry →</a>
                    </p>
                </div>

                <div>
                    <label for="created_at" class="block text-sm font-medium text-gray-700">Joined Date</label>
                    <input type="text" id="created_at" value="<?php echo date('M j, Y g:i A', strtotime($user['created_at'])); ?>" disabled class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="<?php echo base_url('admin/users'); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-800 hover:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-800">
                        Update User
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html> 