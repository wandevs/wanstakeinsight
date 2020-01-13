<script>
    $(function() {

        $(".progress-circle").each(function() {
            var value = $(this).attr('data-value');
            var left = $(this).find('.progress-left .progress-bar');
            var right = $(this).find('.progress-right .progress-bar');

            if (value > 0) {
                if (value <= 50) {
                    right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)');
                } else {
                    right.css('transform', 'rotate(180deg)');
                    left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)');
                }
            }
        });
        function percentageToDegrees(percentage) {
            return percentage / 100 * 360;
        }

        $('[data-toggle="tooltip"]').tooltip({
            delay: { "show":300, "hide": 1000 },
        });

        $('[data-toggle="tooltip-table"]').tooltip({
            delay: { "show":300, "hide": 1000 },
            container: '.table'
        });
    });
</script>