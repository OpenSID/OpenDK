<!-- Name Field -->
<div class="form-group">
    <div class="col-12">

        {!! Form::text('text', null, [
            'class' => 'form-control item-menu',
            'maxlength' => 255,
            'placeholder' => 'Nama Menu',
            'id' => 'text',
        ]) !!}

        {!! Form::hidden('icon', 'fa fa-list', ['class' => 'item-menu']) !!}
    </div>
</div>

<!-- Asal url -->
<div class="form-group">
    <div class="col-12">
        <label class="form-check-inline">
            {!! Form::radio('source', 'link', 'link', ['class' => 'form-check-input', 'id' => 'sourceLink']) !!} Link
        </label>
        <label class="form-check-inline">
            {!! Form::radio('source', 'Halaman', null, ['class' => 'form-check-input', 'id' => 'sourceHalaman']) !!} Halaman
        </label>
        <label class="form-check-inline">
            {!! Form::radio('source', 'Kategori', null, ['class' => 'form-check-input', 'id' => 'sourceKategori']) !!} Kategori
        </label>
        <label class="form-check-inline">
            {!! Form::radio('source', 'Modul', null, ['class' => 'form-check-input', 'id' => 'sourceModul']) !!} Modul
        </label>
        <label class="form-check-inline">
            {!! Form::radio('source', 'Dokumen', null, ['class' => 'form-check-input', 'id' => 'sourceDokumen']) !!} Dokumen
        </label>
    </div>
</div>

<!-- Url Field -->
<div class="form-group">
    <div class="col-12">
        {!! Form::select('sourcelist', $sourceItem, null, ['class' => 'form-control']) !!}
        {!! Form::text('href', null, ['class' => 'form-control item-menu', 'maxlength' => 255, 'placeholder' => 'http://contoh.com']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('target', 'Target') !!}
    {!! Form::select('target', ['_self' => 'Self', '_blank' => 'Blank', '_top' => 'Top'], null, ['class' => 'form-control item-menu', 'id' => 'target']) !!}
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tampilkan input[name=href] dan sembunyikan yang lainnya saat halaman dimuat
            document.querySelector('input[name=href]').style.display = 'block';
            document.querySelector('select[name=sourcelist]').style.display = 'none';

            // Event listener untuk radio button change
            document.querySelectorAll('input[name=source]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    console.log(this.value)
                    let _val = this.value;

                    // Reset semua input dan select
                    document.querySelector('input[name=href]').value = '';
                    document.querySelector('input[name=href]').style.display = 'none';
                    document.querySelector('select[name=sourcelist]').style.display = 'none';

                    // Switch case berdasarkan value radio button
                    switch (_val) {
                        case 'Kategori':
                        case 'Halaman':
                        case 'Dokumen':
                            let select = document.querySelector('select[name=sourcelist]');
                            select.style.display = 'block'; // Tampilkan select sourcelist

                            // Sembunyikan semua optgroup
                            select.querySelectorAll('optgroup').forEach(function(optgroup) {
                                optgroup.style.display = 'none';
                            });

                            // Tampilkan optgroup yang sesuai dengan _val
                            let optgroup = select.querySelector(`optgroup[label="${_val}"]`);
                            if (optgroup) {
                                optgroup.style.display = 'block';

                                // Pilih option pertama dalam optgroup
                                let firstOption = optgroup.querySelector('option');
                                if (firstOption) {
                                    firstOption.selected = true;
                                }
                            }

                            // Trigger change event secara manual
                            select.dispatchEvent(new Event('change'));
                            break;

                        case 'Modul':
                            var inputField = document.querySelector('input[name=href]');
                            inputField.style.display = 'block';
                            inputField.placeholder = '/publikasi/galeri';
                            break;

                        default:
                            var inputField = document.querySelector('input[name=href]');
                            inputField.style.display = 'block';
                            inputField.placeholder = 'http://contoh.com';
                            // document.querySelector('input[name=href]').style.display = 'block';
                    }
                });
            });

            // Event listener untuk select change
            document.querySelector('select[name=sourcelist]').addEventListener('change', function() {
                let selectedValue = this.value;
                document.querySelector('input[name=href]').value = selectedValue;
            });
        });
    </script>
    <!-- <script>
        $(document).ready(function() {
            $('select[name=sourcelist]').hide()
            $(':radio[name=source]').change(function() {
                let _val = $(this).val()
                $('input[name=href]').val('')
                $('input[name=href]').hide()
                $('input[name=url]').val('')
                $('input[name=url]').hide()
                $('select[name=sourcelist]').hide()
                switch (_val) {
                    case 'Kategori':
                        $('select[name=sourcelist]').show()
                        $('select[name=sourcelist]').find('optgroup').hide()
                        $('select[name=sourcelist]').find('optgroup[label="' + _val + '"]').show()
                        $('select[name=sourcelist]').find('optgroup[label="' + _val + '"]').find('option:first').prop('selected', 1)
                        $('select[name=sourcelist]').trigger('change')
                        break;
                    case 'Halaman':
                        $('select[name=sourcelist]').show()
                        $('select[name=sourcelist]').find('optgroup').hide()
                        $('select[name=sourcelist]').find('optgroup[label="' + _val + '"]').show()
                        $('select[name=sourcelist]').find('optgroup[label="' + _val + '"]').find('option:first').prop('selected', 1)
                        $('select[name=sourcelist]').trigger('change')
                        break;
                    case 'Modul':
                        $('input[name=url]').show()
                        break;
                    default:
                        $('input[name=href]').show()
                }
            })

            $('select[name=sourcelist]').on('change', function() {
                $('input[name=href]').val($(this).val())
            })
        })
    </script> -->
@endpush
