@extends('layouts.dashboard_template')
@section('title')
Polls- Listing
@endsection

@section('content')
<div id="app">
    <section class="content-header">
        <h1>
            Jajak Pendapat
            <small>Daftar Jajak Pendapat</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Jajak Pendapat</li>
        </ol>
    </section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                        <a href="{{ route('poll.create') }}" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Tambah</a>
                </div>
                <!-- /.box-header -->
                    <div class="box-body">
                        <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
                            <div class="table-responsive">
                                <table v-if="polls.length > 0"  id="polling" class="table table-striped table-bordered" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">#</th>
                                            <th data-priority="2">Aksi</th>
                                            <th data-priority="3">Pertanyaan</th>
                                            <th data-priority="4">Voting</th>
                                            <th data-priority="5">Status</th>
                                            <th data-priority="6">Aktif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center" v-for="(poll, index) in polls">
                                            <th scope="row">@{{ index + 1 }}</th>
                                            {{-- <th scope="row">@{{ poll.id }}</th> --}}
                                            <td>
                                                <a class="btn btn-primary btn-xs btn-flat" :href="poll.result_link">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <a class="btn btn-warning btn-xs btn-flat" :href="poll.edit_link">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                                <a class="btn btn-danger btn-xs btn-flat" href="#" @click.prevent="deletePoll(index)">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                            <td>@{{ poll.question }}</td>
                                            <td>@{{ poll.votes_count }}</td>
                                            <td>
                                                <span v-if="poll.isLocked" class="label flat label-danger">Closed</span>
                                                <span v-else-if="poll.isComingSoon" class="label flat label-info">Soon</span>
                                                <span v-else-if="poll.isRunning" class="label flat label-success">Started</span>
                                            </td>
                                            <td>
                                                <a class="btn btn-info btn-sm btn-flat" href="#" @click.prevent="toggleLock(index)">
                                                    <i v-if="poll.isLocked" class="fa fa-unlock" aria-hidden="true"></i>
                                                    <i v-else class="fa fa-lock" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <small v-else>No poll has been found. Try to add one <a href="{{ route('poll.create') }}">Now</a></small>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
@include('partials.asset_datatables')
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <!--Datatables -->
    @include('forms.datatable-vertical')
    <script>
    new Vue({
        el: "#app",
        data(){
            return {
                polls: {!! json_encode($polls) !!},
            }
        },
        mounted(){
            $('#polling').DataTable( {
                responsive: true,
            }).columns.adjust();
        },
        methods:{
            deletePoll(index){
                if(confirm('Do You really want to delete this poll?')){
                    axios.delete(this.polls[index].delete_link)
                        .then((response) => {
                            this.polls.splice(index, 1);
                        });
                }
            },
            toggleLock(index){
                if(this.polls[index].isLocked){
                    this.unlock(index);
                    return;
                }

                this.lock(index)
            },
            lock(index){
                if(confirm('Do You really want to lock this poll?')){
                    axios.patch(this.polls[index].lock_link)
                        .then((response) => {
                            this.assignNewData(response)
                        });
                }
            },
            unlock(index){
                if(confirm('Do You really want to unlock this poll?')){
                    axios.patch(this.polls[index].unlock_link)
                        .then((response) => {
                            this.assignNewData(response)
                        });
                }
            },
            assignNewData(response){
                this.polls[index].isLocked = response.data.poll.isLocked;
                this.polls[index].isRunning = response.data.poll.isRunning;
                this.polls[index].isComingSoon = response.data.poll.isComingSoon;
            }
        }
    })
    </script>
@endpush