<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>task </title>
    <style>      
/* Wrapper to ensure full height */
.wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Main Container */
.container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

/* Task Panel (Left) */
.task-panel {
    width: 30%;
    display: flex;
    flex-direction: column;
}

.task-btn {
    padding: 15px;
    margin-bottom: 10px;
    border: none;
    background: gray;
    color: black;
    font-weight: bold;
    cursor: pointer;
}

.task-btn:hover {
    background: #38ff8a;
    color: black;
}

/* Grid Section (Right) */
.grid-container {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 5px;
    width: 60%;
}

.grid-item {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 5px;
    transition: 0.3s;
}

.grid-item:hover {
    background: #38ff8a;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        align-items: center;
        position: static;
    }

    .task-panel {
        width: 90%;
        text-align: center;
    }

    .grid-container {
        grid-template-columns: repeat(4, 1fr);
        width: 100%;
    }
}


    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="wrapper">
        <div class="container">
            <!-- Left Section (Task List) -->
            <div class="task-panel">
                <button class="task-btn">+</button>
                <button class="task-btn">+</button>
                <button class="task-btn">+</button>
                <button class="task-btn">+</button>
                <button class="task-btn">+</button>
            </div>

            <!-- Right Section (Task Grid) -->
            <div class="grid-container">
                <script>
                    for (let i = 0; i < 40; i++) {
                        document.write('<div class="grid-item"></div>');
                    }
                </script>
            </div>
        </div>
    </div>
</body>

</html>