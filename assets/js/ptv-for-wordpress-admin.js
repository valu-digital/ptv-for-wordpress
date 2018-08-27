jQuery(document).on('carbonFields.apiLoaded', function (e, api) {
    jQuery(document).on('carbonFields.validateField', function (e, fieldName, error) {
        if (fieldName === 'ptv_addresses' && ptv.postType === 'ptv-service-location') {
            var addresses = api.getFieldValue(fieldName);
            if (addresses.length === 0) {
                return ptv.errors.addressMissing;
            } else {
                const visitingAddresses = addresses.filter(address => address.type === 'Location');
                if (visitingAddresses.length === 0) {
                    return ptv.errors.addressMissing;
                } else {
                    const visitingAddressesWithWrongSubType = visitingAddresses.filter(address => address.sub_type !== 'Single');

                    if (visitingAddressesWithWrongSubType.length > 0) {
                        return ptv.errors.visitingAddressWithWrongSubType;
                    }
                }
            }
        }
        if (fieldName === 'ptv_delivery_address' && ptv.postType === 'ptv-printable-form') {
            const address = api.getFieldValue(fieldName);
            if (address.length > 1) {
                return ptv.errors.onlyOneDeliveryAddressIsAllowed;
            }
        }
        return error;
    });

    // Update title based on PTV name.
    jQuery(document).on('carbonFields.fieldUpdated', function (e, fieldName) {
        if (fieldName === 'ptv_name') {
            jQuery('#title').val(api.getFieldValue(fieldName));
        }
    });

});

jQuery(document).ready(function ($) {
    $('#ptv-service-classesdiv .hndle, #ptv-target-groupsdiv .hndle').append(' <span style="color:#cd4c15;">*</span>');
});
