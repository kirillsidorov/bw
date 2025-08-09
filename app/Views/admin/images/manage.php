<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h1 {
            color: #722f37;
            margin-bottom: 2rem;
            text-align: center;
        }

        .section {
            margin-bottom: 3rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .section h2 {
            color: #722f37;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .upload-area {
            border: 2px dashed #722f37;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            background: white;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .upload-area:hover {
            border-color: #8b3a42;
            background: #f0f8ff;
        }

        .upload-area.dragover {
            border-color: #28a745;
            background: #f0fff0;
        }

        .upload-icon {
            font-size: 3rem;
            color: #722f37;
            margin-bottom: 1rem;
        }

        .file-input {
            display: none;
        }

        .btn {
            background: #722f37;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
            margin: 0.5rem;
        }

        .btn:hover {
            background: #8b3a42;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .image-item:hover .image-overlay {
            opacity: 1;
        }

        .progress {
            width: 100%;
            height: 20px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin: 1rem 0;
            display: none;
        }

        .progress-bar {
            height: 100%;
            background: #722f37;
            width: 0%;
            transition: width 0.3s;
        }

        .message {
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            display: none;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: #722f37;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <a href="<?= base_url('winery/' . $winery['slug']) ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to <?= esc($winery['name']) ?>
        </a>

        <h1>Manage Images - <?= esc($winery['name']) ?></h1>

        <div class="message" id="message"></div>

        <!-- Featured Image Section -->
        <div class="section">
            <h2><i class="fas fa-star"></i> Featured Image</h2>
            <p>Main image displayed on winery cards and at the top of the winery page.</p>
            
            <div class="upload-area" onclick="document.getElementById('featuredInput').click()">
                <div class="upload-icon">
                    <i class="fas fa-image"></i>
                </div>
                <p>Click or drag to upload featured image</p>
                <input type="file" id="featuredInput" class="file-input" accept="image/*" 
                       onchange="uploadImage('featured', this.files[0])">
            </div>

            <div class="progress" id="featuredProgress">
                <div class="progress-bar" id="featuredProgressBar"></div>
            </div>

            <div class="images-grid" id="featuredImages">
                <?php if (!empty($winery['featured_image'])): ?>
                <div class="image-item">
                    <img src="<?= base_url('uploads/wineries/featured/' . $winery['featured_image']) ?>" 
                         alt="Featured Image">
                    <div class="image-overlay">
                        <button class="btn btn-danger" 
                                onclick="deleteImage('featured', '<?= esc($winery['featured_image']) ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Gallery Section -->
        <div class="section">
            <h2><i class="fas fa-images"></i> Gallery</h2>
            <p>Multiple images displayed in the winery gallery section.</p>
            
            <div class="upload-area" onclick="document.getElementById('galleryInput').click()">
                <div class="upload-icon">
                    <i class="fas fa-images"></i>
                </div>
                <p>Click or drag to upload gallery images</p>
                <input type="file" id="galleryInput" class="file-input" accept="image/*" multiple
                       onchange="uploadMultipleImages('gallery', this.files)">
            </div>

            <div class="progress" id="galleryProgress">
                <div class="progress-bar" id="galleryProgressBar"></div>
            </div>

            <div class="images-grid" id="galleryImages">
                <?php 
                $gallery = json_decode($winery['gallery'] ?? '[]', true);
                if (!empty($gallery)):
                    foreach ($gallery as $image): 
                ?>
                <div class="image-item">
                    <img src="<?= base_url('uploads/wineries/gallery/' . $image) ?>" 
                         alt="Gallery Image">
                    <div class="image-overlay">
                        <button class="btn btn-danger" 
                                onclick="deleteImage('gallery', '<?= esc($image) ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>

        <!-- Logo Section -->
        <div class="section">
            <h2><i class="fas fa-certificate"></i> Logo</h2>
            <p>Winery logo displayed on the winery detail page.</p>
            
            <div class="upload-area" onclick="document.getElementById('logoInput').click()">
                <div class="upload-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <p>Click or drag to upload logo</p>
                <input type="file" id="logoInput" class="file-input" accept="image/*" 
                       onchange="uploadImage('logo', this.files[0])">
            </div>

            <div class="progress" id="logoProgress">
                <div class="progress-bar" id="logoProgressBar"></div>
            </div>

            <div class="images-grid" id="logoImages">
                <?php if (!empty($winery['logo'])): ?>
                <div class="image-item">
                    <img src="<?= base_url('uploads/wineries/logos/' . $winery['logo']) ?>" 
                         alt="Logo">
                    <div class="image-overlay">
                        <button class="btn btn-danger" 
                                onclick="deleteImage('logo', '<?= esc($winery['logo']) ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const winerySlug = '<?= esc($winery['slug']) ?>';

        function showMessage(text, type = 'success') {
            const message = document.getElementById('message');
            message.textContent = text;
            message.className = `message ${type}`;
            message.style.display = 'block';
            
            setTimeout(() => {
                message.style.display = 'none';
            }, 5000);
        }

        function uploadImage(type, file) {
            if (!file) return;

            const progress = document.getElementById(type + 'Progress');
            const progressBar = document.getElementById(type + 'ProgressBar');
            
            progress.style.display = 'block';
            progressBar.style.width = '0%';

            const formData = new FormData();
            formData.append('image', file);
            formData.append('winery_slug', winerySlug);
            formData.append('image_type', type);

            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                }
            });

            xhr.addEventListener('load', function() {
                progress.style.display = 'none';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showMessage(response.message, 'success');
                        location.reload(); // Refresh to show new image
                    } else {
                        showMessage(response.message, 'error');
                    }
                } catch (e) {
                    showMessage('Upload failed', 'error');
                }
            });

            xhr.addEventListener('error', function() {
                progress.style.display = 'none';
                showMessage('Upload failed', 'error');
            });

            xhr.open('POST', '<?= base_url('image-upload/upload') ?>');
            xhr.send(formData);
        }

        function uploadMultipleImages(type, files) {
            for (let i = 0; i < files.length; i++) {
                uploadImage(type, files[i]);
            }
        }

        function deleteImage(type, filename) {
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }

            const formData = new FormData();
            formData.append('winery_slug', winerySlug);
            formData.append('image_type', type);
            formData.append('filename', filename);

            fetch('<?= base_url('image-upload/delete') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    location.reload(); // Refresh to remove deleted image
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                showMessage('Delete failed', 'error');
            });
        }

        // Drag and drop functionality
        document.querySelectorAll('.upload-area').forEach(area => {
            area.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            area.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            area.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                const type = this.querySelector('input').id.replace('Input', '');
                
                if (type === 'gallery') {
                    uploadMultipleImages(type, files);
                } else {
                    uploadImage(type, files[0]);
                }
            });
        });
    </script>
</body>
</html>