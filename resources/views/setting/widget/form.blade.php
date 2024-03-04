<div class="form-group">
    <label for="judul" class="control-label col-md-4 col-sm-3 col-xs-12">Judul Widget <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('judul', null, ['class' => 'form-control', 'required'=>true, 'id' => 'judul']) !!}
    </div>
</div>
<div class="form-group">
    <label for="jenis_widget" class="control-label col-md-4 col-sm-3 col-xs-12">Jenis Widget <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('jenis_widget', ['0'=>'-- Pilih Jenis Widget --', '2'=>'Statis', '3'=>'Dinamis'], 0, ['class' => 'form-control', 'required'=>true, 'id' => 'jenis_widget']) !!}
    </div>
</div>
@php if (isset($widget->jenis_widget) && $widget->jenis_widget != 3 && $widget->jenis_widget != 1) $statis = true; else $statis = false; @endphp
<div id="statis" class="form-group" <?php ! $statis && print 'style="display:none;"' ?> >
    <label for="isi-statis" class="control-label col-md-4 col-sm-3 col-xs-12">Nama File Widget (.php) <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::select('jenis_widget', ['0'=>'-- Pilih Widget --', '2'=>'Statis', '3'=>'Dinamis'], 0, ['class' => 'form-control', 'required'=>true, 'id' => 'jenis_widget']) !!}
    </div>
</div>
@php if (isset($widget->jenis_widget) && $widget->jenis_widget != 2 && $widget->jenis_widget != 1) $dinamis = true; else $dinamis = false; @endphp
<div id="dinamis" class="form-group" <?php ! $dinamis && print 'style="display:none;"' ?>>
    <label for="isi-dinamis" class="control-label col-md-4 col-sm-3 col-xs-12">Kode Widget <span class="required">*</span></label>

    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('isi-dinamis', null, ['class' => 'form-control', 'required'=>true, 'id' => 'isi-dinamis']) !!}
    </div>
</div>
<div class="ln_solid"></div>
<script>
	var elem = document.getElementById("jenis_widget");
	elem.onchange = function() {
		var dinamis = document.getElementById("dinamis");
		var statis = document.getElementById("statis");
		dinamis.style.display = (this.value == "3") ? "block":"none";
		statis.style.display = (this.value == "2") ? "block":"none";
	};
</script>
