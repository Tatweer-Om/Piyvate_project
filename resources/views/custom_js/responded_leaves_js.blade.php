<script>
$(document).ready(function() {
    
    
    $('#all_responded_leaves').DataTable({
        "ajax": {
            "url": "{{ url('show_responded_leaves') }}",
            "type": "GET", // or "POST" if your route expects POST
            "data": function (d) {
                d.status = $('#status').val(); // or any JS variable you want to pass
            }
        },
        "bFilter": true,
        "pagingType": "numbers",
        "ordering": true
    });
});  
function get_responded_leaves()
{
    $('#all_responded_leaves').DataTable().ajax.reload();
}  
</script>
        