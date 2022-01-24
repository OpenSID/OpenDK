<div id="app" class="box-body">
    <form>
        <div class="row form-group">
            <label class="control-label col-md-3 col-sm-6 col-xs-12">Pertanyaan<span class="required">*</span></label>
            <div class="col-md-12 col-sm-6 col-xs-12">
                <input v-model="question" placeholder="Who is the best football player in the world?" class="form-control focus:outline-none focus:shadow-outline" id="password" type="text">
            </div>
        </div>
        
        <div class="row">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-6 col-xs-12">Pilihan Pendapat<span class="required">*</span></label>
                <div v-for="(option, index) in options" class="col-md-12 col-sm-6 col-xs-12">
                    <input v-model="option.value" :placeholder="option.placeholder" class="form-control appearance-none bg-transparent border-none block text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" />
                        <button @click.prevent="remove(index)" class="btn btn-flat btn-danger" type="button">
                            Hapus
                        </button>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <label class="control-label col-md-1 col-sm-6 col-xs-12"></label>
            <div class="col-md-10">
                <input @keyup.enter="addNewOption" v-model="newOption" class="form-control appearance-none bg-transparent border-none block w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="Luka Modric" aria-label="Full name">
            </div>
            <div class="input-group-append">
                <button @click.prevent="addNewOption" class="btn btn-flat btn-info flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="button">
                    Tambah
                </button>
            </div>
        </div>
        <div class="row form-group">
            <label class="control-label col-md-2 col-sm-6 col-xs-12">Mulai<span class="required">*</span></label>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <input id="starts_at" class="form-control appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white localDates" type="text">
                <p class="text-red-500 text-xs italic hidden">Please fill out this field.</p>
            </div>
            <label class="control-label col-md-2 col-sm-6 col-xs-12">Selesai<span class="required">*</span></label>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <input id="ends_at" class="form-control focus:outline-none focus:bg-white focus:border-gray-500 localDates" type="text">
            </div>
        </div>
        <div class="row form-group">
            <label class="control-label col-md-3 col-sm-6 col-xs-12">Izinkan pengunjung untuk Voting<span class="required">*</span></label>
            <div class="col-md-9 col-sm-6 col-xs-12">
                <input type="checkbox" id="canVisitors" name="canVisitorsVote" value="1" {{ old('canVisitorsVote', $poll->canVisitorsVote ??  '')  == 1 ? 'checked' : ''  }}>
            </div>
        </div>
        <div class="box-footer">
            <div class="row form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="{{ route('poll.index') }}">
                        <button type="button" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i>&nbsp; Batal</button>
                    </a>
                    <button @click.prevent="save" class="btn btn-success btn-flat bg-teal-500 text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline" type="button">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>