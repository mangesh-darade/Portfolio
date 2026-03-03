<div class="page-header mb-4">
    <h1 class="page-title">Theme Customizer</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Admin</a></li>
            <li class="breadcrumb-item active">Theme Settings</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="card-title-custom mb-0">Customize Appearance</h5>
                <span class="badge bg-primary px-3">Adjustable Theme</span>
            </div>
            <div class="card-body-custom">
                <form id="themeSettingsForm" onsubmit="return false;">
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Theme Style Preset</label>
                            <div class="row g-3">
                                <?php 
                                $presets = [
                                    'modern_dark' => ['name' => 'Modern Dark', 'icon' => 'fa-moon', 'desc' => 'Sleek dark design with glass effects.'],
                                    'minimalist_light' => ['name' => 'Minimalist Light', 'icon' => 'fa-sun', 'desc' => 'Clean, white and bright appearance.'],
                                    'glassmorphism' => ['name' => 'Glassmorphism', 'icon' => 'fa-cubes', 'desc' => 'High transparency and heavy blur.'],
                                    'gradient_vibrant' => ['name' => 'Vibrant Gradient', 'icon' => 'fa-palette', 'desc' => 'Colorful gradients and bold text.']
                                ];
                                foreach ($presets as $key => $p):
                                ?>
                                <div class="col-md-6">
                                    <div class="theme-preset-card <?= $theme['theme_style'] == $key ? 'active' : '' ?>" data-preset="<?= $key ?>">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas <?= $p['icon'] ?> fa-lg me-3 text-primary"></i>
                                            <h6 class="mb-0 text-white"><?= $p['name'] ?></h6>
                                        </div>
                                        <p class="text-muted small mb-0"><?= $p['desc'] ?></p>
                                        <input type="radio" name="theme_style" value="<?= $key ?>" class="d-none" <?= $theme['theme_style'] == $key ? 'checked' : '' ?>>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary opacity-25 my-4">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Primary Color</label>
                            <div class="input-group color-input-wrapper">
                                <input type="color" name="primary_color" value="<?= $theme['primary_color'] ?>" class="form-control form-control-color border-0 rounded-start" title="Choose primary color">
                                <input type="text" value="<?= $theme['primary_color'] ?>" class="form-control text-uppercase" placeholder="#000000" onkeyup="$(this).prev().val(this.value)">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Secondary Color</label>
                            <div class="input-group color-input-wrapper">
                                <input type="color" name="secondary_color" value="<?= $theme['secondary_color'] ?>" class="form-control form-control-color border-0 rounded-start" title="Choose secondary color">
                                <input type="text" value="<?= $theme['secondary_color'] ?>" class="form-control text-uppercase" placeholder="#000000" onkeyup="$(this).prev().val(this.value)">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Background Color</label>
                            <div class="input-group color-input-wrapper">
                                <input type="color" name="bg_color" value="<?= $theme['bg_color'] ?>" class="form-control form-control-color border-0 rounded-start" title="Choose background color">
                                <input type="text" value="<?= $theme['bg_color'] ?>" class="form-control text-uppercase" placeholder="#000000" onkeyup="$(this).prev().val(this.value)">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Text Color</label>
                            <div class="input-group color-input-wrapper">
                                <input type="color" name="text_color" value="<?= $theme['text_color'] ?>" class="form-control form-control-color border-0 rounded-start" title="Choose text color">
                                <input type="text" value="<?= $theme['text_color'] ?>" class="form-control text-uppercase" placeholder="#000000" onkeyup="$(this).prev().val(this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Font Family</label>
                            <select name="font_family" class="form-select">
                                <option value="Outfit" <?= $theme['font_family'] == 'Outfit' ? 'selected' : '' ?>>Outfit (Modern)</option>
                                <option value="Inter" <?= $theme['font_family'] == 'Inter' ? 'selected' : '' ?>>Inter (Professional)</option>
                                <option value="Poppins" <?= $theme['font_family'] == 'Poppins' ? 'selected' : '' ?>>Poppins (Soft)</option>
                                <option value="Roboto" <?= $theme['font_family'] == 'Roboto' ? 'selected' : '' ?>>Roboto (Standard)</option>
                                <option value="Montserrat" <?= $theme['font_family'] == 'Montserrat' ? 'selected' : '' ?>>Montserrat (Bold)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Border Radius (px)</label>
                            <input type="range" name="border_radius" min="0" max="50" value="<?= $theme['border_radius'] ?>" class="form-range" oninput="$(this).next().text(this.value + 'px')">
                            <span class="text-primary small fw-bold"><?= $theme['border_radius'] ?>px</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Glass Opacity (0 - 1)</label>
                            <input type="range" name="glass_opacity" min="0" max="1" step="0.01" value="<?= $theme['glass_opacity'] ?>" class="form-range" oninput="$(this).next().text(this.value)">
                            <span class="text-primary small fw-bold"><?= $theme['glass_opacity'] ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Card Blur (px)</label>
                            <input type="range" name="card_blur" min="0" max="40" value="<?= $theme['card_blur'] ?>" class="form-range" oninput="$(this).next().text(this.value + 'px')">
                            <span class="text-primary small fw-bold"><?= $theme['card_blur'] ?>px</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Custom CSS (Advanced)</label>
                        <textarea name="custom_css" class="form-control" rows="5" placeholder="/* Add your custom styles here */"><?= esc($theme['custom_css']) ?></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-save me-2"></i> Save Theme Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom sticky-top" style="top: 100px;">
            <div class="card-header-custom">
                <h5 class="card-title-custom mb-0">Live Preview Hint</h5>
            </div>
            <div class="card-body-custom">
                <div class="preview-box p-4 rounded shadow-lg mb-4" id="themePreviewBox" style="background: <?= $theme['bg_color'] ?>; font-family: '<?= $theme['font_family'] ?>', sans-serif;">
                    <div class="preview-card p-3 mb-3 border border-light border-opacity-10" style="background: rgba(255,255,255,<?= $theme['glass_opacity'] ?>); backdrop-filter: blur(<?= $theme['card_blur'] ?>px); border-radius: <?= $theme['border_radius'] ?>px;">
                        <h6 class="preview-title" style="color: <?= $theme['primary_color'] ?>;">Sample Title</h6>
                        <p class="small mb-0" style="color: <?= $theme['text_color'] ?>; opacity: 0.8;">This is how your components will look with current settings.</p>
                        <button class="btn btn-sm mt-3 w-100" style="background: <?= $theme['primary_color'] ?>; color: #fff; border-radius: <?= $theme['border_radius'] ?>px;">Button</button>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge" style="background: <?= $theme['secondary_color'] ?>; color: #fff;">Badge</span>
                        <span class="badge outline border" style="border-color: <?= $theme['primary_color'] ?> !important; color: <?= $theme['primary_color'] ?>;">Outline</span>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt me-2"></i> View Live Portfolio
                    </a>
                </div>
                <p class="text-muted small mt-3"><strong>Note:</strong> Some changes might require a full page refresh on the frontend to take complete effect.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .theme-preset-card {
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .theme-preset-card:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }
    .theme-preset-card.active {
        background: rgba(108, 92, 231, 0.1);
        border-color: #6c5ce7;
        box-shadow: 0 4px 15px rgba(108, 92, 231, 0.2);
    }
    .color-input-wrapper input[type="color"] {
        width: 60px;
        height: 100%;
        padding: 0;
        cursor: pointer;
    }
    .form-control-color {
        background: none !important;
    }
</style>

<script>
$(document).ready(function() {
    // Preset selection
    $('.theme-preset-card').click(function() {
        $('.theme-preset-card').removeClass('active');
        $(this).addClass('active');
        $(this).find('input[type="radio"]').prop('checked', true);
        
        // Auto-fill values based on preset
        const preset = $(this).data('preset');
        if (preset === 'minimalist_light') {
            updateInputs('#6c5ce7', '#a29bfe', '#f8f9fa', '#2d3436', 12, 0.05, 5);
        } else if (preset === 'modern_dark') {
            updateInputs('#6c5ce7', '#a29bfe', '#0f0c29', '#ffffff', 20, 0.1, 10);
        } else if (preset === 'glassmorphism') {
            updateInputs('#00d2ff', '#3a7bd5', '#000000', '#ffffff', 25, 0.15, 25);
        } else if (preset === 'gradient_vibrant') {
            updateInputs('#ff0080', '#7928ca', '#1a1a1a', '#ffffff', 30, 0.2, 15);
        }
    });

    function updateInputs(primary, secondary, bg, text, radius = 20, opacity = 0.1, blur = 10) {
        $('input[name="primary_color"]').val(primary).next().val(primary);
        $('input[name="secondary_color"]').val(secondary).next().val(secondary);
        $('input[name="bg_color"]').val(bg).next().val(bg);
        $('input[name="text_color"]').val(text).next().val(text);
        
        $('input[name="border_radius"]').val(radius).next().text(radius + 'px');
        $('input[name="glass_opacity"]').val(opacity).next().text(opacity);
        $('input[name="card_blur"]').val(blur).next().text(blur + 'px');
        
        updatePreview();
    }

    // Live preview update
    $('input, select, textarea').on('input change', function() {
        updatePreview();
    });

    function updatePreview() {
        const primary = $('input[name="primary_color"]').val();
        const secondary = $('input[name="secondary_color"]').val();
        const bg = $('input[name="bg_color"]').val();
        const text = $('input[name="text_color"]').val();
        const font = $('select[name="font_family"]').val();
        const radius = $('input[name="border_radius"]').val() + 'px';
        const opacity = $('input[name="glass_opacity"]').val();
        const blur = $('input[name="card_blur"]').val() + 'px';

        $('#themePreviewBox').css({
            'background': bg,
            'font-family': font + ', sans-serif'
        });
        $('.preview-card').css({
            'background': 'rgba(255,255,255,' + opacity + ')',
            'backdrop-filter': 'blur(' + blur + ')',
            'border-radius': radius
        });
        $('.preview-title').css('color', primary);
        $('.preview-card p').css('color', text);
        $('.preview-card button').css({'background': primary, 'border-radius': radius});
        $('.preview-box .badge').first().css('background', secondary);
        $('.preview-box .badge.outline').css({'border-color': primary, 'color': primary});
    }

    $('#themeSettingsForm').on('submit', function(e) {
        e.preventDefault();
        var btn = $(this).find('button[type="submit"]');
        var originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

        $.ajax({
            url: '<?= base_url('admin/theme/update') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                btn.prop('disabled', false).html(originalText);
            },
            error: function() {
                toastr.error('An error occurred while saving.');
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
