<div class="box-header with-border text-center  bg-blue">
    <h2 class="box-title text-bold">Media Sosial</h2>
</div>
<ul style="list-style-type: none; display:flex; padding: 0;">
    @foreach ($medsos as $key => $data)
        <li style="margin: 4px">
            <a href="{{ $data->url }}" rel="noopener noreferrer" target="_blank">
                <img src="{{ asset($data->logo) }}" class="logo-medsos" alt="Media Sosial Image">
            </a>
        </li>
    @endforeach
</ul>
