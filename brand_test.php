<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JEL Air Conditioning - Brand Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/enhanced-style.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .test-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 800px;
        }
        .color-palette {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .color-swatch {
            text-align: center;
            padding: 1rem;
            border-radius: 10px;
            color: white;
            font-weight: 600;
        }
        .primary { background: var(--primary-color); }
        .secondary { background: var(--secondary-color); }
        .accent { background: var(--accent-color); }
        .success { background: var(--success-color); }
        .warning { background: var(--warning-color); }
        .danger { background: var(--danger-color); }
        .info { background: var(--info-color); }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-container">
            <div class="text-center mb-4">
                <i class="fas fa-snowflake fa-3x text-primary mb-3"></i>
                <h1 class="brand-logo">JEL Air Conditioning</h1>
                <p class="text-muted">Professional Blue Theme - Brand Test</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h3>Color Palette</h3>
                    <div class="color-palette">
                        <div class="color-swatch primary">Primary<br>#2563eb</div>
                        <div class="color-swatch secondary">Secondary<br>#1e40af</div>
                        <div class="color-swatch accent">Accent<br>#3b82f6</div>
                        <div class="color-swatch success">Success<br>#10b981</div>
                        <div class="color-swatch warning">Warning<br>#f59e0b</div>
                        <div class="color-swatch danger">Danger<br>#ef4444</div>
                        <div class="color-swatch info">Info<br>#06b6d4</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h3>UI Components</h3>
                    <div class="mb-3">
                        <button class="btn btn-primary me-2">
                            <i class="fas fa-check me-1"></i>Primary Button
                        </button>
                        <button class="btn btn-success me-2">
                            <i class="fas fa-plus me-1"></i>Success Button
                        </button>
                        <button class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Danger Button
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>Success message example
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Warning message example
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Test input field">
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h3>Typography</h3>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Card Example</h5>
                        </div>
                        <div class="card-body">
                            <h1>Heading 1</h1>
                            <h2>Heading 2</h2>
                            <h3>Heading 3</h3>
                            <p>This is a paragraph with <strong>bold text</strong> and <em>italic text</em>.</p>
                            <p class="text-muted">This is muted text for secondary information.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="login.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Go to Login
                </a>
                <a href="customer_portal/register.php" class="btn btn-outline-primary btn-lg ms-2">
                    <i class="fas fa-user-plus me-2"></i>Customer Registration
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
