<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/users*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/permissions*") ? "active" : "" }} {{ request()->is("admin/roles*") ? "active" : "" }} {{ request()->is("admin/users*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.permission.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.role.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
				 @can('category_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.categories.index") }}" class="nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.category.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('product_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.products.index") }}" class="nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.product.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
				@can('tax_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.taxes.index") }}" class="nav-link {{ request()->is("admin/taxes") || request()->is("admin/taxes/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.tax.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
                @can('payment_method_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.payment-methods.index") }}" class="nav-link {{ request()->is("admin/payment-methods") || request()->is("admin/payment-methods/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.paymentMethod.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
				 @can('shrinkage_access')
                    <li class="nav-item">
                        <a href="{{ route("admin.shrinkages.index") }}" class="nav-link {{ request()->is("admin/shrinkages") || request()->is("admin/shrinkages/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-cogs">

                            </i>
                            <p>
                                {{ trans('cruds.shrinkage.title') }}
                            </p>
                        </a>
                    </li>
                @endcan
				@can('expense_management_access')
					<li class="nav-item has-treeview {{ request()->is("admin/suppliers*") ? "menu-open" : "" }} {{ request()->is("admin/inventories*") ? "menu-open" : "" }} {{ request()->is("admin/expense-payments*") ? "menu-open" : "" }} {{ request()->is("admin/inventories/payment*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/suppliers*") ? "active" : "" }} {{ request()->is("admin/inventories*") ? "active" : "" }} {{ request()->is("admin/expense-payments*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.expenseManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
							@can('supplier_access')
								<li class="nav-item">
									<a href="{{ route("admin.suppliers.index") }}" class="nav-link {{ request()->is("admin/suppliers") || request()->is("admin/suppliers/*") ? "active" : "" }}">
										<i class="fa-fw nav-icon fas fa-cogs">

										</i>
										<p>
											{{ trans('cruds.supplier.title') }}
										</p>
									</a>
								</li>
							@endcan
							@can('inventory_access')
								<li class="nav-item">
									<a href="{{ route("admin.inventories.index") }}" class="nav-link {{ request()->is("admin/inventories") || request()->is("admin/inventories/*") ? "active" : "" }}">
										<i class="fa-fw nav-icon fas fa-cogs">

										</i>
										<p>
											{{ trans('cruds.inventory.title') }}
										</p>
									</a>
								</li>
							@endcan
							@can('expense_payment_access')
								<li class="nav-item">
									<a href="{{ route("admin.expense-payments.index") }}" class="nav-link {{ request()->is("admin/expense-payments") || request()->is("admin/expense-payments/*") ? "active" : "" }}">
										<i class="fa-fw nav-icon fas fa-cogs">

										</i>
										<p>
											{{ trans('cruds.expensePayment.title') }}
										</p>
									</a>
								</li>
							@endcan
							@can('expense_history_access')
								<li class="nav-item">
									<a href="{{ route("admin.inventories.payment") }}" class="nav-link {{ request()->is("admin/inventories/payment") || request()->is("admin/inventories/payment/*") ? "active" : "" }}">
										<i class="fa-fw nav-icon fas fa-cogs">

										</i>
										<p>
											{{ trans('cruds.expenseHistory.expense_history') }}
										</p>
									</a>
								</li>
							@endcan
						</ul>
					</li>
				@endcan
				@can('order_management_access')
					<li class="nav-item has-treeview {{ request()->is("admin/customers*") ? "menu-open" : "" }} {{ request()->is("admin/orders*") ? "menu-open" : "" }} {{ request()->is("admin/order-payments*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/customers*") ? "active" : "" }} {{ request()->is("admin/orders*") ? "active" : "" }} {{ request()->is("admin/order-payments*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.orderManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
							@can('customer_access')
								<li class="nav-item">
									<a href="{{ route("admin.customers.index") }}" class="nav-link {{ request()->is("admin/customers") || request()->is("admin/customers/*") ? "active" : "" }}">
										<i class="fa-fw nav-icon fas fa-cogs">

										</i>
										<p>
											{{ trans('cruds.customer.title') }}
										</p>
									</a>
								</li>
							@endcan
							@can('order_access')
								<li class="nav-item">
									<a href="{{ route("admin.orders.index") }}" class="nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "active" : "" }}">
										<i class="fa-fw nav-icon fas fa-cogs">

										</i>
										<p>
											{{ trans('cruds.order.title') }}
										</p>
									</a>
								</li>
							@endcan
							@can('order_payment_access')
								<li class="nav-item">
									<a href="{{ route("admin.order-payments.index") }}" class="nav-link {{ request()->is("admin/order-payments") || request()->is("admin/order-payments/*") ? "active" : "" }}">
										<i class="fa-fw nav-icon fas fa-cogs">

										</i>
										<p>
											{{ trans('cruds.orderPayment.title') }}
										</p>
									</a>
								</li>
							@endcan							
						</ul>
					</li>				
				@endcan			
                
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon">
                                </i>
                                <p>
                                    {{ trans('global.change_password') }}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>