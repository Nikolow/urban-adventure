# Task
There is a tree of start folder, it&#39;s subfolders, their subfolders, etc...
In each folder, subfolder, etc… there are same structured XML files stored.

1. Read XML parsed content into a data base table:
* PHP script should read XML files information and add it to PostgreSQL two
database tables: “authors” and “books” (use 1:many and unique author’s ID as
link between the tables). XML files content should be displayed as a result.
* If a record from specified file and subfolder already exists PHP script has to
update the record and not to insert it as a new one.
2. XML files should contain Cyrilic, Korean and Japanese symbols as well.
3. Create simple page with a search form (should search by author only from data base).
Result should be printed right after search form. Search word should be populated to
the input after submitting for better user experience. Result design requirements: rows
should slide from left to right one after another with some small animated delay. Data
grid (result) should display the author and assigned books. Please use single sql query.
4. PHP script is supposed to be executed regularly as a Cron Job (Scheduled Task).
5. Requirements:
* Please use object oriented prorgamming;
* Please do not use ready-made frameworks. Use your own codes only;
* Please write short description of test project;
* Please use HTML5 + CSS3 for design purposes;
* Please use only native JavaScript;
* Partial task solution is an option as well;
* Task additional questions are welcome;
* Test for unpredicted sutiations.

<br>

<center>

![Image](https://cloud.netlifyusercontent.com/assets/344dbf88-fdf9-42bb-adb4-46f01eedd629/6c3ead2d-a453-4c41-ac54-2823b27dd966/hr-ross-cooper-2.png)

</center>

<br>

# Description
The Project is separated in two base lines.

1. The XML Parser
- This part is where we have a basic PHP Script that is supposed to be executed regularly as a Cron Job

2. The Main Page
- This part is where we have all records from the XML with a basic search form

<br>

<center>


![Image](https://cloud.netlifyusercontent.com/assets/344dbf88-fdf9-42bb-adb4-46f01eedd629/6c3ead2d-a453-4c41-ac54-2823b27dd966/hr-ross-cooper-2.png)


</center>

<br>

# The Main Page
> For building the Main Page we need to crate basic MVC Framework with a simple strucure.

* The Model: Which is taking care of the iteraction with the DB
* The Controller: Which mediates with the Model and the View
* The View: Which is the visual presentation of the web pages
<br><br>

> Here we have only one page with basic structure and one search feild.

<br>

Steps:
* Loading all components of the framework
* Creating the DB Connection to PostgreSQL
* Getting the route (index, delete, filterBooks)

<br>

Visual Presentation:

![Image](https://i.imgur.com/GWy8O3N.png)

Description:
- Each record is coming with a slide effect from left to right
- Every next record is coming after the end of the animation of the previous
- Delete button: Deletes the Book from DB [Only the book]
- Sort links: Sorts the data by their ID [DESC, ASC]
- Search Form: Search by Author Name
- Table scroll: For better user exprience

<br>

Used technologies in the Framework View:
- jQuery: For the table scroll and animation-delay
- CSS: For the visual design of the elements
- Main HTML Elements: Section, H1, Div, Table

<br>

<center>


![Image](https://cloud.netlifyusercontent.com/assets/344dbf88-fdf9-42bb-adb4-46f01eedd629/6c3ead2d-a453-4c41-ac54-2823b27dd966/hr-ross-cooper-2.png)


</center>

<br>

# The XML Parser
> From my vision we have a storage folder based on some value point (Cities for example). In each folder there have subfolders again with some value point (The Authors first Letter for example).

Strucutre of the Directories:

---

![Image](https://i.imgur.com/I959tXG.png)

---

Strucutre of the XML Files:
```
<?xml version="1.0" encoding="utf-8"?>
	<books>
		<book bn="1000" an="1">
			<author>Author Name 1</author>
		    	<name>Book Name 1</name>
		</book>

		<book bn="1001" an="1">
			<author>Author Name 1</author>
		    	<name>Book Name 2</name>
		</book>

		<book bn="1002" an="1">
			<author>Author Name 1</author>
		    	<name>Book Name 3</name>
		</book>

		<book bn="2000" an="2">
			<author>Author Name 2</author>
		    	<name>Book Name 1</name>
		</book>
	</books>
```
In the XML files on each book we have <u>uniqie</u> <b>book numbers</b> and <b>author numbers</b>

<br><br>

---

> Used a basic HTML, just to be sure that everything is OK with the output. We do not need fancy view of this cron job php script..

Results after running the script:

<b>First Run:</b>

![Image](https://i.imgur.com/L3gn6Fj.png)


<b>Second Run:</b>

![Image](https://i.imgur.com/fF79VdW.png)

<b>Update Run:</b>

![Image](https://i.imgur.com/F64fZMq.png)

---

<br><br>
