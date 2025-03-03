<script>
            $('#all_purchase').DataTable({
                "sAjaxSource": "{{ url('show_purchase') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
            });
</script>
