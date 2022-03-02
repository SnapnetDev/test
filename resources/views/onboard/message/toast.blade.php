<script>
@if (session()->has('message'))

    @if (session()->has('error') && session()->get('error'))
        toastr.error('{{ session()->get('message') }}');
    @else
        toastr.success('{{ session()->get('message') }}');
    @endif

@endif
</script>