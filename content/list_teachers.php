<?php
include '../includes/header.php';
include 'E:\OSPanel\home\eduworknew.local\config\config.php';

function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    $query = "SELECT * FROM teachers WHERE last_name LIKE :search OR first_name LIKE :search OR middle_name LIKE :search OR gender LIKE :search OR position LIKE :search OR employment_date LIKE :search";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%');
} else {
    $query = "SELECT * FROM teachers";
    $stmt = $conn->query($query);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sort_list = array(
	'last_name_asc'   => '`last_name`',
	'last_name_desc'  => '`last_name` DESC',

	'first_name_asc'  => '`first_name`',
	'first_name_desc' => '`first_name` DESC',

	'middle_name_asc'   => '`middle_name`',
	'middle_name_desc'  => '`middle_name` DESC',

	'birth_date_asc'   => '`birth_date`',
	'birth_date_desc'  => '`birth_date` DESC',

	'gender_asc'   => '`gender`',
	'gender_desc'  => '`gender` DESC',

	'address_asc'   => '`address`',
	'address_desc'  => '`address` DESC',

	'phone_asc'   => '`phone`',
	'phone_desc'  => '`phone` DESC',

	'email_asc'   => '`email`',
	'email_desc'  => '`email` DESC',

	'employment_date_asc'   => '`employment_date`',
	'employment_date_desc'  => '`employment_date` DESC',

	'position_asc'   => '`position`',
	'position_desc'  => '`position` DESC',
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
            <h1 class="text-center" style="color: rgb(0,0,0);">Преподаватели</h1>
        </div>
        <form action="teachers.php" method="get">
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
                                    <th><?php echo sort_link_th('Фамилия', 'last_name_asc', 'last_name_desc'); ?></th>
                                    <th><?php echo sort_link_th('Имя', 'first_name_asc', 'first_name_desc'); ?></th>
                                    <th><?php echo sort_link_th('Отчество', 'middle_name_asc', 'middle_name_desc'); ?></th>
                                    <th><?php echo sort_link_th('Дата<br>рождения', 'birth_date_asc', 'birth_date_desc'); ?></th>
                                    <th><?php echo sort_link_th('Пол', 'gender_asc', 'gender_desc'); ?></th>
                                    <th><?php echo sort_link_th('Адрес', 'address_asc', 'address_desc'); ?></th>
                                    <th><?php echo sort_link_th('Номер<br> телефона', 'phone_asc', 'phone_desc'); ?></th>
                                    <th><?php echo sort_link_th('Почта', 'email_asc', 'email_desc'); ?></th>
                                    <th><?php echo sort_link_th('Дата<br>трудоустройства', 'employment_date	_asc', 'employment_date	_desc'); ?></th>
                                    <th><?php echo sort_link_th('Должность', 'position_asc', 'position_desc'); ?></th>
                                    <th>
                                    <a class="btn btn-primary shadow" role="button" href="action/export_excel_teachers.php?search=<?php echo isset($GET['search']) ? htmlspecialchars($GET['search']) : ''; ?>" style="background: rgb(66,70,133);border-color: #ffffff00;">Скачать EXCEL</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td>" . escape($row['last_name']) . "</td>";
                                    echo "<td>" . escape($row['first_name']) . "</td>";
                                    echo "<td>" . escape($row['middle_name']) . "</td>";
                                    echo "<td>" . escape($row['birth_date']) . "</td>";
                                    echo "<td>" . escape($row['gender']) . "</td>";
                                    echo "<td>" . escape($row['address']) . "</td>";
                                    echo "<td>" . escape($row['phone']) . "</td>";
                                    echo "<td>" . escape($row['email']) . "</td>";
                                    echo "<td>" . escape($row['employment_date']) . "</td>";
                                    echo "<td>" . escape($row['position']) . "</td>";
                                    echo "<td>";

                                    echo "<a class='btn btn-primary shadow open-view-modal-btn' role='button' href='#' data-bs-toggle='modal' data-bs-target='#modal-view' style='background: rgb(66,70,133); border-color: #ffffff00; margin-right: 5px;' onclick='onViewModalOpen({$row['id']}, \"{$row['last_name']}\", \"{$row['first_name']}\", \"{$row['middle_name']}\", \"{$row['birth_date']}\", \"{$row['gender']}\", \"{$row['address']}\", \"{$row['phone']}\", \"{$row['email']}\", \"{$row['employment_date']}\", \"{$row['position']}\")'>";
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
                <h4 class="modal-title" style="color: rgb(0,0,0);">Просмотр преподавателя</h4>
                <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                    <input type="hidden" id="edit_teachers_id" name="teachers_id">
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_first_name" class="form-label">Имя</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_middle_name" class="form-label">Отчество</label>
                        <input type="text" class="form-control" id="edit_middle_name" name="middle_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_birth_date" class="form-label">Дата рождения</label>
                        <input type="date" class="form-control" id="edit_birth_date" name="birth_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gender" class="form-label">Пол</label>
                        <input type="text" class="form-control" id="edit_gender" name="gender" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Адрес</label>
                        <input type="text" class="form-control" id="edit_address" name="address" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Номер телефона</label>
                        <input type="number" class="form-control" id="edit_phone" name="phone" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Почта</label>
                        <input type="email" class="form-control" id="edit_email" name="email" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_employment_date" class="form-label">Дата трдоустройства</label>
                        <input type="date" class="form-control" id="edit_employment_date" name="employment_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_position" class="form-label">Должность</label>
                        <input type="text" class="form-control" id="edit_position" name="position" readonly>
                    </div>
                </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    function onViewModalOpen(teachersId, lastName, firstName, middleName, birthDate, gender, address, phone, email, employmentDate, position) {
        document.getElementById('edit_teachers_id').value = teachersId;
        document.getElementById('edit_last_name').value = lastName;
        document.getElementById('edit_first_name').value = firstName;
        document.getElementById('edit_middle_name').value = middleName;
        document.getElementById('edit_birth_date').value = birthDate;
        document.getElementById('edit_gender').value = gender;
        document.getElementById('edit_address').value = address;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_employment_date').value = employmentDate;
        document.getElementById('edit_position').value = position;
    }
</script>

<?php include '../includes/footer.php'; ?>
