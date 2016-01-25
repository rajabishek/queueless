<?php

return [

	'adminmenu-top' => [

		[
			'label' => 'Manage Employees',
			'route' => 'admin.employees.index',
			'active' => ['admin/employees','admin/employees/*/edit'],
			'glyphicon' => 'glyphicon glyphicon-user'
		],
		
		[
			'label' => 'Add Employee',
			'route' => 'admin.employees.create',
			'active' => ['admin/employees/create'],
			'glyphicon' => 'glyphicon glyphicon-plus'
		],
		
		[
			'label' => 'Download List',
			'route' => 'admin.employees.getDownload',
			'active' => ['admin/employees/download'],
			'glyphicon' => 'glyphicon glyphicon-download'
		],
	],

	'adminmenu-bottom' => [

		[
			'label' => 'Settings',
			'route' => 'admin.settings.index',
			'active' => ['admin/settings'],
			'glyphicon' => 'glyphicon glyphicon-cog'
		],
	]
];
