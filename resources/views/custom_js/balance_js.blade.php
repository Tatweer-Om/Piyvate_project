<script>


$(document).ready(function (){


let balanceId = {{ $id }}; // From Blade
$('#all_balances').DataTable({
    "bFilter": true,
    'pagingType': 'numbers',
    "ordering": true,
    "ajax": {
        "url": "{{ url('show_balance') }}",
        "type": "GET",
        "data": function (d) {
            d.balance_id = balanceId; // attach balance ID to request
        }
    }
});

});
</script>
