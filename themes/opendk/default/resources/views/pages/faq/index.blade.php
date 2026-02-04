@extends('layouts.app')
@section('content')
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border text-bold">
                <h3 class="box-title text-bold"><i class="fa  fa-arrow-circle-right fa-lg text-blue"></i> Pertanyaan Yang Sering Diajukan</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="faq-table">
                        <thead>
                            <tr>
                                <th>Pertanyaan</th>
                                <th>Jawaban</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('partials.asset_datatables')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#faq-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{!! $urlApi ?? url("/api/frontend/v1") !!}/faq',
                    dataSrc: 'data',
                    data: function(d) {
                        // Convert DataTables parameters to API format (use safe defaults to avoid NaN)
                        var start = (typeof d.start !== 'undefined' && d.start !== null) ? d.start : 0;
                        var length = (typeof d.length !== 'undefined' && d.length) ? d.length : (typeof d.pageLength !== 'undefined' ? d.pageLength : 10);
                        var pageNumber = 1;
                        if (length && !isNaN(length)) {
                            pageNumber = Math.floor(start / length) + 1;
                        }

                        return {
                            'page[number]': pageNumber,
                            'page[size]': length,
                        };
                    }
                },
                columns: [
                    {
                        data: 'attributes.question',
                        name: 'question',
                        render: function(data, type, row) {
                            if (data && data.length > 100) {
                                return '<span title="' + data + '">' + data.substring(0, 100) + '...</span>';
                            }
                            return data || '';
                        }
                    },
                    {
                        data: 'attributes.answer',
                        name: 'answer',
                        render: function(data, type, row) {
                            if (data && data.length > 200) {
                                return '<span title="' + data + '">' + data.substring(0, 200) + '...</span>';
                            }
                            return data || '';
                        }
                    },
                    {
                        data: 'attributes.status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return '<span class="label label-success">Published</span>';
                            } else {
                                return '<span class="label label-warning">Draft</span>';
                            }
                        }
                    }
                ],
                order: [
                    [0, 'asc']
                ],                
            });
        });
    </script>    
@endpush
