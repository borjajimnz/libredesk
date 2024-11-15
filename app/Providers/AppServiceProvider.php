<?php

namespace App\Providers;

use App\Setting;
use App\Traits\Locale;
use App\Translate;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    use Locale;

    public $bindings = [
        "setting" => Setting::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginResponse::class, \App\Http\Responses\LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->translateLabels();
        $this->registerFilamentViews();
        $this->registerFilamentColors();
        $this->registerBladeDirectives();
    }
    private function translateLabels(): void
    {
        $components = [
            TextInput::class,
            Select::class,
            Textarea::class,
            DateTimePicker::class,
            TextColumn::class,
            IconColumn::class,
            Toggle::class,
            Action::class,
            \Filament\Forms\Components\Actions\Action::class,
            \Filament\Tables\Actions\Action::class,
            TagsInput::class,
            Select::class,
            FileUpload::class,
        ];

        foreach ($components as $component) {
            $component::configureUsing(function ($c): void {
                $c->label(self::t(
                    Str::slug($c->getName(), '_')
                ));
            });
        }
    }

    private function registerFilamentViews()
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): string => Blade::render('@livewire(\'social\')'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_REGISTER_FORM_AFTER,
            fn (): string => Blade::render('@livewire(\'social\')'),
        );
    }

    private function registerFilamentColors()
    {
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Slate,
            'info' => Color::Blue,
            'primary' => Setting::getThemeColor(),
            'success' => Color::Green,
            'warning' => Color::Amber,
        ]);
    }

    private function registerBladeDirectives()
    {
        Blade::directive('setting', function ($expression) {
            return "<?php echo setting($expression); ?>";
        });

        Blade::directive('translate', function ($expression) {
            // Evalúa la expresión y luego pasa los parámetros a la función de traducción
            return "<?php echo translate($expression); ?>";
        });
    }
}
