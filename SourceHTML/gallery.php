<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Swamp - Gallery</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>The Swamp</h1>
        <div class="header-row">
            <div class="banner">Welcome to the murky depths</div>
            <div class="auth-buttons">
                <button>Login</button>
                <button>Signup</button>
            </div>
        </div>
    </header>

    <nav>
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='gallery.php'">Gallery</button>
        <button onclick="window.location.href='armies.php'">Armies</button>
        <button onclick="window.location.href='battles.php'">Battles</button>
        <button onclick="window.location.href='about.php'">About</button>
    </nav>

    <div class="gallery-controls">
        <input type="text" placeholder="Search images...">
        <button class="search-button">Search</button>
        <select>
            <option value="">Filter by...</option>
            <option value="armies">Armies</option>
            <option value="battles">Battles</option>
            <option value="misc">Misc</option>
        </select>
        <button class="add-button">+ Add</button>
    </div>

    <div class="gallery-grid" id="gallery-grid">
        <?php
        // PHP dynamically loads images from the gallery folder
        $dir = 'gallery/';
        $images = array_diff(scandir($dir), array('.', '..'));
        foreach ($images as $img) {
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif'])) {
                echo '<img src="'.$dir.$img.'" alt="Gallery image">';
            }
        }
        ?>
    </div>

</body>
</html>
