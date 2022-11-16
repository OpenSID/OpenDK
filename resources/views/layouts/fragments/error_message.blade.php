@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Ups!</strong> Ada beberapa masalah dengan masukan Anda.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif