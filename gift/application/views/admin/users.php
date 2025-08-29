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
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                All Users (<?php echo count($users); ?>)
            </h3>
        </div>
        <!-- Search Input -->
    <div class="mb-1 px-2 py-3 sm:px-6">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="searchUsers" class="form-control" placeholder="Search users...">
        </div>
    </div>
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
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                        <span class="text-emerald-800 font-medium"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $user['name']; ?></div>
                                    <div class="text-sm text-gray-500">@<?php echo $user['username']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $user['phone'] ?: 'N/A'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="<?php echo base_url($user['username']); ?>" target="_blank" class="text-emerald-600 hover:text-emerald-900">
                                <?php echo base_url($user['username']); ?>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $user['show_gift_section'] ? 'Yes' : 'No'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo date('M j, Y g:i A', strtotime($user['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="copyInvite(event, '<?php echo addslashes($user['name']); ?>', '<?php echo $user['username']; ?>')" 
                                class="text-emerald-600 hover:text-emerald-900 mr-3"
                                title="Copy Invitation">
                                <i class="fas fa-envelope"></i> Invite
                            </button>
                            <a href="<?php echo base_url('admin/users/edit/' . $user['id']); ?>" class="text-emerald-600 hover:text-emerald-900 mr-3">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?php echo base_url('admin/users/delete/' . $user['id']); ?>" 
                               onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                               class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>

<script>
$(document).ready(function() {
    // Search functionality
    $('#searchUsers').on('keyup', function() {
        const searchText = $(this).val().toLowerCase();
        
        $('#usersTable tbody tr').each(function() {
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.indexOf(searchText) > -1);
        });
    });
});

function copyInvite(event, name, username) {
    // Prevent default button behavior and get button reference
    event.preventDefault();
    const button = event.currentTarget;
    const originalContent = button.innerHTML;
    
    // Get the template and replace placeholders
    let template = document.getElementById('inviteTemplate').value;
    // Remove leading spaces from each line of the template
    // template = template.replace(/^\s+/gm, '');
    
    let message = template
        .replace(/\$name/g, name)
        .replace(/\$username/g, username);
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Copying...';
    button.disabled = true;
    
    // Copy to clipboard
    navigator.clipboard.writeText(message).then(function() {
        // Show success message
        console.log("Sukses Copy");
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.classList.add('text-green-600');
        
        // Revert button after 2 seconds
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('text-green-600');
            button.disabled = false;
        }, 2000);
        
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        button.innerHTML = '<i class="fas fa-times"></i> Failed';
        button.classList.add('text-red-600');
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('text-red-600');
            button.disabled = false;
        }, 2000);
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
                    window.location.reload();
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
</script>

</html>