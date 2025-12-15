document.addEventListener('DOMContentLoaded', () => {
    const hotspots = document.querySelectorAll('.hotspot');
    const modal = document.getElementById('hotspotModal');
    const modalText = document.getElementById('modalText');
    const modalTextCtl = document.getElementById('modalTextCtl');
    const modalCatalog = document.getElementById('modalCatalog');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');

    hotspots.forEach(hotspot => {
        hotspot.addEventListener('click', (e) => {
            e.stopPropagation();

            modalText.textContent = hotspot.dataset.text;

            const modalImageDiv = document.querySelector('.modal-image'); // get the container

            // only show image if data-img exists and is not empty
            if (hotspot.dataset.img && hotspot.dataset.img.trim()) {
                modalImage.src = hotspot.dataset.img;
                modalImage.style.display = 'block';
                if (modalImageDiv) modalImageDiv.style.display = 'block'; // show image container
                modalTextCtl.textContent = hotspot.dataset.textCtl || hotspot.dataset.textctl || '';
            } else {
                modalImage.style.display = 'none';
                if (modalImageDiv) modalImageDiv.style.display = 'none'; // hide image container
                modalTextCtl.textContent = '';
            }

            if (modalCatalog) modalCatalog.style.display = modalTextCtl.textContent ? 'block' : 'none';

            modal.classList.add('show');
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
        });
    });


    closeModal.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    modal.addEventListener('click', (e) => {
        if (!e.target.closest('.modal-content')) modal.classList.remove('show');
    });

    // Colofon modal functionality
    const openColofon = document.getElementById('openColofon');
    const closeColofon = document.getElementById('closeColofon');
    const colofonModal = document.getElementById('colofonModal');

    if (openColofon && colofonModal) {
        openColofon.addEventListener('click', () => {
            colofonModal.classList.add('show');
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeColofon && colofonModal) {
        closeColofon.addEventListener('click', () => {
            colofonModal.style.display = 'none';
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
        });
    }

    if (colofonModal) {
        colofonModal.addEventListener('click', (e) => {
            if (!e.target.closest('.modal-content')) {
                colofonModal.classList.remove('show');
                document.documentElement.style.overflow = '';
                document.body.style.overflow = '';
            }
        });
    }

});



document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('globalOverlay');
    const globalPopup = document.getElementById('globalPopup');
    const popupContent = document.getElementById('popupContent');
    const closePopup = document.getElementById('closePopup');
    const toggleMagnify = document.getElementById('toggleMagnify');
    let magnifierEnabled = false;

    document.querySelectorAll('.card-front img').forEach(img => {
        img.addEventListener('click', (e) => {
            const card = e.currentTarget.closest('.card');
            if (!card || !globalPopup || !overlay) return;

            const imageId = e.currentTarget.dataset.id;

            // Hide all popup items
            document.querySelectorAll('.popup-item').forEach(item => {
                item.classList.remove('show');
            });

            // Show the corresponding popup item
            const popupItem = document.querySelector(`.popup-item[data-id="${imageId}"]`);
            if (popupItem) {
                popupItem.classList.add('show');

                // magnifier: cleanup & init for this popup image if enabled
                document.querySelectorAll('.img-magnifier-glass').forEach(g => g.remove());
                if (magnifierEnabled) {
                    const popupImg = popupItem.querySelector('img');
                    if (popupImg) {
                        if (!popupImg.id) popupImg.id = 'popup-img-' + (popupItem.dataset.id || imageId);
                        if (popupImg.complete && popupImg.naturalWidth) {
                            magnify(popupImg.id, 3);
                        } else {
                            const onLoad = () => { popupImg.removeEventListener('load', onLoad); magnify(popupImg.id, 3); };
                            popupImg.addEventListener('load', onLoad);
                        }
                    }
                }
            }

            overlay.classList.add('show');
            globalPopup.classList.add('show');
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
            globalPopup.setAttribute('aria-hidden', 'false');
            overlay.setAttribute('aria-hidden', 'false');
        });
    });

    function closePopupFn() {
        // Hide all popup items
        document.querySelectorAll('.popup-item').forEach(item => {
            item.classList.remove('show');
        });

        // cleanup magnifier glass when closing popup
        document.querySelectorAll('.img-magnifier-glass').forEach(g => g.remove());
        overlay.classList.remove('show');
        globalPopup.classList.remove('show');
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        globalPopup.setAttribute('aria-hidden', 'true');
        overlay.setAttribute('aria-hidden', 'true');
    }

    if (closePopup) closePopup.addEventListener('click', closePopupFn);
    if (overlay) overlay.addEventListener('click', closePopupFn);
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePopupFn();
    });

    if (toggleMagnify) {
        toggleMagnify.addEventListener('click', () => {
            magnifierEnabled = !magnifierEnabled;
            toggleMagnify.textContent = magnifierEnabled ? '🔍 Aan' : '🔍 Uit';

            // If popup is open, toggle magnifier for current image
            const currentPopupItem = document.querySelector('.popup-item.show');
            if (currentPopupItem) {
                const popupImg = currentPopupItem.querySelector('img');
                if (popupImg) {
                    // cleanup previous glasses
                    document.querySelectorAll('.img-magnifier-glass').forEach(g => g.remove());

                    if (magnifierEnabled) {
                        if (!popupImg.id) popupImg.id = 'popup-img-' + currentPopupItem.dataset.id;
                        if (popupImg.complete && popupImg.naturalWidth) {
                            magnify(popupImg.id, 3);
                        } else {
                            const onLoad = () => {
                                popupImg.removeEventListener('load', onLoad);
                                magnify(popupImg.id, 3);
                            };
                            popupImg.addEventListener('load', onLoad);
                        }
                    }
                }
            }
        });
    }
});


function magnify(imgID, zoom) {
    var img = document.getElementById(imgID);
    if (!img) return;

    // remove any previous glass for this image
    const existing = img.parentElement.querySelector('.img-magnifier-glass');
    if (existing) existing.remove();

    var glass = document.createElement("DIV");
    glass.setAttribute("class", "img-magnifier-glass");
    img.parentElement.insertBefore(glass, img);

    // use displayed size for mapping so math stays consistent
    function updateBackgroundSize() {
        glass.style.backgroundImage = "url('" + img.src + "')";
        glass.style.backgroundRepeat = "no-repeat";
        glass.style.backgroundSize = (img.offsetWidth * zoom) + "px " + (img.offsetHeight * zoom) + "px";
    }
    updateBackgroundSize();

    var bw = 3;
    var w = glass.offsetWidth / 2;
    var h = glass.offsetHeight / 2;

    // unified move handler for mouse/touch/pointer
    function moveMagnifier(e) {
        e.preventDefault();
        var pos = getCursorPos(e);
        var x = pos.x;
        var y = pos.y;
        // clamp so the glass stays inside the image
        if (x > img.offsetWidth - (w / zoom)) x = img.offsetWidth - (w / zoom);
        if (x < w / zoom) x = w / zoom;
        if (y > img.offsetHeight - (h / zoom)) y = img.offsetHeight - (h / zoom);
        if (y < h / zoom) y = h / zoom;

        // position glass relative to the container, accounting for image offset within container
        var imgRect = img.getBoundingClientRect();
        var containerRect = img.parentElement.getBoundingClientRect();
        var imgOffsetX = imgRect.left - containerRect.left;
        var imgOffsetY = imgRect.top - containerRect.top;

        glass.style.left = (imgOffsetX + x - w) + "px";
        glass.style.top = (imgOffsetY + y - h) + "px";

        // background position based on displayed coords (consistent with backgroundSize)
        glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
    }

    function getCursorPos(e) {
        // get image rect
        var rect = img.getBoundingClientRect();
        // decide coords based on event type
        var clientX = (e.touches && e.touches[0]) ? e.touches[0].clientX : (e.clientX !== undefined ? e.clientX : (e.changedTouches && e.changedTouches[0] && e.changedTouches[0].clientX));
        var clientY = (e.touches && e.touches[0]) ? e.touches[0].clientY : (e.clientY !== undefined ? e.clientY : (e.changedTouches && e.changedTouches[0] && e.changedTouches[0].clientY));
        // relative inside the image
        var x = clientX - rect.left;
        var y = clientY - rect.top;
        // optional clamp to [0..img.width/img.height]
        x = Math.max(0, Math.min(x, rect.width));
        y = Math.max(0, Math.min(y, rect.height));
        return { x: x, y: y };
    }

    // attach events — include mouse and touch
    glass.addEventListener("mousemove", moveMagnifier);
    img.addEventListener("mousemove", moveMagnifier);
    glass.addEventListener("touchmove", moveMagnifier);
    img.addEventListener("touchmove", moveMagnifier);

    // update on resize (image may change size when popup opens)
    window.addEventListener('resize', updateBackgroundSize);
    img.addEventListener('load', updateBackgroundSize);
}
let items = [];
// Load items from database
fetch("getItems.php")
    .then(res => res.json())
    .then(data => {
        items = data;
        console.log("Loaded items:", items);
    });
 
// Custom Dropdown Functionality
const customDropdown = document.getElementById('customDropdown');
const dropdownSelected = document.getElementById('dropdownSelected');
const dropdownOptions = document.getElementById('dropdownOptions');

customDropdown.addEventListener('click', (e) => {
    e.stopPropagation();
    customDropdown.classList.toggle('open');
});

dropdownOptions.addEventListener('click', (e) => {
    if (e.target.classList.contains('dropdown-option')) {
        const value = e.target.dataset.value;
        dropdownSelected.innerHTML = value + '<div class="dropdown-arrow">▼</div>';
        customDropdown.classList.remove('open');
    }
});

// Close dropdown when clicking outside
document.addEventListener('click', () => {
    customDropdown.classList.remove('open');
});

// Search Functionality
const searchButton = document.getElementById("submit");

function searchForItem(query) {
    query = parseInt(query);
    return document.getElementById(`card-${query}`);
}

searchButton.addEventListener("click", () => {
    const query = parseInt(dropdownSelected.textContent);
    const card = searchForItem(query);

    if (card) {
        card.scrollIntoView({ behavior: "smooth", block: 'nearest', inline: 'start' });
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const pano = document.getElementById('panoramaFotos');
    const minimapTrack = document.getElementById('panoramaMinimapTrack');
    const viewport = document.getElementById('panoramaMinimapViewport');

    if (!pano || !minimapTrack || !viewport) {
        console.warn('Panorama of minimap elementen niet gevonden.');
        return;
    }

    const cards = Array.from(pano.querySelectorAll('.card'));
    const minimapThumbs = [];

    function positionIndicator(thumbElement) {
        if (!viewport || !thumbElement) return;
        const indicatorWidth = viewport.offsetWidth || 4;
        const visibleLeft = thumbElement.offsetLeft - minimapTrack.scrollLeft;
        const center = visibleLeft + thumbElement.offsetWidth / 2;
        const maxOffset = minimapTrack.clientWidth - indicatorWidth;
        const offset = Math.max(0, Math.min(center - indicatorWidth / 2, maxOffset));
        viewport.style.transform = `translateX(${offset}px)`;
    }

    function ensureThumbVisible(thumbElement) {
        if (!thumbElement) return;
        const thumbLeft = thumbElement.offsetLeft;
        const thumbRight = thumbLeft + thumbElement.offsetWidth;
        const viewLeft = minimapTrack.scrollLeft;
        const viewRight = viewLeft + minimapTrack.clientWidth;

        if (thumbLeft < viewLeft) {
            minimapTrack.scrollTo({ left: thumbLeft, behavior: 'smooth' });
        } else if (thumbRight > viewRight) {
            minimapTrack.scrollTo({ left: thumbRight - minimapTrack.clientWidth, behavior: 'smooth' });
        }
    }

    function setActiveThumb(cardId) {
        let activeThumb = null;
        minimapThumbs.forEach(({ id, element }) => {
            const isActive = id === cardId;
            element.classList.toggle('active', isActive);
            if (isActive) activeThumb = element;
        });
        if (activeThumb) {
            ensureThumbVisible(activeThumb);
            positionIndicator(activeThumb);
        }
    }

    function syncActiveThumb() {
        if (!cards.length) return;
        const center = pano.scrollLeft + pano.clientWidth / 2;
        let closest = cards[0];
        let smallestDelta = Math.abs((cards[0].offsetLeft + cards[0].clientWidth / 2) - center);

        for (let i = 1; i < cards.length; i++) {
            const card = cards[i];
            const cardCenter = card.offsetLeft + card.clientWidth / 2;
            const delta = Math.abs(cardCenter - center);
            if (delta < smallestDelta) {
                smallestDelta = delta;
                closest = card;
            }
        }

        setActiveThumb(closest.id);
    }

    if (cards.length) {
        cards.forEach((card, index) => {
            const sourceImg = card.querySelector('.card-front img');
            if (!sourceImg) return;

            const thumb = document.createElement('div');
            thumb.className = 'panorama-minimap-thumb';
            thumb.dataset.target = card.id;
            const pageNumber = index + 1;
            const labelValue = sourceImg.dataset.catalogus || sourceImg.dataset.beschrijving || pageNumber;
            thumb.setAttribute('aria-label', `Miniatuur van afbeelding ${labelValue}`);

            const thumbImg = document.createElement('img');
            thumbImg.src = sourceImg.src;
            thumbImg.alt = `Thumbnail ${pageNumber}`;
            thumbImg.loading = 'lazy';

            const numberOverlay = document.createElement('span');
            numberOverlay.className = 'panorama-minimap-thumb-number';
            numberOverlay.textContent = pageNumber;

            thumb.appendChild(thumbImg);
            thumb.appendChild(numberOverlay);

            minimapTrack.appendChild(thumb);
            minimapThumbs.push({ id: card.id, element: thumb, card });
        });
    }

    function focusCard(card) {
        if (!card) return;
        const cardCenter = card.offsetLeft + card.clientWidth / 2;
        const target = cardCenter - pano.clientWidth / 2;
        const maxScroll = Math.max(pano.scrollWidth - pano.clientWidth, 0);
        const nextScroll = Math.max(0, Math.min(target, maxScroll));
        pano.scrollTo({ left: nextScroll, behavior: 'smooth' });
    }
 
    minimapTrack.addEventListener('click', function (e) {
        const rect = minimapTrack.getBoundingClientRect();
        const clickX = e.clientX - rect.left + minimapTrack.scrollLeft;
        let nearest = null;
        let smallestDelta = Infinity;

        minimapThumbs.forEach((thumb) => {
            const thumbCenter = thumb.element.offsetLeft + thumb.element.offsetWidth / 2;
            const delta = Math.abs(thumbCenter - clickX);
            if (delta < smallestDelta) {
                smallestDelta = delta;
                nearest = thumb;
            }
        });

        if (nearest && nearest.card) {
            focusCard(nearest.card);
        }
    });

    function updateMinimap() {
        // Proportional scroll like a collage
        const sliderScrollWidth = pano.scrollWidth - pano.clientWidth;
        const trackScrollWidth = minimapTrack.scrollWidth - minimapTrack.clientWidth;
        if (sliderScrollWidth > 0 && trackScrollWidth > 0) {
            const ratio = pano.scrollLeft / sliderScrollWidth;
            minimapTrack.scrollLeft = ratio * trackScrollWidth;
        }
        syncActiveThumb();
    }

    syncActiveThumb();

    // Add scroll event listener to panorama to sync minimap
    pano.addEventListener('scroll', updateMinimap);
});
