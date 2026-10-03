/* Travelora - interactions */
document.addEventListener('DOMContentLoaded', function () {
    var I = window.I18N || {};
    /* ---------- Dropdowns ---------- */
    document.querySelectorAll('.lang-dropdown, .user-dropdown').forEach(function (dd) {
        var btn = dd.querySelector('button');
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var wasOpen = dd.classList.contains('open');
            closeDropdowns();
            if (!wasOpen) dd.classList.add('open');
        });
    });
    function closeDropdowns() {
        document.querySelectorAll('.lang-dropdown.open, .user-dropdown.open').forEach(function (el) {
            el.classList.remove('open');
        });
    }
    document.addEventListener('click', closeDropdowns);

    /* ---------- Mobile navigation ---------- */
    var navToggle = document.getElementById('navToggle');
    var mainNav = document.getElementById('mainNav');
    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function () {
            mainNav.classList.toggle('open');
        });
    }

    /* ---------- Search tabs ---------- */
    document.querySelectorAll('.search-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.getAttribute('data-target');
            document.querySelectorAll('.search-tab').forEach(function (t) { t.classList.remove('active'); });
            document.querySelectorAll('.search-panel').forEach(function (p) { p.classList.remove('active'); });
            tab.classList.add('active');
            var panel = document.getElementById(target);
            if (panel) panel.classList.add('active');
        });
    });

    /* ---------- Date sanity ---------- */
    var today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="date"]').forEach(function (input) {
        if (!input.min) input.min = today;
        input.addEventListener('change', function () {
            var group = input.closest('.search-panel, form, .modal');
            if (!group) return;
            var checkin = group.querySelector('input[name="check_in"], input[name="depart_date"], input[name="travel_date"]');
            var checkout = group.querySelector('input[name="check_out"], input[name="return_date"], input[name="end_date"]');
            if (checkin && checkout && checkin.value) {
                checkout.min = checkin.value;
                if (checkout.value && checkout.value < checkin.value) checkout.value = checkin.value;
            }
        });
    });

    /* ---------- Booking modal ---------- */
    var modal = document.getElementById('bookingModal');
    if (modal) {
        var typeInput = modal.querySelector('input[name="booking_type"]');
        var idInput = modal.querySelector('input[name="item_id"]');
        var nameInput = modal.querySelector('input[name="item_name"]');
        var priceInput = modal.querySelector('input[name="unit_price"]');
        var endWrap = modal.querySelector('[data-end-date]');
        var modalTitle = modal.querySelector('[data-modal-title]');
        var modalPrice = modal.querySelector('[data-modal-price]');
        var travelLabel = modal.querySelector('[data-travel-label]');
        var modalUnit = modal.querySelector('[data-modal-unit]');

        document.querySelectorAll('[data-book]').forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                var type = trigger.getAttribute('data-book');
                var id = trigger.getAttribute('data-id');
                var name = trigger.getAttribute('data-name');
                var price = parseFloat(trigger.getAttribute('data-price')) || 0;
                var needsEnd = trigger.getAttribute('data-needs-end') === '1';

                typeInput.value = type;
                idInput.value = id;
                nameInput.value = name;
                priceInput.value = price;
                modalTitle.textContent = (I.bookPrefix || 'Book') + ': ' + name;
                modalPrice.textContent = '$' + price.toLocaleString(undefined, { minimumFractionDigits: 0 });
                endWrap.style.display = needsEnd ? '' : 'none';
                travelLabel.textContent = trigger.getAttribute('data-travel') || 'Travel Date';
                if (modalUnit) modalUnit.textContent = trigger.getAttribute('data-unit') || '';
                modal.classList.add('open');
            });
        });

        function closeModal() { modal.classList.remove('open'); }
        modal.querySelectorAll('.modal-close, [data-modal-cancel]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });
        modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
    }

    /* ---------- Escape helper ---------- */
    function esc(str) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(str == null ? '' : String(str)));
        return d.innerHTML;
    }

    /* ---------- Live hotels from OpenStreetMap (Overpass API) ---------- */
    document.querySelectorAll('[data-live-hotels]').forEach(function (box) {
        var id = box.getAttribute('data-destination-id');
        if (!id) return;
        fetch('api/nearby-hotels.php?destination_id=' + encodeURIComponent(id))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.ok || !data.hotels || !data.hotels.length) {
                    box.innerHTML = '<div class="live-loading"><i class="fa-solid fa-circle-info"></i> ' + esc(I.noHotels || 'No live hotel data available for this destination right now.') + '</div>';
                    return;
                }
                var cards = data.hotels.slice(0, 8).map(function (h) {
                    var stars = h.stars ? '<div class="lh-stars">' + '\u2605'.repeat(h.stars) + '</div>' : '';
                    var site = h.website ? ' <a href="' + encodeURI(h.website) + '" target="_blank" rel="noopener"><i class="fa-solid fa-globe"></i> ' + esc(I.website || 'Website') + '</a>' : '';
                    return '<div class="live-hotel-card">' +
                        '<div class="lh-top"><h4>' + esc(h.name) + '</h4><span class="lh-dist">' + h.distance + ' km</span></div>' +
                        stars +
                        '<p class="lh-addr">' + esc(h.address || (I.addrUnknown || 'Address not listed')) + '</p>' +
                        '<div class="lh-links"><a href="https://www.openstreetmap.org/' + esc(h.osm_id) + '" target="_blank" rel="noopener"><i class="fa-solid fa-map-location-dot"></i> ' + esc(I.viewMap || 'View map') + '</a>' + site + '</div>' +
                    '</div>';
                }).join('');
                box.innerHTML = '<div class="live-hotels-grid">' + cards + '</div>' +
                    '<p class="live-updated"><i class="fa-solid fa-satellite-dish"></i> ' + data.count + ' ' + esc(I.realFound || 'real hotels found near') + ' ' + esc(data.place || '') + ' &middot; ' + esc(I.updated || 'updated') + ' ' + esc(data.updated_at) + '</p>';
            })
            .catch(function () {
                box.innerHTML = '<div class="live-loading">' + esc(I.hotelsError || 'Could not load live hotels right now.') + '</div>';
            });
    });

    /* ---------- Auto-hide flash ---------- */
    var flash = document.querySelector('.flash');
    if (flash) {
        setTimeout(function () {
            flash.style.transition = 'opacity .4s ease';
            flash.style.opacity = '0';
            setTimeout(function () { flash.remove(); }, 420);
        }, 5000);
    }
});
