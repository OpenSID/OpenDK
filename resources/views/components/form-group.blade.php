<div class="form-group {{ $errors->has($attributes->get('name')) ? 'has-error' : '' }}">
    {{ $slot }}
</div>
