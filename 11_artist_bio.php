<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Bio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #2c3e50; /* Dark background color */
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h2,
        p {
            color: #ecf0f1; /* Adjusted text color */
        }

        .links-container {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Align items vertically in the center */
            width: 80%;
            margin-bottom: 20px;
        }

        /* Add your own styles for links */
        a {
            color: white; /* Set the color to your preferred value */
            text-decoration: none; /* Add underline if desired */
        }

        /* Additional styling for links on hover */
        a:hover {
            color: #2980b9; /* Change color on hover */
        }

        .left-link {
            font-size: 50px;
            font-family: 'Pacifico', cursive; /* Apply Pacifico font style to Dhwani */
        }

        /* Style for the search form */
        form {
            display: flex;
            margin-top: 10px; /* Adjust the top margin to create space between the Dhwani link and the search form */
        }

        input[type="text"] {
            padding: 8px;
            margin-right: 8px; /* Add margin to the right of the input field */
            border-radius: 25px;
            border-style: solid;
        }

        button {
            color: white;/* Button background color */
            background-color: #2c3e50;
            padding: 8px;
            border: none;
            cursor: pointer;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ecf0f1; /* Adjusted border color */
            padding: 8px;
            text-align: center;
        }

        .right-link {
            font-size: 20px;
            margin-top: 10px; /* Adjust the top margin for spacing */
        }
    </style>
</head>

<body>

    <div class="links-container">
        <div class="left-link">
            <a href="http://localhost/Project/01_home.php">Dhwani</a>
        </div>

        <form method="post" action="http://localhost/Project/06_artist.php">
            <input type="text" name="search" placeholder="Search..." required>
            <button type="submit">Search</button>
        </form>



        <div class="right-link">
            <?php
            // Check if the user is logged in
            if (isset($_SESSION['username'])) {
                // If logged in, show a link to the profile
                echo '<a href="http://localhost/Project/05_profile.php">Profile</a>';
            } else {
                // If not logged in, show a link to the login page
                echo '<a href="http://localhost/Project/02_login.php">Login</a>';
            }
            ?>
        </div>
    </div>

    <?php
    // Assuming you have a database connection established earlier
    $conn = new mysqli('localhost', 'root', 'Ayush', 'project');

    if (!$conn) {
        die("Connection Failed: " . mysqli_connect_error());
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
        // Sanitize the search query to prevent SQL injection
        $search_query = mysqli_real_escape_string($conn, $_POST["search"]);

        // Use the search query to fetch artists from the database
        $stmt = $conn->prepare("SELECT * FROM artist WHERE name LIKE ?");
        $search_param = "%" . $search_query . "%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();

        $result = $stmt->get_result();

        // Display the search results in tabular form
        echo "<h2>Search Results:</h2>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Output the artist information
                echo "<h2>{$row['name']}</h2>";
                echo "<p>Date of Birth: {$row['dob']}</p>";
                echo "<p>Bio: {$row['bio']}</p>";
            }
        } else {
            echo "<p>No results found.</p>";
        }

        $stmt->close();
    } else {
        // Check if the artist ID is provided in the URL
        if (isset($_GET['id'])) {
            $artist_id = $_GET['id'];

            // Fetch artist information based on artist ID
            $stmt = $conn->prepare("SELECT * FROM artist WHERE artist_id = ?");
            $stmt->bind_param("s", $artist_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Display artist information
                $row = $result->fetch_assoc();
                echo "<h2>{$row['name']}</h2>";
                echo "<p>Date of Birth: {$row['dob']}</p>";
                echo "<p>Bio: {$row['bio']}</p>";
            } else {
                echo "<p>Artist not found.</p>";
            }

            $stmt->close();

            // Display 5 random albums for the artist
            $stmt = $conn->prepare("SELECT * FROM album WHERE artist_id = ?");
            $stmt->bind_param("s", $artist_id);
            $stmt->execute();
            $album_result = $stmt->get_result();
            $stmt->close();

            if ($album_result->num_rows > 0) {
                echo "<h2>Random Albums:</h2>";
                echo "<table>
                        <tr>
                            <th>Album Name</th>
                            <th>Release Date</th>
                        </tr>";

                while ($album_row = $album_result->fetch_assoc()) {
                    // Output the album information in tabular form
                    $album_id = $album_row['album_id'];
                    echo "<tr>
                            <td><a href=\"http://localhost/Project/16_album_details.php?title={$album_row['title']}\">{$album_row['title']}</a></td>
                            <td>{$album_row['release_date']}</td>
                        </tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No albums found for this artist.</p>";
            }
        }
    }

    $conn->close();
    ?>

</body>

</html>
