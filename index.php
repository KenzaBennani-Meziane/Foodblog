<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>    
    <div class="container">

        <div id="header">
            <h1>Foodblog</h1>
            <a href="new_post.php"><button>Nieuwe post</button></a>
        </div>
            <?php
            require_once 'connection.php';
            // Isset check is above the fetchAll function or else it will repeat the like of the last submitted button. 
            // You want to update before fetching data again.
            if (isset($_POST['like'])) {
                $likes = $conn->prepare("UPDATE posts SET likes=likes+1 WHERE id=" . $_POST['like'] . "");
                $likes->execute();
                // Important to use header or else you will resubmit the like
                header('Location: ./index.php'); 
            }

            $data = $conn->prepare("SELECT naam, SUM(likes) AS totaal_likes FROM auteurs INNER JOIN posts on auteurs.id = posts.auteur_id GROUP BY naam HAVING SUM(likes) > 10;");
            $data->execute();
            $authors = $data->fetchAll();
            echo "<p>Populaire chefs</p>
            <ul>";
            foreach ($authors as $author) {
                echo "<li>" . $author['naam'] . "</li>";
            }
            echo "</ul>";
            $data = $conn->prepare("SELECT * FROM auteurs INNER JOIN posts on auteurs.id = posts.auteur_id ORDER BY likes DESC;");
            $data->execute();
            $posts = $data->fetchAll();

            foreach ($posts as $post) {
                echo "<div class='post'>
                <div class='header'>
                <h2>" . $post['titel'] . "</h2>
                <img src=" . $post['img_url'] . ">
                </div>
                <span class='details'>Geschreven op: " . $post['datum'] . " 13:25:00 door " . $post['naam'] . "</span>
                <span class='details'>Tags ";
                $data = $conn->prepare("SELECT posts_tags.post_id, posts_tags.tag_id, tags.titel FROM posts_tags 
                INNER JOIN posts ON posts_tags.post_id=posts.id INNER JOIN tags ON posts_tags.tag_id=tags.id WHERE post_id=" . $post['id'] . "");
                $data->execute();
                $tags = $data->fetchAll();
                foreach ($tags as $tag) {
                    echo "<a href='lookup.php?tag=" . $tag['titel'] . "'>" . $tag['titel'] . " </a>";
                }
                echo "</span>
                <span class='right'>
                <form action='index.php' method='post'>
                <button type='submit' value=" . $post['id'] . " name='like'>" . $post['likes'] . " likes</button>
                </form>
                </span>
                <p>" . $post['inhoud'] . "</p>
                </div>";
            }
            ?>
        </div>
    </body>
</html>