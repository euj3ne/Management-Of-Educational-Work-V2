<?php
include '../../includes/header.php';
include 'E:\OSPanel\home\eduworknew.local\config\config.php';

function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$themes = [
    'd1' => 'Журнал по воспитательной работе',
    'd2' => 'Тематическое планирование "Разговоры о важном"',
    'd3' => 'Педагогическая характеристика',
    'd4' => 'Социальный паспорт',
    'd5' => 'Активность групп',
    'd6' => 'Памятка для родителей',
    'd7' => 'Протокол родительского собрания',
    'p1' => 'План работы',
    'p2' => 'План на учебные сборы',
    'r1' => 'Отчет воспитательной работы',
];

try {
    if (!$conn) {
        throw new Exception("Failed to connect to database.");
    }

    $search = isset($_GET['search']) ? $_GET['search'] : '';

    if (!empty($search)) {
        $query = "SELECT * FROM documents WHERE file_name LIKE :search OR file_description LIKE :search OR theme LIKE :search";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':search', '%' . $search . '%');
    } else {
        $query = "SELECT * FROM documents";
        $stmt = $conn->query($query);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = "Database Error: " . $e->getMessage();
    echo "<script>console.log('Ошибка при выполнении программы: " . addslashes($errorMessage) . "');</script>";
    echo "Ошибка при выполнении программы: " . htmlspecialchars($errorMessage);
    exit();
} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
    echo "<script>console.log('Ошибка при выполнении программы: " . addslashes($errorMessage) . "');</script>";
    echo "Ошибка при выполнении программы: " . htmlspecialchars($errorMessage);
    exit();
}

$sort_list = array(
	'file_name_asc'   => '`file_name`',
	'file_name_desc'  => '`file_name` DESC',

	'file_description_asc'  => '`file_description`',
	'file_description_desc' => '`file_description` DESC',

	'theme_asc'   => '`theme`',
	'theme_desc'  => '`theme` DESC',

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
            <!-- Меню навигации -->
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
            <h1 class="text-center" style="color: rgb(0,0,0);">Документы</h1>
        </div>
        <form action="documents.php" method="get">
            <!-- Форма поиска -->
            <div class="input-group mb-3">
                <input type="text"  class="form-control" placeholder="Поиск" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
                <button class="btn btn-primary" style="background: rgb(66,70,133); border-color: #ffffff00;" type="submit">Найти</button>
            </div>
        </form>
        <div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive text-center">
                        <!-- Таблица с документами -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><?php echo sort_link_th('Название', 'last_name_asc', 'last_name_desc'); ?></th>
                                    <th><?php echo sort_link_th('Описание', 'first_name_asc', 'first_name_desc'); ?></th>
                                    <th><?php echo sort_link_th('Направление документа', 'middle_name_asc', 'middle_name_desc'); ?></th>
                                    <th>
                                        <a class="btn btn-primary shadow" role="button" href="#" data-bs-toggle="modal" data-bs-target="#modal-add" style="background: rgb(66,70,133);border-color: #ffffff00;">Добавить</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($result as $document) {
                                    echo "<tr>";
                                    echo "<td><input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'></td>";
                                    echo "<td>" . htmlspecialchars($document['file_name'], ENT_QUOTES) . "</td>";
                                    echo "<td>" . htmlspecialchars($document['file_description'], ENT_QUOTES) . "</td>";
                                    echo "<td>" . (isset($themes[$document['theme']]) ? htmlspecialchars($themes[$document['theme']], ENT_QUOTES) : 'Неизвестная тема') . "</td>";
                                    echo "<td>";
                                    echo "<a class='btn btn-primary shadow open-trash-modal-btn' role='button' href='#' data-bs-toggle='modal' data-bs-target='#modal-delete' style='background: rgb(66,70,133); border-color: #ffffff00; margin-right: 5px;' onclick='setDocumentsIdToDelete(" . htmlspecialchars($document['id'], ENT_QUOTES) . ")'>";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' fill='currentColor' viewBox='0 0 16 16' class='bi bi-trash'>";
                                    echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'></path>";
                                    echo "<path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'></path>";
                                    echo "</svg>";
                                    echo "</a>";

                                    echo "<a class='btn btn-primary shadow' role='button' href='../../../uploads/documents/" . htmlspecialchars($document['file_path'], ENT_QUOTES) . "' download style='background: rgb(66,70,133); border-color: #ffffff00;margin-right: 5px;'>";
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


<!-- Модальное окно для удаления -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-delete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="../actions/documents/delete.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: rgb(0,0,0);">Удалить запись?</h4>
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="documents_id" id="documents_id_to_delete">
                    <p style="color: rgb(0,0,0);">Вы точно хотите это удалить?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
                    <button class="btn btn-danger" type="submit" name="delete_documents">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function setDocumentsIdToDelete(documentsId) {
        document.getElementById('documents_id_to_delete').value = documentsId;
    }
</script>


<!-- Модальное окно для добавления новой записи -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-add">
    <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="../actions/documents/add.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title" style="color: rgb(0,0,0);">Добавление документы</h4>
                        <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Название</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="full_description" class="form-label">Полное описание</label>
                        <input type="text" class="form-control" id="full_description" name="full_description" required>
                    </div>

                    <label for="file" class="form-label">Направление документа</label>
                    <select class="form-control" name="theme">
                        <optgroup label="Документы" >
                            <option value="d1">Журнал по воспитательной работе</option>
                            <option value="d2">Тематическое планирование "Разговоры о важном"</option>
                            <option value="d3">Педагогическая характеристика</option>
                            <option value="d4">Социальный паспорт</option>
                            <option value="d5">Активность групп</option>
                            <option value="d6">Памятка для родителей</option>
                            <option value="d7">Протокол родительского собрания</option>
                        </optgroup>
                        <optgroup label="Планы">
                            <option value="p1">План работы</option>
                            <option value="p2">План на учебные сборы</option>
                        </optgroup>
                        <optgroup label="Отчеты">
                            <option value="r1">Отчет воспитательной работы</option>
                        </optgroup>
                    </select>


                        <div class="mb-3 mt-3">
                            <label for="file" class="form-label">Файл (PDF, DOC(X), XSLS)</label>
                            <input type="file" class="form-control" id="file" name="file">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
                        <button class="btn btn-primary" type="submit" name="add_documents">Добавить</button>
                    </div>
                </form>
            </div>
    </div>
</div>


<?php include '../../includes/footer.php'; ?>
