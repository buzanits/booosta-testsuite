<?php
$menu = [
          'Management' => [
                            'Personal Settings' => '/admin_self',
                            #'Personal Settings' => '{%base_dir}{%usersystem_dir}exec/admin_self.php',
                          ],

         'User Administration' => [
                                    'Privileges' => '/admin_privilege',
                                    'Roles' => '/admin_role',
                                    'Adminuser' => '/admin_adminuser',
                                    'User' => '/admin_user',
                                    #'Privileges' => '{%base_dir}{%privileges_dir}exec/admin_privilege.php',
                                    #'Roles' => '{%base_dir}{%privileges_dir}exec/admin_role.php',
                                    #'Adminuser' => '{%base_dir}{%usersystem_dir}exec/admin_adminuser.php',
                                    #'User' => '{%base_dir}{%usersystem_dir}exec/admin_user.php',
                                  ],
         'Application' => [
                            'Dummy' => '/index',
                            #'SMS' => '{%base_dir}sms.php',
                            'Test' => '/test',
            'Compactdisc' => 'compactdisc',
            'Event' => 'event',
            ###menuitems###
                          ],
         'Logout' => '/logout_adminuser',
         #'Logout' => '{%base_dir}{%usersystem_dir}exec/logout_adminuser.php',
        ];

$menuicons = [
        'Management' => '<i class="fas fa-pencil-alt"></i>',
        'User Administration' => '<i class="fa fa-user"></i>',
        'Application' => '<i class="fas fa-cogs"></i>',
        'Logout' => '<i class="fas fa-sign-out-alt"></i>',
            ];

