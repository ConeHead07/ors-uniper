#!/bin/bash

chown root:www-data attachements/
chown root:www-data tmp/
chown root:www-data log/
chown root:www-data smarty_lab/
chown root:www-data smarty_lab/templates
chown root:www-data smarty_lab/templates_c/
chmod ug+w attachements
chmod ug+w log
chmod ug+w tmp
chmod ug+w smarty_lab/
chmod ug+w smarty_lab/templates
chmod ug+w smarty_lab/templates/*
chmod ug+w smarty_lab/templates_c/
chmod ug+w smarty_lab/templates_c/*
