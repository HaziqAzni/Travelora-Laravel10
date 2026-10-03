<div class="modal-backdrop" id="bookingModal">
    <div class="modal">
        <button type="button" class="modal-close" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        <h3 data-modal-title>{{ t('modal.book', 'Book') }}</h3>
        <p class="price-line">{{ t('modal.from', 'From') }} <strong data-modal-price>$0</strong> <span data-modal-unit></span></p>

        <form action="/book" method="post">
            @csrf
            <input type="hidden" name="booking_type" value="">
            <input type="hidden" name="item_id" value="">
            <input type="hidden" name="item_name" value="">
            <input type="hidden" name="unit_price" value="">

            <div class="form-field">
                <label data-travel-label>{{ t('modal.travel_date', 'Travel Date') }}</label>
                <div class="control">
                    <i class="fa-regular fa-calendar"></i>
                    <input type="date" name="travel_date" required>
                </div>
            </div>

            <div class="form-field" data-end-date style="display:none">
                <label>{{ t('modal.checkout', 'Check-out Date') }}</label>
                <div class="control">
                    <i class="fa-regular fa-calendar"></i>
                    <input type="date" name="end_date">
                </div>
            </div>

            <div class="form-field">
                <label>{{ t('modal.guests', 'Guests') }}</label>
                <div class="control">
                    <i class="fa-regular fa-user"></i>
                    <select name="guests">
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ $i === 2 ? 'selected' : '' }}>{{ $i }} {{ t('modal.traveller', 'travellers') }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-outline" data-modal-cancel>{{ t('modal.cancel', 'Cancel') }}</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> {{ t('modal.confirm', 'Send Booking Request') }}</button>
            </div>

            <p class="demo-note"><i class="fa-solid fa-circle-info"></i> {{ t('modal.demo_note', 'Demo request only — no payment is taken and live availability is not checked. Dates and guest counts are illustrative.') }}</p>
        </form>
    </div>
</div>
