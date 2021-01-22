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
    ], [
        "icon" => "users",
        "title" => "Parties",
        "child" => [
            [
                "child" => "module.suppliers.home",
                "title" => "View Suppliers"
            ], [
                "child" => "module.suppliers.add",
                "title" => "Add Supplier"
            ], [
                "child" => "module.customers.home",
                "title" => "View Customers"
            ], [
                "child" => "module.customers.add",
                "title" => "Add Customer"
            ],
        ]
    ], [
        "icon" => "credit-card",
        "title" => "Accounts",
        "child" => [
            [
                "child" => "module.suppliersAccount.home",
                "title" => "Suppliers Account"
            ], [
                "child" => "module.suppliersAccount.add",
                "title" => "Add Supplier Payment"
            ], [
                "child" => "module.customersAccount.home",
                "title" => "Customers Account"
            ], [
                "child" => "module.customersAccount.add",
                "title" => "Add Customer Payment"
            ],
        ]
    ], [
        "icon" => "archive",
        "title" => "Inventory",
        "child" => 'module.inventory.home'
    ], [
        "icon" => "clipboard",
        "title" => "Purchases",
        "child" => [
            [
                "child" => "module.purchaseOrders.home",
                "title" => "History"
            ], [
                "child" => "module.purchaseOrders.add",
                "title" => "Add Purchase Order"
            ],
        ]
    ], [
        "icon" => "dollar-sign",
        "title" => "Orders",
        "child" => [
            [
                "child" => "module.saleOrders.home",
                "title" => "History"
            ], [
                "child" => "module.saleOrders.add",
                "title" => "Add Sale Order"
            ],
        ]
    ], [
        "icon" => "inbox",
        "title" => "Expenses",
        "child" => [
            [
                "child" => "module.expenses.home",
                "title" => "History"
            ], [
                "child" => "module.expenseCategories.home",
                "title" => "Categories"
            ], [
                "child" => "module.expenseCategories.home",
                "title" => "Add Expense Category"
            ], [
                "child" => "module.expenses.home",
                "title" => "Add Expense"
            ],
        ]
    ], [
        "icon" => "file-text",
        "title" => "Reports",
        "child" => [
            [
                "child" => "module.PurchaseOrderReport.home",
                "title" => "Purchase Order Report"
            ], [
                "child" => "module.SaleOrderReport.home",
                "title" => "Sale Order Report"
            ], [
                "child" => "module.ExpenseReport.home",
                "title" => "Expense Report"
            ], [
                "child" => "module.ProfitLossReport.home",
                "title" => "Profit & Loss Report"
            ],
        ]
    ]

];