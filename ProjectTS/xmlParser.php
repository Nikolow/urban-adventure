<?php 

      xmlScan('C:\xampp\htdocs\ProjectTS\storageBooks\*'); 

      
      /*

            Scan for XML file in each folder
            * param1: path to the main folder
            * return: array with paths (files and folders)

            - DataBase 

      */
      function xmlScan($folder)
      {
            // DB
            $dbConnection = "pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=advancedd";
            $pdo = new PDO($dbConnection);

            $files = glob($folder); // Find pathnames matching a pattern
            foreach ($files as $f) // Loop it
            {
                  if (is_dir($f)) // If is DIR - scan again - recursive
                  {
                        $files = array_merge($files, xmlScan($f .'\*')); // scan subfolder (recursive)
                  }
                  else // If not - this is xml file!
                  {
                        echo "<h2>File Found: ".$f."</h2>";

                        // XML Document Parse
                        $objXmlDocument = simplexml_load_file($f);
      
                        if ($objXmlDocument === FALSE) // If there have any error
                        {
                              echo "<font color=red><strong>There were errors parsing the XML file - SKIPPING..</strong></font><br>";
                              foreach(libxml_get_errors() as $error) 
                              {
                                    echo $error->message;
                              }
                        }
                        else // If xml file is OK
                        {
                              $objJsonDocument = json_encode($objXmlDocument); // json encode
                              $arrOutput = json_decode($objJsonDocument, TRUE); // then decode to array
                        
                              foreach($arrOutput['book'] as $book) // loop though all book nodes
                              {
                                    $book_id = $book['@attributes']['bn']; // attribite bn (used for book n)
                                    $book_name = $book['name']; // element book name
                                    $author_id = $book['@attributes']['an']; // attribute an (used for author n)
                                    $author_name = $book['author']; // element author name

                                    // Print it to know that is in the node
                                    echo "BookN: " . $book_id . " - ";
                                    echo "AuthorN: " . $author_id . " - ";
                                    echo $book_name . " - ";
                                    echo $author_name . "<br>";

                                    // DB Manipulation - PDO, the book N, the author N, the book name, the author name
                                    db_add($pdo, $book_id, $author_id, $book_name, $author_name);
                              }

                              // What a beautifull horizontal line between each xml file?
                              echo "<hr>";
                        }
                  }
            }

            return $files;
      }

      /*
            Help Function for DB Select
            * param1 - DB Object
            * param2 - Author ID
            * param3 - Book ID
            * param4 - Type (1/0) [used for table names]
      */
      function select($pdo, $author_id, $book_id, $type)
      {
            if($type) // for Authors table
            {
                  $sql_select_author = "SELECT * FROM authors WHERE author_id = :author_id";
                  $sth_select_author = $pdo->prepare($sql_select_author);
                  $sth_select_author->execute(array(':author_id' => $author_id));
                  $result_select_author = $sth_select_author->fetchAll();

                  return $result_select_author;
            }
            else // for Books table
            {
                  $sql_select = "SELECT * FROM books WHERE book_id = :book_id AND author_id = :author_id";
                  $sth_select = $pdo->prepare($sql_select);
                  $sth_select->execute(array(':book_id' => $book_id, ':author_id' => $author_id));
                  $result_select = $sth_select->fetchAll();

                  return $result_select;
            }
      }

      /*
            Help Function for DB Insert
            * param1 - DB Object
            * param2 - Author ID
            * param3 - Book ID
            * param4 - Author Name
            * param5 - Book Name
            * param6 - Type (1/0) [used for table names]
      */
      function insert($pdo, $author_id, $book_id, $author_name, $book_name, $type)
      {
            if($type) // for Authors table
            {
                  $sql_insert_author = "INSERT INTO authors (author_id, author_name) VALUES (:author_id, :author_name)";
                  $sth_insert_author = $pdo->prepare($sql_insert_author);
                  $sth_insert_author->execute(array(':author_id' => $author_id, ':author_name' => $author_name));
                  $result_insert_author = $sth_insert_author->rowCount();

                  return $result_insert_author;
            }
            else // for Books table
            {
                  $sql_insert = "INSERT INTO books (book_id, author_id, book_name) VALUES (:book_id, :author_id, :book_name)";
                  $sth_insert= $pdo->prepare($sql_insert);
                  $sth_insert->execute(array(':book_id' => $book_id, ':author_id' => $author_id, ':book_name' => $book_name));
                  $result_insert = $sth_insert->rowCount();

                  return $result_insert;
            }
      }
      
      /*
            Help Function for DB Update
            * param1 - DB Object
            * param2 - Author ID
            * param3 - Book ID
      */
      function update($pdo, $author_id, $book_id)
      {
            // Updating Books table records

            $sql_update = "UPDATE books SET author_id = :author_id WHERE book_id = :book_id";
            $sth_update = $pdo->prepare($sql_update);
            $sth_update->execute(array(':author_id' => $author_id, ':book_id' => $book_id));
            $result_update = $sth_update->rowCount();

            return $result_update;
      }


      /*
            Help Function for DB Manipulation
            * param1 - DB Object
            * param2 - Book ID
            * param3 - Author ID
            * param4 - Book Name
            * param5 - Author Name
            * param6 - Type (1/0) [used for table names]
      */
      function db_add($pdo, $book_id, $author_id, $book_name, $author_name)
      {
            // AUTHOR
            $result_author_select = select($pdo, $author_id, $book_id, 1); // Check if Author in DB?
            if(empty($result_author_select)) // If not -> Insert!
            {
                  if(insert($pdo, $author_id, $book_id, $author_name, $book_name, 1) > 0) echo "<font color=green><strong>Author with ID ".$author_id." (".$author_name.") Inserted Successfully!</strong></font><br>";
                  else echo "<font color=red><strong>Problem with query found!</strong></font><br>";
            }
            else echo "<font color=orange><strong>Author with ID ".$result_author_select[0]['author_id']." (".$result_author_select[0]['author_name'].") Found - NO INSERT!</strong></font><br>";
            

            // BOOK
            $result_book_select = select($pdo, $author_id, $book_id, 0); // Check if Book with that Author in DB?
            if(empty($result_book_select)) // If not ->Insert / Update
            {
                  // INSERT (gets the number of rows affected)
                  if(insert($pdo, $author_id, $book_id, $author_name, $book_name, 0) > 0) echo "<font color=green><strong>Book with ID ".$book_id." (".$book_name.") Inserted Successfully!</strong></font><br>";
                  else // If Insert fails, that means there have a record already in DB for that book_id and we need to update it with the new author_id
                  {
                        // UPDATE (get the number of rows affected)
                        if(update($pdo, $author_id, $book_id) > 0) echo "<font color=green><strong>Successfully Updated Book with ID ".$book_id." - New Author with ID ".$author_id."!</strong></font>";
                        else echo "<font color=red><strong>Problem with query found!</strong></font><br>";
                  }
            }
            else // If there have a record with exact book_id and author_id
            {
                  foreach($result_book_select as $book) // loop thought the all records (LOOP just for anycase.. Our record must be [0] value of the array, but to be sure..)
                  {
                        // Print some usefull info!
                        echo "<font color=orange><strong>Book with ID " . $book['book_id']." (".$book['book_name'].") Found written by Author ID " . $book['author_id'] . " - NO INSERT or UPDATE!</strong></font><br><br>";
                  }
            }
      }


      
?>