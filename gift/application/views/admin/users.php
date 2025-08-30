    <!-- Main Content -->
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    
    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-serif font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Users Management
            </h2>
        </div>
        <?php if(false){ ?><div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="<?php echo base_url('admin/users/add'); ?>" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-800 hover:bg-emerald-900">
                <i class="fas fa-plus mr-2"></i> Add New User
            </a>
        </div><?php } ?>
    </div>
    <!-- Template Textarea -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
    <h3 class="text-lg font-bold text-emerald-600">Invitation Template</h3>
        <textarea id="inviteTemplate" class="w-full h-48 p-3 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500" 
            placeholder="Enter your invitation template here...">Assalamualaikum warahmatullahi wabarakatuh
*Kepada Yth. $name*

Tanpa mengurangi rasa hormat, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk hadir dalam acara pernikahan kami.

*Gibran Sansadewa Asshadiqi*
                        *&* 
*Diyang Gita Cendekia*

Merupakan suatu kebahagiaan bagi kami apabila Bapak/Ibu berkenan untuk hadir dan memberikan doa restu.

untuk info lengkap mempelai dan acara bisa kunjungi :
https://digiland.space/$username

Wassalamualaikum warahmatullahi wabarakatuh
Kami yang berbahagia
*Keluarga Kedua Mempelai*</textarea>
        <div class="mt-2 text-xs text-gray-500">
            Use <code>$name</code> for the recipient's name and <code>$username</code> for their username.
        </div>
    </div>

    <!-- Add User Form -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center cursor-pointer" onclick="toggleAddUserForm()">
            <h3 class="text-lg font-bold text-emerald-600">Add New User</h3>
            <i class="fas fa-chevron-up" id="addUserToggleIcon"></i>
        </div>
        
        <div id="addUserForm" class="mt-4">
            <form id="userForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" id="name" name="name" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username *</label>
                        <input type="text" id="username" name="username" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800">
                    </div>



                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-800 focus:border-emerald-800" 
                            placeholder="+6281234567890">
                    </div>

                    <div class="flex items-end">
                        <div class="flex items-center h-5">
                            <input id="show_gift_section" name="show_gift_section" type="checkbox" value="1" 
                                class="h-4 w-4 text-emerald-800 focus:ring-emerald-800 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="show_gift_section" class="font-medium text-gray-700">Show Gift Section</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="resetAddUserForm()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Reset
                    </button>
                    <button type="button" id="addAndCopyBtn"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span id="addAndCopyText">Add & Copy</span>
                        <span id="addAndCopySpinner" class="ml-2 hidden">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-800 hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-800">
                        <span id="submitButtonText">Add User</span>
                        <span id="submitButtonSpinner" class="ml-2 hidden">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
            </form>
            <div id="formMessage" class="mt-3 text-sm"></div>
        </div>
    </div>

    

    <!-- Users Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md px-3">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                All Users 
            </h3>
        </div>
        <!-- Search Input -->
    <!-- <div class="mb-1 px-2 py-3 sm:px-6">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="searchUsers" class="form-control" placeholder="Search users...">
        </div>
    </div> -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registry URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Show Gift</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo base_url('admin/get_users_ajax'); ?>',
            type: 'GET',
            dataType: 'json',
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', error, thrown);
                $('#usersTable').before(
                    '<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">' +
                    'Error loading data. Please try again.' +
                    '</div>'
                );
            }
        },
        columns: [
            { 
                data: 'name',
                name: 'name',
                orderable: true,
                className: 'py-4'
            },
            { 
                data: 'phone',
                name: 'phone',
                orderable: true,
                className: 'py-4'
            },
            { 
                data: 'username',
                name: 'username',
                orderable: true,
                className: 'py-4'
            },
            { 
                data: 'show_gift_section',
                name: 'show_gift_section',
                orderable: true,
                className: 'py-4'
            },
            { 
                data: 'created_at',
                name: 'created_at',
                orderable: true,
                className: 'py-4'
            },
            { 
                data: 'actions',
                name: 'actions',
                orderable: false,
                className: 'py-4 text-center',
                render: function(data, type, row, meta) {
                    return data;
                }
            }
        ],
        order: [[4, 'desc']],
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        language: {
            processing: '<div class="flex items-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-700 mr-3"></div>Loading...</div>',
            search: "_INPUT_",
            searchPlaceholder: "Search users...",
            lengthMenu: "Show _MENU_ users per page",
            zeroRecords: "No users found",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            infoEmpty: "No users available",
            infoFiltered: "(filtered from _MAX_ total users)",
            paginate: {
                first: "First",
                last: "Last",
                next: "<i class='fas fa-chevron-right'></i>",
                previous: "<i class='fas fa-chevron-left'></i>"
            }
        },
        dom: "<'flex flex-col md:flex-row justify-between mb-4'<'mb-4 md:mb-0'l><'flex items-center'f>>" +
             "<'w-full overflow-x-auto'tr>" +
             "<'flex flex-col md:flex-row justify-between items-center mt-4 space-y-4 md:space-y-0'<'text-sm text-gray-500'i><'pagination flex space-x-2'p>>",
        initComplete: function() {
            $('.dataTables_filter input').addClass('block w-full md:w-64 px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500');
            $('.dataTables_length select').addClass('block w-20 px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500');
        }
    });

    // Handle copy invite button click
    $(document).on('click', '.copy-invite-btn', function() {
        const name = $(this).data('name');
        const username = $(this).data('username');
        copyInvite({ preventDefault: () => {} }, name, username);
    });

    // Handle refresh button click
    $('#refreshTable').on('click', function() {
        table.ajax.reload(null, false);
        showToast('Table refreshed', 'success');
    });

    // Show toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-md text-white ${type === 'success' ? 'bg-emerald-600' : 'bg-red-600'} shadow-lg`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});

// Copy invite function (existing function)
function copyInvite(event, name, username) {
    event.preventDefault();
    const template = document.getElementById('inviteTemplate').value;
    const inviteText = template
        .replace(/\$name/g, name)
        .replace(/\$username/g, username);
    
    navigator.clipboard.writeText(inviteText).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 px-6 py-3 bg-emerald-600 text-white rounded-md shadow-lg';
        toast.textContent = 'Invitation copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy invitation. Please try again.');
    });
}

// Toggle add user form
function toggleAddUserForm() {
    const form = document.getElementById('addUserForm');
    const icon = document.getElementById('addUserToggleIcon');
    form.classList.toggle('hidden');
    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-up');
}

// Reset add user form
function resetAddUserForm() {
    document.getElementById('userForm').reset();
    document.getElementById('formMessage').textContent = '';
    document.getElementById('formMessage').className = 'mt-3 text-sm';
    // Reset modified state
    $('#username').data('modified', false);
}

// Handle form submission
$('#userForm').on('submit', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const submitBtn = form.find('button[type="submit"]');
    const submitText = $('#submitButtonText');
    const spinner = $('#submitButtonSpinner');
    const messageDiv = $('#formMessage');
    
    // Show loading state
    submitBtn.prop('disabled', true);
    submitText.text('Adding...');
    spinner.removeClass('hidden');
    messageDiv.removeClass('text-red-600 text-green-600').text('');
    
    // Submit form via AJAX
    $.ajax({
        url: '<?php echo base_url("admin/users/add"); ?>',
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Show success message
                messageDiv.removeClass('text-red-600').addClass('text-green-600').text(response.message);
                
                // Reset form
                form[0].reset();
                
                // Reload the users table
                setTimeout(() => {
                    // window.location.reload();
                    $('#usersTable').DataTable().ajax.reload();

                }, 1000);
            } else {
                // Show error message
                messageDiv.removeClass('text-green-600').addClass('text-red-600').text(response.message || 'An error occurred');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON || {};
            messageDiv.removeClass('text-green-600').addClass('text-red-600')
                .text(response.message || 'Failed to add user. Please try again.');
        },
        complete: function() {
            // Reset button state
            submitBtn.prop('disabled', false);
            submitText.text('Add User');
            spinner.addClass('hidden');
        }
    });
});

// Handle Add & Copy button click
$('#addAndCopyBtn').on('click', function() {
    const form = $('#userForm');
    const submitBtn = $(this);
    const submitText = $('#addAndCopyText');
    const spinner = $('#addAndCopySpinner');
    const messageDiv = $('#formMessage');
    
    // Show loading state
    submitBtn.prop('disabled', true);
    submitText.text('Adding...');
    spinner.removeClass('hidden');
    messageDiv.removeClass('text-red-600 text-green-600').text('');
    
    // Submit form via AJAX
    $.ajax({
        url: '<?php echo base_url("admin/users/add"); ?>',
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Show success message
                messageDiv.removeClass('text-red-600').addClass('text-green-600').text('User added successfully! Copying invitation...');
                
                // Get the invitation text
                const name = $('#name').val();
                const username = $('#username').val();
                let template = $('#inviteTemplate').val();
                template = template.replace(/^\s+/gm, '');
                
                const message = template
                    .replace(/\$name/g, name)
                    .replace(/\$username/g, username);
                
                // Copy to clipboard
                navigator.clipboard.writeText(message).then(function() {
                    messageDiv.text('User added and invitation copied to clipboard!');
                    
                    // Reset form and reload after a delay
                    setTimeout(() => {
                        form[0].reset();
                        // window.location.reload();
                        $('#usersTable').DataTable().ajax.reload();
                    }, 1000);
                }).catch(function(err) {
                    console.error('Copy failed:', err);
                    messageDiv.removeClass('text-green-600').addClass('text-yellow-600')
                        .text('User added but failed to copy invitation. ' + (err.message || ''));
                });
            } else {
                // Show error message
                messageDiv.removeClass('text-green-600').addClass('text-red-600')
                    .text(response.message || 'An error occurred');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON || {};
            messageDiv.removeClass('text-green-600').addClass('text-red-600')
                .text(response.message || 'Failed to add user. Please try again.');
        },
        complete: function() {
            // Reset button state
            submitBtn.prop('disabled', false);
            submitText.text('Add & Copy');
            spinner.addClass('hidden');
        }
    });
});

// Auto-generate username from name
$('#name').on('input', function() {
    const usernameField = $('#username');
    // Only generate if username is empty or hasn't been manually modified
    if (!usernameField.data('modified') && usernameField.val() === '') {
        const name = $(this).val().trim();
        if (name.length > 0) {
            // Remove special characters and convert to uppercase
            let generated = name.replace(/[^a-zA-Z]/g, '').toUpperCase();
            // Take first 6 characters or pad with random letters if shorter
            if (generated.length < 6) {
                const randomChars = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
                while (generated.length < 6) {
                    generated += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
                }
            } else {
                generated = generated.substring(0, 6);
            }
            usernameField.val(generated);
        }
    }
});

// Track if username was manually modified
$('#username').on('input', function() {
    $(this).data('modified', $(this).val().length > 0);
});

</script>

</html>