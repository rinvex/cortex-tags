<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.sidebar', function (MenuGenerator $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.taxonomy'), 10, 'fa fa-arrows', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.tags.index'], trans('cortex/tags::common.tags'), 20, 'fa fa-tags')->ifCan('list-tags')->activateOnRoute('adminarea.tags');
    });
});
