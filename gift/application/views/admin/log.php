<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 styling to match Tailwind */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            height: 38px;
            padding: 0.5rem 0.75rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 22px;
            color: #374151;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #059669;
        }
        
        /* Action badges */
        .action-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .action-booking { 
            background-color: #fef3c7; 
            color: #92400e; 
        }
        .action-purchased { 
            background-color: #d1fae5; 
            color: #065f46; 
        }
        .action-opening_website { 
            background-color: #dbeafe; 
            color: #1e40af; 
        }
        .action-get_detail { 
            background-color: #fce7f3; 
            color: #be185d; 
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-serif font-bold text-gray-900 mb-6">
                <i class="fas fa-chart-line text-emerald-600"></i> Activity Logs
            </h1>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-emerald-600" id="total-logs">-</div>
                    <div class="text-gray-500 text-sm">Total Logs</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-yellow-600" id="total-bookings">-</div>
                    <div class="text-gray-500 text-sm">Bookings</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-green-600" id="total-purchases">-</div>
                    <div class="text-gray-500 text-sm">Purchases</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600" id="total-visits">-</div>
                    <div class="text-gray-500 text-sm">Website Visits</div>
                </div>
            </div>

            <!-- Action-Based Logs Table -->
            <div class="bg-white rounded-lg shadow-lg mb-8">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 text-white p-6 rounded-t-lg">
                    <h3 class="text-xl font-serif font-bold">
                        <i class="fas fa-list"></i> Logs by Action
                    </h3>
                    <p class="text-emerald-100 text-sm">View all activities grouped by action type</p>
                </div>
                
                <div class="bg-gray-50 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="action-filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Action:</label>
                            <select id="action-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">All Actions</option>
                                <option value="booking">Booking</option>
                                <option value="purchased">Purchased</option>
                                <option value="opening_website">Website Opening</option>
                                <option value="get_detail">Gift Detail View</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button onclick="loadActionLogs()" class="w-full bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <i class="fas fa-search"></i> Load Logs
                            </button>
                        </div>
                        <div class="flex items-end">
                            <button onclick="refreshActionLogs()" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id="action-logs-loading" class="text-center py-12 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl"></i>
                        <p class="mt-2">Loading logs...</p>
                    </div>
                    <div id="action-logs-table" class="hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gift</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody id="action-logs-tbody" class="bg-white divide-y divide-gray-200">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Activity Table -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 text-white p-6 rounded-t-lg">
                    <h3 class="text-xl font-serif font-bold">
                        <i class="fas fa-users"></i> User Activity
                    </h3>
                    <p class="text-emerald-100 text-sm">View activities for specific users</p>
                </div>
                
                <div class="bg-gray-50 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label for="username-search" class="block text-sm font-medium text-gray-700 mb-2">Search by Username:</label>
                            <select id="username-search" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select a username...</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button onclick="loadUserActivity()" class="w-full bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <i class="fas fa-search"></i> Load Activity
                            </button>
                        </div>
                        <div class="flex items-end">
                            <button onclick="clearUserSearch()" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id="user-activity-loading" class="hidden text-center py-12 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl"></i>
                        <p class="mt-2">Loading user activity...</p>
                    </div>
                    <div id="user-activity-empty" class="text-center py-12 text-gray-500">
                        <i class="fas fa-user-search text-4xl mb-4"></i>
                        <p>Select a username to view their activity</p>
                    </div>
                    <div id="user-activity-table" class="hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gift</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody id="user-activity-tbody" class="bg-white divide-y divide-gray-200">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Select2 for autocomplete -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for username search with autocomplete
            $('#username-search').select2({
                placeholder: "Type to search username...",
                allowClear: true,
                ajax: {
                    url: '<?= base_url('admin/get_usernames') ?>',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.map(function(username) {
                                return {
                                    id: username,
                                    text: username
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Load initial data
            loadActionLogs();
            loadStatistics();
        });

        function loadActionLogs() {
            const action = $('#action-filter').val();
            $('#action-logs-loading').removeClass('hidden');
            $('#action-logs-table').addClass('hidden');

            $.ajax({
                url: '<?= base_url('admin/get_logs_by_action') ?>',
                method: 'GET',
                data: { action: action },
                dataType: 'json',
                success: function(data) {
                    displayActionLogs(data);
                    $('#action-logs-loading').addClass('hidden');
                    $('#action-logs-table').removeClass('hidden');
                },
                error: function() {
                    $('#action-logs-loading').html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Error loading logs</div>');
                }
            });
        }

        function displayActionLogs(logs) {
            const tbody = $('#action-logs-tbody');
            tbody.empty();

            if (logs.length === 0) {
                tbody.append('<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No logs found</td></tr>');
                return;
            }

            logs.forEach(function(log) {
                const actionBadge = getActionBadge(log.action);
                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.id || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${log.username}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${actionBadge}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.gift_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(log.created_at)}</td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        function loadUserActivity() {
            const username = $('#username-search').val();
            
            if (!username) {
                alert('Please select a username first');
                return;
            }

            $('#user-activity-loading').removeClass('hidden');
            $('#user-activity-empty').addClass('hidden');
            $('#user-activity-table').addClass('hidden');

            $.ajax({
                url: '<?= base_url('admin/get_user_activity') ?>',
                method: 'GET',
                data: { username: username },
                dataType: 'json',
                success: function(data) {
                    displayUserActivity(data);
                    $('#user-activity-loading').addClass('hidden');
                    $('#user-activity-table').removeClass('hidden');
                },
                error: function() {
                    $('#user-activity-loading').html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Error loading user activity</div>');
                }
            });
        }

        function displayUserActivity(logs) {
            const tbody = $('#user-activity-tbody');
            tbody.empty();

            if (logs.length === 0) {
                tbody.append('<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No activity found for this user</td></tr>');
                return;
            }

            logs.forEach(function(log) {
                const actionBadge = getActionBadge(log.action);
                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.id || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${actionBadge}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.gift_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDateTime(log.created_at)}</td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        function getActionBadge(action) {
            const badges = {
                'booking': '<span class="action-badge action-booking">Booking</span>',
                'purchased': '<span class="action-badge action-purchased">Purchased</span>',
                'opening_website': '<span class="action-badge action-opening_website">Website Visit</span>',
                'get_detail': '<span class="action-badge action-get_detail">Gift Detail</span>'
            };
            return badges[action] || `<span class="action-badge">${action}</span>`;
        }

        function formatDateTime(dateTime) {
            if (!dateTime) return '-';
            const date = new Date(dateTime);
            return date.toLocaleString();
        }

        function refreshActionLogs() {
            loadActionLogs();
        }

        function clearUserSearch() {
            $('#username-search').val(null).trigger('change');
            $('#user-activity-table').addClass('hidden');
            $('#user-activity-empty').removeClass('hidden');
            $('#user-activity-loading').addClass('hidden');
        }

        function loadStatistics() {
            // Load statistics for each action type
            $.ajax({
                url: '<?= base_url('admin/get_logs_by_action') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#total-logs').text(data.length);
                    
                    const bookings = data.filter(log => log.action === 'booking').length;
                    const purchases = data.filter(log => log.action === 'purchased').length;
                    const visits = data.filter(log => log.action === 'opening_website').length;
                    
                    $('#total-bookings').text(bookings);
                    $('#total-purchases').text(purchases);
                    $('#total-visits').text(visits);
                }
            });
        }
    </script>
</body>
</html>