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
                    <th width="100" class="text-center">Akses Modul</th>
                    <th width="80" class="text-center">View</th>
                    <th width="80" class="text-center">Create</th>
                    <th width="80" class="text-center">Edit</th>
                    <th width="80" class="text-center">Delete</th>
                    <th width="80" class="text-center">Export</th>
                    <th width="80" class="text-center">Import</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1; 
                    $actions = ['view', 'create', 'edit', 'delete', 'export', 'import'];
                @endphp
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
                                <strong>{{ permission_name($permission['name'] ?? '-') }}</strong>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <input
                                    type="checkbox"
                                    name="permissions[{{ $permission['slug'] ?? ($permission['name'] ?? '') }}]"
                                    value="1"
                                    {{ $permission_val ? 'checked' : '' }}
                                    class="permission-checkbox module-check"
                                    data-name="{{ $permission['name'] ?? '' }}"
                                    style="width: 18px; height: 18px; cursor: pointer;"
                                >
                            </td>
                            @foreach ($actions as $action)
                                @php
                                    $actionChild = null;
                                    foreach ($childs as $c) {
                                        if (($c['name'] ?? '') === ($permission['name'] ?? '') . '.' . $action) {
                                            $actionChild = $c;
                                            break;
                                        }
                                    }
                                    
                                    $actionChecked = 0;
                                    if ($actionChild && isset($role)) {
                                        $actionChecked = permission_val($role->id, $actionChild['slug'] ?? $actionChild['name']);
                                    }
                                @endphp
                                <td class="text-center" style="vertical-align: middle;">
                                    @if ($actionChild)
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $actionChild['slug'] ?? $actionChild['name'] }}]"
                                            value="1"
                                            {{ $actionChecked ? 'checked' : '' }}
                                            class="permission-checkbox action-check"
                                            data-parent="{{ $permission['name'] ?? '' }}"
                                            style="width: 18px; height: 18px; cursor: pointer;"
                                        >
                                    @endif
                                </td>
                            @endforeach
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
        $(function() {
            // Jika checkbox action di-klik, pastikan checkbox modul utama juga terceklis
            $('.action-check').on('change', function() {
                if ($(this).is(':checked')) {
                    var parentRow = $(this).closest('tr');
                    parentRow.find('.module-check').prop('checked', true);
                }
            });

            // Jika checkbox modul utama di-uncheck, uncheck semua action di baris yang sama
            $('.module-check').on('change', function() {
                if (!$(this).is(':checked')) {
                    var parentRow = $(this).closest('tr');
                    parentRow.find('.action-check').prop('checked', false);
                }
            });
        });
    </script>
@endpush
