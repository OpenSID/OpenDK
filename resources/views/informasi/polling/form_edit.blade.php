    <div class="cbox box-body" id="app">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="row form-group">
                <div class="col-md-3">
                <label class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold" for="question">
                    Pertanyaan
                </label>
                </div>
                <div class="col-md-8">
                    <input v-model="question" placeholder="{{ $poll->question }}" class="form-control" id="password" type="text">
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-3">
                    <label class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold" for="options">
                        Opsi / Pilihan
                    </label>
                </div>
                <div class="col-md-8">
                    <button v-if="canChangeOptions" @click.prevent="remove(index)" class="flex-shrink-0 border-transparent border-4 text-teal-500 hover:text-teal-800 text-sm py-1 px-2 rounded" type="button">
                        Hapus
                    </button>
                    <div v-for="(option, index) in options" class="w-full flex items-center border-b border-b-2 border-teal-500 py-2">
                        <input :disabled="canChangeOptions" v-model="option.value" :placeholder="option.placeholder" class="appearance-none bg-transparent border-none block w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" />
                    </div>
                </div>
                <div v-if="canChangeOptions" class="w-full flex items-center border-b border-b-2 border-teal-500 py-2">
                    <input @keyup.enter="addNewOption" v-model="newOption" class="appearance-none bg-transparent border-none block w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="Type your option ..." aria-label="Full name">
                    <button @click.prevent="addNewOption" class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="button">
                        Tambah
                    </button>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <label class="control-label">Durasi<span class="required">*</span></label>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <label class="control-label">Mulai<span class="required">*</span></label>
                    <input value="{{ $poll->starts_at }}" id="starts_at" class="form-control localDates" type="text">
                    <p class="text-red-500 text-xs italic hidden">Please fill out this field.</p>
                </div>
                <label class="control-label col-md-2 col-sm-6 col-xs-12">Selesai<span class="required">*</span></label>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <input value="{{ $poll->ends_at }}"  id="ends_at" class="form-control localDates" type="text">
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-3 col-sm-3">
                    <label class="control-label">Allow guests to vote on the question</label>
                </div>
                <div class="col-md-1 col-sm-3">
                    <input id="canVisitors" type="checkbox" class="" {{ $poll->canVisitorsVote ? 'checked' : ''  }}>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3 col-sm-3">
                    <label class="control-label">Can Voter see result</label>
                </div>
                <div class="col-md-1 col-sm-3">
                    <input id="canVoterSeeResult" type="checkbox" class="" {{ $poll->showResultsEnabled() ? 'checked' : ''  }}>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <label class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold" for="question">
                        Jumlah Opsi yang bisa dipilih
                    </label>
                </div>
                    <div class="col-md-2">

                        <select v-model="maxCheck" name="count_check" class="form-control block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option
                            v-for="(option, index) in Array(options.length -1 ).keys()"
                            :value="index + 1"
                            >
                            @{{ index + 1 }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <button @click.prevent="save" class="btn btn-flat btn-info" type="button">
                    Update
                </button>
            </div>
        </form>
    </div>
