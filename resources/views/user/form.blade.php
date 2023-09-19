@include('partials.flash_message')
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pengurus_id">Pengurus</label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="pengurus_id" id="pengurus" class="form-control">
            <option class="form-control" value="">Pilih Pengurus</option>
            @foreach ($pengurus as $list)
                @if (empty($user))
                    <option value="{{ $list['id'] }}" data-nama="{{ $list['nama'] }}">{{ $list['nama'] }}</option>
                @else
                    <option {{ $user->pengurus_id == $list['id'] ? 'selected' : '' }} data-nama="{{ $list['nama'] }}" value="{{ $list['id'] }}">{{ $list['nama'] }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nama', 'pattern' => '^[A-Za-z\.\']+(?:\s[A-Za-z\.\']+)*$']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Email <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        @if (empty($user))
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
        @else
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email', 'readOnly' => true]) !!}
        @endif
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Telepon </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => '0xxxxxxxxxxx']) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Foto Profil </label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="file" name="image" id="foto" class="form-control" accept="jpg,jpeg,png">
        <br>
        <img src="{{ is_img($user->foto ?? null) }}" id="showfoto" style="max-width:400px;max-height:250px;float:left;" />
    </div>
</div>

@if (empty($user))
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Password <span class="required">*</span></label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {!! Form::password('password', ['class' => 'form-control password']) !!}
        </div>
        <div class="col-md-1 col-sm-1 col-xs-12">
            <button type="button" class="btn showpass"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </div>
    </div>
@endif

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('address', null, ['class' => 'form-control', 'cols' => 10, 'rows' => 5]) !!}
    </div>
</div>

@if (empty($user))

    <div class="form-group">
        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Grup Pengguna <span class="required">*</span></label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            {{ Form::select('role', $item, null, ['class' => 'form-control']) }}
        </div>
    </div>
@elseif(auth()->user()->id == 1)
    @if ($user->id != 1)
        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Grup Pengguna <span class="required">*</span></label>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {{ Form::select('role', $item, $user->getRoleNames(), ['class' => 'form-control']) }}
            </div>
        </div>
    @endif
@endif

<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <a href="{{ route('setting.user.index') }}">
            <button type="button" class="btn btn-default">Batal</button>
        </a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</div>
@include('partials.asset_jqueryvalidation')

@push('scripts')
    <script type="text/javascript">
        $('#pengurus').on('change', function() {
            var data = $('#pengurus :selected').data('nama');
            $('input[name="name"]').val(data);
        });

        $(function() {

            var fileTypes = ['jpg', 'jpeg', 'png']; //acceptable file types

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var extension = input.files[0].name.split('.').pop().toLowerCase(), //file extension from input file
                        isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

                    if (isSuccess) { //yes
                        var reader = new FileReader();
                        reader.onload = function(e) {

                            $('#showfoto').attr('src', e.target.result);
                            $('#showfoto').removeClass('hide');
                        }

                        reader.readAsDataURL(input.files[0]);
                    } else { //no
                        //warning
                        $("#foto").val('');
                        alert('File tersebut tidak diperbolehkan.');
                    }
                }
            }

            $("#foto").change(function() {
                readURL(this);
            });
        });
    </script>
@endpush
