@extends('layouts.dashboard_template')
@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" rel="stylesheet" />
@endpush
@section('content')
<section class="content-header">
    <h1>
        Jajak Pendapat
        <small>Tambah</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('poll.index') }}">Jajak Pendapat</a></li>
        <li class="active">tambah</li>
    </ol>
</section>
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
            @include('larapoll::dashboard.form_create')
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script>
    Vue.use(Toasted)
    new Vue({
       el: '#app',
        computed:{
          filledOptions(){
            return this.options.map((option) => {
                return option.value;
            } ).filter((option) => option);
          }
        },
        mounted(){
            $('.localDates').datetimepicker({
                format: 'y-m-d H:m',
            });
        },
        data(){
           return {
                newOption: '',
                question: '',
                options: [
                    { value: '', placeholder: 'Opsi 1'},
                    { value: '', placeholder: 'Opsi 2'},
                ],
               error_message: '',
           }
        },
        methods:{
            addNewOption(){
                if(this.newOption.length === 0){
                    this.error_message = "Silahkan isi kolom Opsi";
                    this.flushModal();
                    return;
                }
                if(this.filledOptions.filter( option => option === this.newOption).length !== 0){
                    this.error_message = "Anda tidak dapat menggunakan Nama Opsi yang sama";
                    this.flushModal();
                    return;
                }

                this.options.push({
                    value: this.newOption,
                    placeholder: ''
                });
                this.newOption = '';
            },
            remove(index){
                if(this.filledOptions.length <= 2){
                    this.error_message = "Minimal ada dua Opsi";
                    this.flushModal();
                    return;
                }
                this.options = this.options.map((option, localIndex) => {
                    if(localIndex === index){
                        return null;
                    }

                    return option
                }).filter(option => option);
            },
            save(){
                if(this.question.length === 0){
                    this.error_message = "Silahkan isi pertanyaan dulu";
                    this.flushModal();
                    return;
                }

                if(this.filledOptions.length < 2){
                    this.error_message = "Minimal dua Opsi";
                    this.flushModal();
                    return;
                }

               let data = {
                   question: this.question,
                   options: this.filledOptions,
                   starts_at: document.getElementById('starts_at').value,
                   canVisitorsVote: document.getElementById('canVisitors').checked
               };

               if(document.getElementById('ends_at').value !== ''){
                   data.ends_at = document.getElementById('ends_at').value;
               }

               // POST TO STORE
               axios.post("{{ route('poll.store') }}", data)
               .then((response) => {
                   Vue.toasted.success(response.data).goAway(1500);
                   setTimeout(() => {
                       window.location.replace("{{ route('poll.index') }}");
                   }, 1500)
               })
               .catch((error) => {

                   Object.values(error.response.data.errors)
                   .forEach((error) => {
                       this.flushModal(error[0], 2000);
                   })
               })
            },
            flushModal(message = this.error_message, after = 1500){
                Vue.toasted.error(message).goAway(after);
            }
        }
    });

    
</script>
@endpush