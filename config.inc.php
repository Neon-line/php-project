<?php
$cfg['blowfish_secret'] = '7040DF61B592E6F8CEBE4CAE84F9D7FD';

$cfg['Servers'][1]['auth_type'] = 'cookie';
$cfg['Servers'][1]['host'] = 'mysql';  // Имя сервиса MySQL из docker-compose.yaml
$cfg['Servers'][1]['compress'] = false;
$cfg['Servers'][1]['AllowNoPassword'] = true;