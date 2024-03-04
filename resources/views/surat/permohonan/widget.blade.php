<div class="row">
    <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ App\Models\Surat::permohonan()->count() }}</h3>
                <p>Permohonan</p>
            </div>
            <div class="icon">
                <i class="fa fa-files-o"></i>
            </div>
            <a href="{{ route('surat.permohonan') }}" class="small-box-footer">
                Selengkapnya <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ App\Models\Surat::ditolak()->count() }}</h3>
                <p>Ditolak</p>
            </div>
            <div class="icon">
                <i class="fa fa-close"></i>
            </div>
            <a href="{{ route('surat.permohonan.ditolak') }}" class="small-box-footer">
                Selengkapnya <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
