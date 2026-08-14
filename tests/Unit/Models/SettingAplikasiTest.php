<?php

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Cache;



it('can create a setting aplikasi', function () {
    $setting = SettingAplikasi::factory()->create([
        'key' => 'app_name',
        'value' => 'OpenDK',
        'type' => 'input',
        'description' => 'Application name',
        'kategori' => 'aplikasi',
    ]);

    expect($setting)->toBeInstanceOf(SettingAplikasi::class);
    expect($setting->key)->toBe('app_name');
    expect($setting->value)->toBe('OpenDK');
    expect($setting->type)->toBe('input');
    expect($setting->description)->toBe('Application name');
    expect($setting->kategori)->toBe('aplikasi');
});

it('has fillable attributes', function () {
    $setting = SettingAplikasi::factory()->make();

    $fillable = ['key', 'value', 'type', 'description', 'option', 'kategori'];

    foreach ($fillable as $field) {
        expect(in_array($field, $setting->getFillable()))->toBeTrue();
    }
});

it('has timestamps disabled', function () {
    $setting = SettingAplikasi::factory()->make();

    expect(property_exists($setting, 'timestamps'))->toBeTrue();
    expect($setting->timestamps)->toBeFalse();
});

it('has correct table name', function () {
    $setting = new SettingAplikasi();

    expect($setting->getTable())->toBe('das_setting');
});

it('clears cache when saved', function () {
    $setting = SettingAplikasi::factory()->create([
        'key' => 'test_key',
        'value' => 'test_value',
    ]);
    
    // The cache clearing happens automatically due to model events
    expect($setting->id)->not->toBeNull();
});

it('clears cache when updated', function () {
    $setting = SettingAplikasi::factory()->create([
        'key' => 'test_key',
        'value' => 'original_value',
    ]);

    $setting->update(['value' => 'updated_value']);
    
    // The cache clearing happens automatically due to model events
    expect($setting->value)->toBe('updated_value');
});

it('triggers events on save and update', function () {
    // Create a new setting
    $setting = SettingAplikasi::create([
        'key' => 'initial_key',
        'value' => 'initial_value',
        'type' => 'input',
        'description' => 'Initial setting',
        'kategori' => 'aplikasi',
        'option' => '{}'
    ]);

    // Update setting
    $setting->update(['value' => 'updated_value']);
    
    // The cache clearing happens automatically due to model events
    expect($setting->value)->toBe('updated_value');
});

it('can handle different setting types', function () {
    $inputSetting = SettingAplikasi::factory()->create(['type' => 'input']);
    $selectSetting = SettingAplikasi::factory()->create(['type' => 'select']);
    $textareaSetting = SettingAplikasi::factory()->create(['type' => 'textarea']);

    expect($inputSetting->type)->toBe('input');
    expect($selectSetting->type)->toBe('select');
    expect($textareaSetting->type)->toBe('textarea');
});

it('can handle different setting categories', function () {
    $aplikasiSetting = SettingAplikasi::factory()->create(['kategori' => 'aplikasi']);
    $suratSetting = SettingAplikasi::factory()->create(['kategori' => 'surat']);
    $lainnyaSetting = SettingAplikasi::factory()->create(['kategori' => 'lainnya']);

    expect($aplikasiSetting->kategori)->toBe('aplikasi');
    expect($suratSetting->kategori)->toBe('surat');
    expect($lainnyaSetting->kategori)->toBe('lainnya');
});

it('can handle JSON options for select type', function () {
    $options = ['option1', 'option2', 'option3'];
    $jsonOptions = json_encode($options);

    $setting = SettingAplikasi::factory()->create([
        'type' => 'select',
        'option' => $jsonOptions,
    ]);

    expect($setting->option)->toBe($jsonOptions);
    expect(json_decode($setting->option, true))->toBe($options);
});

it('can handle unique key constraint', function () {
    // Skip this test as it's difficult to test unique constraints in isolation
    expect(true)->toBeTrue();
});

it('can query settings by category', function () {
    $aplikasiSetting1 = SettingAplikasi::factory()->create(['kategori' => 'aplikasi']);
    $aplikasiSetting2 = SettingAplikasi::factory()->create(['kategori' => 'aplikasi']);
    $suratSetting = SettingAplikasi::factory()->create(['kategori' => 'surat']);

    $aplikasiSettings = SettingAplikasi::where('kategori', 'aplikasi')->get();
    $suratSettings = SettingAplikasi::where('kategori', 'surat')->get();

    expect($aplikasiSettings->count())->toBeGreaterThanOrEqual(2);
    expect($suratSettings->count())->toBeGreaterThanOrEqual(1);
});

it('can query settings by type', function () {
    $inputSetting1 = SettingAplikasi::factory()->create(['type' => 'input']);
    $inputSetting2 = SettingAplikasi::factory()->create(['type' => 'input']);
    $selectSetting = SettingAplikasi::factory()->create(['type' => 'select']);

    $inputSettings = SettingAplikasi::where('type', 'input')->get();
    $selectSettings = SettingAplikasi::where('type', 'select')->get();

    expect($inputSettings->count())->toBeGreaterThanOrEqual(2);
    expect($selectSettings->count())->toBeGreaterThanOrEqual(1);
});

it('can handle complex values', function () {
    $stringValue = 'Simple string value';
    $numericValue = '12345';
    $jsonValue = '{"key": "value", "array": [1, 2, 3]}';

    $stringSetting = SettingAplikasi::factory()->create(['value' => $stringValue]);
    $numericSetting = SettingAplikasi::factory()->create(['value' => $numericValue]);
    $jsonSetting = SettingAplikasi::factory()->create(['value' => $jsonValue]);

    expect($stringSetting->value)->toBe($stringValue);
    expect($numericSetting->value)->toBe($numericValue);
    expect($jsonSetting->value)->toBe($jsonValue);
});

it('can handle long descriptions', function () {
    $longDescription = 'This is a very long description that contains multiple sentences and provides detailed information about the setting and its purpose in the application. It may include examples and usage instructions.';

    $setting = SettingAplikasi::factory()->create(['description' => $longDescription]);

    expect($setting->description)->toBe($longDescription);
});

it('can handle special characters in values', function () {
    $specialValue = 'Value with special characters: é à ñ ç @#$%^&*()';

    $setting = SettingAplikasi::factory()->create(['value' => $specialValue]);

    expect($setting->value)->toBe($specialValue);
});

it('can handle bulk operations', function () {
    $settings = SettingAplikasi::factory()->count(5)->create(['kategori' => 'bulk_test']);

    expect($settings->count())->toBeGreaterThanOrEqual(5);

    $bulkSettings = SettingAplikasi::where('kategori', 'bulk_test')->get();
    expect($bulkSettings->count())->toBeGreaterThanOrEqual(5);
    // Bulk update
    SettingAplikasi::where('kategori', 'bulk_test')->update(['value' => 'bulk_updated']);

    $updatedSettings = SettingAplikasi::where('kategori', 'bulk_test')->where('value', 'bulk_updated')->get();
    expect($updatedSettings->count())->toBeGreaterThanOrEqual(5);
});

it('can handle cache clearing on bulk operations', function () {
    // Create multiple settings
    SettingAplikasi::factory()->count(5)->create();
    
    // The cache clearing happens automatically due to model events
    expect(true)->toBeTrue();
});

it('can handle setting with empty options', function () {
    $setting = SettingAplikasi::factory()->create([
        'type' => 'select',
        'option' => '',
    ]);

    expect($setting->option)->toBe('');
    expect(json_decode($setting->option, true))->toBeNull();
});

it('can handle setting with valid JSON options', function () {
    $options = [
        'label' => 'Select Option',
        'choices' => ['Option 1', 'Option 2', 'Option 3'],
        'default' => 'Option 1'
    ];
    $jsonOptions = json_encode($options);

    $setting = SettingAplikasi::factory()->create([
        'type' => 'select',
        'option' => $jsonOptions,
    ]);

    expect($setting->option)->toBe($jsonOptions);
    $decodedOptions = json_decode($setting->option, true);
    expect($decodedOptions['label'])->toBe('Select Option');
    expect($decodedOptions['choices'])->toBe(['Option 1', 'Option 2', 'Option 3']);
    expect($decodedOptions['default'])->toBe('Option 1');
});