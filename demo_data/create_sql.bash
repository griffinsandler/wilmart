#!/bin/bash

mysqldump --databases cs6400_sm21_team49 --add-drop-database -uroot --skip-opt > sql/database_export.sql
mysqldump --databases cs6400_sm21_team49 --add-drop-database -uroot > sql/database_export_quickload.sql
