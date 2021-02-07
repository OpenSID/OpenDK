@if (Route::currentRouteName() === 'beranda')
@else
<!-- Content Header (Page header) -->
<div class="col-md-8">
      <ol class="breadcrumb">
        <a class="fa fa-home" href="{{ route('beranda') }}"></a>
        @for ($i = 1; $i <= count(Request::segments()); $i++)
        <li>
          <a href="{{ URL::to( implode( '/', array_slice(Request::segments(), 0 ,$i, true)))}}">
            {{strtoupper(str_replace('-',' ',Request::segment($i)))}}</a>
          </a>
        </li>
        @if (($i < count(Request::segments())) & ($i> 0))
        {!! '<i class="fa fa-angle-double-right"></i>' !!}
        @endif
      </li>
      @endfor
    </ol>
  </div>
  <!-- Content Header (Page header) -->   
  @endif
  
  