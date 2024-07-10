<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Songs</title>

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
            /* Button background color */
            color: white;
            background-color: #2c3e50;
            padding: 8px;
            border: none;
            cursor: pointer;
        }

        button:hover{
            color: #2980b9;
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

        /* Style for the audio player */
        audio {
            width: 100%;
        }

    </style>
</head>

<body>

    <div class="links-container">
        <div class="left-link">
            <a href="http://localhost/Project/01_home.php">Dhwani</a>
        </div>

        <form method="post" action="http://localhost/Project/12_search.php">
          <input type="text" name="search" placeholder="Search for a song" required>
          <button type="submit">Search</button>
      </form>


        <div class="right-links">
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
    </div>

    <?php
    // Assuming you have a database connection established earlier
    $conn = new mysqli('localhost', 'root', 'Ayush', 'project');

    if (!$conn) {
        die("Connection Failed: " . mysqli_connect_error());
    }

    // Display the latest 40 songs
    $result = $conn->query("SELECT * FROM song ORDER BY release_date DESC LIMIT 40");

    echo "<h2>Latest Songs:</h2>";

    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Release Date</th>
                    <th>Play Audio</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['title']}</td>
                    <td>{$row['genre']}</td>
                    <td>{$row['release_date']}</td>
                    <td>
                        <audio controls>
                            <source src=\"{$row['audio_url']}\" type=\"audio/mp3\">
                          Your browser does not support the audio element.
                        </audio>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No songs found.</p>";
    }

    $conn->close();
    ?>

</body>

</html>