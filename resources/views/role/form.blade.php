<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="control-label" for="first-name">Nama <span class="required">*</span></label>
    {!! html()->text('name')->class('form-control')->required()->placeholder('Nama')->value(old('name', isset($role) ? $role->name : '')) !!}
</div>

<div class="form-group">
    <label class="control-label">Daftar Permissions</label>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th width="50" class="text-center">No</th>
                    <th>Nama Permission</th>
                    <th width="100" class="text-center">Aktif</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($permissions as $key => $permission)
                    @if (isset($permission['parent_id']) && $permission['parent_id'] == 0)
                        @php
                            $childs = $permission['children'] ?? [];
                            if (isset($role)) {
                                $permission_val = permission_val($role->id, $permission['slug'] ?? ($permission['name'] ?? ''));
                            } else {
                                $permission_val = 0;
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>
                                <strong>{{ $permission['name'] ?? '-' }}</strong>
                                @if (count($childs) > 0)
                                    <ul class="list-unstyled" style="margin-left: 15px; margin-top: 5px; margin-bottom: 5px;">
                                        @foreach ($childs as $child)
                                            <li>
                                                <small>{{ $child['name'] ?? '-' }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <input
                                    type="checkbox"
                                    name="permissions[{{ $permission['slug'] ?? ($permission['name'] ?? '') }}]"
                                    value="1"
                                    {{ $permission_val ? 'checked' : '' }}
                                    class="permission-checkbox"
                                    data-name="{{ $permission['name'] ?? '' }}"
                                    style="width: 18px; height: 18px; cursor: pointer;"
                                >
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="ln_solid"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <a href="{{ route('setting.role.index') }}"><button class="btn btn-default" type="button">Batal</button></a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).on('change', '.permission-checkbox', function() {
            // Optional: Add any additional functionality when checkbox changes
        });
    </script>
@endpush
