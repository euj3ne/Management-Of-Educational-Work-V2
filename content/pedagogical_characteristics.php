<?php include '../includes/header.php'; ?>

<section style="margin: 17px;">
    <div class="container">
        <div>
            <h1 class="text-center" style="color: rgb(0,0,0);">Педагогическая характеристика</h1>
        </div>
        <div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive text-center">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include '../config/config.php';

                                $query = "SELECT * FROM documents WHERE theme = 'd3'";
                                $stmt = $conn->query($query);

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['file_name'], ENT_QUOTES) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['file_description'], ENT_QUOTES) . "</td>";
                                    echo "<td>";
                                    echo "<a class='btn btn-primary shadow' role='button' href='../../../uploads/documents/" . htmlspecialchars($row['file_path'], ENT_QUOTES) . "' download style='background: rgb(66,70,133); border-color: #ffffff00;margin-right: 5px;'>";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' fill='currentColor' viewBox='0 0 16 16' class='bi bi-download'>";
                                    echo "<path d='M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5'></path>";
                                    echo "<path d='M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z'></path>";
                                    echo "</svg>";
                                    echo "</a>";

                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
