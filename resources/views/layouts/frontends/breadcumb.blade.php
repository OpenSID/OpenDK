<!-- Content Header (Page header) -->
<div class="col-md-8">
<<<<<<< HEAD
=======
    {{-- <section class="content-header"> --}}
      {{-- <h1>
        <i class="fa fa-home"> </i> {{ $page_title ?? "Page Title" }}
        <small>{{ $page_description ?? '' }}</small>
      </h1> --}}
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1
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
<<<<<<< HEAD
</div>
  <!-- Content Header (Page header) -->   
=======
  {{-- </section> --}}
</div>
  <!-- Content Header (Page header) -->
>>>>>>> 2890337063ab134daf3e7f211cd0f029924addf1

