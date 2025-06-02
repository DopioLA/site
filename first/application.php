<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="js/bootstrap.bundle.min.js" defer></script>
    <style>
    :root {
        --primary: #3b82f6;
        --primary-dark: #2563eb;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-700: #374151;
        --gray-900: #111827;
    }
    
    body {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        background-color: var(--gray-100);
        color: var(--gray-900);
        line-height: 1.6;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    /* Header Styles */
    .header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        padding: 1.5rem 0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .header-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
    }
    
    /* Violation Cards */
    .violation-card {
        margin: 1.5rem 0;
        padding: 1.75rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        background: #fff;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .violation-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        transition: width 0.3s ease;
    }
    
    .violation-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .violation-card:hover::before {
        width: 8px;
    }
    
    .violation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .car-number {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--gray-900);
        letter-spacing: 0.5px;
    }
    
    .violation-date {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .violation-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0.5rem 0;
        color: var(--gray-900);
    }
    
    .violation-description {
        color: #4b5563;
        margin-bottom: 1rem;
    }
    
    .violation-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
    }
    
    .violation-location {
        display: flex;
        align-items: center;
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .violation-location i {
        margin-right: 0.5rem;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-badge i {
        font-size: 0.7rem;
    }
    
    .status-new { 
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--primary);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    
    .status-confirmed { 
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .status-rejected { 
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .status-pending { 
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    /* Divider */
    .divider {
        border-top: 1px solid var(--gray-200);
        margin: 1.25rem 0;
        opacity: 0.6;
    }
    
    /* Section Headings */
    .section-title {
        font-weight: 700;
        color: var(--gray-900);
        padding-bottom: 0.75rem;
        position: relative;
        margin: 2rem 0 1.5rem;
        font-size: 1.5rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        border-radius: 2px;
    }
    
    /* Filter Controls */
    .filter-controls {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
    }
    
    .filter-label {
        margin-right: 0.75rem;
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .filter-select {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: 1px solid var(--gray-200);
        background-color: white;
        cursor: pointer;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .violation-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .violation-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .filter-controls {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    
    /* Animation for new violations */
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    
    .new-violation {
        animation: pulse 1.5s infinite;
    }
</style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="statements.php">
                <img src="img/car.jpg" alt="Logo" width="75" height="75" class="me-2 rounded-circle">
                <span class="fs-4">Нарушениям.Нет</span>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="application.php">Мои заявки</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <?php
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }

        try {
            $connection = new PDO("mysql:host=MySQL-8.0;dbname=demka_db", "root", "");
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $user_id = $_SESSION['user_id'];
            $sql = "SELECT * FROM statements WHERE user_id = :user_id";
            $stmt = $connection->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);

            if ($stmt->rowCount() > 0) {
                echo '<h2 class="mb-4">Мои заявления о нарушениях</h2>';
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $status_class = 'status-' . $row['status'];
                    $status_text = match($row['status']) {
                        'new' => 'На рассмотрении',
                        'confirmed' => 'Подтверждено',
                        'rejected' => 'Отклонено',
                        default => 'Неизвестный статус'
                    };
        ?>
                    <div class="violation-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="mb-0">Номер авто: <?= htmlspecialchars($row['carNumber']) ?></h5>
                            <span class="status-badge <?= $status_class ?>">
                                <?= $status_text ?>
                            </span>
                        </div>

                        <p class="mb-0"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    </div>
        <?php
                }
            } else {
                echo '<div class="alert alert-info">У вас нет активных заявлений</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Ошибка: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>
</body>
</html>