// ===== DYNAMIC NAME SYSTEM =====
function initDynamicName() {
    const path = window.location.pathname;
    const pathSegments = path.split('/').filter(segment => segment !== '');
    
    let guestName = 'Family and Friends';
    
    if (pathSegments.length > 0) {
        const lastName = pathSegments[pathSegments.length - 1];
        if (lastName && lastName !== 'index.html' && lastName !== 'index') {
            guestName = formatGuestName(lastName);
        }
    }
    
    // Check URL parameters as fallback
    const urlParams = new URLSearchParams(window.location.search);
    const nameParam = urlParams.get('name') || urlParams.get('to');
    if (nameParam) {
        guestName = formatGuestName(nameParam);
    }
    
    updateNameInDocument(guestName);
    trackEvent('guest_name_detected', { guestName, url: window.location.href });
}

function formatGuestName(name) {
    if (!name) return 'Family and Friends';
    
    let decodedName = decodeURIComponent(name)
        .replace(/[-_+]/g, ' ')
        .replace(/\./g, ' ')
        .trim();
    
    // Kembalikan nama apa adanya tanpa mengubah case
    return decodedName || 'Family and Friends';
}

function updateNameInDocument(guestName) {
    const elements = document.querySelectorAll('h1, p, span, div');
    elements.forEach(element => {
        if (element.textContent.includes('Your Name Here')) {
            element.innerHTML = element.innerHTML.replace(/Your Name Here/g, guestName);
        }
    });
    
    // Update page title
    document.title = `DIGILAND - Wedding Invitation for ${guestName}`;
}

// ===== PUZZLE LOGIC =====
const PUZZLE_ROWS = 2;
const PUZZLE_COLS = 2;
const IMAGE_SRC = "./assets/cover1.webp";
const PUZZLE_BOARD_SCALE = window.innerWidth <= 1024 ? 0.9 : 0.75;

let canvas = null;
let ctx = null;

let puzzleWidth, puzzleHeight;
let pieceWidth, pieceHeight;
let knobSize;
let puzzleOffsetX, puzzleOffsetY;

let pieces = [];
const sourceImage = new Image();

let draggedPiece = null;
let offsetX = 0;
let offsetY = 0;
let isSolved = false;

class Piece {
    constructor(row, col, shape) {
        this.row = row;
        this.col = col;
        this.shape = shape;
        this.isLocked = false;

        this.correctX = puzzleOffsetX + this.col * pieceWidth;
        this.correctY = puzzleOffsetY + this.row * pieceHeight;

        const pos = this.getScramblePosition();
        this.x = pos.x;
        this.y = pos.y;
    }

    getScramblePosition() {
        if (!canvas) return { x: 50, y: 50 }; // Fallback
        let x, y;
        const isMobile = window.innerWidth <= 1024;
        const margin = isMobile ? 30 : 40;
        
        if (isMobile) {
            const area = Math.random() > 0.3 ? (Math.random() > 0.5 ? 0 : 2) : (Math.random() > 0.5 ? 1 : 3);
            
            switch (area) {
                case 0:
                    x = margin + Math.random() * (canvas.width - 2 * margin);
                    y = Math.random() * Math.max(margin, puzzleOffsetY - pieceHeight - 10);
                    break;
                case 1:
                    x = (puzzleOffsetX + puzzleWidth + 10) + Math.random() * Math.max(margin, canvas.width - puzzleOffsetX - puzzleWidth - pieceWidth - 20);
                    y = margin + Math.random() * (canvas.height - 2 * margin);
                    break;
                case 2:
                    x = margin + Math.random() * (canvas.width - 2 * margin);
                    y = (puzzleOffsetY + puzzleHeight + 10) + Math.random() * Math.max(margin, canvas.height - puzzleOffsetY - puzzleHeight - pieceHeight - 20);
                    break;
                case 3:
                    x = Math.random() * Math.max(margin, puzzleOffsetX - pieceWidth - 10);
                    y = margin + Math.random() * (canvas.height - 2 * margin);
                    break;
            }
        } else {
            const area = Math.floor(Math.random() * 4);
            switch (area) {
                case 0:
                    x = Math.random() * canvas.width;
                    y = Math.random() * (puzzleOffsetY - pieceHeight);
                    break;
                case 1:
                    x = (puzzleOffsetX + puzzleWidth) + Math.random() * (puzzleOffsetX - pieceWidth);
                    y = Math.random() * canvas.height;
                    break;
                case 2:
                    x = Math.random() * canvas.width;
                    y = (puzzleOffsetY + puzzleHeight) + Math.random() * (puzzleOffsetY - pieceHeight);
                    break;
                case 3:
                    x = Math.random() * (puzzleOffsetX - pieceWidth);
                    y = Math.random() * canvas.height;
                    break;
            }
        }
        
        return {
            x: Math.max(5, Math.min(x, canvas.width - pieceWidth - 5)),
            y: Math.max(5, Math.min(y, canvas.height - pieceHeight - 5))
        };
    }

    draw(context) {
        context.save();
        this.createPath(context);

        if (!this.isLocked) {
            context.shadowColor = 'rgba(0,0,0,0.5)';
            context.shadowBlur = 10;
            context.shadowOffsetX = 3;
            context.shadowOffsetY = 3;
        }

        context.fillStyle = '#fff';
        context.fill();
        context.clip();

        const sourcePixelPerCanvasPixel = sourceImage.width / puzzleWidth;
        const sX = this.col * (sourceImage.width / PUZZLE_COLS) - (this.shape.left === 1 ? knobSize * sourcePixelPerCanvasPixel : 0);
        const sY = this.row * (sourceImage.height / PUZZLE_ROWS) - (this.shape.top === 1 ? knobSize * sourcePixelPerCanvasPixel : 0);
        const sWidth = (sourceImage.width / PUZZLE_COLS) + ((this.shape.left === 1 ? knobSize : 0) + (this.shape.right === 1 ? knobSize : 0)) * sourcePixelPerCanvasPixel;
        const sHeight = (sourceImage.height / PUZZLE_ROWS) + ((this.shape.top === 1 ? knobSize : 0) + (this.shape.bottom === 1 ? knobSize : 0)) * sourcePixelPerCanvasPixel;

        const dX = this.x - (this.shape.left === 1 ? knobSize : 0);
        const dY = this.y - (this.shape.top === 1 ? knobSize : 0);
        const dWidth = pieceWidth + (this.shape.left === 1 ? knobSize : 0) + (this.shape.right === 1 ? knobSize : 0);
        const dHeight = pieceHeight + (this.shape.top === 1 ? knobSize : 0) + (this.shape.bottom === 1 ? knobSize : 0);

        context.drawImage(sourceImage, sX, sY, sWidth, sHeight, dX, dY, dWidth, dHeight);

        context.strokeStyle = this.isLocked ? 'rgba(139, 115, 85, 0.3)' : '#8b7355';
        context.lineWidth = this.isLocked ? 1 : 2;
        context.stroke();

        context.restore();
    }

    createPath(context) {
        context.beginPath();
        const { top, right, bottom, left } = this.shape;
        const [x, y] = [this.x, this.y];
        const [pw, ph] = [pieceWidth, pieceHeight];
        const ks = knobSize;
        const KNOB_LENGTH_PERCENT = 0.4;
        const MARGIN = (1 - KNOB_LENGTH_PERCENT) / 2;

        context.moveTo(x, y);

        if (top !== 0) {
            context.lineTo(x + pw * MARGIN, y);
            context.lineTo(x + pw * MARGIN, y - ks * top);
            context.lineTo(x + pw * (1 - MARGIN), y - ks * top);
            context.lineTo(x + pw * (1 - MARGIN), y);
        }
        context.lineTo(x + pw, y);

        if (right !== 0) {
            context.lineTo(x + pw, y + ph * MARGIN);
            context.lineTo(x + pw + ks * right, y + ph * MARGIN);
            context.lineTo(x + pw + ks * right, y + ph * (1 - MARGIN));
            context.lineTo(x + pw, y + ph * (1 - MARGIN));
        }
        context.lineTo(x + pw, y + ph);

        if (bottom !== 0) {
            context.lineTo(x + pw * (1 - MARGIN), y + ph);
            context.lineTo(x + pw * (1 - MARGIN), y + ph + ks * bottom);
            context.lineTo(x + pw * MARGIN, y + ph + ks * bottom);
            context.lineTo(x + pw * MARGIN, y + ph);
        }
        context.lineTo(x, y + ph);

        if (left !== 0) {
            context.lineTo(x, y + ph * (1 - MARGIN));
            context.lineTo(x - ks * left, y + ph * (1 - MARGIN));
            context.lineTo(x - ks * left, y + ph * MARGIN);
            context.lineTo(x, y + ph * MARGIN);
        }
        context.lineTo(x, y);
        context.closePath();
    }

    isPointInside(x, y) {
        if (!ctx) return false;
        this.createPath(ctx);
        return ctx.isPointInPath(x, y);
    }
}

function init() {
    //Cari canvas di sini, bukan di global scope
    canvas = document.getElementById('puzzle-canvas');
    ctx = canvas ? canvas.getContext('2d') : null;
    
    console.log('Canvas found:', canvas);
    console.log('Context found:', ctx);
    
    if (!canvas || !ctx) {
        console.error('Canvas not found or context failed');
        // Coba cari lagi setelah delay
        setTimeout(() => {
            canvas = document.getElementById('puzzle-canvas');
            ctx = canvas ? canvas.getContext('2d') : null;
            if (canvas && ctx) {
                console.log('Canvas found on retry');
                init(); // Coba lagi
            }
        }, 1000);
        return;
    }

    canvas.style.visibility = 'visible';
    
    sourceImage.onload = () => {
        console.log("Image loaded successfully!");
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;

        const imageAspectRatio = sourceImage.width / sourceImage.height;
        let maxBoardWidth = canvas.width * PUZZLE_BOARD_SCALE;
        let maxBoardHeight = canvas.height * PUZZLE_BOARD_SCALE;

        if (maxBoardWidth / imageAspectRatio > maxBoardHeight) {
            puzzleHeight = maxBoardHeight;
            puzzleWidth = puzzleHeight * imageAspectRatio;
        } else {
            puzzleWidth = maxBoardWidth;
            puzzleHeight = puzzleWidth / imageAspectRatio;
        }

        puzzleOffsetX = (canvas.width - puzzleWidth) / 2;
        puzzleOffsetY = (canvas.height - puzzleHeight) / 2;

        pieceWidth = puzzleWidth / PUZZLE_COLS;
        pieceHeight = puzzleHeight / PUZZLE_ROWS;
        
        const isMobile = window.innerWidth <= 1024;
        knobSize = Math.min(pieceWidth, pieceHeight) * (isMobile ? 0.2 : 0.25);

        generatePieces();
        addEventListeners();
        requestAnimationFrame(gameLoop);
    };
    
    sourceImage.onerror = () => {
        console.error("Failed to load image:", IMAGE_SRC);
        const puzzleContainer = document.querySelector('#puzzleContainer');
        if (puzzleContainer) {
            const errorMsg = document.createElement('div');
            errorMsg.className = 'absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white/90 p-5 rounded-lg text-center text-warm-brown text-sm z-50 max-w-xs';
            errorMsg.innerHTML = `
                <p class="mb-2">⚠️ Gambar tidak dapat dimuat</p>
                <p class="text-xs">
                    Pastikan file gambar ada di: <br>
                    <code class="bg-gray-100 px-1 py-0.5 rounded text-xs">${IMAGE_SRC}</code>
                </p>
            `;
            puzzleContainer.appendChild(errorMsg);
        }
    };
    
    console.log("Loading image from:", IMAGE_SRC);
    sourceImage.src = IMAGE_SRC;
}

function generatePieces() {
    pieces = [];
    const shapes = Array(PUZZLE_ROWS).fill(null).map(() => Array(PUZZLE_COLS).fill(null));

    for (let r = 0; r < PUZZLE_ROWS; r++) {
        for (let c = 0; c < PUZZLE_COLS; c++) {
            const shape = {};
            shape.top = (r > 0) ? -shapes[r - 1][c].bottom : 0;
            shape.right = (c < PUZZLE_COLS - 1) ? (Math.random() > 0.5 ? 1 : -1) : 0;
            shape.bottom = (r < PUZZLE_ROWS - 1) ? (Math.random() > 0.5 ? 1 : -1) : 0;
            shape.left = (c > 0) ? -shapes[r][c - 1].right : 0;

            shapes[r][c] = shape;
            pieces.push(new Piece(r, c, shape));
        }
    }
}

function addEventListeners() {
    if (!canvas) return;

    const options = { passive: false };
    
    canvas.addEventListener('mousedown', onMouseDown);
    canvas.addEventListener('mousemove', onMouseMove);
    canvas.addEventListener('mouseup', onMouseUp);
    
    canvas.addEventListener('touchstart', onMouseDown, options);
    canvas.addEventListener('touchmove', onMouseMove, options);
    canvas.addEventListener('touchend', onMouseUp, options);
    
    canvas.addEventListener('contextmenu', (e) => e.preventDefault());
    
    window.addEventListener('resize', debounce(() => {
        init();
    }, 250));
}

function getMousePos(e) {
    if (!canvas) return { x: 0, y: 0 };

    const rect = canvas.getBoundingClientRect();
    const touch = e.touches ? e.touches[0] : null;
    
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;
    
    const clientX = touch ? touch.clientX : e.clientX;
    const clientY = touch ? touch.clientY : e.clientY;
    
    return {
        x: (clientX - rect.left) * scaleX,
        y: (clientY - rect.top) * scaleY,
    };
}

function onMouseDown(e) {
    if (isSolved) return;
    e.preventDefault();
    const pos = getMousePos(e);

    for (let i = pieces.length - 1; i >= 0; i--) {
        if (!pieces[i].isLocked && pieces[i].isPointInside(pos.x, pos.y)) {
            draggedPiece = pieces[i];
            pieces.push(pieces.splice(i, 1)[0]);
            offsetX = pos.x - draggedPiece.x;
            offsetY = pos.y - draggedPiece.y;
            break;
        }
    }
}

function onMouseMove(e) {
    if (draggedPiece) {
        e.preventDefault();
        const pos = getMousePos(e);
        draggedPiece.x = pos.x - offsetX;
        draggedPiece.y = pos.y - offsetY;
    }
}

function onMouseUp() {
    if (draggedPiece) {
        const isMobile = window.innerWidth <= 1024;
        const snapDistance = isMobile ? pieceWidth * 0.4 : pieceWidth * 0.3;
        
        if (Math.abs(draggedPiece.x - draggedPiece.correctX) < snapDistance &&
            Math.abs(draggedPiece.y - draggedPiece.correctY) < snapDistance) {

            draggedPiece.x = draggedPiece.correctX;
            draggedPiece.y = draggedPiece.correctY;
            draggedPiece.isLocked = true;

            const index = draggedPiece.row * PUZZLE_COLS + draggedPiece.col;
            const lastPiece = pieces.pop();
            pieces.splice(index, 0, lastPiece);

            checkWinCondition();
        }
        draggedPiece = null;
    }
}

function checkWinCondition() {
    const allLocked = pieces.every(p => p.isLocked);
    if (allLocked) {
        isSolved = true;
        onPuzzleSolved();
    }
}

function onPuzzleSolved() {
    setTimeout(() => {
        if (!ctx || !canvas) return;

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        ctx.save();
        ctx.fillStyle = 'white';
        ctx.fillRect(puzzleOffsetX, puzzleOffsetY, puzzleWidth, puzzleHeight);
        ctx.restore();
        
        ctx.drawImage(sourceImage, puzzleOffsetX, puzzleOffsetY, puzzleWidth, puzzleHeight);
        
        ctx.save();
        ctx.strokeStyle = 'white';
        ctx.lineWidth = 3;
        ctx.strokeRect(puzzleOffsetX, puzzleOffsetY, puzzleWidth, puzzleHeight);
        ctx.restore();
        
        const puzzleSuccess = document.getElementById('puzzleSuccess');
        if (puzzleSuccess) {
            puzzleSuccess.classList.add('opacity-100');
            puzzleSuccess.classList.remove('opacity-0');
        }
        
        setTimeout(() => {
            const puzzleContainer = document.getElementById('puzzleContainer');
            const openButton = document.getElementById('openButton');
            
            if (puzzleContainer) {
                puzzleContainer.classList.add('opacity-0', 'pointer-events-none');
            }
            
            if (openButton) {
                openButton.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
                openButton.classList.remove('opacity-0', 'translate-y-5', 'pointer-events-none');
            }
        }, 1000);
    }, 500);
}

function gameLoop() {
    if (isSolved || !ctx || !canvas) return;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.save();
    ctx.fillStyle = 'white';
    ctx.fillRect(puzzleOffsetX, puzzleOffsetY, puzzleWidth, puzzleHeight);
    ctx.restore();

    ctx.save();
    ctx.strokeStyle = 'white';
    ctx.lineWidth = 3;
    ctx.strokeRect(puzzleOffsetX, puzzleOffsetY, puzzleWidth, puzzleHeight);
    ctx.restore();

    ctx.save();
    ctx.globalAlpha = 0.15;
    ctx.drawImage(sourceImage, puzzleOffsetX, puzzleOffsetY, puzzleWidth, puzzleHeight);
    ctx.restore();

    for (const piece of pieces) {
        piece.draw(ctx);
    }

    requestAnimationFrame(gameLoop);
}

// ===== MAIN FUNCTIONS =====
function openInvitation() {
    const button = document.getElementById('openButton');
    if (!button) return;

    const span = button.querySelector('span');
    const originalText = span ? span.textContent : button.textContent;

    button.classList.add('bg-dark-brown/90');
    if (span) {
        span.textContent = 'Membuka...';
    } else {
        button.textContent = 'Membuka...';
    }
    button.disabled = true;

    setTimeout(() => {
        const puzzleSection = document.getElementById('puzzleSection');
        const weddingInvitation = document.getElementById('weddingInvitation');
        const hero = document.getElementById('hero');

        if (puzzleSection) puzzleSection.style.display = 'none';
        if (weddingInvitation) weddingInvitation.classList.remove('hidden');
        if (hero) hero.scrollIntoView({ behavior: 'smooth' });
        
        initWeddingEffects();
        initAudioPlayer(); // ← Tambahkan ini
        trackEvent('invitation_opened');
    }, 1500);
}
// ===== GALLERY FUNCTIONS =====
function initGallery() {
    // Build gallery data from gal1.webp ... gal14.webp with auto heights by aspect ratio
    const totalImages = 14;
    const numColumns = 4;

    function loadImage(src) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = function() {
                resolve({ src, width: img.naturalWidth || img.width, height: img.naturalHeight || img.height });
            };
            img.onerror = function() {
                // Fallback if image can't be loaded; use default medium height
                resolve({ src, width: 1, height: 1 });
            };
            img.src = src;
        });
    }

    function getHeightClassFromAspect(aspect, index) {
        // aspect = width / height
        // Landscape => smaller height; Portrait => taller height; Square-ish => medium
        if (aspect > 1.2) {
            // landscape
            return index % 2 === 0 ? 'h-48' : 'h-56';
        } else if (aspect < 0.85) {
            // portrait
            return index % 2 === 0 ? 'h-80' : 'h-72';
        } else {
            // square-ish
            return 'h-64';
        }
    }

    const sources = Array.from({ length: totalImages }, (_, i) => `./assets/gal${i + 1}.webp`);

    Promise.all(sources.map(loadImage)).then((loaded) => {
        const photos = loaded.map((meta, idx) => {
            const aspect = meta.width / Math.max(1, meta.height);
            return {
                src: meta.src,
                alt: `Wedding Photo ${idx + 1}`,
                height: getHeightClassFromAspect(aspect, idx)
            };
        });

        // Distribute photos into columns (round-robin)
        const columns = Array.from({ length: numColumns }, () => []);
        photos.forEach((photo, i) => {
            columns[i % numColumns].push(photo);
        });

        const galleryData = columns;
        loadGallery(galleryData);
    });

    function createGalleryColumn(photos, columnWidth = 'w-40') {
        let columnHTML = `<div class="gallery-column ${columnWidth} flex-shrink-0">`;
        
        photos.forEach(photo => {
            columnHTML += `
                <div class="${photo.height} bg-gray-200 overflow-hidden transition-all duration-300 hover:scale-105 cursor-pointer gallery-item" 
                    data-src="${photo.src}" data-alt="${photo.alt}">
                    <img src="${photo.src}" alt="${photo.alt}" class="w-full h-full object-cover" 
                        onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full bg-gradient-to-br from-warm-brown/20 to-light-brown/20 flex items-center justify-center\\'>
                        <span class=\\'text-warm-brown text-sm\\'>Photo</span></div>'" />
                </div>
            `;
        });
        
        columnHTML += '</div>';
        return columnHTML;
    }

    function loadGallery(galleryData) {
        const galleryGrid = document.getElementById('galleryGrid');
        const galleryContainer = document.getElementById('galleryContainer');
        const galleryLoading = document.getElementById('galleryLoading');
        const galleryError = document.getElementById('galleryError');

        if (!galleryGrid) return;

        try {
            setTimeout(() => {
                let galleryHTML = '';
                const columnWidths = ['w-44', 'w-48', 'w-40', 'w-52'];
                
                galleryData.forEach((columnPhotos, index) => {
                    const width = columnWidths[index] || 'w-40';
                    galleryHTML += createGalleryColumn(columnPhotos, width);
                });

                galleryGrid.innerHTML = galleryHTML;
                
                if (galleryLoading) galleryLoading.classList.add('hidden');
                if (galleryContainer) galleryContainer.classList.remove('hidden');

                initGalleryClickHandlers();
                trackEvent('gallery_loaded');
            }, 1000);
        } catch (error) {
            console.error('Gallery loading error:', error);
            if (galleryLoading) galleryLoading.classList.add('hidden');
            if (galleryError) galleryError.classList.remove('hidden');
            trackEvent('gallery_error', { error: error.message });
        }
    }

    function initGalleryClickHandlers() {
        const galleryItems = document.querySelectorAll('.gallery-item');
        galleryItems.forEach(item => {
            item.addEventListener('click', (e) => {
                const src = item.dataset.src;
                const alt = item.dataset.alt;
                
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4';
                modal.innerHTML = `
                    <div class="relative max-w-full max-h-full">
                        <img src="${src}" alt="${alt}" class="max-w-full max-h-full object-contain" />
                        <button class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 w-10 h-10 flex items-center justify-center" onclick="this.parentElement.parentElement.remove()">
                            ×
                        </button>
                    </div>
                `;
                document.body.appendChild(modal);
                
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.remove();
                    }
                });

                trackEvent('gallery_image_viewed', { src, alt });
            });
        });
    }

    // rendering happens after images are measured (see Promise above)
}

// ===== VIDEO INITIALIZATION =====
function initVideoSection() {
    const iframe = document.querySelector('iframe[src*="youtube"]');
    
    if (iframe) {
        const videoObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    console.log('Video is in view');
                    trackEvent('video_in_view');
                    videoObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.3
        });
        
        videoObserver.observe(iframe);
    }
}

// ===== WEDDING EFFECTS =====
function initWeddingEffects() {
    const heroFrame = document.getElementById('heroFrame');
    const heroImage = document.getElementById('heroImage');
    
    const scrollHandler = debounce(() => {
        const scrolled = window.pageYOffset;
        const windowHeight = window.innerHeight;
        
        const heroScrollProgress = Math.min(scrolled / (windowHeight * 0.5), 1);
        
        if (heroScrollProgress > 0.2) {
            if (heroFrame) heroFrame.classList.add('expanded');
            if (heroImage) heroImage.classList.add('colored');
        } else {
            if (heroFrame) heroFrame.classList.remove('expanded');
            if (heroImage) heroImage.classList.remove('colored');
        }
        
        const coupleFrames = document.querySelectorAll('.couple-frame');
        coupleFrames.forEach(frame => {
            const rect = frame.getBoundingClientRect();
            const coupleImage = frame.querySelector('.couple-image');
            
            const sectionCenter = rect.top + (rect.height / 2);
            const screenCenter = windowHeight / 2;
            
            if (sectionCenter < screenCenter && rect.bottom > 0) {
                if (coupleImage) coupleImage.classList.add('expanded');
            } else {
                if (coupleImage) coupleImage.classList.remove('expanded');
            }
        });
    }, 10);

    window.addEventListener('scroll', scrollHandler);
   
    initGallery();
    initVideoSection();

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
}

// ===== AUDIO PLAYER =====
let backgroundMusic = null;
let isPlaying = false;

function initAudioPlayer() {
    backgroundMusic = document.getElementById('backgroundMusic');
    const audioToggle = document.getElementById('audioToggle');
    const audioPlayer = document.getElementById('audioPlayer');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');
    
    if (!backgroundMusic || !audioToggle) return;
    
    // Show audio player
    audioPlayer.classList.remove('hidden');
    
    // Set volume
    backgroundMusic.volume = 0.2;
    
    // Audio toggle click handler
    audioToggle.addEventListener('click', toggleAudio);
    
    // Audio events
    backgroundMusic.addEventListener('play', () => {
        isPlaying = true;
        playIcon.classList.add('hidden');
        pauseIcon.classList.remove('hidden');
        audioToggle.classList.add('playing');
        trackEvent('audio_started');
    });
    
    backgroundMusic.addEventListener('pause', () => {
        isPlaying = false;
        playIcon.classList.remove('hidden');
        pauseIcon.classList.add('hidden');
        audioToggle.classList.remove('playing');
        trackEvent('audio_paused');
    });
    
    backgroundMusic.addEventListener('error', (e) => {
        console.error('Audio error:', e);
        trackEvent('audio_error', { error: e.message });
    });
    
    // Auto-play with user interaction (modern browsers require this)
    setTimeout(() => {
        playAudio();
    }, 2000);
}

function toggleAudio() {
    if (!backgroundMusic) return;
    
    if (isPlaying) {
        pauseAudio();
    } else {
        playAudio();
    }
}

function playAudio() {
    if (!backgroundMusic) return;
    
    const playPromise = backgroundMusic.play();
    
    if (playPromise !== undefined) {
        playPromise
            .then(() => {
                console.log('Audio started successfully');
            })
            .catch(error => {
                console.log('Auto-play prevented:', error);
                // Auto-play was prevented, user needs to interact first
            });
    }
}

function pauseAudio() {
    if (!backgroundMusic) return;
    backgroundMusic.pause();
}

// Volume control functions
function setVolume(volume) {
    if (!backgroundMusic) return;
    backgroundMusic.volume = Math.max(0, Math.min(1, volume));
}

function fadeInAudio(duration = 2000) {
    if (!backgroundMusic) return;
    
    backgroundMusic.volume = 0;
    playAudio();
    
    const fadeInterval = setInterval(() => {
        if (backgroundMusic.volume < 0.3) {
            backgroundMusic.volume += 0.01;
        } else {
            clearInterval(fadeInterval);
        }
    }, duration / 30);
}

function fadeOutAudio(duration = 1000) {
    if (!backgroundMusic) return;
    
    const fadeInterval = setInterval(() => {
        if (backgroundMusic.volume > 0.01) {
            backgroundMusic.volume -= 0.01;
        } else {
            backgroundMusic.volume = 0;
            pauseAudio();
            clearInterval(fadeInterval);
        }
    }, duration / 30);
}

// ===== RSVP FUNCTIONS =====
let rsvpMessages = [
    {
        name: "Digiland Space",
        message: "Selamat menempuh hidup baru Diyang Gibran! Semoga selalu bersama selamanya",
        attendance: "hadir",
        timestamp: new Date(),
        id: 1
    },
    {
        name: "Keluarga Besar",
        message: "Barakallahu lakuma wa baraka alaikuma wa jama'a bainakuma fi khair",
        attendance: "hadir",
        timestamp: new Date(),
        id: 2
    },
    {
        name: "Sahabat Lama",
        message: "Semoga menjadi keluarga yang sakinah, mawaddah, warahmah",
        attendance: "hadir",
        timestamp: new Date(),
        id: 3
    },
    {
        name: "Teman Kerja",
        message: "Selamat ya! Semoga langgeng sampai maut memisahkan",
        attendance: "hadir",
        timestamp: new Date(),
        id: 4
    },
    {
        name: "Tetangga",
        message: "Turut berbahagia atas pernikahan kalian. Semoga bahagia selalu!",
        attendance: "hadir",
        timestamp: new Date(),
        id: 5
    }
];

function validateForm(formData) {
    const { name, message, attendance } = formData;
    
    if (!name || name.length < 2) {
        return { valid: false, error: 'Nama harus diisi minimal 2 karakter' };
    }
    
    if (!message || message.length < 10) {
        return { valid: false, error: 'Ucapan harus diisi minimal 10 karakter' };
    }
    
    if (!attendance) {
        return { valid: false, error: 'Harap pilih apakah Anda akan menghadiri' };
    }
    
    return { valid: true };
}

function handleRSVPSubmission(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('name')?.value.trim() || '',
        message: document.getElementById('message')?.value.trim() || '',
        attendance: document.getElementById('attendance')?.value || ''
    };
    
    const validation = validateForm(formData);
    
    if (!validation.valid) {
        showErrorMessage(validation.error);
        return;
    }
    
    const newMessage = {
        ...formData,
        timestamp: new Date(),
        id: Date.now() + Math.random()
    };
    
    rsvpMessages.unshift(newMessage);
    
    showSuccessMessage();
    
    setTimeout(() => {
        displayAllMessages();
        scrollToNewestMessage();
    }, 500);
    
    setTimeout(() => {
        const form = document.getElementById('rsvpForm');
        if (form) form.reset();
    }, 1000);
    
    setTimeout(() => {
        const messagesSection = document.querySelector('#messagesContainer')?.parentElement;
        if (messagesSection) {
            messagesSection.scrollIntoView({ behavior: 'smooth' });
        }
    }, 1500);

    trackEvent('rsvp_submitted', { attendance: formData.attendance });
}

function showSuccessMessage() {
    const button = document.querySelector('#rsvpForm button[type="submit"]');
    if (!button) return;

    const originalText = button.textContent;
    
    button.textContent = 'Terkirim ✓';
    button.classList.add('bg-green-600');
    button.classList.remove('bg-dark-brown', 'hover:bg-warm-brown');
    button.disabled = true;
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-600');
        button.classList.add('bg-dark-brown', 'hover:bg-warm-brown');
        button.disabled = false;
    }, 2000);
}

function showErrorMessage(message) {
    const button = document.querySelector('#rsvpForm button[type="submit"]');
    if (!button) return;

    const originalText = button.textContent;
    
    button.textContent = message;
    button.classList.add('bg-red-600');
    button.classList.remove('bg-dark-brown', 'hover:bg-warm-brown');
    button.disabled = true;
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-red-600');
        button.classList.add('bg-dark-brown', 'hover:bg-warm-brown');
        button.disabled = false;
    }, 3000);
}

function displayAllMessages() {
    const container = document.getElementById('messagesContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    rsvpMessages.forEach(msg => {
        const messageElement = createMessageElement(msg);
        container.appendChild(messageElement);
    });
}

function createMessageElement(msg) {
    const div = document.createElement('div');
    div.className = 'bg-white/80 backdrop-blur-sm p-4 rounded-lg border border-warm-brown/20 shadow-sm flex-shrink-0';
    
    div.innerHTML = `
        <div class="text-center">
            <h4 class="font-medium text-warm-brown mb-2 text-base">${escapeHtml(msg.name)}</h4>
            <p class="text-sm text-gray-700 leading-relaxed">${escapeHtml(msg.message)}</p>
        </div>
    `;
    
    return div;
}

function scrollToNewestMessage() {
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = 0;
    }
}

// ===== GIFT FUNCTIONS =====
function showGiftDetails() {
    const initialState = document.getElementById('giftInitial');
    const detailsState = document.getElementById('giftDetails');
    
    if (!initialState || !detailsState) return;

    initialState.style.opacity = '0';
    initialState.style.transform = 'scale(0.95)';
    initialState.style.transition = 'all 0.3s ease-out';
    
    setTimeout(() => {
        initialState.classList.add('hidden');
        detailsState.classList.remove('hidden');
        
        detailsState.style.opacity = '0';
        detailsState.style.transform = 'scale(0.95)';
        detailsState.style.transition = 'all 0.3s ease-out';
        
        setTimeout(() => {
            detailsState.style.opacity = '1';
            detailsState.style.transform = 'scale(1)';
        }, 50);
    }, 300);

    trackEvent('gift_details_shown');
}

function copyAccountNumber() {
    const accountNumber = '9030166111';
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(accountNumber).then(() => {
            showCopySuccess();
        }).catch(() => {
            fallbackCopyTextToClipboard(accountNumber);
        });
    } else {
        fallbackCopyTextToClipboard(accountNumber);
    }

    trackEvent('account_number_copied');
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showCopySuccess();
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
        showCopyError();
    }
    
    document.body.removeChild(textArea);
}

function showCopySuccess() {
    const copyBtn = document.getElementById('copyBtn');
    if (!copyBtn) return;

    const originalText = copyBtn.textContent;
    
    copyBtn.textContent = 'COPIED ✓';
    copyBtn.classList.remove('bg-dark-brown', 'hover:bg-warm-brown');
    copyBtn.classList.add('bg-green-600');
    copyBtn.disabled = true;
    
    setTimeout(() => {
        copyBtn.textContent = originalText;
        copyBtn.classList.remove('bg-green-600');
        copyBtn.classList.add('bg-dark-brown', 'hover:bg-warm-brown');
        copyBtn.disabled = false;
    }, 2000);
}

function showCopyError() {
    const copyBtn = document.getElementById('copyBtn');
    if (!copyBtn) return;

    const originalText = copyBtn.textContent;
    
    copyBtn.textContent = 'ERROR';
    copyBtn.classList.remove('bg-dark-brown', 'hover:bg-warm-brown');
    copyBtn.classList.add('bg-red-600');
    
    setTimeout(() => {
        copyBtn.textContent = originalText;
        copyBtn.classList.remove('bg-red-600');
        copyBtn.classList.add('bg-dark-brown', 'hover:bg-warm-brown');
    }, 2000);
}

// ===== PARTICLE EFFECTS =====
function createParticle() {
    const particleContainer = document.querySelector('.invitation-bg > div');
    if (!particleContainer) return;

    const particle = document.createElement('div');
    particle.className = 'particle absolute w-1 h-1 bg-warm-brown/30 rounded-full pointer-events-none';
    particle.style.left = Math.random() * 100 + '%';
    particle.style.animationDelay = Math.random() * 2 + 's';
    particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
    
    // Add CSS animation for floating particles
    particle.style.animation = `float-particle ${particle.style.animationDuration} linear infinite ${particle.style.animationDelay}`;
    
    particleContainer.appendChild(particle);

    setTimeout(() => {
        if (particle.parentNode) {
            particle.remove();
        }
    }, 10000);
}

// ===== UTILITY FUNCTIONS =====
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function handleImageError(img) {
    if (!img) return;
    img.onerror = null;
    img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OTk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBmb3VuZDwvdGV4dD4KICA8L3N2Zz4K';
}

// ===== PERFORMANCE OPTIMIZATIONS =====
function preloadImages() {
    const imageUrls = [
        './assets/CroppedGib.webp',
        './assets/CroppedDyg.webp',
        './assets/farewell.webp',
        './assets/gal1.webp',
        './assets/gal2.webp',
        './assets/gal3.webp',
        './assets/gal4.webp',
        './assets/gal5.webp',
        './assets/gal6.webp',
        './assets/gal7.webp',
        './assets/gal8.webp',
        './assets/gal9.webp',
        './assets/gal10.webp',
        './assets/gal11.webp',
        './assets/gal12.webp',
        './assets/gal13.webp',
        './assets/gal14.webp',
        './assets/cover1.webp',
        './assets/digiland-logo.png',
        './assets/bismillah.png'
    ];
    
    imageUrls.forEach(url => {
        const img = new Image();
        img.src = url;
        img.onerror = () => handleImageError(img);
    });
}

// ===== ACCESSIBILITY IMPROVEMENTS =====
function initAccessibility() {
    // Add keyboard navigation for gallery
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.querySelector('.fixed.inset-0.bg-black\\/80');
            if (modal) {
                modal.remove();
                trackEvent('modal_closed_keyboard');
            }
        }
    });
    
    // Add focus management
    const focusableElements = document.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    
    focusableElements.forEach(element => {
        element.addEventListener('focus', (e) => {
            e.target.style.outline = '2px solid #8b7355';
            e.target.style.outlineOffset = '2px';
        });
        
        element.addEventListener('blur', (e) => {
            e.target.style.outline = 'none';
        });
    });

    // Add aria labels for better screen reader support
    const puzzleCanvas = document.getElementById('puzzle-canvas');
    if (puzzleCanvas) {
        puzzleCanvas.setAttribute('aria-label', 'Puzzle interaktif - seret potongan gambar ke posisi yang tepat');
        puzzleCanvas.setAttribute('role', 'application');
    }
}

// ===== MOBILE OPTIMIZATIONS =====
function initMobileOptimizations() {
    // Prevent zoom on iOS when focusing inputs
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.style.fontSize = '16px';
            });
            input.addEventListener('blur', () => {
                input.style.fontSize = '';
            });
        });
    }
    
    // Handle orientation change
    window.addEventListener('orientationchange', () => {
        setTimeout(() => {
            window.scrollTo(0, window.pageYOffset);
            // Reinitialize canvas if needed
            if (canvas && !isSolved) {
                init();
            }
        }, 100);
    });

    // Add touch-friendly scroll indicators
    const scrollIndicators = document.querySelectorAll('[class*="scroll"]');
    scrollIndicators.forEach(indicator => {
        indicator.style.touchAction = 'manipulation';
    });
}

// ===== ANALYTICS & TRACKING =====
function trackEvent(eventName, eventData = {}) {
    // Add your analytics tracking here (Google Analytics, Facebook Pixel, etc.)
    console.log('Event tracked:', eventName, eventData);
    
    // Example implementation for Google Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', eventName, eventData);
    }
    
    // Example implementation for Facebook Pixel
    if (typeof fbq !== 'undefined') {
        fbq('trackCustom', eventName, eventData);
    }
}

// ===== SCROLL EFFECTS =====
function initScrollEffects() {
    const scrollHandler = debounce(() => {
        const scrolled = window.pageYOffset;
        const windowHeight = window.innerHeight;
        
        // Add scroll-based animations or effects
        document.body.style.setProperty('--scroll', scrolled / windowHeight);
        
        // Track scroll depth for analytics
        const scrollDepth = Math.round((scrolled / (document.body.scrollHeight - windowHeight)) * 100);
        if (scrollDepth > 0 && scrollDepth % 25 === 0) {
            trackEvent('scroll_depth', { depth: scrollDepth });
        }
    }, 10);

    window.addEventListener('scroll', scrollHandler, { passive: true });
}

// ===== FORM ENHANCEMENTS =====
function initFormEnhancements() {
    const form = document.getElementById('rsvpForm');
    if (!form) return;

    // Add real-time validation
    const nameInput = document.getElementById('name');
    const messageInput = document.getElementById('message');
    
    if (nameInput) {
        nameInput.addEventListener('input', debounce((e) => {
            const value = e.target.value.trim();
            if (value.length >= 2) {
                e.target.style.borderBottomColor = '#10b981'; // green
            } else {
                e.target.style.borderBottomColor = '#ef4444'; // red
            }
        }, 300));
    }

    if (messageInput) {
        messageInput.addEventListener('input', debounce((e) => {
            const value = e.target.value.trim();
            if (value.length >= 10) {
                e.target.style.borderBottomColor = '#10b981'; // green
            } else {
                e.target.style.borderBottomColor = '#ef4444'; // red
            }
        }, 300));
    }
}

// ===== INITIALIZATION =====
window.addEventListener('load', function() {
    const container = document.querySelector('.invitation-bg');
    if (container) {
        container.style.opacity = '0';

        setTimeout(() => {
            container.style.transition = 'opacity 1s ease-in-out';
            container.style.opacity = '1';

            setTimeout(() => {
                init();
                preloadImages();
                initScrollEffects();
                initAccessibility();
                initMobileOptimizations();
                initFormEnhancements();
                
                // Start particle effects
                setInterval(createParticle, 2000);
                
                trackEvent('page_loaded', {
                    userAgent: navigator.userAgent,
                    timestamp: new Date().toISOString(),
                    viewport: {
                        width: window.innerWidth,
                        height: window.innerHeight
                    }
                });
            }, 500);
        }, 100);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Wait longer untuk memastikan canvas ter-render
    setTimeout(() => {
        console.log('Starting init after delay...');
        init();
        preloadImages();
        initScrollEffects();
        initAccessibility();
        initMobileOptimizations();
        initFormEnhancements();
        
        // Start particle effects
        setInterval(createParticle, 2000);
    }, 2000);
    // Initialize dynamic name system
    initDynamicName();
    
    const rsvpForm = document.getElementById('rsvpForm');
    if (rsvpForm) {
        rsvpForm.addEventListener('submit', handleRSVPSubmission);
    }

    setTimeout(() => {
        if (document.getElementById('messagesContainer')) {
            displayAllMessages();
        }
    }, 1000);
    
    trackEvent('dom_ready', {
        timestamp: new Date().toISOString()
    });
});

// ===== ERROR HANDLING =====
window.addEventListener('error', (e) => {
    console.error('JavaScript error:', e.error);
    trackEvent('javascript_error', {
        message: e.message,
        filename: e.filename,
        line: e.lineno,
        column: e.colno,
        stack: e.error ? e.error.stack : null
    });
});

window.addEventListener('unhandledrejection', (e) => {
    console.error('Unhandled promise rejection:', e.reason);
    trackEvent('promise_rejection', {
        reason: e.reason.toString(),
        timestamp: new Date().toISOString()
    });
});

// ===== CLEANUP =====
window.addEventListener('beforeunload', () => {
    // Cleanup any timers or resources
    if (window.resizeTimeout) {
        clearTimeout(window.resizeTimeout);
    }
    
    // Track page unload
    trackEvent('page_unload', {
        timestamp: new Date().toISOString()
    });
});

// ===== SERVICE WORKER REGISTRATION (Optional) =====
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('SW registered: ', registration);
                trackEvent('service_worker_registered');
            })
            .catch((registrationError) => {
                console.log('SW registration failed: ', registrationError);
                trackEvent('service_worker_failed', { error: registrationError.toString() });
            });
    });
}

// ===== EXPORT FUNCTIONS FOR GLOBAL ACCESS =====
window.weddingInvitation = {
    openInvitation,
    showGiftDetails,
    copyAccountNumber,
    trackEvent
};
