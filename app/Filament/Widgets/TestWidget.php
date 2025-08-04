<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class TestWidget extends Widget
{

    protected static ?int $sort = 0;

    protected static string $view = 'filament.widgets.test-widget';
}
