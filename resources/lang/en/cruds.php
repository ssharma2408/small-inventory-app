<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission' => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'name'                     => 'Name',
            'name_helper'              => ' ',
            'email'                    => 'Email',
            'email_helper'             => ' ',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password'                 => 'Password',
            'password_helper'          => ' ',
            'roles'                    => 'Roles',
            'roles_helper'             => ' ',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
        ],
    ],
	'expenseManagement' => [
        'title'          => 'Expense management',
        'title_singular' => 'Expense management',	
    ],
    'supplier' => [
        'title'          => 'Supplier',
        'title_singular' => 'Supplier',
        'fields'         => [
            'id'                     => 'ID',
            'id_helper'              => ' ',
            'supplier_name'          => 'Supplier Name',
            'supplier_name_helper'   => ' ',
            'supplier_number'        => 'Supplier Number',
            'supplier_number_helper' => ' ',
            'supplier_email'         => 'Supplier Email',
            'supplier_email_helper'  => ' ',
            'created_at'             => 'Created at',
            'created_at_helper'      => ' ',
            'updated_at'             => 'Updated at',
            'updated_at_helper'      => ' ',
            'deleted_at'             => 'Deleted at',
            'deleted_at_helper'      => ' ',
        ],
    ],
	'orderManagement' => [
        'title'          => 'Order management',
        'title_singular' => 'Order management',
    ],
    'customer' => [
        'title'          => 'Customers',
        'title_singular' => 'Customer',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'company_name'        => 'Company Name',
			'company_name_helper' => ' ',
            'name'                => 'Customer Name',
            'name_helper'         => ' ',            
            'address'             => 'Address',
            'address_helper'      => ' ',
            'pincode'      		  => 'Postal Code',
            'pincode_helper'      => ' ',
            'phone_number'        => 'Phone Number',
            'phone_number_helper' => ' ',
            'email'               => 'Email',
            'email_helper'        => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
            'deleted_at'          => 'Deleted at',
            'deleted_at_helper'   => ' ',
            'contact_name'        => 'Contact Person',
            'contact_name_helper' => ' ',
            'payment_terms' 	  => 'Payment Terms',
            'payment_terms_helper' => ' ',
        ],
    ],
    'inventory' => [
        'title'          => 'Expense',
        'title_singular' => 'Expense',
        'fields'         => [
            'id'                              => 'ID',
            'id_helper'                       => ' ',
            'supplier'                        => 'Supplier',
            'supplier_helper'                 => ' ',
            'stock'                           => 'Stock',
            'stock_helper'                    => ' ',
            'discount'                        => 'Discount',
            'discount_helper'                 => ' ',
            'final_price'                     => 'Final Price',
            'final_price_helper'              => ' ',
            'created_at'                      => 'Created at',
            'created_at_helper'               => ' ',
            'updated_at'                      => 'Updated at',
            'updated_at_helper'               => ' ',
            'deleted_at'                      => 'Deleted at',
            'deleted_at_helper'               => ' ',
            'discount_type'                   => 'Discount Type',
            'discount_type_helper'            => ' ',
            'product'                         => 'Product',
            'product_helper'                  => ' ',
            'purchase_price'                  => 'Purchase Price',
            'purchase_price_helper'           => ' ',
            'po_file'                         => 'Po File',
            'po_file_helper'                  => ' ',
            'box_or_unit'                     => 'Box Or Unit',
            'box_or_unit_helper'              => ' ',
            'tax'                             => 'Tax',            
            'tax_helper'                      => ' ',
			'invoice_number'                  => 'Invoice Number',
            'invoice_number_helper'           => ' ',
            'days_payable_outstanding'        => 'Days Payable Outstanding',
            'days_payable_outstanding_helper' => ' ',
			'expense_total'                   => 'Expense Total',            
            'expense_total_helper'            => ' ',
			'expense_tax'                     => 'Expense Tax',            
            'expense_tax_helper'              => ' ',
        ],
    ],
    'order' => [
        'title'          => 'Orders',
        'title_singular' => 'Order',
        'fields'         => [
            'id'                   => 'ID',
            'id_helper'            => ' ',
            'sales_manager'        => 'Sales Manager',
            'sales_manager_helper' => ' ',
            'customer'             => 'Store Name',
            'customer_helper'      => ' ',
			'discount_type'                   => 'Discount Type',
            'discount_type_helper'            => ' ',
			'extra_discount'      => 'Extra Discount',
			'extra_discount_helper'   => ' ',
            'order_total'          => 'Order Final Total',
            'order_total_helper'   => ' ',
            'comments'             => 'Comments',
            'comments_helper'      => ' ',
            'delivery_note'        => 'Delivery Note',
            'delivery_note_helper' => ' ',
            'customer_sign'        => 'Customer Sign',
            'customer_sign_helper' => ' ',
            'created_at'           => 'Created at',
            'created_at_helper'    => ' ',
            'updated_at'           => 'Updated at',
            'updated_at_helper'    => ' ',
            'deleted_at'           => 'Deleted at',
            'deleted_at_helper'    => ' ',
            'status'               => 'Status',
            'status_helper'        => ' ',
			'delivery_agent'        => 'Delivery Agent',
            'delivery_agent_helper' => ' ',
			'order_total_without_tax'  => 'Order Total',
            'order_total_without_tax_helper'   => ' ',
			'order_tax'          => 'Order Tax',
            'order_tax_helper'   => ' ',
            'credit_balance'   => 'Credit Balance',			
            'order_date'   => 'Order Date',
			'order_date_helper'   => ' ',
			'delivery_pic'   => 'Delivery Pic',
			'delivery_pic_helper'   => ' ',			
        ],
    ],
    'product' => [
        'title'          => 'Product',
        'title_singular' => 'Product',
        'fields'         => [
            'id'                           => 'ID',
            'id_helper'                    => ' ',
            'name'                         => 'Name',
            'name_helper'                  => ' ',
            'created_at'                   => 'Created at',
            'created_at_helper'            => ' ',
            'updated_at'                   => 'Updated at',
            'updated_at_helper'            => ' ',
            'deleted_at'                   => 'Deleted at',
            'deleted_at_helper'            => ' ',
            'selling_price'                => 'Minimum Selling Price',
            'selling_price_helper'         => ' ',
            'stock'                        => 'Stock',
            'stock_helper'                 => ' ',
            'maximum_selling_price'        => 'Maximum Selling Price',
            'maximum_selling_price_helper' => ' ',
            'product_image'                => 'Product Image',
            'product_image_helper'         => ' ',
            'box_size'                     => 'Box Size',
            'box_size_helper'              => ' ',
			'tax'                             => 'Tax',
            'tax_helper'                      => ' ',
        ],
    ],
    'category' => [
        'title'          => 'Category',
        'title_singular' => 'Category',
        'fields'         => [
            'id'                    => 'ID',
            'id_helper'             => ' ',
            'name'                  => 'Name',
            'name_helper'           => ' ',
            'category_order'        => 'Category Order',
            'category_order_helper' => ' ',
            'category' 				=> 'Category',
            'category_helper' 		=> ' ',
            'sub_category' 			=> 'Sub Category',
            'sub_category_helper' 	=> ' ',
            'created_at'            => 'Created at',
            'created_at_helper'     => ' ',
            'updated_at'            => 'Updated at',
            'updated_at_helper'     => ' ',
            'deleted_at'            => 'Deleted at',
            'deleted_at_helper'     => ' ',
        ],
    ],
    'tax' => [
        'title'          => 'Tax',
        'title_singular' => 'Tax',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'tax'               => 'Tax ( Percentage )',
            'tax_helper'        => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
        ],
    ],
    'shrinkage' => [
        'title'          => 'Shrinkage',
        'title_singular' => 'Shrinkage',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'product'            => 'Product',
            'product_helper'     => ' ',
            'number'             => 'Number',
            'number_helper'      => ' ',
            'date'               => 'Date',
            'date_helper'        => ' ',
            'description'        => 'Description',
            'description_helper' => ' ',
            'added_by'           => 'Added By',
            'added_by_helper'    => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'orderPayment' => [
        'title'          => 'Order Payment',
        'title_singular' => 'Order Payment',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'order'              => 'Order',
            'order_helper'       => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
            'payment'            => 'Payment',
            'payment_helper'     => ' ',
            'amount'             => 'Amount',
            'amount_helper'      => ' ',
            'description'        => 'Description',
            'description_helper' => ' ',
            'date'               => 'Date',
            'date_helper'        => ' ',
        ],
    ],
    'paymentMethod' => [
        'title'          => 'Payment Method',
        'title_singular' => 'Payment Method',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'status'            => 'Status',
            'status_helper'     => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'expensePayment' => [
        'title'          => 'Expense Payment',
        'title_singular' => 'Expense Payment',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'expense'            => 'Expense',
            'expense_helper'     => ' ',
            'payment'            => 'Payment',
            'payment_helper'     => ' ',
            'amount'             => 'Amount',
            'amount_helper'      => ' ',
            'description'        => 'Description',
            'description_helper' => ' ',
            'date'               => 'Date',
            'date_helper'        => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
			'invoice'            => 'Invoice Number',
			'invoice_helper'     => ' ',			
        ],
    ],
	
	'expenseHistory' => [	
		'expense_history'          => 'Expense History',
		'expense_history_singular' => 'Expense History',
	],
	
	'orderHistory' => [	
		'order_history'          => 'Order History',
		'order_history_singular' => 'Order History',
	],
	
	'creditNote' => [
        'title'          => 'Credit Note',
        'title_singular' => 'Credit Note',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'order'              => 'Order',
            'order_helper'       => ' ',
            'amount'             => 'Amount',
            'amount_helper'      => ' ',
            'description'        => 'Description',
            'description_helper' => ' ',
            'date'               => 'Date',
            'date_helper'        => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],

];
