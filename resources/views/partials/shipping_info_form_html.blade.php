
<form method="POST" action="{{ route('save-shipping-info') }}" id="add-shipping-info-form">
@csrf
    <label>
        <input type="text" class="input-field" name="name" required placeholder="Name">
    </label>

    <label>
        <input type="tel" class="input-field" name="phone_number" required placeholder="Phone Number">
    </label>

    <label>
        <input type="text" class="input-field" name="address_line1" required placeholder="Line 1">
    </label>

    <label>
        <input type="text" class="input-field" name="address_line2" placeholder="Line 2">
    </label>

    <label>
        <input type="text" class="input-field" name="city_locality" required placeholder="City">
    </label>

    <label>
        <input type="text" class="input-field" name="state_province" required placeholder="State">
    </label>

    <label>
        <input type="text" class="input-field" name="postal_code" required placeholder="Postal Code">
    </label>

    <label id="add-shipping-info-form-error" style="color: red;text-align: center;"></label>

    <button type="submit" class="btn-clean">
        Lookup
    </button>
</form>

<button id="add-shipping-info-clear" onclick="javascript:clearNewShippingInfo()" type="button" class="btn-clean btn-red" style="margin-top: 1em;" hidden>
        Clear
</button>