<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Calender Image Database</title>
        <link rel="stylesheet" href="accordionStyle.css">
    </head>
    <body>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image" />
            <input type="submit" name="submit" value="Upload" />
            <input type="text" name="imageName" />
        </form>
        
        <button class="accordion">A</button>
        <div class="panel">
            <ul>
                <li>Adele</li>
                <li>Agnes</li>
            </ul>
        </div>
        
<!--        <button class="accordion">B</button>
        <div class="panel">
            <ul>
                <li>Button</li>
                <li>Beauticrux</li>
            </ul>
        </div>
        
        <button class="accordion">C</button>
        <div class="panel">
            <ul>
                <li>Class</li>
                <li>Carrolling</li>
            </ul>
        </div>-->
        
        <?php
        $servername = "localhost";
        $username = "root";
        $password = '';
        $dbname = "myDB";
        
        // Create connection
        $connect = mysqli_connect($servername, $username, $password);
        
        // Check connection
        if ($connect->connect_error) {
            die("Connection failed: " . mysqli_error($connect));
        }
        
        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS myDB";
        if(!(mysqli_query($connect, $sql))) {
            echo "Error creating datababse: " . mysqli_error($connect) . "<br>";
        }
        
        // Create connection
        $connect = mysqli_connect($servername, $username, $password, $dbname);
        
        // Check connection
        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS PHOTO(
        PhotoID int NOT NULL AUTO_INCREMENT,
        Title varchar(64),
        Author varchar(64),
        Date DATE,
        Event varchar(64),
        EventType varchar(64),
        Link varchar(256) NOT NULL,
        PRIMARY KEY(PhotoID)
        )";
        
        if(!(mysqli_query($connect, $sql))) {
            echo "Error creating table PHOTO: " . mysqli_error($connect) . "<br>";
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS TAG(
        PhotoID int NOT NULL AUTO_INCREMENT,
        Tag varchar(64),
        PRIMARY KEY(PhotoID)
        )";
        
        if(!(mysqli_query($connect, $sql))) {
            echo "Error creating table TAG: " . mysqli_error($connect) . "<br>";
        }
        
        // Clear table each run for testing purposes
        $sql = "TRUNCATE TABLE PHOTO";
        if(!(mysqli_query($connect, $sql))) {
            echo "Failed to clear PHOTO table: " . mysqli_error($connect) . "<br>";
        }
        
        $sql = "TRUNCATE TABLE TAG";
        if(!(mysqli_query($connect, $sql))) {
            echo "Failed to clear TAG table: " . mysqli_error($connect) . "<br>";
        }
        
        // Insert a test record
        $sql = "INSERT INTO PHOTO(Title, Author, Date, Event, EventType, Link)
            VALUES ('Cool Picture', 'John', '20170305', 'Fun Event', 'test', 'nbproject/databaseimages/peanuts.jpg')";
        if(!(mysqli_query($connect, $sql))) {
            echo "Error inserting record: " . mysqli_error($connect) . "<br>";
        }
        {
            // Insert tags
            $sql = "INSERT INTO TAG(Tag)
                VALUES ('#fun')";
            if(!(mysqli_query($connect, $sql))) {
                echo "Error inserting tag: " . mysqli_error($connect) . "<br>";
            }
            
            $sql = "INSERT INTO TAG(Tag)
                VALUES ('#cool')";
            if(!(mysqli_query($connect, $sql))) {
                echo "Error inserting tag: " . mysqli_error($connect) . "<br>";
            }
        }
            
        // Insert a test record
        $sql = "INSERT INTO PHOTO(Title, Author, Date, Event, EventType, Link)
            VALUES ('Nice Picture', 'Joe', '', 'Fun Event', 'test', 'http//:DifferentLinkHere')";
        if(!(mysqli_query($connect, $sql))) {
            echo "Error inserting record: " . mysqli_error($connect) . "<br>";
        }
        
        // Insert a test record
        $sql = "INSERT INTO PHOTO(Title, Author, Date, Event, EventType, Link)
            VALUES ('Awesome Picture', '', '', 'Amazing Event', '', 'http//:AnotherLinkHere')";
        if(!(mysqli_query($connect, $sql))) {
            echo "Error inserting record: " . mysqli_error($connect) . "<br>";
        }
        {
            // Insert tags
            $sql = "INSERT INTO TAG(Tag)
                VALUES ('#fun')";
            if(!(mysqli_query($connect, $sql))) {
                echo "Error inserting tag: " . mysqli_error($connect) . "<br>";
            }
        }

        // List distinct event names
        $sql = "SELECT DISTINCT Event FROM PHOTO";
        $result = mysqli_query($connect, $sql);
        
        if (mysqli_num_rows($result) > 0)    
        {
            while($row = $result->fetch_assoc())
            {
                echo "<button class=\"accordion\">" . $row['Event'] . "</button>"; //Prints event name
                
                // grab table values from distinct event
                $sql2 = "SELECT * FROM PHOTO WHERE Event='" . $row['Event'] . "'";
                $result2 = mysqli_query($connect, $sql2);
                if (mysqli_num_rows($result2) > 0)    
                {
                    echo "<div class='panel'>";
                    echo "<ul>";
                    while($row2 = $result2->fetch_assoc())
                    {
                        echo "<li>";
                        echo "<a href = " . $row2['Link'] . ">" . $row2['Title'] . "</a>" ; // prints hyperlink with photo title
                        echo "</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
                else
                    echo "0 results <br>";
            }
//            echo "</ul>";
        }
        else
            echo "0 results <br>";
    
        echo "I want to see the pictures with '#fun'!!!";
        $sql = "SELECT * FROM TAG WHERE Tag='#fun'";
        $result = mysqli_query($connect, $sql);
        
        if (mysqli_num_rows($result) > 0)    
        {
            while($row = $result->fetch_assoc())
            {
                $sql2 = "SELECT * FROM PHOTO WHERE PhotoID='" . $row['PhotoID'] . "'";
                $result2 = mysqli_query($connect, $sql2);
                
                if (mysqli_num_rows($result2) > 0)    
                {
                    echo "<ul>";
                    while($row2 = $result2->fetch_assoc())
                    {
                        echo "<li>";
                        echo "<a href = " . $row2['Link'] . ">" . $row2['Title'] . "</a>" ; // prints hyperlink with photo title
                    }
                    echo "</ul>";
                }
                else
                    echo "0 results <br>";
            }
        }
        ?>
        
        <!--php code to upload images to the filesystem for the database-->
        <?php
        if (isset($_POST['submit'])) {
            $imageName = $_POST['imageName']; //Get user's new image title
            $myname = null;
            $save_path=null;
            
            if ($_FILES['image']['name']) {
                $save_path = getcwd() . "/nbproject/DatabaseImages/"; //Folder where images are stored
                $ext = "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); //Get extenstion of image
                $myname = $imageName . $ext; //Rename file here
                move_uploaded_file($_FILES['image']['tmp_name'], $save_path . $myname);
                echo "<p style='color:green;'>Image added successfully</p>";
            } else {
                echo "Error uploading file<br/>";
            }
            
            $sql = "INSERT INTO PHOTO(Title, Author, Date, Event, EventType, Link)
            VALUES (' ".$myname." ', '', '', 'Amazing Event', '', '/nbproject/DatabaseImages/ ".$myname." ')";
            if (!(mysqli_query($connect, $sql))) {
                echo "Error inserting record: " . mysqli_error($connect) . "<br>";
            }
        }
        
        ?>
        
        <script>
            var acc = document.getElementsByClassName("accordion");
            var i;
            
            for(i=0; i<acc.length; i++) {
                acc[i].onclick = function() {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;
                    if(panel.style.display === "block") {
                        panel.style.display = "none";
                    } else {
                        panel.style.display = "block";
                    }
                }
            }
        </script>
    </body>
</html>