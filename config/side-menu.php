<?php
return [
    [
        "icon" => "home",
        "title" => "Dashboard",
        "child" => 'dashboard'
    ],
    [
        "icon" => "tag",
        "title" => "Products",
        "child" => [
            [
                "child" => "module.categories.home",
                "title" => "Categories"
            ],[
                "child" => "module.categories.add",
                "title" => "Add Category"
            ],[
                "child" => "module.items.home",
                "title" => "Listing"
            ],[
                "child" => "module.items.add",
                "title" => "Add Items"
            ],
        ]
    ],


];
