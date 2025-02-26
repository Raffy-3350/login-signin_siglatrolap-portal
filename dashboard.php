<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styled.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll(".expand, .normal");

            buttons.forEach(button => {
                button.addEventListener("click", function() {
                    const isExpanding = this.classList.contains("expand");
                    const parentBox = this.closest(".box");

                    if (isExpanding) {
                        parentBox.classList.add("full-view");
                        parentBox.querySelector(".expand").style.display = "none";
                        parentBox.querySelector(".normal").style.display = "inline-block";
                    } else {
                        parentBox.classList.remove("full-view");
                        parentBox.querySelector(".expand").style.display = "inline-block";
                        parentBox.querySelector(".normal").style.display = "none";
                    }
                });
            });
        });
    </script>
    <style>
        .main{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            gap: 20px;
        }

        .box {
            background: linear-gradient(135deg, rgb(92, 98, 92), rgb(42, 43, 42)); 
            color: white;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .full-view {
            width: 400px;
            height: auto;
        }

        .button-container {
            margin-top: 10px;
        }

        .expand, .normal {
            background: #ffffff;
            color: #008000;
            border: 2px solid #008000;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            margin: 5px;
        }

        .expand:hover, .normal:hover {
            background: #008000;
            color: #ffffff;
        }

    </style>
</head>
<body>
    <?php include 'header.php'?>
     <main class="main">
     <div class="box">
        <h2>Your Dashboard Update</h2>
    </div>

    <div class="box">
        <div class="button-container">
            <button class="expand">▼ FULL VIEW 1</button>
            <button class="normal" style="display:none;">▶ Normal View 1</button>
        </div>
    </div>

    <div class="box">
        <div class="button-container">
            <button class="expand">▼ FULL VIEW 2</button>
            <button class="normal" style="display:none;">▶ Normal View 2</button>
        </div>
    </div>
     </main>

</body>
</html>
