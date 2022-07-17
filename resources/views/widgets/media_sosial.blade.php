<div class="box-header with-border text-center  bg-blue">
    <h2 class="box-title text-bold">Media Sosial</h2>
</div>
<ul style="list-style-type: none; display:flex; padding: 0;">
    @if(count($medsos) > 0)
        @foreach($medsos as $key => $data)
            <li style="margin: 4px">
                <a href="{{ $data->link }}" rel="noopener noreferrer" target="_blank">
                    <img src="{{ 'storage/media_sosial/' . $data->gambar }}" width="50" height="50" alt="Media Sosial Image">
                </a>
            </li>
        @endforeach
    @else
        <li class="time-label">
            <span class="bg-gray">
                Media sosial tidak tersedia.
            </span>
        </li>
    @endif
</ul>
