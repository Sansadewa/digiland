<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Digiland Wedding Registry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="<?php echo base_url('assets/Digiland.svg'); ?>" sizes="any" type="image/svg+xml" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
    <nav class="bg-emerald-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-serif font-bold">Digiland Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo base_url('admin'); ?>" class="hover:text-emerald-200">Dashboard</a>
                    <a href="<?php echo base_url('admin/users'); ?>" class="hover:text-emerald-200">Users</a>
                    <a href="<?php echo base_url('admin/gifts'); ?>" class="hover:text-emerald-200">Gifts</a>
                    <a href="<?php echo base_url('admin/logs'); ?>" class="hover:text-emerald-200">Logs</a>
                    <a href="<?php echo base_url('admin/logout'); ?>" class="hover:text-emerald-200">Logout</a>
                </div>
            </div>
        </div>
    </nav>