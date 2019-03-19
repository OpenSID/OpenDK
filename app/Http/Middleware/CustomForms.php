<?php

namespace App\Http\Middleware;

use Closure;
use Form;

class CustomForms
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the errors from session...
        $errors = session('errors');

        // Extends Form::model() to create a new form opening
        // with configured default options...
        Form::macro('modelHorizontal', function ($model, $options = []) {
            $options = array_diff_key([
                'class' => 'form-horizontal',
                'autocomplete' => 'off',
            ], $options) + $options;

            return Form::model($model, $options);
        });

        // If the field has errors, then add 'has-error' css class to the given field...
        Form::macro('hasError', function ($field) use ($errors) {
            if ($errors && $errors->has($field)) {
                return ' has-error';
            }

            return;
        });

        // Generate error message if the given field has errors...
        Form::macro('errorMsg', function ($field) use ($errors) {
            if ($errors && $errors->has($field)) {
                return sprintf('<p class="help-block text-danger">%s</p>', $errors->first($field));
            }

            return;
        });


        return $next($request);
    }
}
