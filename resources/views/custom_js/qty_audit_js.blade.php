<script>
$('#all_qty_audit').DataTable({
    "bFilter": true,
    "pagingType": 'numbers',
    "ordering": true,
    "ajax": {
        "url": "{{ url('show_qty_audit') }}",
        "type": "GET",
        "data": function (d) {
            d.start_date = $('.start_date').val();
            d.end_date = $('.end_date').val();
            d.product_id = $('.product_id').val();
        }
    },
});


</script>
