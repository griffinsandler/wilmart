#!/bin/bash

mysql -uroot < sql/schema.sql

echo "Importing User Table..."
python import_user_csv.py 
echo ""
echo "Importing City Table..."
python import_city_csv.py
echo ""
echo "Importing Store Table..."
python import_store_csv.py
echo ""
echo "Importing Business Day Table..."
python import_businessday_csv.py
echo ""
echo "Importing Manufacturer Table..."
python import_manufacturer_csv.py
echo ""
echo "Importing Category Table..."
python import_category_csv.py
echo ""
echo "Importing Product Table..."
python import_product_csv.py
echo ""
echo "Importing Manages Table..."
python import_manages_csv.py
echo ""
echo "Importing Discounted On Table..."
python import_discountedon_csv.py
echo ""
echo "Importing IsInCategory Table..."
python import_isincategory_csv.py
echo ""
echo "Importing Sold Table..."
python import_sold_csv.py
