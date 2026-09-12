<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>UniManager — Modern Theme Preview</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
  :root {
    --primary: #2563eb;
    --primary-hover: #1e4ed8;
    --bg: #f9fbfd;
    --card-bg: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --radius: 14px;
  }

  body {
    background: var(--bg);
    color: var(--text-main);
    font-family: "Poppins", sans-serif;
    padding-top: 80px;
    transition: all 0.3s ease-in-out;
  }

  /* Navbar */
  .navbar {
    background: #fff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s;
  }

  .navbar-brand {
    font-weight: 700;
    color: var(--primary) !important;
  }

  .btn-primary {
    background: var(--primary);
    border: none;
    border-radius: var(--radius);
    transition: all 0.2s ease;
    font-weight: 500;
  }

  .btn-primary:hover {
    background: var(--primary-hover);
    transform: translateY(-2px);
  }

  .card {
    background: var(--card-bg);
    border: none;
    border-radius: var(--radius);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    padding: 2rem;
    transition: all 0.3s ease-in-out;
  }

  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
  }

  footer {
    background: white;
    color: var(--text-muted);
    text-align: center;
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    margin-top: 60px;
    font-size: 0.9rem;
  }

  h3 {
    font-weight: 600;
  }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="bi bi-mortarboard"></i> UniManager
      </a>
      <button class="btn btn-primary ms-auto">Login</button>
    </div>
  </nav>

  <!-- Main content -->
  <div class="container text-center">
    <div class="card mx-auto mt-5" style="max-width: 500px;">
      <h3 class="mb-3">Modern UniManager Theme</h3>
      <p class="text-muted mb-4">Smooth, modern, and professional — ideal for students, organizers, and admins.</p>
      <button class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Try It</button>
    </div>
  </div>

  <footer>
    © 2025 King Saud University — UniManager Platform
  </footer>
</body>
</html>
