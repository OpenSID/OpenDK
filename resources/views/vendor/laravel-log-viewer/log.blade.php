@push('css')
<style>

    #table-log {
        /*font-size: 0.85rem;*/
    }
    .sidebar {
        /*font-size: 0.85rem;*/
        line-height: 1;
    }
    .btn {
        font-size: 0.8em;
    }
    .stack {
      font-size: 0.85em;
    }
    .date {
      min-width: 60px;
    }
    .text {
      word-break: break-all;
      font-size: 9pt;
    }
    a.llv-active {
      z-index: 2;
      background-color: #f5f5f5;
      border-color: #777;
    }
    .list-group-item {
      word-break: break-word;
    }
    .folder {
      padding-top: 15px;
    }
    .div-scroll {
      height: 80vh;
      overflow: hidden auto;
    }
    .nowrap {
      white-space: nowrap;
      text-align:center;
    }
    .list-group {
            padding: 5px;
        }

    </style>
@endpush
    <div class="row">
        <div class="col-md-3 table-container">
            <div class="box box-info">
        		<div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-folder" aria-hidden="true"></i>File Logs</h3>
                    <p class="text-muted"></p>
                    <div class="list-group div-scroll box box-body no-padding">
                        @foreach($folders as $folder)
                        <div class="list-group-item"><?php \Rap2hpoutre\LaravelLogViewer\LaravelLogViewer::DirectoryTreeStructure( $storage_path, $structure ); ?></div>
                        @endforeach
                        @foreach($files as $file)
                          <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}" class="list-group-item @if ($current_file == $file) llv-active @endif"> {{$file}} </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    <div class="col-md-9 table-container">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <div class="p-3">
                            @if($current_file)
                              <a class="btn btn-social btn-flat btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                <span class="fa fa-download"></span> Unduh
                              </a>
                              <a class="btn btn-social btn-flat btn-info visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                <span class="fa fa-times-circle"></span> Bersihkan File
                              </a>
                              <a class="btn btn-social btn-flat btn-danger visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="delete-log"  href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                <span class="fa fa-trash"></span> Hapus File
                              </a>
                              @if(count($files) > 1)
                              <a id="delete-all-log" class="btn btn-social btn-flat btn-danger visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                                  <span class="fa fa-trash"></span> Hapus Semua file
                              </a>
                            @endif
                            @endif
                            </div>
                        </div>
                        <div class="box-body">
						    <div class="col-sm-12">
                                @if ($logs === null)
                                <div>Log file >50M, Silahkan download.</div>
                                    @else
                                    <div class="table-responsive">
                                        <table id="table-log" class="table table-striped table table-bordered dataTable table-hover" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                                          <thead>
                                          <tr>
                                            @if ($standardFormat)
                                              <th width="50px">Level</th>
                                              <th width="80px">Tanggal</th>
                                            @else
                                              <th>Line number</th>
                                            @endif
                                            <th>Pesan</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          @foreach($logs as $key => $log)
                                            <tr data-display="stack{{{$key}}}">
                                              @if ($standardFormat)
                                                <td class="nowrap text-{{{$log['level_class']}}}">
                                                  <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                                                </td>
                                              @endif
                                              <td class="date">{{ \Carbon\Carbon::parse($log['date'])->translatedFormat('d-m-Y H:i') }} </td>
                                              <td class="text">
                                                @if ($log['stack'])
                                                  <button style="float:right" type="button"
                                                          class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                                          data-display="stack{{{$key}}}">
                                                    <span class="fa fa-search"></span>
                                                  </button>
                                                @endif
                                                {{ str_limit($log['text'],250) }}
                                                @if (isset($log['in_file']))
                                                  <br/>{{{$log['in_file']}}}
                                                @endif
                                                @if ($log['stack'])
                                                  <div class="stack" id="stack{{{$key}}}"
                                                       style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                                  </div>
                                                @endif
                                              </td>
                                            </tr>
                                          @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
            </div>
@include('partials.asset_datatables')
 @push('scripts')
 <script type="text/javascript">
  $(document).ready(function () {
    $('.table-container tr').on('click', function () {
      $('#' + $(this).data('display')).toggle();
    });
    $('#table-log').DataTable({
      "order": [$('#table-log').data('orderingIndex'), 'desc'],
      "stateSave": true,
      "stateSaveCallback": function (settings, data) {
        window.localStorage.setItem("datatable", JSON.stringify(data));
      },
      "stateLoadCallback": function (settings) {
        var data = JSON.parse(window.localStorage.getItem("datatable"));
        if (data) data.start = 0;
        return data;
      }
    });
    $('#delete-log, #clean-log, #delete-all-log').click(function () {
      return confirm('Yakin Untuk Menghapus Data File log?');
    });
    $('#run-linkstorage').click(function () {
        return confirm('Yakin Untuk Menjalankan php artisan storage:link?');
    });
  });
</script>
@include('forms.datatable-vertical')
@endpush