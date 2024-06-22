<section style="margin: 17px;">
    <style>
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: rgb(52,47,112);
            color: rgb(255,255,255);
        }
        .card-body {
            flex: 1;
        }
        .card-img {
            max-height: 300px;
            object-fit: cover;
        }
    </style>

    <div class="container">
        <div class="row">
            <?php
            include 'E:\OSPanel\home\eduworknew.local\config\config.php'; 
            $result = $conn->query("SELECT * FROM news");

            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($row['image']) . '" class="card-img-top card-img" alt="Изображение новости">';
                    echo '<div class="card-body">';
                    echo '<h4 class="card-title">' . htmlspecialchars($row['name']) . '</h4>';
                    echo '<h6 class="card-subtitle mb-2">' . htmlspecialchars($row['description']) . '</h6>';
                    echo '<a class="btn btn-primary shadow" role="button" href="#" data-bs-toggle="modal" data-bs-target="#modal-view-' . $row['id'] . '">Читать далее</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>'; 

                    // modal 
                    echo '<div class="modal fade" id="modal-view-' . $row['id'] . '" tabindex="-1" aria-labelledby="modal-view-title-' . $row['id'] . '" aria-hidden="true">';
                    echo '<div class="modal-dialog modal-dialog-centered">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<h5 class="modal-title">' . htmlspecialchars($row['name']) . '</h5>';
                    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<p>' . htmlspecialchars($row['full_description']) . '</p>';
                    echo '</div>';
                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<div class='col'>Нет новостей в базе данных.</div>";
            }

            $conn = null;
            ?>
        </div>
    </div>
</section>