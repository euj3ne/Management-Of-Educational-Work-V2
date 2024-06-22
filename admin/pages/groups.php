

<?php
include '../../includes/header.php';
include 'E:\OSPanel\home\eduworknew.local\config\config.php';

function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    $query = "SELECT * FROM groups WHERE number_group LIKE :search OR number_students LIKE :search OR classroom_teacher LIKE :search";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%');
} else {
    $query = "SELECT * FROM groups";
    $stmt = $conn->query($query);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sort_list = array(
	'number_group_asc'   => '`number_group`',
	'number_group_desc'  => '`number_group` DESC',

	'number_students_asc'  => '`number_students`',
	'number_students_desc' => '`number_students` DESC',

	'enrollment_year_asc'   => '`enrollment_year`',
	'enrollment_year_desc'  => '`enrollment_year` DESC',

	'graduation_year_asc'   => '`graduation_year`',
	'graduation_year_desc'  => '`graduation_year` DESC',
    
	'classroom_teacher_asc'   => '`classroom_teacher`',
	'classroom_teacher_desc'  => '`classroom_teacher` DESC',
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
            <h1 class="text-center" style="color: rgb(0,0,0);">Группы</h1>
        </div>
        <form action="groups.php" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Поиск" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
                <button class="btn btn-primary" style="background: rgb(66,70,133); border-color: #ffffff00;" type="submit">Найти</button>
            </div>
        </form>
        <div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive text-center">
                        <table class="table">
                            <thead>
                                <tr>
                                <th></th>
                                    <th><?php echo sort_link_th('Номер группы', 'number_group_asc', 'number_group_desc'); ?></th>
                                    <th><?php echo sort_link_th('Количество студентов', 'number_students_asc', 'number_students_desc'); ?></th>
                                    <th><?php echo sort_link_th('Год поступления', 'enrollment_year_asc', 'enrollment_year_desc'); ?></th>
                                    <th><?php echo sort_link_th('Год окончания', 'graduation_year_asc', 'graduation_year_desc'); ?></th>
                                    <th><?php echo sort_link_th('Классный руководитель', 'classroom_teacher_asc', 'classroom_teacher_desc'); ?></th>
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
                                    echo "<td>" . escape($row['number_group']) . "</td>";
                                    echo "<td>" . escape($row['number_students']) . "</td>";
                                    echo "<td>" . escape($row['enrollment_year']) . "</td>";
                                    echo "<td>" . escape($row['graduation_year']) . "</td>";
                                    echo "<td>" . escape($row['classroom_teacher']) . "</td>";
                                    echo "<td>";
                                    echo "<a class='btn btn-primary shadow open-trash-modal-btn' role='button' href='#' data-bs-toggle='modal' data-bs-target='#modal-delete' style='background: rgb(66,70,133); border-color: #ffffff00; margin-right: 5px;' onclick='setGroupsIdToDelete(" . escape($row['id']) . ")'>";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' fill='currentColor' viewBox='0 0 16 16' class='bi bi-trash'>";
                                    echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'></path>";
                                    echo "<path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'></path>";
                                    echo "</svg>";
                                    echo "</a>";

                                    echo "<a class='btn btn-primary shadow open-view-modal-btn' role='button' href='#' data-bs-toggle='modal' data-bs-target='#modal-view' style='background: rgb(66,70,133); border-color: #ffffff00; margin-right: 5px;' onclick='onViewModalOpen({$row['id']}, \"{$row['number_group']}\", \"{$row['number_students']}\", \"{$row['enrollment_year']}\", \"{$row['graduation_year']}\", \"{$row['classroom_teacher']}\")'>";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' fill='currentColor' viewBox='0 0 16 16' class='bi bi-eye-fill'>";
                                    echo "<path d='M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0'></path>";
                                    echo "<path d='M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7'></path>";
                                    echo "</svg>";
                                    echo "</a>";

                                    echo "<a class='btn btn-primary shadow open-modal-btn' role='button' href='#' data-bs-toggle='modal' data-bs-target='#modal-edit' style='background: rgb(66,70,133); border-color: #ffffff00; margin-right: 5px;' onclick='onEditModalOpen({$row['id']}, \"{$row['number_group']}\", \"{$row['number_students']}\", \"{$row['enrollment_year']}\", \"{$row['graduation_year']}\", \"{$row['classroom_teacher']}\")'>";
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



<!-- Модальное окно для удаления -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-delete">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="../actions/groups/delete.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: rgb(0,0,0);">Удалить запись?</h4>
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="groups_id" id="groups_id_to_delete">
                    <p style="color: rgb(0,0,0);">Вы точно хотите это удалить?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
                    <button class="btn btn-danger" type="submit" name="delete_groups">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function setGroupsIdToDelete(groupsId) {
        document.getElementById('groups_id_to_delete').value = groupsId;
    }
</script>


<!-- Модальное окно для просмотра -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-view">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="color: rgb(0,0,0);">Просмотр группы</h4>
                <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                    <input type="hidden" id="edit_groups_id" name="groups_id">
                    <div class="mb-3">
                        <label for="edit_number_group" class="form-label">Номер группы</label>
                        <input type="text" class="form-control" id="edit_number_group" name="number_group" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_number_students" class="form-label">Количество студентов</label>
                        <input type="text" class="form-control" id="edit_number_students" name="number_students" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_enrollment_year" class="form-label">Год поступления</label>
                        <input type="date" class="form-control" id="edit_enrollment_year" name="enrollment_year" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_graduation_year" class="form-label">Год окончания</label>
                        <input type="date" class="form-control" id="edit_graduation_year" name="graduation_year" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_classroom_teacher" class="form-label">Классный руководитель</label>
                        <input type="text" class="form-control" id="edit_classroom_teacher" name="classroom_teacher" readonly>
                    </div>
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
            <form method="post" action="../actions/groups/add.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: rgb(0,0,0);">Добавление группы</h4>
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_number_group" class="form-label">Номер группы</label>
                        <input type="text" class="form-control" id="add_number_group" name="number_group" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_number_students" class="form-label">Количество студентов</label>
                        <input type="text" class="form-control" id="add_number_students" name="number_students" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_enrollment_year" class="form-label">Год поступления</label>
                        <input type="date" class="form-control" id="add_enrollment_year" name="enrollment_year" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_graduation_year" class="form-label">Год окончания</label>
                        <input type="date" class="form-control" id="add_graduation_year" name="graduation_year" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_classroom_teacher" class="form-label">Классный руководитель</label>
                        <input type="text" class="form-control" id="add_classroom_teacher" name="classroom_teacher" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
                    <button class="btn btn-primary" type="submit" name="add_groups">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Модальное окно для изменения -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-edit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="../actions/groups/edit.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: rgb(0,0,0);">Изменение группы</h4>
                    <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_groups_id" name="groups_id">
                    <div class="mb-3">
                        <label for="edit_number_group" class="form-label">Номер группы</label>
                        <input type="text" class="form-control" id="edit_number_group" name="number_group" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_number_students" class="form-label">Количество студентов</label>
                        <input type="text" class="form-control" id="edit_number_students" name="number_students" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_enrollment_year" class="form-label">Год поступления</label>
                        <input type="date" class="form-control" id="edit_enrollment_year" name="enrollment_year" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_graduation_year" class="form-label">Год окончания</label>
                        <input type="date" class="form-control" id="edit_graduation_year" name="graduation_year" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_classroom_teacher" class="form-label">Классный руководитель</label>
                        <input type="text" class="form-control" id="edit_classroom_teacher" name="classroom_teacher" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
                    <button class="btn btn-primary" type="submit" name="edit_groups">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function onEditModalOpen(groupsId, numberGroup, numberStudents, enrollmentYear, graduationYear, classroomTeacher) {
        document.getElementById('edit_groups_id').value = groupsId;
        document.getElementById('edit_number_group').value = numberGroup;
        document.getElementById('edit_number_students').value = numberStudents;
        document.getElementById('edit_enrollment_year').value = enrollmentYear;
        document.getElementById('edit_graduation_year').value = graduationYear;
        document.getElementById('edit_classroom_teacher').value = classroomTeacher;
    }
</script>

<script>
    function onViewModalOpen(groupsId, numberGroup, numberStudents, enrollmentYear, graduationYear, classroomTeacher) {
        document.getElementById('edit_groups_id').value = groupsId;
        document.getElementById('edit_number_group').value = numberGroup;
        document.getElementById('edit_number_students').value = numberStudents;
        document.getElementById('edit_enrollment_year').value = enrollmentYear;
        document.getElementById('edit_graduation_year').value = graduationYear;
        document.getElementById('edit_classroom_teacher').value = classroomTeacher;
    }
</script>

<?php include '../../includes/footer.php'; ?>
