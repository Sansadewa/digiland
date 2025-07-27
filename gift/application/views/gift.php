<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digiland's Wedding Registry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="<?php echo base_url('assets/Digiland.svg'); ?>" sizes="any" type="image/svg+xml" />

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #F9F8F6;
        }
        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=2080&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .tab-active {
            border-bottom: 2px solid #A0522D;
            color: #A0522D;
            font-weight: bold;
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .product-card.is-purchased .status-overlay,
        .product-card.is-booked .status-overlay {
            opacity: 1;
            pointer-events: auto; /* Allow clicks on overlay */
        }
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.6);
            transition: opacity 0.3s ease-in-out;
        }
        .view-btn.active {
            color: #A0522D;
            background-color: #F3E5D8;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #A0522D;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .modal-close-btn {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            color: #9CA3AF;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }
        .modal-close-btn:hover {
            color: #1F2937;
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- Main Container -->
    <div class="container mx-auto max-w-6xl p-4 md:p-8">

        <!-- Header, Tabs, etc. -->
        <header class="text-center mb-12">
            <div class="hero-bg h-64 md:h-80 rounded-lg flex items-center justify-center text-white shadow-lg">
                <div class="bg-black bg-opacity-40 p-6 rounded-lg">
                    <h1 class="text-4xl md:text-6xl font-bold">DIGILAND</h1>
                    <p class="mt-2 text-lg md:text-xl tracking-widest">WEDDING REGISTRY</p>
                    <p class="mt-4 text-md md:text-lg bg-white bg-opacity-20 inline-block px-4 py-1 rounded-full">
                        Minggu, 07 September 2025
                    </p>
                </div>
            </div>
        </header>

        <div class="w-full mb-8">
            <nav class="flex justify-center border-b border-gray-300">
                <button data-tab="welcome" class="tab-btn tab-inactive px-6 py-3 text-lg">Welcome</button>
                <button data-tab="gift-list" class="tab-btn tab-active px-6 py-3 text-lg">Gift List</button>
                <button data-tab="shipping" class="tab-btn tab-inactive px-6 py-3 text-lg">Shipping Info</button>
            </nav>
        </div>

        <main id="tab-content">
            <section id="welcome" class="hidden text-center p-8 bg-white rounded-lg shadow-md">
                <h2 class="text-3xl font-serif text-gray-700 mb-4">Welcome to Our Registry!</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Dearest friends and family, thank you so much for visiting our wedding registry. Your presence at our wedding is the greatest gift of all. However, if you wish to honor us with a gift, we have put together a list of items we would love to have as we begin our new life together. We are so excited to celebrate our special day with you!
                </p>
                <p class="mt-4 text-lg text-gray-600">With love,</p>
                <p class="mt-2 text-2xl font-serif text-gray-700">Gibran & Diyang</p>
            </section>

            <section id="gift-list">
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                    <div class="flex flex-wrap justify-center gap-2" id="filters">
                        <button data-filter="all" class="filter-btn bg-amber-800 text-white px-4 py-2 rounded-full text-sm font-semibold shadow">All Gifts</button>
                        <button data-filter="Home" class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold shadow">Home</button>
                        <button data-filter="Kitchen" class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold shadow">Kitchen</button>
                        <button data-filter="Electronics" class="filter-btn bg-white text-gray-700 px-4 py-2 rounded-full text-sm font-semibold shadow">Electronics</button>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                           <label for="sort" class="text-sm font-medium text-gray-600">Sort by:</label>
                           <select id="sort" class="border border-gray-300 rounded-full px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-800">
                               <option value="name-asc">Name: A-Z</option>
                               <option value="name-desc">Name: Z-A</option>
                               <option value="price-asc">Price: Low to High</option>
                               <option value="price-desc">Price: High to Low</option>
                           </select>
                        </div>
                        <div class="flex items-center border border-gray-300 rounded-full p-1 bg-white">
                            <button id="grid-view-btn" class="view-btn active p-2 rounded-full leading-none"><i class="fas fa-th-large"></i></button>
                            <button id="list-view-btn" class="view-btn p-2 rounded-full leading-none"><i class="fas fa-list"></i></button>
                        </div>
                    </div>
                </div>

                <div id="product-container">
                    <!-- Product cards will be injected here by JavaScript -->
                </div>
            </section>
            
            <section id="shipping" class="hidden text-center p-8 bg-white rounded-lg shadow-md">
                <h2 class="text-3xl font-serif text-gray-700 mb-4">Shipping Information</h2>
                <div class="text-lg text-gray-600 max-w-md mx-auto leading-relaxed">
                    <p class="mb-2">For your convenience, please have all gifts shipped to the following address:</p>
                    <div class="bg-gray-100 p-4 rounded-md border border-gray-200">
                        <p class="font-bold">Gibran & Diyang</p>
                        <p>Jl. Ratu Elok</p>
                        <p>Jakarta Selatan, 12345</p>
                        <p>Indonesia</p>
                    </div>
                    <p class="mt-4">Thank you for your generosity!</p>
                </div>
            </section>
        </main>

    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="modal-backdrop fixed inset-0"></div>
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg mx-auto z-10 transform transition-all scale-95 opacity-0 relative" id="modal-content">
            <!-- Modal content will be injected here -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- REAL DATA FROM SERVER ---
            let gifts = [];
            let currentUserId = null;
            const BOOKING_DURATION_MS = 15 * 60 * 1000; // 15 minutes

            // Parse initial gifts data from server
            try {
                gifts = JSON.parse('<?php echo addslashes($initial_gifts_json); ?>');
                currentUserId = <?php echo $this->session->userdata('user_id') ?: 'null'; ?>;
            } catch (e) {
                console.error('Error parsing gifts data:', e);
                gifts = [];
            }

            // --- STATE ---
            let currentFilter = 'all';
            let currentSort = 'name-asc'; // Default sort
            let currentView = 'grid';
            let countdownInterval;

            // --- DOM ELEMENTS ---
            const container = document.getElementById('product-container');
            const modal = document.getElementById('modal');
            const modalContent = document.getElementById('modal-content');
            const modalBackdrop = document.querySelector('.modal-backdrop');

            // --- API / AJAX FUNCTIONS (PRODUCTION-READY) ---
            
            async function fetchGiftDetails(giftId) {
                const response = await fetch(`/get_details?id=${giftId}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || 'Failed to fetch details.');
                }
                // Add description if not provided by the server
                if (!result.data.description) {
                    result.data.description = `This ${result.data.name} is a fantastic addition to any home. Known for its durability and stylish design, it combines functionality with modern aesthetics. It's perfect for everyday use and for special occasions. We believe this will bring much joy and convenience to the couple's new life together.`;
                }
                return result.data;
            }

            async function bookGiftOnServer(giftId) {
                const response = await fetch('/book', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ giftId: giftId })
                });
                if (!response.ok) {
                    const errorResult = await response.json().catch(() => ({}));
                    throw new Error(errorResult.message || `HTTP error! status: ${response.status}`);
                }
                return await response.json();
            }
            
            async function confirmPurchaseOnServer(giftId, orderNumber) {
                 const response = await fetch('/confirm_purchase', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ giftId, orderNumber })
                 });
                 if (!response.ok) {
                    const errorResult = await response.json().catch(() => ({}));
                    throw new Error(errorResult.message || `HTTP error! status: ${response.status}`);
                 }
                 return await response.json();
            }

            async function cancelBookingOnServer(giftId) {
                 const response = await fetch('/cancel_booking', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ giftId })
                 });
                 if (!response.ok) {
                    const errorResult = await response.json().catch(() => ({}));
                    throw new Error(errorResult.message || `HTTP error! status: ${response.status}`);
                 }
                 return await response.json();
            }

            async function refreshGiftsList() {
                const response = await fetch('/get_gifts');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const result = await response.json();
                if (result.success) {
                    gifts = result.data;
                    renderGifts();
                }
            }


            // --- RENDER FUNCTIONS ---
            const renderGifts = () => {
                const filteredGifts = gifts.filter(gift => currentFilter === 'all' || gift.category === currentFilter);
                
                const sortedGifts = filteredGifts.sort((a, b) => {
                    switch(currentSort) {
                        case 'name-asc': return a.name.localeCompare(b.name);
                        case 'name-desc': return b.name.localeCompare(a.name);
                        case 'price-asc': return a.price - b.price;
                        case 'price-desc': return b.price - a.price;
                        default: return 0;
                    }
                });

                container.innerHTML = '';
                container.className = currentView === 'grid' 
                    ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8' 
                    : 'flex flex-col gap-4';

                if (sortedGifts.length === 0) {
                    container.innerHTML = `<p class="col-span-full text-center text-gray-500">No gifts found.</p>`;
                    return;
                }

                sortedGifts.forEach(gift => container.appendChild(createGiftCard(gift)));
                document.querySelectorAll('.action-btn, .status-overlay').forEach(el => el.addEventListener('click', handleActionClick));
            };

            const createGiftCard = (gift) => {
                const card = document.createElement('div');
                const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
                
                let statusClass = '', buttonHtml = '', overlayHtml = '';

                switch(gift.status) {
                    case 'available':
                        buttonHtml = `<button data-id="${gift.id}" data-action="book" class="action-btn w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-2 px-4 rounded-lg transition-colors">Book this Gift</button>`;
                        break;
                    case 'booked':
                        statusClass = 'is-booked';
                        if (gift.booked_by_user_id == currentUserId) {
                            buttonHtml = `<button data-id="${gift.id}" data-action="manage" class="action-btn w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Manage Booking</button>`;
                            overlayHtml = `<div class="status-overlay" data-id="${gift.id}" data-action="manage"><div class="bg-green-800 text-white px-6 py-3 rounded-lg shadow-xl pointer-events-none"><h4 class="font-bold text-xl">You Booked This</h4><p class="text-sm">Time left: <span class="countdown font-bold" data-id="${gift.id}"></span></p></div></div>`;
                        } else {
                            buttonHtml = `<button disabled class="w-full bg-yellow-500 text-white font-bold py-2 px-4 rounded-lg cursor-not-allowed">Booked</button>`;
                            overlayHtml = `<div class="status-overlay" data-id="${gift.id}"><div class="bg-amber-800 text-white px-6 py-3 rounded-lg shadow-xl pointer-events-none"><h4 class="font-bold text-xl">Booked by Other</h4><p class="text-sm">Time left: <span class="countdown font-bold" data-id="${gift.id}"></span></p></div></div>`;
                        }
                        break;
                    case 'purchased':
                        statusClass = 'is-purchased';
                        buttonHtml = `<button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded-lg cursor-not-allowed">Purchased</button>`;
                        overlayHtml = `<div class="status-overlay" data-id="${gift.id}"><div class="bg-slate-800 text-white px-6 py-3 rounded-lg shadow-xl pointer-events-none"><h4 class="font-bold text-xl">Purchased</h4><p class="text-sm">Thank you!</p></div></div>`;
                        break;
                }

                card.className = `product-card bg-white rounded-lg shadow-md overflow-hidden relative ${statusClass}`;
                card.dataset.id = gift.id;

                if (currentView === 'grid') {
                    card.classList.add('flex', 'flex-col');
                    card.innerHTML = `<div class="relative pb-[100%]"><img src="${gift.image_url}" alt="${gift.name}" class="absolute h-full w-full object-cover"></div><div class="p-4 flex flex-col flex-grow"><h3 class="font-semibold text-gray-800 flex-grow">${gift.name}</h3><p class="text-lg font-bold text-amber-800 mt-2">${formatter.format(gift.price)}</p><div class="mt-4">${buttonHtml}</div></div>${overlayHtml}`;
                } else {
                    card.classList.add('flex', 'items-center', 'p-4');
                    card.innerHTML = `<img src="${gift.image_url}" alt="${gift.name}" class="w-20 h-20 object-cover rounded-md mr-4"><div class="flex-grow"><h3 class="font-semibold text-gray-800">${gift.name}</h3><p class="text-md font-bold text-amber-800">${formatter.format(gift.price)}</p></div><div class="w-40 ml-4">${buttonHtml}</div>${overlayHtml}`;
                }
                return card;
            };
            
            // --- EVENT HANDLERS ---
            const handleActionClick = (e) => {
                const target = e.currentTarget;
                const giftId = parseInt(target.dataset.id, 10);
                const action = target.dataset.action;
                const gift = gifts.find(g => g.id === giftId);
                const userHasActiveBooking = gifts.some(g => g.status === 'booked' && g.booked_by_user_id == currentUserId);

                if (action === 'book') {
                    if (userHasActiveBooking) {
                        showInfoModal('One Booking at a Time', 'You can only book one gift at a time. Please complete or cancel your current booking before selecting another.');
                    } else {
                        showBookingModal(giftId);
                    }
                } else if (action === 'manage') {
                    showPostBookingModal(gift);
                }
            };

            const showBookingModal = async (giftId) => {
                showModal();
                modalContent.innerHTML = `<div class="p-8 flex items-center justify-center min-h-[400px]"><div class="loader"></div></div>`;
                
                try {
                    const giftDetails = await fetchGiftDetails(giftId);

                    const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

                    modalContent.innerHTML = `
                        <button class="modal-close-btn" id="close-modal-btn">&times;</button>
                        <div class="p-6">
                            <h3 class="text-2xl font-serif mb-4 text-gray-800">Book this Gift</h3>
                            <div class="flex flex-col md:flex-row gap-6 mb-4">
                                <img src="${giftDetails.image_url}" class="w-full md:w-1/3 h-auto object-cover rounded-lg">
                                <div class="flex-grow">
                                    <h4 class="text-xl font-bold">${giftDetails.name}</h4>
                                    <p class="text-2xl font-bold text-amber-800 my-2">${formatter.format(giftDetails.price)}</p>
                                    <p class="text-gray-600 text-sm leading-relaxed">${giftDetails.description}</p>
                                </div>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-lg border mb-4">
                                <h5 class="font-bold mb-1">Ship To:</h5>
                                <p class="text-sm text-gray-700">Anindia & Mustofiq, Jl. Bahagia Selalu No. 25, Jakarta Selatan, 12345, Indonesia</p>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between gap-3">
                                <button onclick="window.open('${giftDetails.store_url}', '_blank')" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold flex-grow"><i class="fas fa-shopping-cart mr-2"></i>View on Store</button>
                                <div class="flex gap-3">
                                    <button id="cancel-modal" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold">Cancel</button>
                                    <button id="confirm-book" data-id="${giftDetails.id}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-semibold">Confirm & Book</button>
                                </div>
                            </div>
                        </div>`;
                    document.getElementById('close-modal-btn').addEventListener('click', hideModal);
                    document.getElementById('cancel-modal').addEventListener('click', hideModal);
                    document.getElementById('confirm-book').addEventListener('click', confirmBooking);
                } catch (error) {
                    showInfoModal('Error', 'Could not load gift details. Please try again later.');
                }
            };

            const confirmBooking = async (e) => {
                const giftId = parseInt(e.currentTarget.dataset.id, 10);
                try {
                    const result = await bookGiftOnServer(giftId);
                    if (result.success) {
                        // Refresh the gifts list from server
                        await refreshGiftsList();
                        startCountdownTimer();
                        // Find the updated gift
                        const gift = gifts.find(g => g.id === giftId);
                        if (gift) {
                            showPostBookingModal(gift);
                        }
                    } else {
                        showInfoModal('Booking Failed', result.message);
                    }
                } catch (error) {
                    showInfoModal('Booking Failed', error.message);
                }
            };
            
            const showPostBookingModal = (gift) => {
                showModal();
                modalContent.innerHTML = `
                    <button class="modal-close-btn" id="close-modal-btn">&times;</button>
                    <div class="p-6 text-center">
                        <h3 class="text-2xl font-serif mb-2 text-green-600">Gift Booked!</h3>
                        <p class="text-gray-600 mb-4">This item is reserved for you. Please complete the purchase and enter the Order Number below.</p>
                        <div class="text-4xl font-bold text-amber-800 my-4" id="modal-countdown"></div>
                        <div class="my-4">
                            <label for="order-number" class="block text-sm font-medium text-gray-700 text-left">Order Number (Required)</label>
                            <input type="text" id="order-number" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-amber-800 focus:border-amber-800 sm:text-sm" placeholder="e.g., INV/2025/03/XYZ">
                        </div>
                        <button id="confirm-purchase" data-id="${gift.id}" class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-lg opacity-50 cursor-not-allowed" disabled>I've Purchased It!</button>
                        <button id="cancel-booking" data-id="${gift.id}" class="mt-2 text-sm text-gray-500 hover:underline">Cancel my booking</button>
                    </div>`;

                const orderInput = document.getElementById('order-number');
                const confirmBtn = document.getElementById('confirm-purchase');
                
                orderInput.addEventListener('input', () => {
                    if (orderInput.value.trim() !== '') {
                        confirmBtn.disabled = false;
                        confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        confirmBtn.disabled = true;
                        confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                });
                
                document.getElementById('close-modal-btn').addEventListener('click', hideModal);
                document.getElementById('confirm-purchase').addEventListener('click', confirmPurchase);
                document.getElementById('cancel-booking').addEventListener('click', cancelBooking);
            };

            const confirmPurchase = async (e) => {
                const giftId = parseInt(e.currentTarget.dataset.id, 10);
                const orderNumber = document.getElementById('order-number').value;
                
                try {
                    const result = await confirmPurchaseOnServer(giftId, orderNumber);
                    if (result.success) {
                        // Refresh the gifts list from server
                        await refreshGiftsList();
                        // Find the updated gift
                        const gift = gifts.find(g => g.id === giftId);
                        if (gift) {
                            showThankYouModal(gift);
                        }
                    } else {
                        showInfoModal('Error', result.message || 'Could not confirm purchase. Please try again.');
                    }
                } catch (error) {
                    showInfoModal('Error', 'Could not confirm purchase. Please try again.');
                }
            };
            
            const cancelBooking = async (e) => {
                const giftId = parseInt(e.currentTarget.dataset.id, 10);
                try {
                    const result = await cancelBookingOnServer(giftId);
                    if (result.success) {
                        // Refresh the gifts list from server
                        await refreshGiftsList();
                        hideModal();
                    } else {
                        showInfoModal('Error', result.message || 'Could not cancel booking. Please try again.');
                    }
                } catch (error) {
                    showInfoModal('Error', 'Could not cancel booking. Please try again.');
                }
            };
            
            const showThankYouModal = (gift) => {
                showModal();
                modalContent.innerHTML = `
                    <button class="modal-close-btn" id="close-modal-btn">&times;</button>
                    <div class="p-8 text-center">
                        <i class="fas fa-heart text-4xl text-red-500 mb-4"></i>
                        <h3 class="text-3xl font-serif mb-2">Thank You!</h3>
                        <p class="text-gray-600 mb-6">Your generous gift of the <strong>${gift.name}</strong> is deeply appreciated. Anindia and Mustofiq are so grateful for your kindness.</p>
                        <button id="close-thankyou-modal" class="px-8 py-2 bg-amber-800 text-white rounded-lg hover:bg-amber-900 font-semibold">Close</button>
                    </div>
                `;
                document.getElementById('close-modal-btn').addEventListener('click', hideModal);
                document.getElementById('close-thankyou-modal').addEventListener('click', hideModal);
            };

            const showInfoModal = (title, message) => {
                showModal();
                modalContent.innerHTML = `
                    <button class="modal-close-btn" id="close-modal-btn">&times;</button>
                    <div class="p-6 text-center">
                        <h3 class="text-2xl font-serif mb-2">${title}</h3>
                        <p class="text-gray-600 mb-6">${message}</p>
                        <button id="close-info-modal" class="px-6 py-2 bg-amber-800 text-white rounded-lg hover:bg-amber-900 font-semibold">OK</button>
                    </div>
                `;
                document.getElementById('close-modal-btn').addEventListener('click', hideModal);
                document.getElementById('close-info-modal').addEventListener('click', hideModal);
            };

            // --- UTILITY & TIMER FUNCTIONS ---
            const startCountdownTimer = () => {
                if (countdownInterval) clearInterval(countdownInterval);
                countdownInterval = setInterval(updateCountdowns, 1000);
            };

            const updateCountdowns = () => {
                const now = new Date();
                let activeBookings = false;
                gifts.forEach(gift => {
                    if (gift.status === 'booked' && gift.booked_until) {
                        activeBookings = true;
                        const bookedUntil = new Date(gift.booked_until);
                        const timeLeft = bookedUntil.getTime() - now.getTime();
                        if (timeLeft <= 0) {
                            // Booking expired, refresh the list
                            refreshGiftsList();
                        } else {
                            const minutes = Math.floor((timeLeft / 1000 / 60) % 60);
                            const seconds = Math.floor((timeLeft / 1000) % 60);
                            const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                            
                            document.querySelectorAll(`.countdown[data-id="${gift.id}"]`).forEach(el => el.textContent = formattedTime);
                            const modalCountdown = document.getElementById('modal-countdown');
                            if (modalCountdown && !modal.classList.contains('hidden')) {
                                modalCountdown.textContent = formattedTime;
                            }
                        }
                    }
                });
                if (!activeBookings) clearInterval(countdownInterval);
            };

            const showModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                }, 10);
            };

            const hideModal = () => {
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            };
            
            // --- INITIALIZATION ---
            document.getElementById('filters').addEventListener('click', (e) => {
                if (e.target.tagName !== 'BUTTON') return;
                currentFilter = e.target.dataset.filter;
                document.querySelector('#filters .bg-amber-800').classList.replace('bg-amber-800', 'bg-white');
                document.querySelector('#filters .text-white').classList.replace('text-white', 'text-gray-700');
                e.target.classList.add('bg-amber-800', 'text-white');
                renderGifts();
            });
            document.getElementById('sort').addEventListener('change', (e) => { currentSort = e.target.value; renderGifts(); });
            document.getElementById('grid-view-btn').addEventListener('click', () => { currentView = 'grid'; document.getElementById('grid-view-btn').classList.add('active'); document.getElementById('list-view-btn').classList.remove('active'); renderGifts(); });
            document.getElementById('list-view-btn').addEventListener('click', () => { currentView = 'list'; document.getElementById('list-view-btn').classList.add('active'); document.getElementById('grid-view-btn').classList.remove('active'); renderGifts(); });
            document.querySelector('.flex.justify-center.border-b').addEventListener('click', (e) => {
                if (!e.target.classList.contains('tab-btn')) return;
                const targetTab = e.target.dataset.tab;
                document.querySelector('.tab-active').classList.replace('tab-active','tab-inactive');
                e.target.classList.replace('tab-inactive','tab-active');
                document.querySelectorAll('#tab-content > section').forEach(s => s.classList.add('hidden'));
                document.getElementById(targetTab).classList.remove('hidden');
            });
            modalBackdrop.addEventListener('click', hideModal);
            
            renderGifts();
            startCountdownTimer();
        });
    </script>

</body>
</html>
