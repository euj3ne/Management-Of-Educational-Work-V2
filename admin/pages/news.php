<?php
include '../../includes/header.php';
include 'E:\OSPanel\home\eduworknew.local\config\config.php';

function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    $query = "SELECT * FROM news WHERE name LIKE :search OR description LIKE :search OR full_description LIKE :search";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%');
} else {
    $query = "SELECT * FROM news";
    $stmt = $conn->query($query);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sort_list = array(
	'name_asc'   => '`name`',
	'name_desc'  => '`name` DESC',

	'description_asc'  => '`description`',
	'description_desc' => '`description` DESC',
    
	'full_description_asc'   => '`full_description`',
	'full_description_desc'  => '`full_description` DESC',
);
 
$sort = @$_GET['sort'];
if (array_key_exists($sort, $sort_list)) {
	$sort_sql = $sort_list[$sort];
} else {
	$sort_sql = reset($sort_list);
}
 
function sort_link_th($title, $a, $b) {
	$sort = @$_GET['sort'];
 
	if ($sort == $a) {
		return '<a class="active" href="?sort=' . $b . '">' . $title . ' <i>▲</i></a>';
	} elseif ($sort == $b) {
		return '<a class="active" href="?sort=' . $a . '">' . $title . ' <i>▼</i></a>';  
	} else {
		return '<a href="?sort=' . $a . '">' . $title . '</a>';  
	}
}

?>

<section style="margin: 17px;">
    <div class="container">
        <div class="text-center">
            <a class="btn btn-primary shadow" role="button" href="../index.php" style="background: rgb(66,70,133);border-color: #ffffff00;">Главная</a>
            <a class="btn btn-primary shadow" role="button" href="news.php" style="background: rgb(66,70,133);border-color: #ffffff00;">Новости</a>
            <a class="btn btn-primary shadow" role="button" href="students.php" style="background: rgb(66,70,133);border-color: #ffffff00;">Студенты</a>
            <a class="btn btn-primary shadow" role="button" href="groups.php" style="background: rgb(66,70,133);border-color: #ffffff00;">Группы</a>
            <a class="btn btn-primary shadow" role="button" href="teachers.php" style="background: rgb(66,70,133);border-color: #ffffff00;">Преподаватели</a>
            <a class="btn btn-primary shadow" role="button" href="documents.php" style="background: rgb(66,70,133);border-color: #ffffff00;">Документы</a>
        </div>
    </div>
</section>

<section style="margin: 17px;">
    <div class="container">
        <div>
            <h1 class="text-center" style="color: rgb(0,0,0);">Новости</h1>
        </div>
        <div>
        <form action="news.php" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Поиск" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
                <button class="btn btn-primary" style="background: rgb(66,70,133); border-color: #ffffff00;" type="submit">Найти</button>
            </div>
        </form>
        </div>
        <div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive text-center">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><?php echo sort_link_th('Название', 'name_asc', 'name_desc'); ?></th>
                                    <th><?php echo sort_link_th('Описание', 'description_asc', 'description_desc'); ?></th>
                                    <th><?php echo sort_link_th('Подробное<br>описание', 'full_description_asc', 'full_description_desc'); ?></th>
                                    <th>Картинка</th>
                                    <th>
                                        <a class="btn btn-primary shadow" role="button" href="#" data-bs-toggle="modal" data-bs-target="#modal-add" style="background: rgb(66,70,133);border-color: #ffffff00;">Добавить</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td><input class='form-check-input shadow' type='checkbox' value='' id='flexCheckDefault'></td>";
                                    echo "<td>" . escape($row['name']) . "</td>";
                                    echo "<td>" . escape($row['description']) . "</td>";
                                    echo "<td>" . escape($row['full_description']) . "</td>";
                                    echo "<td><img src='" . escape($row['image']) . "' alt='Изображение' style='max-width: 100px;'></td>";
                                    echo "<td>";
                                    echo "<a class='btn btn-primary shadow open-trash-modal-btn' role='button' href='#' data-bs-toggle='modal' data-bs-target='#modal-delete' style='background: rgb(66,70,133); border-color: #ffffff00; margin-right: 5px;' onclick='setNewsIdToDelete(" . escape($row['id']) . ")'>";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' fill='currentColor' viewBox='0 0 16 16' class='bi bi-trash'>";
                                    echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'></path>";
                                    echo "<path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'></path>";
                                    echo "</svg>";
                                    echo "</a>";


                                    echo "<a class='btn btn-primary shadow open-modal-btn' role='button' href='#' data-bs-toggle='modal' data-bs-target='#modal-edit' style='background: rgb(66,70,133); border-color: #ffffff00; margin-right: 5px;' onclick='onEditModalOpen({$row['id']}, \"{$row['name']}\", \"{$row['description']}\", \"{$row['full_description']}\")'>";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' fill='currentColor' viewBox='0 0 16 16' class='bi bi-pencil'>";
                                    echo "<path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'></path>";
                                    echo "</svg>";
                                    echo "</a>";
                                    echo "</td>"; // закрытие ячейки
                                    echo "</tr>"; // закрытие строки
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

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------ -->

<!-- Модальное окно для удаления -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-delete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="../actions/news/delete.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: rgb(0,0,0);">Удалить запись?</h4>
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="news_id" id="news_id_to_delete">
                    <p style="color: rgb(0,0,0);">Вы точно хотите это удалить?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
                    <button class="btn btn-danger" type="submit" name="delete_news">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function setNewsIdToDelete(newsId) {
        document.getElementById('news_id_to_delete').value = newsId;
    }
</script>


<!-- Модальное окно для просмотра -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-view">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: rgb(0,0,0);">Просмотр (GET)</h4>
                <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="color: rgb(0,0,0);margin-bottom: -1px;">Название</p><input type="text" name="surname" style="padding-right: 232px;margin-bottom: 25px;" readonly>
                <p style="color: rgb(0,0,0);margin-bottom: -1px;">Описание</p><input type="text" name="name" style="padding-right: 232px;margin-bottom: 25px;" readonly>
                <p style="color: rgb(0,0,0);margin-bottom: -1px;">Подробное описание</p><textarea rows="10" style="padding-right: 232px;margin-bottom: 25px;" readonly></textarea>
                <p style="color: rgb(0,0,0);margin-bottom: -1px;">Картинка</p><input type="file">
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления новой записи -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-add">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="../actions/news/add.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: rgb(0,0,0);">Добавление новой записи</h4>
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Название</label>
                        <input type="text" class="form-control" id="add_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_description" class="form-label">Описание</label>
                        <input type="text" class="form-control" id="add_description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_full_description" class="form-label">Подробное описание</label>
                        <textarea class="form-control" id="add_full_description" name="full_description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="add_image" class="form-label">Картинка</label>
                        <input type="file" class="form-control" id="add_image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
                    <button class="btn btn-primary" type="submit" name="add_news">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно для изменения -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-edit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="../actions/news/edit.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: rgb(0,0,0);">Изменение новости</h4>
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_news_id" name="news_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Название</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Описание</label>
                        <input type="text" class="form-control" id="edit_description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_full_description" class="form-label">Подробное описание</label>
                        <textarea class="form-control" id="edit_full_description" name="full_description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Картинка</label>
                        <input type="file" class="form-control" id="edit_image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
                    <button class="btn btn-primary" type="submit" name="edit_news">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function onEditModalOpen(newsId, name, description, fullDescription) {
        document.getElementById('edit_news_id').value = newsId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_full_description').value = fullDescription;
    }
</script>

<?php include '../../includes/footer.php'; ?>