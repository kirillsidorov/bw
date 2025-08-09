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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h1, h2, h3 {
            color: #722f37;
        }

        .summary {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .summary-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .summary-number {
            font-size: 2rem;
            font-weight: bold;
            color: #722f37;
        }

        .status-good { color: #28a745; }
        .status-warning { color: #ffc107; }
        .status-error { color: #dc3545; }

        .section {
            margin-bottom: 3rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .directory-list {
            list-style: none;
            padding: 0;
        }

        .directory-item {
            padding: 0.5rem;
            margin: 0.5rem 0;
            background: white;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .winery-item {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            background: white;
        }

        .winery-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .image-status {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .image-type {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
        }

        .image-preview {
            width: 100px;
            height: 75px;
            object-fit: cover;
            border-radius: 5px;
            margin-top: 0.5rem;
        }

        .btn {
            background: #722f37;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: #8b3a42;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-danger {
            background: #dc3545;
        }

        .collapsible {
            cursor: pointer;
            user-select: none;
        }

        .collapsible:hover {
            background: #e9ecef;
        }

        .collapsible-content {
            display: none;
            margin-top: 1rem;
        }

        .collapsible.active + .collapsible-content {
            display: block;
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-images"></i> Image Status Debug</h1>
        
        <!-- Summary -->
        <div class="summary">
            <h2>Summary</h2>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-number"><?= $imageStatus['summary']['total_wineries'] ?></div>
                    <div>Total Wineries</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number status-good"><?= $imageStatus['summary']['wineries_with_featured'] ?></div>
                    <div>With Featured Images</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number status-error"><?= $imageStatus['summary']['missing_featured'] ?></div>
                    <div>Missing Featured</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number status-good"><?= $imageStatus['summary']['wineries_with_gallery'] ?></div>
                    <div>With Gallery</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number status-good"><?= $imageStatus['summary']['wineries_with_logo'] ?></div>
                    <div>With Logos</div>
                </div>
            </div>
        </div>

        <!-- Directory Status -->
        <div class="section">
            <h2><i class="fas fa-folder"></i> Directory Status</h2>
            <ul class="directory-list">
                <?php foreach ($imageStatus['directories'] as $name => $dir): ?>
                <li class="directory-item">
                    <span>
                        <strong><?= esc($name) ?></strong><br>
                        <small><?= esc($dir['path']) ?></small>
                    </span>
                    <span>
                        <?php if ($dir['exists']): ?>
                            <span class="status-good"><i class="fas fa-check"></i> Exists</span>
                            <?php if ($dir['writable']): ?>
                                <span class="status-good"><i class="fas fa-edit"></i> Writable</span>
                            <?php else: ?>
                                <span class="status-error"><i class="fas fa-lock"></i> Not Writable</span>
                            <?php endif; ?>
                            <small>(<?= $dir['permissions'] ?>)</small>
                        <?php else: ?>
                            <span class="status-error"><i class="fas fa-times"></i> Missing</span>
                        <?php endif; ?>
                    </span>
                </li>
                <?php endforeach; ?>
            </ul>
            <button class="btn" onclick="createDirectories()">Create Missing Directories</button>
        </div>

        <!-- Winery Images -->
        <div class="section">
            <h2><i class="fas fa-wine-glass-alt"></i> Winery Images</h2>
            
            <?php foreach ($imageStatus['wineries'] as $winery): ?>
            <div class="winery-item">
                <div class="winery-header collapsible" onclick="toggleCollapsible(this)">
                    <h3>
                        <?= esc($winery['name']) ?>
                        <small>(ID: <?= $winery['id'] ?>)</small>
                    </h3>
                    <div>
                        <?php 
                        $hasImages = $winery['featured_image']['exists'] || !empty($winery['gallery']['images']) || $winery['logo']['exists'];
                        ?>
                        <?php if ($hasImages): ?>
                            <span class="status-good"><i class="fas fa-check"></i> Has Images</span>
                        <?php else: ?>
                            <span class="status-error"><i class="fas fa-times"></i> No Images</span>
                        <?php endif; ?>
                        <a href="<?= base_url('admin/images/' . $winery['slug']) ?>" class="btn" target="_blank">
                            Manage Images
                        </a>
                    </div>
                </div>
                
                <div class="collapsible-content">
                    <div class="image-status">
                        <!-- Featured Image -->
                        <div class="image-type">
                            <h4>Featured Image</h4>
                            <?php if ($winery['featured_image']['filename']): ?>
                                <p><strong>File:</strong> <?= esc($winery['featured_image']['filename']) ?></p>
                                <?php if ($winery['featured_image']['exists']): ?>
                                    <span class="status-good"><i class="fas fa-check"></i> Exists</span>
                                    <img src="<?= $winery['featured_image']['url'] ?>" class="image-preview" alt="Featured">
                                <?php else: ?>
                                    <span class="status-error"><i class="fas fa-times"></i> File not found</span>
                                    <br><small><?= esc($winery['featured_image']['path']) ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="status-warning"><i class="fas fa-exclamation"></i> Not set</span>
                            <?php endif; ?>
                        </div>

                        <!-- Gallery -->
                        <div class="image-type">
                            <h4>Gallery (<?= $winery['gallery']['count'] ?> images)</h4>
                            <?php if (!empty($winery['gallery']['images'])): ?>
                                <span class="status-good"><i class="fas fa-check"></i> <?= count($winery['gallery']['images']) ?> found</span>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                                    <?php foreach (array_slice($winery['gallery']['images'], 0, 3) as $image): ?>
                                        <img src="<?= $image['url'] ?>" class="image-preview" alt="Gallery">
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($winery['gallery']['missing'])): ?>
                                <span class="status-error"><i class="fas fa-times"></i> <?= count($winery['gallery']['missing']) ?> missing</span>
                                <ul>
                                    <?php foreach ($winery['gallery']['missing'] as $missing): ?>
                                        <li><small><?= esc($missing['filename']) ?></small></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            
                            <?php if ($winery['gallery']['count'] === 0): ?>
                                <span class="status-warning"><i class="fas fa-exclamation"></i> No gallery images</span>
                            <?php endif; ?>
                        </div>

                        <!-- Logo -->
                        <div class="image-type">
                            <h4>Logo</h4>
                            <?php if ($winery['logo']['filename']): ?>
                                <p><strong>File:</strong> <?= esc($winery['logo']['filename']) ?></p>
                                <?php if ($winery['logo']['exists']): ?>
                                    <span class="status-good"><i class="fas fa-check"></i> Exists</span>
                                    <img src="<?= $winery['logo']['url'] ?>" class="image-preview" alt="Logo">
                                <?php else: ?>
                                    <span class="status-error"><i class="fas fa-times"></i> File not found</span>
                                    <br><small><?= esc($winery['logo']['path']) ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="status-warning"><i class="fas fa-exclamation"></i> Not set</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Region Images -->
        <div class="section">
            <h2><i class="fas fa-map-marked-alt"></i> Region Images</h2>
            
            <?php foreach ($imageStatus['regions'] as $region): ?>
            <div class="winery-item">
                <div class="winery-header">
                    <h3>
                        <?= esc($region['name']) ?>
                        <small>(ID: <?= $region['id'] ?>)</small>
                    </h3>
                    <div>
                        <?php if ($region['image']['exists']): ?>
                            <span class="status-good"><i class="fas fa-check"></i> Has Image</span>
                        <?php else: ?>
                            <span class="status-error"><i class="fas fa-times"></i> No Image</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="image-status">
                    <div class="image-type">
                        <?php if ($region['image']['filename']): ?>
                            <p><strong>File:</strong> <?= esc($region['image']['filename']) ?></p>
                            <?php if ($region['image']['exists']): ?>
                                <span class="status-good"><i class="fas fa-check"></i> Exists</span>
                                <img src="<?= $region['image']['url'] ?>" class="image-preview" alt="Region">
                            <?php else: ?>
                                <span class="status-error"><i class="fas fa-times"></i> File not found</span>
                                <br><small><?= esc($region['image']['path']) ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="status-warning"><i class="fas fa-exclamation"></i> Not set</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleCollapsible(element) {
            element.classList.toggle('active');
        }

        function createDirectories() {
            fetch('<?= base_url('image-debug/create-directories') ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Directories created successfully! Refresh page to see changes.');
                    location.reload();
                } else {
                    alert('Error creating directories: ' + data.errors.join(', '));
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }

        // Auto-collapse items with no issues
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.collapsible').forEach(function(element) {
                const hasError = element.querySelector('.status-error');
                if (!hasError) {
                    element.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>