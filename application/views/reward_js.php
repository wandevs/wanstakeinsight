<script>
    $(function() {

        $('[data-toggle="tooltip"]').tooltip({
            delay: { "show":300, "hide": 1000 },
        });

        $('[data-toggle="tooltip-table"]').tooltip({
            delay: { "show":300, "hide": 1000 },
            container: '.table'
        });
    });
</script>