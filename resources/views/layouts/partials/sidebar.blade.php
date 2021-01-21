<aside class="aside aside-fixed">
    <div class="aside-header">
        <a href="../../index.html" class="aside-logo">dash<span>forge</span></a>
        <a href="" class="aside-menu-link">
            <i data-feather="menu"></i>
            <i data-feather="x"></i>
        </a>
    </div>
    <div class="aside-body">
        <div class="aside-loggedin">
            <div class="d-flex align-items-center justify-content-start">
                <a href="" class="avatar"><img src="https://via.placeholder.com/500" class="rounded-circle" alt=""></a>
                <div class="aside-alert-link">
                    <a href="" class="new" data-toggle="tooltip" title="You have 2 unread messages"><i data-feather="message-square"></i></a>
                    <a href="" class="new" data-toggle="tooltip" title="You have 4 new notifications"><i data-feather="bell"></i></a>
                    <a href="" data-toggle="tooltip" title="Sign out"><i data-feather="log-out"></i></a>
                </div>
            </div>
            <div class="aside-loggedin-user">
                <a href="#loggedinMenu" class="d-flex align-items-center justify-content-between mg-b-2" data-toggle="collapse">
                    <h6 class="tx-semibold mg-b-0">Katherine Pechon</h6>
                    <i data-feather="chevron-down"></i>
                </a>
                <p class="tx-color-03 tx-12 mg-b-0">Administrator</p>
            </div>
            <div class="collapse" id="loggedinMenu">
                <ul class="nav nav-aside mg-b-0">
                    <li class="nav-item"><a href="" class="nav-link"><i data-feather="edit"></i> <span>Edit Profile</span></a></li>
                    <li class="nav-item"><a href="" class="nav-link"><i data-feather="user"></i> <span>View Profile</span></a></li>
                    <li class="nav-item"><a href="" class="nav-link"><i data-feather="settings"></i> <span>Account Settings</span></a></li>
                    <li class="nav-item"><a href="" class="nav-link"><i data-feather="help-circle"></i> <span>Help Center</span></a></li>
                    <li class="nav-item"><a href="" class="nav-link"><i data-feather="log-out"></i> <span>Sign Out</span></a></li>
                </ul>
            </div>
        </div><!-- aside-loggedin -->
        <ul class="nav nav-aside">
            <li class="nav-item"><a href="{{route('dashboard')}}" class="nav-link"><i data-feather="home"></i> <span>Dashboard</span></a></li>
            <li class="nav-item with-sub">
                <a class="nav-link"><i data-feather="tag"></i> <span>Products</span></a>
                <ul>
                    <li><a href="{{route('module.categories.home')}}">Categories</a></li>
                    <li><a href="{{route('module.categories.add')}}">Add Category</a></li>
                    <li><a href="{{route('module.items.home')}}">Item Listing</a></li>
                    <li><a href="{{route('module.items.add')}}">Add Items</a></li>
                </ul>
            </li>
            <li class="nav-item with-sub">
                <a class="nav-link"><i data-feather="users"></i> <span>Parties</span></a>
                <ul>
                    <li><a href="{{route('module.suppliers.home')}}">View Suppliers</a></li>
                    <li><a href="{{route('module.suppliers.add')}}">Add Supplier</a></li>
                    <li><a href="{{route('module.customers.home')}}">View Customers</a></li>
                    <li><a href="{{route('module.customers.add')}}">Add Customer</a></li>
                </ul>
            </li>
            <li class="nav-item with-sub">
                <a class="nav-link"><i data-feather="credit-card"></i> <span>Accounts</span></a>
                <ul>
                    <li><a href="{{route('module.suppliersAccount.home')}}">Suppliers Account</a></li>
                    <li><a href="{{route('module.suppliersAccount.add')}}">Add Supplier Payment</a></li>
                    <li><a href="{{route('module.customersAccount.home')}}">Customers Account</a></li>
                    <li><a href="{{route('module.customersAccount.add')}}">Add Customer Payment</a></li>
                </ul>
            </li>
            <li class="nav-item with-sub">
                <a class="nav-link"><i data-feather="clipboard"></i> <span>Purchases</span></a>
                <ul>
                    <li><a href="{{route('module.purchaseOrders.home')}}">History</a></li>
                    <li><a href="{{route('module.purchaseOrders.add')}}">Add Purchase Order</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="{{route('module.inventory.home')}}" class="nav-link"><i data-feather="archive"></i> <span>Inventory</span></a></li>
            <li class="nav-item with-sub">
                <a class="nav-link"><i data-feather="dollar-sign"></i> <span>Orders</span></a>
                <ul>
                    <li><a href="{{route('module.saleOrders.home')}}">History</a></li>
                    <li><a href="{{route('module.saleOrders.add')}}">Add Sale Order</a></li>
                </ul>
            </li>
            <li class="nav-item with-sub">
                <a class="nav-link"><i data-feather="inbox"></i> <span>Expenses</span></a>
                <ul>
                    <li><a href="{{route('module.expenses.home')}}">History</a></li>
                    <li><a href="{{route('module.expenseCategories.home')}}">Categories</a></li>
                    <li><a href="{{route('module.expenseCategories.add')}}">Add Expense Category</a></li>
                    <li><a href="{{route('module.expenses.add')}}">Add Expense</a></li>
                </ul>
            </li>
            <li class="nav-item with-sub">
                <a class="nav-link"><i data-feather="file-text"></i> <span>Reports</span></a>
                <ul>
                    <li><a href="{{route('module.PurchaseOrderReport.home')}}">Purchase Order Report</a></li>
                    <li><a href="{{route('module.SaleOrderReport.home')}}">Sale Order Report</a></li>
                    <li><a href="{{route('module.ExpenseReport.home')}}">Expense Report</a></li>
                    <li><a href="{{route('module.ProfitLossReport.home')}}">Profit & Loss Report</a></li>
                </ul>
            </li>

            <li class="nav-label mg-t-25">Contact Us</li>
            <li class="nav-item"><a href="{{route('module.inventory.home')}}" class="nav-link"><i data-feather="phone-call"></i> <span>+92 317 1015636</span></a></li>


        </ul>
    </div>
</aside>
