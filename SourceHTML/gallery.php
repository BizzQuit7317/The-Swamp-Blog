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
    <button onclick="window.location.href='index.html'">Home</button>
    <button onclick="window.location.href='gallery.php'">Gallery</button>
    <button onclick="window.location.href='armies.html'">Armies</button>
    <button onclick="window.location.href='battles.html'">Battles</button>
    <button onclick="window.location.href='about.html'">About</button>
</nav>

<div class="gallery-controls">
    <select id="filterSelect">
<!--
        <option value="">Filter by...</option>
        <option value="armies">Armies</option>
        <option value="battles">Battles</option>
	<option value="misc">Misc</option>
-->
    </select>
    <button class="search-button" id="searchButton">Search</button>
    <!-- Add button triggers file input -->
    <button class="add-button" id="addButton">+ Add</button>
    <input type="file" id="imageUpload" style="display:none">
</div>

<!-- Modal for tag selection -->
<div id="tagModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div style="background:#fff; padding:20px; border-radius:8px; max-width:300px; width:100%;">
        <h3>Hold CTRL to select multiple tags</h3>
	<select id="tagSelect" multiple size="5" style="width:100%; height:100px;">
            <!--
            <option value="seraphon">Seraphon</option>
            <option value="slaves">Slaves</option>
            <option value="orruk">Orruk</option>
	    <option value="misc">Misc</option>
            -->
        </select>
        <button id="uploadTags">Upload</button>
        <button id="cancelTags">Cancel</button>
    </div>
</div>

<div class="gallery-grid" id="gallery-grid">
    <?php
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

<script>
const searchButton = document.getElementById('searchButton');
const galleryGrid = document.getElementById('gallery-grid');

searchButton.addEventListener('click', () => {
    const selectedTag = document.getElementById('filterSelect').value;

    fetch('gallery/gallery.json') // adjust path if needed
        .then(res => res.json())
        .then(data => {
            // Clear current gallery
            galleryGrid.innerHTML = '';

            // Filter images by tag
            let imagesToShow = data.images;
            if (selectedTag) {
                imagesToShow = imagesToShow.filter(img => img.tags.includes(selectedTag));
            }

            // Add images to gallery
            imagesToShow.forEach(img => {
                const imageEl = document.createElement('img');
                imageEl.src = 'gallery/' + img.file;
                imageEl.alt = img.file;
                galleryGrid.appendChild(imageEl);
            });
        })
        .catch(err => console.error(err));
});
</script>


<script>
// Load tags from JSON and populate select
fetch('tags.json')
    .then(response => response.json())
    .then(tags => {
        const tagSelect = document.getElementById('tagSelect');
        tags.forEach(tag => {
            const option = document.createElement('option');
            option.value = tag;
            option.textContent = tag.charAt(0).toUpperCase() + tag.slice(1);
            tagSelect.appendChild(option);
        });
    })
    .catch(err => console.error('Failed to load tags:', err));

fetch('tags.json')
    .then(response => response.json())
    .then(tags => {
        const tagSelect = document.getElementById('filterSelect');
        tags.forEach(tag => {
            const option = document.createElement('option');
            option.value = tag;
            option.textContent = tag.charAt(0).toUpperCase() + tag.slice(1);
            tagSelect.appendChild(option);
        });
    })
    .catch(err => console.error('Failed to load tags:', err));

const addButton = document.getElementById('addButton');
const fileInput = document.getElementById('imageUpload');
const modal = document.getElementById('tagModal');
const tagSelect = document.getElementById('tagSelect');
const uploadBtn = document.getElementById('uploadTags');
const cancelBtn = document.getElementById('cancelTags');

addButton.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        modal.style.display = 'flex'; // show modal
    }
});

uploadBtn.addEventListener('click', () => {
    const selectedTags = Array.from(tagSelect.selectedOptions).map(o => o.value);

    const formData = new FormData();
    formData.append('imageUpload', fileInput.files[0]);
    formData.append('tags', JSON.stringify(selectedTags));

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        location.reload();
    })
    .catch(err => {
        alert('Upload failed.');
        console.error(err);
    });

    modal.style.display = 'none'; // hide modal
});

cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    fileInput.value = ''; // clear selected file
});

</script>

</body>
</html>

