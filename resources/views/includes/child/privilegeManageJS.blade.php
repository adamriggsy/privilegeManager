<script>
    $(function(){
        startDateRange($("#datepicker_start_restore" ), $("#datepicker_end_restore"));
        startDateRange($("#datepicker_start_ban" ), $("#datepicker_end_ban"));
    });

    function startDateRange( toEl, fromEl ) {
        var from = toEl.datepicker({
            dateFormat : 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 1
        }).on( "change", function() {
            to.datepicker( "option", "minDate", getDate( this ) );
        }).datepicker('setDate', "{{isset($parameters['date']) ? $parameters['date'] : ''}}");

        var to = fromEl.datepicker({
            dateFormat : 'yy-mm-dd',
            changeMonth: true,
            numberOfMonths: 1
        }).on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
        }).datepicker('setDate', "{{isset($parameters['date']) ? $parameters['date'] : ''}}");
    }

    function getDate( element ) {
        var date;
        var dateFormat = 'yy-mm-dd';
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }
</script>