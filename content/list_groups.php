<?php
include '../includes/header.php';
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
        <div>
            <h1 class="text-center" style="color: rgb(0,0,0);">Группы</h1>
        </div>
        <form action="groups.php" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Поиск" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>">
                <button class="btn btn-primary" style="background: rgb(66,70,133);border-color: #ffffff00;" type="submit">Найти</button>
            </div>
        </form>
        <div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive text-center">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo sort_link_th('Номер группы', 'number_group_asc', 'number_group_desc'); ?></th>
                                    <th><?php echo sort_link_th('Количество студентов', 'number_students_asc', 'number_students_desc'); ?></th>
                                    <th><?php echo sort_link_th('Год поступления', 'enrollment_year_asc', 'enrollment_year_desc'); ?></th>
                                    <th><?php echo sort_link_th('Год окончания', 'graduation_year_asc', 'graduation_year_desc'); ?></th>
                                    <th><?php echo sort_link_th('Классный руководитель', 'classroom_teacher_asc', 'classroom_teacher_desc'); ?></th>
                                    <th>
                                    <a class="btn btn-primary shadow" role="button" href="action/export_excel_groups.php?search=<?php echo isset($GET['search']) ? htmlspecialchars($GET['search']) : ''; ?>" style="background: rgb(66,70,133);border-color: #ffffff00;">Скачать EXCEL</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td>" . escape($row['number_group']) . "</td>";
                                    echo "<td>" . escape($row['number_students']) . "</td>";
                                    echo "<td>" . escape($row['enrollment_year']) . "</td>";
                                    echo "<td>" . escape($row['graduation_year']) . "</td>";
                                    echo "<td>" . escape($row['classroom_teacher']) . "</td>";
                                    echo "<td>";

                                    echo "<a class='btn btn-primary shadow open-view-modal-btn' role='button' href='#' data-bs-toggle='modal' data-bs-target='#modal-view' style='background: rgb(66,70,133); border-color: #ffffff00; margin-right: 5px;' onclick='onViewModalOpen({$row['id']}, \"{$row['number_group']}\", \"{$row['number_students']}\", \"{$row['enrollment_year']}\", \"{$row['graduation_year']}\", \"{$row['classroom_teacher']}\")'>";
                                    echo "<svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' fill='currentColor' viewBox='0 0 16 16' class='bi bi-eye-fill'>";
                                    echo "<path d='M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0'></path>";
                                    echo "<path d='M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7'></path>";
                                    echo "</svg>";
                                    echo "</a>";

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

<?php include '../includes/footer.php'; ?>
