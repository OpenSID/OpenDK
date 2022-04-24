@push('scripts')
        <!-- Numeral.js  -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script>
  numeral.register('locale', 'id', {
    delimiters: {
        thousands: '.',
        decimal: ','
    },
  })
  numeral.locale('id');

</script>
@endpush