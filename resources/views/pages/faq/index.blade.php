@extends('layouts.app')
@section('content')
<div class="col-md-8">
  <div class="box box-primary">
    <div class="box-header with-border text-bold">
      <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> Pertanyaan Yang Sering Diajukan</h3>
    </div>
    <div class="box-body">
      <div class="box-group" id="accordion">
        @forelse($faq as $key => $item)
          <div class="panel box box-success">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}" aria-expanded="false" class="collapsed">
                  {{ $item->question }}
                </a>
              </h4>
            </div>
            <div id="collapse{{$key}}" class="panel-collapse collapse" aria-expanded="false" >
              <div class="box-body">
                {!! $item->answer !!}
              </div>
            </div>
          </div>
          <div class="text-center">
            {{ $faq->links() }}
          </div>
        @empty
        <div class="callout callout-info">
          <p class="text-bold">Tidak ada data yang ditampilkan!</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  $(function () {
    $('#accordion').find('.box-title').first().find('a').trigger('click')
  });
</script>
@endpush