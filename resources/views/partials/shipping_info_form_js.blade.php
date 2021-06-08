<script type="text/javascript">
    $('#add-shipping-info-form').on('submit', function(event) {
        event.preventDefault();

        var form = $(this);

        if($(`#add-shipping-info-form button`).text() == 'Confirm') {
            form.unbind('submit').submit();
        } else {
            $.ajax({
                url: "{{ route('validate-shipping-info') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                type: 'POST',
                dataType: 'json',
                data: form.serialize(),
            }).done(function (resp) {
                resp = JSON.parse(resp)[0];

                if(resp['status'] == 'error' || resp['status'] == 'unverified') {
                    if(resp['messages'].length > 0) {
                        $("#add-shipping-info-form-error").css('color', 'red');
                        $("#add-shipping-info-form-error").text('Address not found');
                    }
                } else if (resp['status'] == 'verified') {
                    var phone_number = $(`#add-shipping-info-form input[name=phone_number]`).val();
                    $(`#add-shipping-info-form input[name=phone_number]`).val(addDashes(phone_number));

                    for (const [key, value] of Object.entries(resp['matched_address'])) {
                        $(`#add-shipping-info-form input[name=${key}]`).attr('readonly', "readonly");
                        $(`#add-shipping-info-form input[name=${key}]`).val(value);
                        $("#add-shipping-info-form-error").css('color', 'green');
                        $("#add-shipping-info-form-error").text('Confirm address details');
                        $(`#add-shipping-info-form button`).text('Confirm');
                    }
                
                    $('#add-shipping-info-clear').attr('hidden', false);
                }

            });
        }
	});

    function clearNewShippingInfo() {
        $("#add-shipping-info-form :input").val("");
        $("#add-shipping-info-form :input").attr('readyonly', '');
        $("#add-shipping-info-form-error").text("");
        $('#add-shipping-info-clear').attr('hidden', true);
        $(`#add-shipping-info-form button`).text('Save');
        $(`#add-shipping-info-form button`).css('background-color', '');
    }

    function addDashes(str) {
        var f_val = str.replace(/\D[^\.]/g, "");
        return f_val.slice(0,3) + "-" + f_val.slice(3,6) + "-" + f_val.slice(6);
    }
</script>