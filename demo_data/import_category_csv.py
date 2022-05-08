import csv
import MySQLdb

mydb = MySQLdb.connect(host='127.0.0.1', user='root', passwd='', db='cs6400_sm21_team49', port=3306)
cursor = mydb.cursor()

csv_data = csv.reader(file('csvs/category.csv'))
for row in csv_data:
    cursor.execute("INSERT INTO Category VALUES(%s)", row);
mydb.commit()
cursor.close()
print "Done"
