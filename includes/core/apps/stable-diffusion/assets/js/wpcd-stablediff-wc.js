(function ($, attributes) {

    $(document).ready(function () {
        init();
    });

    function init() {
        // change regions when provider changes.
        $('#wpcd_app_stablediff_provider').on('change', function (e) {
            $('#wpcd_app_stablediff_region').empty();
            $regions = attributes.provider_regions[$(this).val()];
            $.each($regions, function (i, j) {
                $('#wpcd_app_stablediff_region').append('<option value="' + j.slug + '">' + j.name + '</option>');
            });
        });
    }
})(jQuery, attributes);
