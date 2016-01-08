<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.pjax/1.9.6/jquery.pjax.min.js"></script>
<script type="application/javascript">
    $(document).pjax('a[href^="{{ url('') }}"]', '#page');
</script>